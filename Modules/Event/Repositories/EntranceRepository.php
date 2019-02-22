<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 16:20
 */

namespace Modules\Event\Repositories;

use Carbon\Carbon;
use Modules\Event\Models\Entrance;
use Modules\Event\Models\Event;
use Z1lab\JsonApi\Traits\CacheTrait;

class EntranceRepository
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

        $data['starts_at'] = $previous = Carbon::createFromFormat('Y-m-d', $data['starts_at'])->startOfDay();
        $entrance = $event->entrances()->create(array_except($data, ['lots']));

        foreach ($data['lots'] as $key => $lot) {
            $lot['number'] = $key + 1;
            $lot['value'] = (int)($lot['value'] * 100);
            $lot['fee'] = (int)($lot['value'] / 10);
            $lot['finishes_at'] = Carbon::createFromFormat('Y-m-d', $lot['finishes_at'])->endOfDay();
            $lot['starts_at'] = $previous;
            $entrance->lots()->create($lot);
            $previous = $lot['finishes_at']->addDay()->startOfDay();
        }

        return $event->fresh();
    }

    /**
     * @param array  $data
     * @param string $event
     * @param string $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function update(array $data, string $event, string $id)
    {
        $event = $this->event($event);

        $data['starts_at'] = $previous = Carbon::createFromFormat('Y-m-d', $data['starts_at'])->startOfDay();
        $entrance = $event->entrances()->find($id);
        $entrance->update(array_except($data, ['lots']));

        if ($entrance->lots()->exists()) $entrance->lots()->delete();

        foreach ($data['lots'] as $key => $lot) {
            $lot['number'] = $key + 1;
            $lot['value'] = (int)($lot['value'] * 100);
            $lot['fee'] = (int)($lot['value'] / 10);
            $lot['finishes_at'] = Carbon::createFromFormat('Y-m-d', $lot['finishes_at'])->endOfDay();
            $lot['starts_at'] = $previous;
            $entrance->lots()->create($lot);
            $previous = $lot['finishes_at']->addDay()->startOfDay();
        }

        return $event->fresh();
    }

    /**
     * @param string $event
     *
     * @return \Modules\Event\Models\Event
     * @throws \Exception
     */
    public function destroy(string $event, string $id)
    {
        $event = $this->event($event);

        $entrance = $event->entrances()->find($id);

        if (NULL === $entrance) abort(404);

        $entrance->delete();

        return $event->fresh();
    }

    /**
     * @param string $event
     * @param string $id
     *
     * @return \Modules\Event\Models\Entrance
     */
    public function find(string $event, string $id)
    {
        $event = $this->event($event);

        /** @var \Modules\Event\Models\Entrance $entrance */
        $entrance = $event->entrances()->find($id);

        if (NULL === $entrance) abort(404);

        return $entrance;
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(string $event)
    {
        $event = $this->event($event);

        return $event->entrances;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getEntrances()
    {
        return Entrance::where('available.finishes_at', '<', now())->get();
    }
}
