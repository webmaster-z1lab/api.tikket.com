<?php

namespace Modules\Event\Http\Requests;

use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class BasicInformationRequest extends ApiFormRequest
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
            'name'          => 'bail|required|string',
            'description'   => 'bail|required|string',
            'body'          => 'bail|required|string',
//            'cover'         => 'bail|required|file|image',
            'category'      => 'bail|required|string',
            'types'         => 'bail|required|string',
            'starts_at'     => 'bail|required|date_format:Y-m-d H:i|after:now',
            'finishes_at'   => 'bail|required|date_format:Y-m-d H:i|after:starts_at',
            'is_active'     => 'bail|required|boolean',
            'is_public'     => 'bail|required|boolean',
        ];
    }
}
