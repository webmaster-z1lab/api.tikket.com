<?php

namespace Modules\Event\Http\Requests;

use Modules\Event\Repositories\EntranceRepository;
use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class CouponRequest extends ApiFormRequest
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
            'entrance_id'   => 'bail|required|exists:entrances,_id',
            'name'          => 'bail|required|string|max:50',
            'is_percentage' => 'bail|required|boolean',
            'valid_until'   => 'bail|required|date_format:Y-m-d|after_or_equal:today',
            'code'          => 'bail|required|string|max:20',
            'discount'      => 'bail|required|integer|min:1',
            'quantity'      => 'bail|required|integer|min:1',
        ];
    }

    /**
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            /** @var \Illuminate\Validation\Validator $validator */
            if ($this->is_percentage && $this->discount > 100) {
                $validator->errors()->add('discount', 'O desconto deve ser no máximo de 100%.');
            } elseif (!$this->is_percentage) {
                $entranceRepository = resolve(EntranceRepository::class);

                $entrance = $entranceRepository->getEntrance($this->entrance_id);

                if ($this->discount > $entrance->available->price)
                    $validator->errors()->add('discount', 'O desconto deve ser no máximo igual ao preço do lote atual.');
            }
        });
    }
}
