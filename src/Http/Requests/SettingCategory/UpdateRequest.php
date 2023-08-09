<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\SettingCategory;

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
            'key'       => 'nullable|string',
            'name'      => 'string',
            'module_id' => 'integer|exists:modules,id',
            'parent_id' => 'uuid|exists_except_zero:setting_categories,id'
        ];
    }
}
