<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Cart\Scopes\NotExpiredScope;
use Modules\Event\Models\Event;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'type', 'hash', 'callback', 'expires_at'];

    protected $dates = ['expires_at'];

    protected $attributes = ['type' => 'credit_card'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function tickets()
    {
        return $this->embedsMany(Ticket::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function card()
    {
        return $this->embedsOne(Card::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new NotExpiredScope());
    }
}
