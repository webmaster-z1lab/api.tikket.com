<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Costumer
 *
 * @package Modules\Cart\Models
 *
 * @property string document
 * @property \Modules\Cart\Models\Phone phone
 */
class Costumer extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'document'
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }
}
