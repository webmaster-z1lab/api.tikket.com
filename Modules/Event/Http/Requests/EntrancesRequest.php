<?php

namespace Modules\Event\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class EntrancesRequest extends ApiFormRequest
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
            'name'        => 'bail|required|string|between:3,25',
            'is_free'     => 'bail|required|boolean',
            'min_buy'     => 'bail|nullable|integer|min:0|lte:max_buy',
            'max_buy'     => 'bail|required|integer|gte:min_buy',
            'starts_at'   => 'bail|required|date_format:Y-m-d|after:today',
            'description' => 'bail|nullable|string',

            'lots' => 'bail|required|array|min:1',

            'lots.*.amount'      => 'bail|required|integer|min:1',
            'lots.*.value'       => 'bail|required_if:entrances.*.is_free,false|numeric',
            'lots.*.finishes_at' => 'bail|required|date_format:Y-m-d|after:entrances.*.starts_at',
        ];
    }
}
