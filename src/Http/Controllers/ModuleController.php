<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Module\IndexResource;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Module\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\Field;
use Elemenx\CirFrameworkSkeleton\Models\Module;
use Elemenx\CirFrameworkSkeleton\Models\SettingCategory;
use Elemenx\CirFrameworkSkeleton\Models\SettingItem;
use Elemenx\CirFrameworkSkeleton\Traits\Controller\Sequence;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Module\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Module\UpdateRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ModuleController extends Controller
{
    use Sequence;

    public $model;

    public function __construct(Module $model)
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
        $this->model = $model;
    }

    public function index()
    {
        $models = $this->model->sortField()->with('resources', 'dataResource', 'settingItems', 'settingCategories')->get();

        return $this->success(IndexResource::collection($models));
    }

    public function show($module)
    {
        $model = $this->model->with('dataResource', 'resources', 'settingItems', 'settingCategories', 'fields')->findOrFail($module);

        return $this->success(new ShowResource($model));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $model = $this->model;
        $model = $model->create(Arr::except($data, 'resources'));

        if (isset($data['resources'])) {
            $collect_resources = collect($data['resources'])->keyBy('id')->map(function ($item) {
                return ['identifier_alias' => Arr::get($item, 'identifier_alias')];
            });
            $model->resources()->sync($collect_resources->toArray());
        }
        Cache::tags('settings')->flush();

        return $this->success(new ShowResource($model));
    }

    public function update(UpdateRequest $request, $module)
    {
        $model = $this->model->findOrFail($module);
        $data = $request->validated();
        $model->update(Arr::except($data, 'resources'));

        if (isset($data['resources'])) {
            $collect_resources = collect($data['resources'])->keyBy('id')->map(function ($item) {
                return ['identifier_alias' => Arr::get($item, 'identifier_alias')];
            });
            $model->resources()->sync($collect_resources->toArray());
        }
        Cache::tags('settings')->flush();

        return $this->success(new ShowResource($model));
    }

    public function destroy($module)
    {
        $model = $this->model->findOrFail($module);
        $model->delete();

        return $this->success();
    }

    public function fieldSequenceList($module)
    {
        $model = $this->model->with('fields')->findOrFail($module);

        $fields = $model->fields->toArray();

        return $this->success([
            'list_sequence' => array_values(Arr::sort($fields, function ($value) {
                return $value['list_sequence'];
            })),
            'search_sequence' => array_values(Arr::sort($fields, function ($value) {
                return $value['search_sequence'];
            })),
        ]);
    }

    public function copy($module)
    {
        $data = $this->validate(request(), [
            'name'       => 'required|string',
            'identifier' => 'required|string|unique:modules,identifier',
        ]);

        $copy_module = $this->model->with('fields')
            ->with('settingCategories.items')
            ->with(['descendants' => function ($query) {
                $query->with('fields', 'settingCategories.items');
            }])->findOrFail($module);

        $this->deepCopy($copy_module, $data);
        Cache::tags('settings')->flush();

        return $this->success();
    }

    private function deepCopy($copy_module, $data = [])
    {
        $module_data = array_merge(Arr::except(
            $copy_module->toArray(),
            [
                'id',
                'descendants',
                'fields',
                'setting_categories'
            ]
        ), $data);

        $module = Module::create($module_data);

        if (is_null($module->parent_id)) {
            $module->setParentId(0);
            $module->save();
        }

        $this->syncSettingModule($module, $copy_module);

        $field_data = [];

        foreach ($copy_module->fields as $field) {
            $field_data[] = array_merge(Arr::except($field->toArray(), ['id', 'dynamic_module_id']), [
                'module_id' => $module->id
            ]);
        }

        $kv_descendants = $copy_module->descendants->keyBy('id');

        foreach ($kv_descendants as &$descendant) {
            $descendant_module = Module::create(array_merge(Arr::except(
                $descendant->toArray(),
                [
                    'id',
                    'parent_id',
                    'parent_identifier',
                    'descendants',
                    'fields',
                    'setting_categories'
                ]
            ), [
                'name'              => $descendant->name . '_复制',
                'identifier'        => $descendant->identifier . '_copy',
                'parent_id'         => $module->id,
                'parent_identifier' => $module->identifier
            ]));
            $this->syncSettingModule($descendant_module, $descendant);
            $descendant_module->parent_id = $module->id;
            $descendant_module->save();

            $descendant->new_id = $descendant_module->id;
            $descendant->new_identifier = $descendant_module->identifier;

            if (isset($kv_descendants[$descendant->parent_id])) {
                $descendant_module->parent_identifier = $kv_descendants[$descendant->parent_id]->new_identifier;
                $descendant_module->parent_id = $kv_descendants[$descendant->parent_id]->new_id;
                $descendant_module->save();
            }

            foreach ($descendant->fields as $field) {
                $field_data[] = array_merge(Arr::except($field->toArray(), ['id', 'dynamic_module_id']), [
                    'module_id' => $descendant_module->id,
                ]);
            }
        }

        if (!empty($field_data)) {
            Field::insert($field_data);
        }
    }

    protected function syncSettingModule(Module $module, Module $copy_module)
    {
        $setting_category_relation = []; // 记录新旧setting_category的ID映射
        $new_setting_category = collect();
        $items = [];

        foreach ($copy_module->settingCategories as $setting_category) {
            $setting_category_model = SettingCategory::create(
                array_merge(
                    Arr::only($setting_category->toArray(), ['key', 'name', 'sequence']),
                    [
                        'module_id' => $module->id,
                    ]
                )
            );
            $setting_category_relation[$setting_category->id] = $setting_category_model->id;
            $new_setting_category->put($setting_category_model->id, $setting_category_model);

            foreach ($setting_category->items as $item) {
                $items[] = array_merge(
                    Arr::except($item->toArray(), ['id', 'parent_id', 'module_id', 'type_params']),
                    [
                        'id'          => Str::uuid()->toString(),
                        'module_id'   => $module->id,
                        'parent_id'   => $setting_category_model->id,
                        'type_params' => $item->getRawOriginal('type_params')
                    ]
                );
            }
        }

        foreach ($copy_module->settingCategories as $setting_category) {
            if (isset($setting_category_relation[$setting_category->parent_id])
                && isset($setting_category_relation[$setting_category->id])
            ) {
                $new_id = $setting_category_relation[$setting_category->id];
                $new_parent_id = $setting_category_relation[$setting_category->parent_id];

                if (isset($new_setting_category[$new_id])) {
                    $new_setting_category[$new_id]->update([
                        'parent_id' => $new_parent_id
                    ]);
                }
            }
        }

        if (!empty($items)) {
            SettingItem::insert($items);
        }
    }
}
