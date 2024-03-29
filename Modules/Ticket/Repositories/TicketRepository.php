<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 18/02/2019
 * Time: 15:33
 */

namespace Modules\Ticket\Repositories;

use Modules\Event\Models\Event;
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
        $past = \Request::query('past', FALSE);

        $past = $past === 'false' ? FALSE : boolval($past);

        if ($past) {
            return $this->model->whereIn('event.status', [[Event::CANCELED, Event::FINALIZED]])
                ->where(function ($query) {
                    $query->where('participant.email', \Auth::user()->email)
                        ->orWhereHas('order', function ($query) {
                            $query->where('customer.user_id', Auth::id());
                        });
                })->latest()->get();
        }

        return $this->model->whereNotIn('event.status', [[Event::CANCELED, Event::FINALIZED]])
            ->where(function ($query) {
                $query->where('participant.email', \Auth::user()->email)
                    ->orWhereHas('order', function ($query) {
                        $query->where('customer.user_id', \Auth::id());
                    });
            })->latest()->get();
    }
}
