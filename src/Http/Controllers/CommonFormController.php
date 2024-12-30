<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Models\CommonForm;

class CommonFormController extends Controller
{
    public $model;

    public function __construct(CommonForm $model)
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
        $this->model = $model;
    }

    public function index()
    {
        $common_forms = $this->model->filterField()->sortField()->apiPaginate();

        return $this->success($common_forms);
    }

    public function show($id)
    {
        if (is_integer(intval($id)) && intval($id) != 0) {
            $common_form = $this->model->findOrFail($id);
        } else {
            $common_form = $this->model->where('identifier', $id)->firstOrFail();
        }

        return $this->success($common_form);
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name'       => 'string',
            'identifier' => 'nullable|string',
            'dsl'        => 'nullable|string',
        ]);

        $common_form = $this->model->create($data);

        return $this->success($common_form);
    }

    public function update($id)
    {
        $data = $this->validate(request(), [
            'name'       => 'string',
            'identifier' => 'nullable|string',
            'dsl'        => 'nullable|string',
        ]);

        $common_form = $this->model->findOrFail($id);
        $common_form->update($data);

        return $this->success($common_form);
    }

    public function destroy($id)
    {
        $common_form = $this->model->findOrFail($id);
        $common_form->delete($id);

        return $this->success();
    }
}
