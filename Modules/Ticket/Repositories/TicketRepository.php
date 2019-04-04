<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 18/02/2019
 * Time: 15:33
 */

namespace Modules\Ticket\Repositories;

use Modules\Ticket\Models\Ticket;
use Z1lab\JsonApi\Repositories\ApiRepository;

class TicketRepository extends ApiRepository
{
    /**
     * TicketRepository constructor.
     *
     * @param  \Modules\Ticket\Models\Ticket  $model
     */
    public function __construct(Ticket $model)
    {
        parent::__construct($model, 'ticket');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser()
    {
        return $this->model->where('participant.email', \Auth::user()->email)
            ->orWhereHas('order', function ($query) {
                $query->where('costumer.user_id', \Auth::id());
            })->latest()
            ->get();
    }
}
