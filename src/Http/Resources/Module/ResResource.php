<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Module;

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
            'id'               => $this->id,
            'identifier'       => $this->identifier,
            'model'            => $this->model,
            'identifier_alias' => !empty($this->pivot) ? $this->pivot->identifier_alias : null,
        ];

        return $data;
    }
}
