<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 18/02/2019
 * Time: 15:33
 */

namespace Modules\Ticket\Repositories;

use Illuminate\Support\Str;
use Modules\Order\Repositories\OrderRepository;
use Modules\Ticket\Models\Ticket;
use Z1lab\JsonApi\Repositories\ApiRepository;

class TicketRepository extends ApiRepository
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    private $orderRepository;

    /**
     * TicketRepository constructor.
     *
     * @param \Modules\Ticket\Models\Ticket               $model
     * @param \Modules\Order\Repositories\OrderRepository $orderRepository
     */
    public function __construct(Ticket $model, OrderRepository $orderRepository)
    {
        parent::__construct($model, 'ticket');
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $data
     *
     * @return \Modules\Ticket\Models\Ticket|null
     */
    public function create(array $data)
    {
        $data['code'] = strtoupper(Str::random(Ticket::CODE_LENGTH));

        /** @var \Modules\Ticket\Models\Ticket $ticket */
        $ticket = $this->model->create(array_only($data, ['name', '1ot', 'code']));

        $ticket->order()->associate($data['order_id']);

        $ticket->entrance()->associate($data['entrance_id']);

        $ticket->save();

        $ticket->participant()->create($data['participant']);

        /** @var \Modules\Ticket\Models\Event $event */
        $event = $ticket->event()->create(array_except($data['event'], ['image']));

        $event->image()->create($data['event']['image']);

        $ticket = $ticket->fresh();

        $this->setCacheKey($ticket->id);
        $this->remember($ticket);

        return $ticket;
    }

    /**
     * @param string $order_id
     */
    public function createFromOrder(string $order_id)
    {
        $order = $this->orderRepository->find($order_id);

        $event_data = [
            'event_id'  => $order->event->id,
            'name'      => $order->event->name,
            'url'       => $order->event->url,
            'address'   => $order->event->address->formatted,
            'starts_at' => $order->event->starts_at,
            'image'     => $order->event->image->toArray(),
        ];

        $order->tickets->each(function ($ticket, $key) use ($event_data, $order_id) {
            $data = [
                'entrance_id' => $ticket->entrance_id,
                'order_id'    => $order_id,
                'name'        => $ticket->entrance,
                'lot'         => $ticket->lot,
                'event'       => $event_data,
                'participant' => [
                    'name'     => $ticket->name,
                    'document' => $ticket->document,
                    'email'    => $ticket->email,
                ],
            ];

            $this->create($data);
        });
    }
}
