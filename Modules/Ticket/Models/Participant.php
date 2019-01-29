<?php

namespace Modules\Ticket\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Participant
 *
 * @package Modules\Ticket\Models
 *
 * @property string name
 * @property string document
 * @property string email
 */
class Participant extends Model
{
    protected $fillable = [
        'name',
        'document',
        'email',
    ];
}
