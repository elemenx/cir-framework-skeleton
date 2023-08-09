<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'        => $this->id,
            'module_id' => $this->module_id,
            'name'      => $this->name,
            'config'    => $this->config,
        ];

        return $data;
    }
}
