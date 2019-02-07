<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Costumer
 *
 * @package Modules\Order\Models
 *
 * @property string user_id
 * @property string name
 * @property string email
 * @property string document
 * @property \Modules\Order\Models\Phone phone
 */
class Costumer extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'document',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }
}
