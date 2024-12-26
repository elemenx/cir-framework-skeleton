<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'title'      => $this->title,
            'sub_title'  => $this->sub_title,
            'icon'       => $this->icon,
            'path'       => $this->path,
            'acl'        => $this->acl,
            'query'      => $this->query,
            'params'     => $this->params,
            'sort'       => $this->sort,
            'hidden'     => $this->hidden,
            'open_blank' => $this->open_blank,
            'parent_id'  => is_null($this->parent_id) ? 0 : $this->parent_id,
        ];

        return $data;
    }
}
