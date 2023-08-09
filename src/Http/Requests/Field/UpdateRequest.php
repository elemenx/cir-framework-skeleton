<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Field;

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
            'name'   => 'string|unique:fields,name,' . app('request')->route('field') . ',id,module_id,' . app('request')->route('module'),
            'config' => 'nullable|string',
        ];
    }
}
