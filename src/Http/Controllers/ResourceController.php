<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Res\IndexCollection;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Res\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\Resource;
use Elemenx\CirFrameworkSkeleton\Models\Resourceable;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Resource\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Resource\UpdateRequest;
use Elemenx\CirFrameworkSkeleton\CirFrameworkSkeleton;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public $model;

    public function __construct(Resource $model)
    {
        $this->model = $model;
        $this->middleware(CirFrameworkSkeleton::getAdminMiddlewares(), ['only' => ['store', 'update', 'destroy', 'fieldList', 'data']]);
    }

    public function index()
    {
        $models = $this->model->sortField()->apiPaginate();

        return $this->success(new IndexCollection($models));
    }

    public function show($resource)
    {
        $model = $this->model->findOrFail($resource);

        return $this->success(new ShowResource($model));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $model = $this->model->create($data);

        return $this->success(new ShowResource($model));
    }

    public function update(UpdateRequest $request, $resource)
    {
        $data = $request->validated();
        $model = $this->model->findOrFail($resource);
        $model->update($data);

        return $this->success(new ShowResource($model));
    }

    public function destroy($resource)
    {
        $model = $this->model->findOrFail($resource);
        Resourceable::where('resource_id', $model->id)->delete();
        $model->delete();

        return $this->success();
    }

    public function fieldList($resource)
    {
        $resource = $this->model->findOrFail($resource);

        $classname = 'Elemenx\CirFrameworkSkeleton\Models\\' . Str::studly($resource->model);

        if (!class_exists($classname)) {
            return $this->error(42205);
        }

        $model = new $classname;

        return $this->success(array_diff($model->getColumns(), $model->getHidden()));
    }

    public function data($resource)
    {
        $resource = $this->model->findOrFail($resource);
        $classname = 'Elemenx\CirFrameworkSkeleton\Models\\' . Str::studly($resource->model);

        if (!class_exists($classname)) {
            return $this->error(42205);
        }

        $model = new $classname;
        $model->sortField();

        if ($resource->type == 'paginate') {
            $models = $model->apiPaginate();
        } elseif ($resource->type == 'list') {
            $models = $model->get();
        } elseif ($resource->type == 'cascader') {
            $models = $model->where('parent_id', $resource->parent_id)->get();
            foreach ($models as $model) {
                $model->isLeaf = $model->isLeaf();
            }
        }

        return $this->success($models);
    }
}
