<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Coordinate
 *
 * @package Modules\Event\Models
 *
 * @property array location
 *
 */
class Coordinate extends Model
{
    const TYPE = 'Point';

    public $timestamps = FALSE;

    protected $attributes = [
        'type' => self::TYPE,
    ];

    protected $fillable = [
        'location',
    ];
}
