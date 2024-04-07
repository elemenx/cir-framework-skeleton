<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Module;

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
            'name'                         => 'required|string',
            'type'                         => 'required|string|in:action,permission,module,listPage,tabPage,configPage,customPage',
            'identifier'                   => 'unique:modules,identifier',
            'params'                       => 'nullable|array',
            'acl'                          => 'nullable|string',
            'config'                       => 'nullable|array',
            'data_resource_id'             => 'integer',
            'parent_id'                    => 'nullable|integer',
            'parent_identifier'            => 'nullable|string',
            'icon'                         => 'nullable|string',
            'resources'                    => 'array',
            'resources.*'                  => 'array',
            'resources.*.id'               => 'integer',
            'resources.*.identifier_alias' => 'string',
        ];
    }
}
