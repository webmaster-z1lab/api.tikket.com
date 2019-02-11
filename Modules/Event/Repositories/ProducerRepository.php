<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 06/02/2019
 * Time: 15:43
 */

namespace Modules\Event\Repositories;

use Modules\Event\Models\Event;
use Modules\Event\Models\Producer;
use Z1lab\JsonApi\Traits\CacheTrait;

class ProducerRepository
{
    use CacheTrait;

    /**
     * @var \Modules\Event\Models\Event
     */
    private $model;

    /**
     * ProducerRepository constructor.
     *
     * @param \Modules\Event\Models\Event $model
     */
    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function event(string $id)
    {
        $event = \Cache::remember("producer-$id", $this->cacheDefault(), function () use ($id) {
            return $this->model->find($id);
        });

        if (NULL === $event) abort(404);

        return $event;
    }

    /**
     * @param array  $data
     * @param string $event
     *
     * @return \Modules\Event\Models\Event
     */
    public function insert(array $data, string $event)
    {
        $event = $this->event($event);

        if ($event->producer()->exists()) $event->producer()->delete();

        $data['user_id'] = \Auth::id();

        $producer = $event->producer()->create($data);

        $event->producer()->associate($producer);

        $event->save();

        return $event->fresh();
    }

    /**
     * @param string $event
     * @param string $id
     *
     * @return \Modules\Event\Models\Event
     * @throws \Exception
     */
    public function destroy(string $event, string $id)
    {
        $event = $this->event($event);

        $producer = $event->producer()->find($id);

        if (NULL === $producer) abort(404);

        $producer->delete();

        return $event->fresh();
    }

    /**
     * @param string $event
     * @param string $id
     *
     * @return \Modules\Event\Models\Producer|null
     */
    public function find(string $event, string $id)
    {
        $event = $this->event($event);

        $producer = $event->producer()->find($id);

        if (NULL === $producer) abort(404);

        return $producer;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser()
    {
        return Producer::where('user_id', \Auth::id())->get();
    }
}
