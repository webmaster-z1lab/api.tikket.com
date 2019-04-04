<?php

namespace Modules\Ticket\Http\Controllers;

use Modules\Ticket\Repositories\TicketRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class TicketController extends ApiController
{
    /**
     * TicketController constructor.
     *
     * @param  \Modules\Ticket\Repositories\TicketRepository  $repository
     */
    public function __construct(TicketRepository $repository)
    {
        parent::__construct($repository, 'Ticket');
        $this->middleware(['auth', 'can:ticket_receiver,ticket'])->except('index');
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        return $this->collectResource($this->repository->getByUser());
    }
}
