<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 15:46
 */

namespace Modules\Event\Repositories;

use Carbon\Carbon;
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
        $data['starts_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['starts_at']);
        $data['finishes_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['finishes_at']);
        $data['url'] = snake_case($data['name']);
        $data['referer'] = \Request::url();
        $data['user_id'] = \Auth::id();

        $event = $this->model->create(array_except($data, ['cover']));

        $image = $event->image()->create(['original' => $data['cover']]);
        $image->event()->associate($event);
        $image->save();

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

        $data['starts_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['starts_at']);
        $data['finishes_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['finishes_at']);
        $data['url'] = snake_case($data['name']);
        $data['referer'] = \Request::url();
        $data['user_id'] = \Auth::id();

        $event = $event->update(array_except($data, ['cover']));

        if ($event->image()->exists()) $event->image()->delete();

        $image = $event->image()->create(['original' => $data['cover']]);
        $image->event()->associate($event);
        $image->save();

        $this->setCacheKey($id);
        $this->flush()->remember($event);

        return $event;
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    public function findByUrl(string $url)
    {
        $event = $this->model->where('url', $url)->first();

        if (NULL === $event) abort(404);

        return $event;
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function setAddress(array $data, string $id)
    {
        $event = $this->find($id);

        if ($event->address()->exists()) $event->address()->delete();

        if (ends_with($data['formatted'], 'Brasil'))
            $data['formatted'] = str_replace_last(', Brasil', '', $data['formatted']);

        $address = $event->address()->create(array_except($data, ['coordinate']));

        $address->coordinate()->create(['location' => $data['coordinate']]);

        return $event->fresh();
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Event\Models\Event|null
     */
    public function setFeeIsHidden(array $data, string $id)
    {
        $event = $this->find($id);

        $event->update($data);

        if ($event->fee_is_hidden) {
            foreach ($event->entrances as $entrance) {
                foreach ($entrance->lots as $lot) {
                    $lot->value = $lot->value - $lot->fee;
                    $lot->save();
                }
                $entrance->save();
            }
        } else {
            foreach ($event->entrances as $entrance) {
                foreach ($entrance->lots as $lot) {
                    $lot->value = $lot->fee * 10;
                    $lot->save();
                }
                $entrance->save();
            }
        }

        return $event->fresh();
    }

    /**
     * @param string $id
     *
     * @return \Modules\Event\Models\Event|null
     */
    public function finilize(string $id)
    {
        $event = $this->find($id);

        if ($event->status !== 'draft') abort(400, 'This event is not a draft.');

        $event->update(['status' => 'complete']);

        return $event->fresh();
    }
}
