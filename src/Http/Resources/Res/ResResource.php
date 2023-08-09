<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Res;

use Illuminate\Http\Resources\Json\JsonResource;

class ResResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'identifier' => $this->identifier,
            'model'      => $this->model,
            'config'     => $this->config
        ];

        return $data;
    }
}
