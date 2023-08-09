<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Controller;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait Sequence
{
    public function sequence(Request $request)
    {
        $data = $this->validate($request, [
            'ids'   => 'required|array',
            'field' => 'in:list_sequence,search_sequence'
        ]);

        if (property_exists($this, 'model')) {
            $model = $this->model instanceof Builder ? $this->model->getModel() : $this->model;
        }

        $field = Arr::get($data, 'field', $model->getDefaultSortField());
        if (!in_array($field, $model->getColumns())) {
            return $this->error(40406);
        }
        $items = $model->get()->keyBy('id');
        $i = 0;

        foreach ($request->ids as $id) {
            if (!$items->has($id)) {
                continue;
            }
            $items[$id]->update([
                $field => $i
            ]);
            $i++;
        }

        return $this->success(null);
    }
}
