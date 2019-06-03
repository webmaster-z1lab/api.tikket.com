<?php

namespace Modules\Cart\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;
use Z1lab\OpenID\Services\ApiService;

class PaymentRequest extends ApiFormRequest
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
            'type'     => 'bail|required|string|in:boleto,credit_card',

            'customer.document'            => 'bail|nullable|cpf',
            'customer.phone'               => 'bail|nullable|cell_phone',

            'card.brand'        => 'bail|required_if:type,credit_card|string',
            'card.number'       => 'bail|required_if:type,credit_card|digits:4',
            'card.token'        => 'bail|required_if:type,credit_card|string',
            'card.installments' => 'bail|required_if:type,credit_card|integer|min:1',
            'card.parcel'       => 'bail|required_if:type,credit_card|numeric|min:1',

            'card.holder.name'       => 'bail|required_if:type,credit_card|string|full_name',
            'card.holder.document'   => 'bail|required_if:type,credit_card|cpf',
            'card.holder.birth_date' => 'bail|required_if:type,credit_card|date_format:Y-m-d|before_or_equal:today -18 years',
            'card.holder.phone'      => 'bail|required_if:type,credit_card|cell_phone',

            'address.street'      => 'bail|required_if:type,credit_card|string',
            'address.number'      => 'bail|required_if:type,credit_card|integer|min:1',
            'address.complement'  => 'bail|nullable|string',
            'address.district'    => 'bail|required_if:type,credit_card|string',
            'address.postal_code' => 'bail|required_if:type,credit_card|digits:8',
            'address.city'        => 'bail|required_if:type,credit_card|string',
            'address.state'       => 'bail|required_if:type,credit_card|string|size:2',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (\Auth::user()->document === NULL && empty($this->customer['document'])) {
                $validator->errors()->add('customer.document', 'O campo do documento do comprador é necessário.');
            } else {
                $user = (new ApiService())->getUser(\Request::bearerToken())->data;
                if ($user->attributes->phone === NULL && empty($this->customer['phone'])) {
                    $validator->errors()->add('customer.phone', 'O campo do telefone do comprador é necessário.');
                } elseif ($this->type === 'boleto' && $user->relationships->address === NULL && empty($this->address)) {
                    $validator->errors()->add('address', 'O campo do endereço do comprador é necessário.');
                }
            }
        });
    }
}
