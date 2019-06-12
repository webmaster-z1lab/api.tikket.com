<?php

namespace Modules\Event\Http\Requests;

use Carbon\Carbon;
use Modules\Event\Models\Entrance;
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
        $min_value = (config('fee.min') + 100) / 100.0;

        if (\Request::isMethod('POST'))
            return [
                'name'        => 'bail|required|string|between:3,25',
                'is_free'     => 'bail|required|boolean',
                'min_buy'     => 'bail|nullable|integer|min:0|lte:max_buy',
                'max_buy'     => 'bail|required|integer|gte:min_buy',
                'starts_at'   => 'bail|required|date_format:Y-m-d|after_or_equal:today',
                'description' => 'bail|nullable|string',

                'lots' => 'bail|required|array|min:1',

                'lots.*.amount'      => 'bail|required|integer|min:1',
                'lots.*.value'       => 'bail|nullable|required_if:is_free,false|numeric|min:' . $min_value,
                'lots.*.finishes_at' => 'bail|required|date_format:Y-m-d|after_or_equal:starts_at',
            ];

        return [
            'name'        => 'bail|required|string|between:3,25',
            'is_free'     => 'bail|required|boolean',
            'min_buy'     => 'bail|nullable|integer|min:0|lte:max_buy',
            'max_buy'     => 'bail|required|integer|gte:min_buy',
            'starts_at'   => 'bail|required|date_format:Y-m-d',
            'description' => 'bail|nullable|string',

            'lots' => 'bail|required|array|min:1',

            'lots.*.amount'      => 'bail|required|integer|min:1',
            'lots.*.value'       => 'bail|nullable|required_if:is_free,false|numeric|min:' . $min_value,
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

            if (\Request::isMethod('POST') || in_array($event->status, [Event::DRAFT, Event::COMPLETED])) {
                $start = Carbon::createFromFormat('Y-m-d', $this->starts_at)->startOfDay();

                if (!$event->starts_at->gte($start))
                    $validator->errors()->add('starts_at', 'The start must be before event start');
                else {
                    $prev = $start;
                    foreach ($this->lots as $key => $lot) {
                        $finish = Carbon::createFromFormat('Y-m-d', $lot['finishes_at'])->endOfDay();
                        if ($event->starts_at->lte($finish->startOfDay())) {
                            $validator->errors()->add('lots.' . $key . '.finishes_at', 'The end of a lot must be before event start');
                            break;
                        }
                        if ($key !== 0 && $prev->gte($finish)) {
                            $validator->errors()->add('lots.' . $key . '.finishes_at', 'The end of a lot must be after the end of previous lot.');
                            break;
                        }
                        $prev = $finish;
                    }
                }
            } else {
                $entrance = Entrance::find(\Route::current()->parameter('entrance'));

                if ($entrance === NULL) abort(404);

                if ($entrance->is_locked) {
                    if ($this->name !== $entrance->name)
                        $validator->errors()->add('name', "You can't update the entrance's name.");
                    elseif ($this->is_free !== $entrance->is_free)
                        $validator->errors()->add('is_free', "You can't update the entrance's gratuity.");
                    elseif ($this->min_buy !== $entrance->min_buy)
                        $validator->errors()->add('min_buy', "You can't update the entrance's minimum quantity.");
                    elseif ($this->max_buy !== $entrance->max_buy)
                        $validator->errors()->add('max_buy', "You can't update the entrance's maximum quantity.");
                    elseif ($this->starts_at !== $entrance->starts_at->format('Y-m-d'))
                        $validator->errors()->add('starts_at', "You can't update the entrance's start date.");
                } elseif($entrance->starts_at->gt(Carbon::createFromFormat('Y-m-d', $this->starts_at)->endOfDay())) {
                    $validator->errors()->add('starts_at', "The start day can't be before today.");
                }

                if ($entrance->available->lot > count($this->lots))
                    $validator->errors()->add('lots', "You can't delete the entrance's active lot.");
                else {
                    $prev = $entrance->starts_at;
                    foreach ($this->lots as $key => $lot) {
                        $aux = $entrance->lots()->where('number', $key + 1)->first();
                        if (($key + 1) < $entrance->available->lot) {
                            if (intval($lot['amount']) !== $aux->amount) {
                                $validator->errors()->add('lots.' . $key . '.amount', "You can't update a past lot.");
                                break;
                            } elseif (intval(floatval($lot['value']) * 100) !== $aux->value) {
                                $validator->errors()->add('lots.' . $key . '.value', "You can't update a past lot.");
                                break;
                            } elseif ($lot['finishes_at'] !== $aux->finishes_at->format('Y-m-d')) {
                                $validator->errors()->add('lots.' . $key . '.finishes_at', "You can't update a past lot.");
                                break;
                            }
                        } elseif (($key + 1) === $entrance->available->lot) {
                            if (intval($lot['amount']) < $aux->amount) {
                                $validator->errors()->add('lots.' . $key . '.amount', "You can't decrease the amount of an active lot.");
                                break;
                            } elseif (intval(floatval($lot['value']) * 100) !== $aux->value) {
                                $validator->errors()->add('lots.' . $key . '.value', "You can't update a past lot.");
                                break;
                            } elseif ($lot['finishes_at'] !== $aux->finishes_at->format('Y-m-d')) {
                                $validator->errors()->add('lots.' . $key . '.finishes_at', "You can't update a past lot.");
                                break;
                            }
                            $prev = $aux->finishes_at;
                        } else {
                            $finish = Carbon::createFromFormat('Y-m-d', $lot['finishes_at'])->endOfDay();
                            if ($event->starts_at->lte($finish->startOfDay())) {
                                $validator->errors()->add('lots.' . $key . '.finishes_at', 'The end of a lot must be before event start');
                                break;
                            }
                            if ($prev->gte($finish)) {
                                $validator->errors()->add('lots.' . $key . '.finishes_at', 'The end of a lot must be after the end of previous lot.');
                                break;
                            }
                            $prev = $finish;
                        }
                    }
                }
            }
        });
    }
}
