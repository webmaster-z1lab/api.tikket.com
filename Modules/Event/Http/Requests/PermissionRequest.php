<?php

namespace Modules\Event\Http\Requests;

use Modules\Event\Models\Permission;
use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class PermissionRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'  => 'bail|required|string|in:' . implode(',', Permission::POSSIBLE_PERMISSIONS),
            'email' => 'bail|required|email',
        ];
    }
}
