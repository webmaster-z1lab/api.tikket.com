<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 16:20
 */

namespace Modules\Event\Repositories;

use Carbon\Carbon;
use Modules\Event\Jobs\UpdateAvailableLot;
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
            $lot['fee'] = $this->calcFee($entrance, $lot['value']);
            $lot['starts_at'] = $previous;
            $lot['finishes_at'] = Carbon::createFromFormat('Y-m-d', $lot['finishes_at'])->endOfDay();
            if ($event->starts_at->isSameDay($lot['finishes_at'])) {
                $lot['finishes_at'] = $event->starts_at->subHours(3);
            }
            $entrance->lots()->create($lot);
            $previous = $lot['finishes_at']->addDay()->startOfDay();
        }

        return $event->fresh();
    }

    /**
     * @param  array  $data
     * @param  string  $event
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event|null
     * @throws \Exception
     */
    public function update(array $data, string $event, string $id)
    {
        $event = $this->event($event);

        $data['starts_at'] = $previous = Carbon::createFromFormat('Y-m-d', $data['starts_at'])->startOfDay();

        /** @var \Modules\Event\Models\Entrance $entrance */
        $entrance = $event->entrances()->find($id);

        $entrance->is_locked
            ? $entrance->update(['description' => $data['description']])
            : $entrance->update(array_except($data, ['lots']));

        $current_lot = $entrance->available->lot;
        $count_lots = count($data['lots']);

        for ($i = 0; $i < $count_lots; $i++) {
            $lot = $entrance->getLot($i + 1);
            if (($i + 1) < $current_lot) {
                $previous = $lot->finishes_at->addDay()->startOfDay();
                continue;
            } else {
                $data['lots'][$i]['value'] = (int) ((float) $data['lots'][$i]['value'] * 100);
                $data['lots'][$i]['fee'] = $this->calcFee($entrance, $data['lots'][$i]['value']);
                $data['lots'][$i]['finishes_at'] = Carbon::createFromFormat('Y-m-d', $data['lots'][$i]['finishes_at'])->endOfDay();
                $data['lots'][$i]['starts_at'] = $previous;

                if ($event->starts_at->isSameDay($data['lots'][$i]['finishes_at'])) {
                    $data['lots'][$i]['finishes_at'] = $event->starts_at->subHours(3);
                }

                if ($lot->update($data['lots'][$i]) && ($i + 1) === $current_lot) {
                    UpdateAvailableLot::dispatch($entrance);
                }
            }
        }

        foreach ($entrance->lots as $lot) {
            if ($lot->number <= count($data['lots'])) {
                continue;
            }

            $lot->delete();
        }

        $entrance->save();

        return $event->fresh();
    }

    /**
     * @param string $event
     *
     * @param string $id
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

    /**
     * @param string $id
     *
     * @return \Modules\Event\Models\Entrance
     */
    public function getEntrance(string $id)
    {
        $entrance = \Cache::remember("entrance-$id", $this->cacheDefault(), function () use ($id) {
            return Entrance::find($id);
        });

        if (NULL === $entrance) abort(404);

        return $entrance;
    }

    /**
     * @param  \Modules\Event\Models\Entrance  $entrance
     * @param  int  $value
     *
     * @return int|void
     */
    private function calcFee($entrance, int $value)
    {
        if(!$entrance->is_free) return max(($value / 10), config('fee.min'));

        return 0;
    }
}
