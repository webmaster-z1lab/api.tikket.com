<?php

namespace Modules\Order\Http\Requests;

use Carbon\Carbon;
use Modules\Cart\Models\Cart;
use Modules\Event\Models\Entrance;
use Modules\Order\Models\Order;
use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class OrderRequest extends ApiFormRequest
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
            'cart' => 'bail|required|exists:carts,_id',
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
            $cart = Cart::find($this->request->get('cart'));
            $sent_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->request->get('sent_at'));

            if ($sent_at > $cart->expires_at) {
                $validator->errors()->add('cart', "Your cart expired before you completed the purchase");
            }

            foreach ($cart->bags as $bag) {
                $entrance = Entrance::find($bag->entrance_id);
                $sold = $entrance->available->waiting + $entrance->available->sold;

                if ($sold + $bag->amount > $entrance->available->amount) {
                    $validator->errors()->add('tickets', "There are no tickets for entrance '$entrance->name' at the moment.");
                } else {
                    $buyed = Order::processed()->byPerson($cart->costumer->document)->get();

                    if (NULL !== $buyed && !$buyed->isEmpty()) {
                        $bagBuy = $buyed->sum(function ($order) use ($bag) {
                            return $order->bags()->where('entrance_id', $bag->entrance_id)->sum('amount');
                        });

                        if ($bagBuy + $bag->amount > $entrance->max_buy) {
                            $validator->errors()->add('tickets', "Entry '$entrance->name' only allows $entrance->max_buy tickets per person.");
                        }
                    }
                }
            }
        });
    }
}
