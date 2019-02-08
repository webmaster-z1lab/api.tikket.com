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
            'fee_is_hidden' => 'bail|required|boolean',
            'entrances'     => 'bail|required|array|min:1',

            'entrances.*.name'      => 'bail|required|string|between:3,25',
            'entrances.*.is_free'   => 'bail|required|boolean',
            'entrances.*.min_buy'   => 'bail|nullable|integer|min:0|lte:max_buy',
            'entrances.*.max_buy'   => 'bail|required|integer|gte:min_buy',
            'entrances.*.starts_at' => 'bail|required|date_format:Y-m-d H:i|after:now',

            'entrances.*.lots' => 'bail|required|array|min:1',

            'entrances.*.lots.*.amount'      => 'bail|required|integer|min:1',
            'entrances.*.lots.*.value'       => 'bail|required_if:entrances.*.is_free,false|numeric',
            'entrances.*.lots.*.finishes_at' => 'bail|required|date_format:Y-m-d H:i|after:entrances.*.starts_at',
        ];
    }
}
