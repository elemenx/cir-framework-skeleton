<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use ElemenX\ApiPagination\Paginator as ElemenXPaginator;

class PaginateCollection extends ResourceCollection
{
    protected $meta;

    public function __construct($resource)
    {
        parent::__construct($resource);

        if ($resource instanceof ElemenXPaginator) {
            $this->meta = $resource->toArray()['meta'];
        }

        $this->resource = $this->collectResource($resource);
    }

    public function getMeta()
    {
        return $this->meta;
    }
}
