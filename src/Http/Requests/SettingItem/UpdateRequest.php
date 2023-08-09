<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\SettingItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'key'           => 'nullable|string|unique:setting_items,key,' . app('request')->route('setting_item') . ',id,parent_id,' . request('parent_id'),
            'name'          => 'required|string',
            'module_id'     => 'required|integer|exists:modules,id',
            'parent_id'     => 'uuid|exists_except_zero:setting_categories,id',
            'description'   => 'nullable|string',
            'help_link'     => 'nullable|string',
            'type'          => 'string',
            'type_params'   => 'array',
            'type_enabled'  => 'boolean',
            'data_source'   => 'nullable|integer',
            'default_value' => 'nullable|string',
            'extra'         => 'nullable|string',
            'span'          => 'integer'
        ];
    }
}
