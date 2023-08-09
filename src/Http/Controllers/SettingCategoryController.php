<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\SettingCategory\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\SettingCategory;
use Elemenx\CirFrameworkSkeleton\Traits\Controller\Sequence;
use Elemenx\CirFrameworkSkeleton\Http\Requests\SettingCategory\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\SettingCategory\UpdateRequest;

class SettingCategoryController extends Controller
{
    use Sequence;

    public $model;

    public function __construct(SettingCategory $model)
    {
        $this->model = $model;
    }

    public function show($setting_category)
    {
        $model = $this->model->with('items', 'children.items', 'module')->findOrFail($setting_category);

        return $this->success(new ShowResource($model));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $model = $this->model->create($data);

        return $this->success(new ShowResource($model));
    }

    public function update(UpdateRequest $request, $setting_category)
    {
        $model = $this->model->findOrFail($setting_category);
        $data = $request->validated();
        $model->update($data);

        return $this->success(new ShowResource($model));
    }

    public function destroy($setting_category)
    {
        $model = $this->model->findOrFail($setting_category);
        $model->delete();

        return $this->success();
    }
}
