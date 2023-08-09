<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Resource;

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
            'name'       => 'string',
            'identifier' => 'string|unique:resources,identifier,' . app('request')->route('resource') . ',id',
            'model'      => 'string',
            'config'     => 'nullable|string',
        ];
    }
}
