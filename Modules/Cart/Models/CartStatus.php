<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 14/02/2019
 * Time: 16:45
 */

namespace Modules\Cart\Models;


interface CartStatus
{
    public const OPENED = 'opened';
    public const FINISHED = 'finished';
    public const RECYCLED = 'recycled';
}
