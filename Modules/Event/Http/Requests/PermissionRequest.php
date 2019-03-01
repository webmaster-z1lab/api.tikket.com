<?php

namespace Modules\Event\Http\Requests;

use Illuminate\Support\Arr;
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
        return Permission::where('email', \Auth::user()->email)
            ->where('event_id', \Route::current()->parameter('event'))
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $permission = Permission::where('email', \Auth::user()->email)
            ->where('event_id', \Route::current()->parameter('event'))
            ->first();

        $possible = Arr::pluck(config('event.permissions.' . $permission->type), 'value');

        return [
            'type'  => 'bail|required|string|in:' . implode(',', $possible),
            'email' => 'bail|required|email',
        ];
    }
}
