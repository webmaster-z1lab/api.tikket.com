<?php

namespace Modules\Cart\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class CardRequest extends ApiFormRequest
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
            'callback' => 'bail|required|string',
            'hash'     => 'bail|required|string',

            'card'              => 'bail|required|array',
            'card.brand'        => 'bail|required|string',
            'card.number'       => 'bail|required|digits:4',
            'card.token'        => 'bail|required|string',
            'card.installments' => 'bail|required|integer|min:1',
            'card.parcel'       => 'bail|required|numeric|min:1',

            'card.holder'            => 'bail|required|array',
            'card.holder.name'       => 'bail|required|string',
            'card.holder.document'   => 'bail|required|cpf',
            'card.holder.birth_date' => 'bail|required|date_format:Y-m-d|before_or_equal:today -18 years',
            'card.holder.phone'      => 'bail|required|digits_between:10,11',

            'card.holder.address'             => 'bail|required|array',
            'card.holder.address.street'      => 'bail|required|string',
            'card.holder.address.number'      => 'bail|required|integer|min:1',
            'card.holder.address.complement'  => 'bail|nullable|string',
            'card.holder.address.district'    => 'bail|required|string',
            'card.holder.address.postal_code' => 'bail|required|digits:8',
            'card.holder.address.city'        => 'bail|required|string',
            'card.holder.address.state'       => 'bail|required|string|size:2',
        ];
    }
}
