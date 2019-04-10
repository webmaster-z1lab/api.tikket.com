<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Phone
 *
 * @package Modules\Order\Models
 *
 * @property string id
 * @property string area_code
 * @property string formatted_phone
 * @property string phone
 */
class Phone extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'area_code',
        'phone',
    ];

    public function getFormattedPhoneAttribute()
    {
        $start = strlen($this->attributes['phone']) === 8 ? 4 : 5;

        return "({$this->attributes['area_code']}) " . substr_replace($this->attributes['phone'], '-', $start, 0);
    }

    public function getPhoneNumberAttribute()
    {
        return $this->attributes['area_code'] . $this->attributes['phone'];
    }
}
