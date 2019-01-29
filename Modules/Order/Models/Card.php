<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Card extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['brand', 'number', 'token', 'installments', 'parcel'];

    protected $casts = [
        'installments' => 'integer',
        'parcel'       => 'integer',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function holder()
    {
        return $this->embedsOne(Holder::class);
    }
}
