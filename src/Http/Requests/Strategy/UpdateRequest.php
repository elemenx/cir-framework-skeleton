<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Strategy;

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
            'name'                   => 'sometimes|required|string',
            'acls'                   => 'sometimes|required|array',
            'acls.*'                 => 'sometimes|required|string',
            'raw_acls'               => 'array',
            'features'               => 'array',
            'features.*'             => 'string|in:sa,ro',
            'rule_config'            => 'string',
            'resources'              => 'array',
            'resources.*'            => 'array',
            'resources.*.id'         => 'integer',
            'resources.*.expression' => 'array',
            'resources.*.keys'       => 'array',
            'resources.*.keys.*'     => 'integer',
        ];
    }
}
