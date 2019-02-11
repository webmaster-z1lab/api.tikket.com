<?php

namespace Modules\Event\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class AddressRequest extends ApiFormRequest
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
            'name'        => 'bail|required|string',
            'street'      => 'bail|required|string',
            'number'      => 'bail|required|integer|min:1',
            'district'    => 'bail|required|string',
            'complement'  => 'bail|nullable|string',
            'city'        => 'bail|required|string',
            'state'       => 'bail|required|string|size:2',
            'postal_code' => 'bail|required|digits:8',
            'maps_url'    => 'bail|required|url',
            'formatted'   => 'bail|required|string',

            'coordinate'     => 'bail|required|array',
            'coordinate.lat' => 'bail|required|numeric',
            'coordinate.lng' => 'bail|required|numeric',
        ];
    }
}
