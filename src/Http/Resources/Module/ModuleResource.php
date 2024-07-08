<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Module;

use Elemenx\CirFrameworkSkeleton\Traits\Resource\RouteName;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    use RouteName;

    public static $names = [
        'module.field_list',
        'module.show'
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
            'id'                => $this->id,
            'identifier'        => $this->identifier,
            'type'              => $this->type,
            'acl'               => $this->acl,
            'name'              => $this->name,
            'params'            => $this->params,
            'config'            => $this->config,
            'data_resource_id'  => $this->data_resource_id,
            'parent_id'         => is_null($this->parent_id) ? 0 : $this->parent_id,
            'parent_identifier' => $this->parent_identifier,
            'icon'              => $this->icon,
            'resources'         => ResResource::collection($this->resources),
            'data_resource'     => new ResResource($this->dataResource)
        ];

        $setting = [];
        $setting_categories = !empty($this->settingCategories) && $this->settingCategories->isNotEmpty() ? $this->settingCategories->toArray() : [];
        $setting_items = !empty($this->settingCategories) && $this->settingItems->isNotEmpty() ? $this->settingItems->toArray() : [];
        $setting = array_merge($setting_categories, $setting_items);

        $data['setting'] = $setting;

        if ($this->isShowNames(app('request')->route()->getName())) {
            $data = array_merge($data, [
                'fields' => $this->fields,
            ]);
        }

        return $data;
    }
}
