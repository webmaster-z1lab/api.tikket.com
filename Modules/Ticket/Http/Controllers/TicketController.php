<?php

namespace Modules\Ticket\Http\Controllers;

use Modules\Ticket\Repositories\TicketRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class TicketController extends ApiController
{
    public function __construct(TicketRepository $repository)
    {
        parent::__construct($repository, 'Ticket');
        $this->middleware(['auth', 'can:ticket_receiver,ticket']);
    }
}
