<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\SettingItem;

use Elemenx\CirFrameworkSkeleton\Traits\Resource\RouteName;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingItemResource extends JsonResource
{
    use RouteName;

    public static $names = [
        'setting_item.show'
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'            => $this->id,
            'name'          => $this->name,
            'key'           => $this->key,
            'description'   => $this->description,
            'help_link'     => $this->help_link,
            'type'          => $this->type,
            'type_params'   => $this->type_params,
            'type_enabled'  => $this->type_enabled,
            'date_source'   => $this->date_source,
            'default_value' => $this->default_value,
            'extra'         => $this->extra,
            'sequence'      => $this->sequence,
            'module_id'     => $this->module_id,
            'parent_id'     => $this->parent_id,
        ];

        if ($this->isShowNames(app('request')->route()->getName())) {
            $data = array_merge($data, [
                'module' => !empty($this->module) ? new ModuleResource($this->module) : $this->module
            ]);
        }

        return $data;
    }
}
