<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'     => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'path'      => 'required|string|max:255',
            'acl'       => 'required|string|max:255',
            'icon'      => 'nullable|string|max:255',
            'query'     => 'nullable|string|max:255',
            'params'    => 'nullable|string|max:255',
            'hidden'    => 'boolean',
            'parent_id' => 'nullable|exists_except_zero:menus,id',
        ];
    }
}
