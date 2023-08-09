<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return new FieldResource($this->resource);
    }
}
