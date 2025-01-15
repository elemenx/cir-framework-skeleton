<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Module;

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
            'name'                         => 'sometimes|required|string',
            'type'                         => 'sometimes|required|string|in:action,permission,module,listPage,tabPage,configPage,customPage',
            'identifier'                   => 'string|unique:modules,identifier,' . app('request')->route('module') . ',id',
            'workflow_identifier'          => 'exists:workflows,identifier',
            'acl'                          => 'nullable|string',
            'params'                       => 'nullable|array',
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
