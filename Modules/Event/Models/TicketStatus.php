<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 13/02/2019
 * Time: 17:13
 */

namespace Modules\Event\Models;


interface TicketStatus
{
    /**
     * Entrances available for sell
     */
    public const AVAILABLE = 'available';
    /**
     * Entrances in orders waiting for payment
     */
    public const WAITING = 'waiting';
    /**
     * Entrances in opened carts
     */
    public const RESERVED = 'reserved';
    /**
     * Entrances sold
     */
    public const SOLD = 'sold';
    /**
     * Entrances offered for sell
     */
    public const AMOUNT = 'amount';
}
