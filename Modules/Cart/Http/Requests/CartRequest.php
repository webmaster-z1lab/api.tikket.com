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
            'callback'           => 'bail|required|string',
            'event_id'           => 'bail|required|exists:events,_id',
            'tickets'            => 'bail|required|array|min:1',
            'tickets.*.id'       => 'bail|required|exists:entrances,_id',
            'tickets.*.quantity' => 'bail|required|integer|min:1',
            'tickets.*.lot'      => 'bail|required|integer|min:1',
        ];
    }
}
