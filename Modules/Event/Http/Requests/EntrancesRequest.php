<?php

namespace Modules\Event\Http\Requests;

use Carbon\Carbon;
use Modules\Event\Models\Event;
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
            'lots.*.value'       => 'bail|required_if:is_free,false|numeric',
            'lots.*.finishes_at' => 'bail|required|date_format:Y-m-d|after_or_equal:starts_at',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $event = Event::find(\Route::current()->parameter('event'));

            if ($event === NULL) abort(404);

            $start = Carbon::createFromFormat('Y-m-d', $this->starts_at)->startOfDay();
            if (!$event->starts_at->gte($start))
                $validator->errors()->add('starts_at', 'The start must be before event start');


            $prev = $start;
            foreach ($this->lots as $key => $lot) {
                $finish = Carbon::createFromFormat('Y-m-d', $lot['finishes_at'])->endOfDay();
                if ($event->starts_at->lte($finish->startOfDay()))
                    $validator->errors()->add('lots.' . $key . '.finishes_at', 'The end of a lot must be before event start');
                if ($prev->gte($finish))
                    $validator->errors()->add('lots.' . $key . '.finishes_at', 'The end of a lot must be after the end of previous lot.');
                $prev = $finish;
            }
        });
    }
}
