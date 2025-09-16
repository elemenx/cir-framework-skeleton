<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Field\IndexResource;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Field\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\Field;
use Elemenx\CirFrameworkSkeleton\Models\Module;
use Elemenx\CirFrameworkSkeleton\Traits\Controller\Sequence;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Field\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Field\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class FieldController extends Controller
{
    use Sequence;

    public $model;

    public function __construct(Field $model)
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
        $this->model = $model;
    }

    public function index($module)
    {
        $module = Module::findOrFail($module);
        $fields = $this->model->where('module_id', $module->id)->sortField()->get();

        return $this->success(IndexResource::collection($fields));
    }

    public function show($module, $field)
    {
        $module = Module::findOrFail($module);
        $field = $this->model->where('module_id', $module->id)->findOrFail($field);

        return $this->success(new ShowResource($field));
    }

    public function store(CreateRequest $request, $module)
    {
        $data = $request->validated();
        $data['module_id'] = $module;
        $field = $this->model->create(Arr::only($data, ['name', 'config', 'module_id']));

        $request = new Request([
            'ids'   => Field::where('module_id', $module)->orderBy('list_sequence', 'ASC')->pluck('id')->toArray(),
            'field' => 'list_sequence',
        ]);
        $this->sequence($request);
        Cache::tags('settings')->flush();

        return $this->success(new ShowResource($field));
    }

    public function update(UpdateRequest $request, $module, $field)
    {
        $data = $request->validated();
        $module = Module::findOrFail($module);
        $field = $this->model->where('module_id', $module->id)->findOrFail($field);
        $field->update(Arr::only($data, ['name', 'config', 'group_name']));
        Cache::tags('settings')->flush();

        return $this->success(new ShowResource($field));
    }

    public function destroy($module, $field)
    {
        $module = Module::findOrFail($module);
        $field = $this->model->where('module_id', $module->id)->findOrFail($field);
        $field->delete();
        Cache::tags('settings')->flush();

        return $this->success();
    }
}
