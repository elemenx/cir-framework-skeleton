<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Menu\IndexResource;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Menu\ShowResource;
use Elemenx\CirFrameworkSkeleton\Models\Menu;
use Elemenx\CirFrameworkSkeleton\Traits\Controller\Sequence;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Menu\CreateRequest;
use Elemenx\CirFrameworkSkeleton\Http\Requests\Menu\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    use Sequence;

    public $model;

    public function __construct(Menu $model)
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
        $this->model = $model;
    }

    public function index()
    {
        $menus = $this->model->sortField()->filterField()->get()->toFlatTree();

        return $this->success(IndexResource::collection($menus));
    }

    public function show($menu)
    {
        $model = $this->model->findOrFail($menu);

        return $this->success(new ShowResource($model));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        if (array_key_exists('parent_id', $data) && $data['parent_id'] == 0) {
            $data['parent_id'] = null;
        }
        $model = $this->model->create($data);

        $this->sequence(new Request(['ids' => $this->model->orderBy('sort', 'ASC')->get()->toFlatTree()->pluck('id')->toArray()]));
        Cache::tags('settings')->flush();

        return $this->success(new ShowResource($model));
    }

    public function update(UpdateRequest $request, $menu)
    {
        $model = $this->model->findOrFail($menu);
        $data = $request->validated();
        if (array_key_exists('parent_id', $data) && $data['parent_id'] == 0) {
            $data['parent_id'] = null;
        }
        $model->update($data);
        $this->sequence(new Request(['ids' => $this->model->orderBy('sort', 'ASC')->get()->toFlatTree()->pluck('id')->toArray()]));
        Cache::tags('settings')->flush();

        return $this->success(new ShowResource($model));
    }

    public function destroy($menu)
    {
        $model = $this->model->findOrFail($menu);
        $model->delete();
        Cache::tags('settings')->flush();

        return $this->success();
    }
}
