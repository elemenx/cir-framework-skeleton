<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Requests\Role;

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
            'name'         => 'sometimes|required|string',
            'features'     => 'nullable|array',
            'features.*'   => 'string|in:sa,ro',
            'acls'         => 'sometimes|required|array',
            'acls.*'       => 'sometimes|required|string',
            'strategies'   => 'array',
            'strategies.*' => 'integer',
        ];
    }
}
