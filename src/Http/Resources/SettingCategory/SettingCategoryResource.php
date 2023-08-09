<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\SettingCategory;

use Elemenx\CirFrameworkSkeleton\Traits\Resource\RouteName;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingCategoryResource extends JsonResource
{
    use RouteName;

    public static $names = [
        'setting_category.show'
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
            'id'        => $this->id,
            'key'       => $this->key,
            'name'      => $this->name,
            'sequence'  => $this->sequence,
            'module_id' => $this->module_id,
            'parent_id' => $this->parent_id,
        ];

        if ($this->isShowNames(app('request')->route()->getName())) {
            $data = array_merge($data, [
                'module'   => !empty($this->module) ? new ModuleResource($this->module) : $this->module,
                'items'    => $this->items,
                'parent'   => !empty($this->parent) ? new SettingCategoryResource($this->parent) : $this->parent,
                'children' => $this->children->isNotEmpty() ? SettingCategoryResource::collection($this->children) : $this->children,
            ]);
        }

        return $data;
    }
}
