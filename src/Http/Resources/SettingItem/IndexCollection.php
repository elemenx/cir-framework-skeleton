<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\SettingItem;

use Elemenx\CirFrameworkSkeleton\Http\Resources\PaginateCollection;

class IndexCollection extends PaginateCollection
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
