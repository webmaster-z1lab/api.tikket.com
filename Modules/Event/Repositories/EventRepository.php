<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 15:46
 */

namespace Modules\Event\Repositories;

use Modules\Event\Models\Event;
use Z1lab\JsonApi\Repositories\ApiRepository;

class EventRepository extends ApiRepository
{
    /**
     * EventRepository constructor.
     *
     * @param \Modules\Event\Models\Event $model
     */
    public function __construct(Event $model)
    {
        parent::__construct($model, 'event');
    }

    /**
     * @param array $data
     *
     * @return \Modules\Event\Models\Event
     */
    public function create(array $data)
    {
        $event = $this->model->create($data);

        $event->address()->create($data['address']);

        $event->producer()->create($data['producer']);

        $this->setCacheKey($event->id);
        $this->remember($event);

        return $event;
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function update(array $data, string $id)
    {
        $event = $this->find($id);
        $event->update($data);

        $event->address->update($data['address']);

        $event->producer->update($data['producer']);

        $this->setCacheKey($id);
        $this->flush()->remember($event);

        return $event->fresh();
    }
}
