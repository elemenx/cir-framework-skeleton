<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Models\Workflow;

class WorkflowController extends Controller
{
    public function __construct(public Workflow $model)
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
        $this->model = $model;
    }

    public function index()
    {
        $models = $this->model->filterField()->sortField()->apiPaginate();

        return $this->success($models);
    }

    public function show($id)
    {
        $model = $this->model->findOrFail($id);

        return $this->success($model);
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name'                   => 'string',
            'identifier'             => 'string|unique:workflows,identifier',
            'common_form_identifier' => 'nullable|string',
            'form_dsl'               => 'nullable|string',
            'workflow_dsl'           => 'nullable|string',
        ]);

        $model = $this->model->create($data);

        return $this->success($model);
    }

    public function update($id)
    {
        $data = $this->validate(request(), [
            'name'                   => 'string',
            'identifier'             => 'string|unique:workflows,identifier,' . $id . ',id',
            'common_form_identifier' => 'nullable|string',
            'form_dsl'               => 'nullable|string',
            'workflow_dsl'           => 'nullable|string',
        ]);
        $model = $this->model->findOrFail($id);
        $model->update($data);

        return $this->success($model);
    }

    public function destroy($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete($id);

        return $this->success();
    }
}
