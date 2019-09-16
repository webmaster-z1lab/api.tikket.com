<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 15:46
 */

namespace Modules\Event\Repositories;

use App\Notifications\Organizer\EventPublished;
use App\Traits\EventValidator;
use Carbon\Carbon;
use Modules\Event\Jobs\DeletePermissions;
use Modules\Event\Models\Event;
use Modules\Event\Models\Permission;
use Modules\Ticket\Jobs\UpdateEventInfo;
use Z1lab\JsonApi\Repositories\ApiRepository;

class EventRepository extends ApiRepository
{
    use EventValidator;

    /**
     * EventRepository constructor.
     *
     * @param  \Modules\Event\Models\Event  $model
     */
    public function __construct(Event $model)
    {
        parent::__construct($model, 'event');
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Event\Models\Event
     */
    public function create(array $data)
    {
        $data['starts_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['starts_at']);
        $data['finishes_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['finishes_at']);
        $data['url'] = str_slug($data['name']);
        $data['referer'] = \Request::url();
        $data['user_id'] = \Auth::id();
        $data['is_public'] = $data['is_public'] === 'false' ? FALSE : (bool) $data['is_public'];

        /** @var \Modules\Event\Models\Event $event */
        $event = $this->model->create(array_except($data, ['cover']));

        /** @var \Modules\Event\Models\Image $image */
        $image = $event->image()->create(['original' => $data['cover']]);
        $image->event()->associate($event);
        $image->save();

        $event->permissions()->save(new Permission([
                'type'  => Permission::MASTER,
                'email' => \Auth::user()->email,
            ])
        );

        $event->save();

        $this->setCacheKey($event->id);
        $this->remember($event);

        return $event;
    }

    /**
     * @param  array   $data
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function update(array $data, string $id)
    {
        /** @var \Modules\Event\Models\Event $event */
        $event = $this->find($id);

        $data['starts_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['starts_at']);
        $data['finishes_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['finishes_at']);
        $data['referer'] = \Request::url();
        $data['is_public'] = $data['is_public'] === 'false' ? FALSE : (bool) $data['is_public'];

        $event->update(array_except($data, ['cover']));

        if ($event->image()->exists()) {
            $event->image()->delete();
        }

        /** @var \Modules\Event\Models\Image $image */
        $image = $event->image()->create(['original' => $data['cover']]);
        $image->event()->associate($event);
        $image->save();

        $this->setCacheKey($id);
        $this->flush()->remember($event->fresh());

        if ($event->is_locked) UpdateEventInfo::dispatch($event);

        return $event;
    }

    /**
     * @param  string  $id
     *
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $event = $this->find($id);

        if (in_array($event->status, [Event::DRAFT, Event::COMPLETED])) {
            $this->flush();

            DeletePermissions::dispatch($id);

            return $event->delete();
        }

        abort(400, "This event can't be canceled or unpublished.");

        return FALSE;
    }

    /**
     * @param  string  $url
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
     * @param  array   $data
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function setAddress(array $data, string $id)
    {
        $event = $this->find($id);

        if ($event->address()->exists()) {
            $event->address()->delete();
        }

        if (ends_with($data['formatted'], 'Brasil')) {
            $data['formatted'] = str_replace_last(', Brasil', '', $data['formatted']);
        }

        $address = $event->address()->create(array_except($data, ['coordinate']));

        $address->coordinate()->create(['location' => $data['coordinate']]);

        if ($event->is_locked) {
            UpdateEventInfo::dispatch($event);
        }

        $this->setCacheKey($id);
        $this->flush()->remember($event->fresh());

        return $event;
    }

    /**
     * @param  array   $data
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event|null
     */
    public function setFeeIsHidden(array $data, string $id)
    {
        $event = $this->find($id);

        $event->update($data);

        $this->setCacheKey($id);
        $this->flush()->remember($event->fresh());

        return $event->fresh();
    }

    /**
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event|null
     */
    public function finalize(string $id)
    {
        $event = $this->find($id);

        if ($event->status !== Event::DRAFT) abort(400, 'This event is not a draft.');

        $event->update(['status' => Event::COMPLETED]);
        $this->setCacheKey($id);
        $this->flush()->remember($event->fresh());

        return $event->fresh();
    }

    /**
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event
     */
    public function publish(string $id)
    {
        /** @var Event $event */
        $event = $this->find($id);

        if ($event->status !== Event::COMPLETED) {
            abort(400, 'This event can not be published.');
        }

        if ($this->is_valid($event)) {
            $event->update(['status' => Event::PUBLISHED]);
            $this->setCacheKey($id);
            $this->flush()->remember($event->fresh());
        }

        foreach ($event->permissions as $permission) {
            if ($permission->type === Permission::MASTER || $permission->type === Permission::ORGANIZER) {
                $permission->notify(new EventPublished($event));
            }
        }

        return $event->fresh();
    }

    /**
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event|null
     */
    public function unpublish(string $id)
    {
        $event = $this->find($id);

        if ($event->status === Event::PUBLISHED && !$event->is_locked) {
            $event->update(['status' => Event::COMPLETED]);
            $this->setCacheKey($id);
            $this->flush()->remember($event->fresh());

            return $event;
        }

        abort(400, "This event can't be canceled or unpublished.");

        return NULL;
    }

    /**
     * @param  string  $id
     *
     * @return \Modules\Event\Models\Event|null
     */
    public function cancel(string $id)
    {
        $event = $this->find($id);

        if ($event->status === Event::PUBLISHED && $event->is_locked) {
            $event->update(['status' => Event::CANCELED]);
            $this->setCacheKey($id);
            $this->flush()->remember($event->fresh());

            UpdateEventInfo::dispatch($event);

            return $event;
        }

        abort(400, "This event can't be canceled or unpublished.");

        return NULL;
    }
}
