<?php

namespace Modules\Cart\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class TicketRequest extends ApiFormRequest
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
            'tickets'            => 'bail|required|array|min:1',
            'tickets.*.id'       => 'bail|required|exists:carts,items._id',
            'tickets.*.name'     => 'bail|required|string',
            'tickets.*.document' => 'bail|required|cpf',
            'tickets.*.email'    => 'bail|required|email',
        ];
    }
}
