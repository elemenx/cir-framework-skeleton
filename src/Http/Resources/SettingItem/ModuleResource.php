<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\SettingItem;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            'identifier' => $this->identifier,
            'name'       => $this->name,
        ];

        return $data;
    }
}
