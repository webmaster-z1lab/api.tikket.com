<?php

namespace Modules\Order\Http\Requests;

use Modules\Event\Models\Entrance;
use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class SaleRequest extends ApiFormRequest
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
            'event'              => 'bail|required|exists:events,_id',
            'tickets'            => 'bail|required|array|min:1',
            'tickets.*.entrance' => 'bail|required|exists:entrances,_id',
            'tickets.*.lot'      => 'bail|required|integer|min:1',
            'tickets.*.name'     => 'bail|nullable|string',
            'tickets.*.document' => 'bail|nullable|cpf',
            'tickets.*.email'    => 'bail|nullable|email',
            'tickets.*.code'     => 'bail|required|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tickets = collect($this->request->get('tickets'));
            foreach ($tickets->groupBy('entrance') as $entrance_id => $items) {
                $entrance = Entrance::find($entrance_id);

                if ($entrance->available->available < $items->count()) {
                    if ($entrance->available->available === 0) {
                        $validator->errors()->add('tickets', "There are no tickets for entrance '$entrance->name' at the moment.");
                    } else {
                        $validator->errors()->add('tickets', "We have only {$entrance->available->available} tickets remaining for the entrance '$entrance->name'");
                    }
                } elseif ($items->count() > $entrance->max_buy) {
                    $validator->errors()->add('tickets', "The max tickets for entrance '$entrance->name' is $entrance->max_buy.");
                } elseif ($items->count() < $entrance->min_buy) {
                    $validator->errors()->add('tickets', "The minimum tickets for entrance '$entrance->name' is $entrance->min_buy.");
                }

                foreach ($items as $ticket) {
                    if (intval($ticket['lot']) !== $entrance->available->lot)
                        $validator->errors()->add('tickets', "Sales for lot {$ticket['lot']} are not available.");

                    if ((filled($ticket['name']) || filled($ticket['document']) || filled($ticket['email'])) &&
                        (!filled($ticket['name']) || !filled($ticket['document']) || !filled($ticket['email'])))
                        $validator->errors()->add('tickets', 'Linking a ticket requires name, document and email.');
                }

//                if (!now()->between($entrance->available->starts_at, $entrance->available->finishes_at)) {
//                    $validator->errors()->add('tickets', "Sales for lot {$entrance->available->lot} are not available.");
//                }
            }
        });
    }
}
