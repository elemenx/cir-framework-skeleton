<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy;

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
            'expression' => !empty($this->pivot) ? json_decode($this->pivot->expression, true) : []
        ];

        return $data;
    }
}
