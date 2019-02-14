<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 14/02/2019
 * Time: 14:43
 */

namespace Modules\Order\Models;


interface OrderStatus
{
    public const WAITING = 'waiting';
    public const PAID = 'paid';
    public const CANCELED = 'canceled';
    public const REVERSED = 'reversed';
}
