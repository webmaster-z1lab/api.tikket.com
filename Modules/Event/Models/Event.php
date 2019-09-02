<?php

namespace Modules\Event\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Event
 *
 * @package Modules\Event\Models
 *
 * @property string                                   name
 * @property string                                   url
 * @property string                                   description
 * @property string                                   body
 * @property string                                   category
 * @property string                                   types
 * @property string                                   referer
 * @property \Carbon\Carbon                           starts_at
 * @property \Carbon\Carbon                           finishes_at
 * @property integer                                  fee_percentage
 * @property bool                                     fee_is_hidden
 * @property bool                                     is_active
 * @property bool                                     is_public
 * @property bool                                     is_locked
 * @property \Illuminate\Database\Eloquent\Collection entrances
 * @property \Modules\Event\Models\Address            address
 * @property \Modules\Event\Models\Producer           producer
 * @property \Modules\Event\Models\Image              image
 * @method \Modules\Event\Models\Event                city(string $value = NULL)
 * @method \Modules\Event\Models\Event                period(string $period = NULL)
 * @method \Modules\Event\Models\Event                search(string $keywords = NULL)
 * @property-read \Carbon\Carbon                      created_at
 * @property-read \Carbon\Carbon                      updated_at
 */
class Event extends Model
{
    use SoftDeletes;

    public const DRAFT     = 'draft';
    public const COMPLETED = 'completed';
    public const FINALIZED = 'finalized';
    public const CANCELED  = 'canceled';
    public const PUBLISHED = 'published';

    const STATUS_ACTIVE  = FALSE;
    const STATUS_PUBLIC  = TRUE;
    const STATUS_FEE     = TRUE;
    const STATUS_LOCKED  = FALSE;
    const FEE_PERCENTAGE = 10;

    protected $attributes = [
        'is_active'      => self::STATUS_ACTIVE,
        'is_public'      => self::STATUS_PUBLIC,
        'fee_is_hidden'  => self::STATUS_FEE,
        'fee_percentage' => self::FEE_PERCENTAGE,
        'is_locked'      => self::STATUS_LOCKED,
        'status'         => self::DRAFT,
    ];

    protected $fillable = [
        'name',
        'user_id',
        'url',
        'description',
        'body',
        'category',
        'types',
        'referer',
        'starts_at',
        'finishes_at',
        'fee_percentage',
        'fee_is_hidden',
        'is_public',
        'is_active',
        'is_locked',
        'status',
    ];

    protected $dates = [
        'starts_at',
        'finishes_at',
    ];

    protected $casts = [
        'fee_percentage' => 'integer',
        'fee_is_hidden'  => 'boolean',
        'is_public'      => 'boolean',
        'is_active'      => 'boolean',
        'is_locked'      => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entrances()
    {
        return $this->hasMany(Entrance::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(Image::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * @param  \Jenssegers\Mongodb\Eloquent\Builder  $query
     * @param  string|NULL                           $value
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopeCity(Builder $query, string $value = NULL)
    {
        if (NULL !== $value && $value !== '') {
            $data = explode('-', $value);

            return $query->where('address.city', trim($data[0]))->where('address.state', trim($data[1]));
        }

        return $query;
    }

    /**
     * @param  \Jenssegers\Mongodb\Eloquent\Builder  $query
     * @param  string|NULL                           $period
     *
     * @return \Illuminate\Database\Query\Builder|\Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopePeriod(Builder $query, string $period = NULL)
    {
        if (NULL !== $period && $period !== '') {
            $start = today()->startOfDay();
            $end = today()->endOfDay();

            switch ($period) {
                case 'today':
                    break;
                case 'tomorrow':
                    $start->addDay();
                    $end->addDay();
                    break;
                case 'this_week':
                    $start->startOfWeek();
                    $end->endOfWeek();
                    break;
                case 'this_weekend':
                    if ($start->isSunday()) {
                        break;
                    } elseif ($start->isFriday() || $start->isSaturday()) {
                        $end->endOfWeek();
                    } else {
                        $start->next(Carbon::FRIDAY);
                        $end->endOfWeek();
                    }
                    break;
                case 'next_week':
                    $start->startOfWeek()->next();
                    $end->endOfWeek()->next();
                    break;
                case 'this_month':
                    $start->startOfMonth();
                    $end->endOfMonth();
                    break;
                case 'next_month':
                    $start->addMonth()->startOfMonth();
                    $end->addMonth()->endOfMonth();
                    break;
                default:
                    return $query;
            }

            return $query->whereBetween('starts_at', [$start, $end]);
        }

        return $query;
    }

    /**
     * @param  \Jenssegers\Mongodb\Eloquent\Builder  $query
     * @param  string|NULL                           $keywords
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $keywords = NULL)
    {
        if (NULL !== $keywords && $keywords !== '') return $query->whereRaw(['$text' => ['$search' => $keywords]]);

        return $query;
    }

    /**
     * @param  \Jenssegers\Mongodb\Eloquent\Builder  $query
     * @param  string|NULL                           $category
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopeCategory(Builder $query, string $category = NULL)
    {
        if (NULL !== $category) return $query->where('category', $category);

        return $query;
    }
}
