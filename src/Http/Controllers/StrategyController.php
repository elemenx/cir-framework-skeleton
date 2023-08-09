<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy\IndexResource;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\Strategy;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Strategy\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Strategy\UpdateRequest;
use Illuminate\Support\Arr;

class StrategyController extends Controller
{
    public $model;

    public function __construct(Strategy $model)
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
        $this->model = $model;
    }

    public function index()
    {
        $models = $this->model->sortField()->with('resources')->get();

        return $this->success(IndexResource::collection($models));
    }

    public function show($strategy)
    {
        $model = $this->model->with('resources')->findOrFail($strategy);

        return $this->success(new ShowResource($model));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $data['acls'] = array_values(array_unique($data['acls']));
        $model = $this->model->create(Arr::except($data, 'resources'));

        if (isset($data['resources'])) {
            $collect_resources = collect($data['resources'])->keyBy('id')->map(function ($item) {
                return [
                    'keys'       => json_encode(Arr::get($item, 'keys', [])),
                    'expression' => json_encode(Arr::get($item, 'expression', []))
                ];
            });
            $model->resources()->sync($collect_resources->toArray());
        }

        return $this->success(new ShowResource($model));
    }

    public function update(UpdateRequest $request, $strategy)
    {
        $data = $request->validated();
        $model = $this->model->findOrFail($strategy);
        $data['acls'] = array_values(array_unique($data['acls']));
        $model->update(Arr::except($data, 'resources'));

        if (isset($data['resources'])) {
            $collect_resources = collect($data['resources'])->keyBy('id')->map(function ($item) {
                return [
                    'keys'       => json_encode(Arr::get($item, 'keys', [])),
                    'expression' => json_encode(Arr::get($item, 'expression', []))
                ];
            });
            $model->resources()->sync($collect_resources->toArray());
        }

        return $this->success(new ShowResource($model));
    }

    public function destroy($strategy)
    {
        $model = $this->model->findOrFail($strategy);
        $model->resources()->detach();
        $model->delete();

        return $this->success();
    }
}
