<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\SettingItem\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\SettingItem;
use Elemenx\CirFrameworkSkeleton\Traits\Controller\Sequence;
use Elemenx\CirFrameworkSkeleton\Http\Requests\SettingItem\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\SettingItem\UpdateRequest;

class SettingItemController extends Controller
{
    use Sequence;

    public $model;

    public function __construct(SettingItem $model)
    {
        $this->model = $model;
    }

    public function show($setting_item)
    {
        $model = $this->model->with('parent', 'module')->findOrFail($setting_item);

        return $this->success(new ShowResource($model));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        $model = $this->model->create($data);

        return $this->success(new ShowResource($model));
    }

    public function update(UpdateRequest $request, $setting_item)
    {
        $model = $this->model->findOrFail($setting_item);

        $data = $request->validated();

        $model->update($data);

        return $this->success(new ShowResource($model));
    }

    public function destroy($setting_item)
    {
        $model = $this->model->findOrFail($setting_item);
        $model->delete();

        return $this->success();
    }
}
