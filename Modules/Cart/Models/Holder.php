<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Holder extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['name', 'document', 'birth_date'];

    protected $dates = ['birth_date'];

    protected $dateFormat = 'Y-m-d';

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }
}
