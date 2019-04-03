<?php

namespace Modules\Cart\Http\Requests;

use Modules\Event\Models\Entrance;
use Modules\Order\Models\Order;
use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class CartRequest extends ApiFormRequest
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
            'callback'           => 'bail|required|string',
            'event'              => 'bail|required|exists:events,_id',
            'tickets'            => 'bail|required|array|min:1',
            'tickets.*.entrance' => 'bail|required|exists:entrances,_id',
            'tickets.*.quantity' => 'bail|required|integer|min:1',
            'tickets.*.lot'      => 'bail|required|integer|min:1',
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
            foreach ($this->request->get('tickets') as $ticket) {
                $entrance = Entrance::find($ticket['entrance']);

                if ($entrance->available->available < $ticket['quantity']) {
                    if ($entrance->available->available === 0) {
                        $validator->errors()->add('tickets', "There are no tickets for entrance '$entrance->name' at the moment.");
                    } else {
                        $validator->errors()->add('tickets', "We have only {$entrance->available->available} tickets remaining for the entrance '$entrance->name'");
                    }
                } elseif ($ticket['quantity'] > $entrance->max_buy) {
                    $validator->errors()->add('tickets', "The max tickets for entrance '$entrance->name' is $entrance->max_buy.");
                } elseif ($ticket['quantity'] < $entrance->min_buy) {
                    $validator->errors()->add('tickets', "The minimum tickets for entrance '$entrance->name' is $entrance->min_buy.");
                }

                $buyed = Order::processed()->byPerson(\Auth::user()->document)->get();

                if (NULL !== $buyed && !$buyed->isEmpty()) {
                    $bagBuy = $buyed->sum(function ($order) use ($entrance) {
                        return $order->bags()->where('entrance_id', $entrance->id)->sum('amount');
                    });

                    $user = \Auth::user()->name;

                    \Log::info("$user already buy $bagBuy tickets.");

                    if ($bagBuy + $ticket['quantity'] > $entrance->max_buy) {
                        $validator->errors()->add('tickets', "Entry '$entrance->name' only allows $entrance->max_buy tickets per person.");
                    }
                }

                if (!now()->between($entrance->available->starts_at, $entrance->available->finishes_at)) {
                    $validator->errors()->add('tickets', "Sales for lot {$entrance->available->lot} are not available.");
                }
            }
        });
    }
}
