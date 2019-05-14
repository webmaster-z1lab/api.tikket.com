<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
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
                        $validator->errors()->add('tickets', "Não existem ingressos disponíveis para a entrada '$entrance->name' no momento.");
                    } else {
                        $validator->errors()->add('tickets', "Nós temos somente {$entrance->available->available} ingresso(s) restante(s) para a entrada '$entrance->name'");
                    }
                } elseif ($ticket['quantity'] > $entrance->max_buy) {
                    $validator->errors()->add('tickets', "Você não pode mais comprar ingressos para a entrada '$entrance->name'. O limite permitido pela organização do evento é de $entrance->max_buy ingresso(s).");
                } elseif ($ticket['quantity'] < $entrance->min_buy) {
                    $validator->errors()->add('tickets', "O mínimo de ingressos para a entrada '$entrance->name' é de $entrance->min_buy.");
                }

                $buyed = Order::processed()->byPerson(\Auth::user()->document)->get();

                if (NULL !== $buyed && !$buyed->isEmpty()) {
                    $bagBuy = $buyed->sum(function ($order) use ($entrance) {
                        return $order->bags()->where('entrance_id', $entrance->id)->sum('amount');
                    });

                    if ($bagBuy + $ticket['quantity'] > $entrance->max_buy) {
                        $validator->errors()->add('tickets', "A entrada '$entrance->name' permite somente $entrance->max_buy ingresso(s) por pessoa.");
                    }
                }

                if (!now()->between($entrance->available->starts_at, $entrance->available->finishes_at)) {
                    $validator->errors()->add('tickets', "As vendas do Lote {$entrance->available->lot} não estão disponíveis.");
                }
            }
        });
    }
}
