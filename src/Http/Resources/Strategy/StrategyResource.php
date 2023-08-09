<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy;

use Illuminate\Http\Resources\Json\JsonResource;

class StrategyResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'acls'        => $this->acls,
            'raw_acls'    => $this->raw_acls,
            'rule_config' => $this->rule_config,
            'resources'   => ResResource::collection($this->resources)
        ];

        return $data;
    }
}
