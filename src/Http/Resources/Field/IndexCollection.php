<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Field;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IndexCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return IndexResource::collection($this->collection);
    }
}
