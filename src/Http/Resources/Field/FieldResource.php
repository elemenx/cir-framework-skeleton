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
        $data = $this->resource->toArray();

        return $data;
    }
}
