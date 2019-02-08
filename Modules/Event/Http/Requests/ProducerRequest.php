<?php

namespace Modules\Event\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class ProducerRequest extends ApiFormRequest
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
            'description' => 'bail|required|string',
        ];
    }
}
