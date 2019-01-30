<?php

namespace Modules\Cart\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class CartRequest extends ApiFormRequest
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
            'callback'         => 'bail|required|string',
            'event_id'         => 'bail|required|exists:events,_id',
            'items'            => 'bail|required|array|min:1',
            'items.*.id'       => 'bail|required|exists:entrances,_id',
            'items.*.quantity' => 'bail|required|integer|min:1',
            'items.*.lot'      => 'bail|required|integer|min:1',
        ];
    }
}
