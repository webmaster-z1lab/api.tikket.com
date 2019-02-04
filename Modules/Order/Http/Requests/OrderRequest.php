<?php

namespace Modules\Order\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class OrderRequest extends ApiFormRequest
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
            'cart' => 'bail|required|exists:carts,_id',
        ];
    }
}
