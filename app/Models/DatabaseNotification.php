<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 27/06/2019
 * Time: 20:13
 */

namespace App\Models;

use Illuminate\Notifications\DatabaseNotificationCollection;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class DatabaseNotification
 *
 * @package App\Models
 *
 * @property-read string         _id
 * @property-read string         id
 * @property-read array          data
 * @property-read string         type
 * @property-read \Carbon\Carbon created_at
 * @property-read \Carbon\Carbon read_at
 */
class DatabaseNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead(): void
    {
        if ($this->read_at === NULL) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     *
     * @return void
     */
    public function markAsUnread(): void
    {
        if ($this->read_at !== NULL) {
            $this->forceFill(['read_at' => NULL])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function read(): bool
    {
        return $this->read_at !== NULL;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function unread(): bool
    {
        return $this->read_at === NULL;
    }

    /**
     * Create a new database notification collection instance.
     *
     * @param  array  $models
     *
     * @return \Illuminate\Notifications\DatabaseNotificationCollection
     */
    public function newCollection(array $models = []): \Illuminate\Notifications\DatabaseNotificationCollection
    {
        return new DatabaseNotificationCollection($models);
    }
}

