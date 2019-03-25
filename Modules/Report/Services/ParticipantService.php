<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 22/03/2019
 * Time: 16:14
 */

namespace Modules\Report\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Order\Models\Ticket;

class ParticipantService
{
    /**
     * @param string $event_id
     *
     * @return \Illuminate\Database\Query\Builder|\Modules\Order\Models\Ticket
     */
    public function latest(string $event_id)
    {
        return Ticket::where('event.event_id', $event_id)->latest()->limit(25);
    }

    /**
     * @param array  $options
     *
     * @param string $event_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function search(array $options, string $event_id)
    {
        $search_token = \Request::get('search_token', Str::uuid()->toString());

        $results = \Cache::remember($search_token, 10, function () use ($event_id) {
            return Ticket::where('event.event_id', $event_id)->latest()->get();
        });

        if (!empty($options)) {
            $results = $results->where(function ($query) use ($options) {
                foreach ($options as $key => $value)
                    if (filled($value))
                        switch ($key) {
                            case 'name':
                                $query->where('participant.name', 'LIKE', "%$value%");
                                break;
                            case 'email':
                                $query->where('participant.email', 'LIKE', "%$value%");
                                break;
                            case 'code':
                                $query->where('code', 'LIKE', "%$value%");
                                break;
                            case 'order':
                                $query->where('order_id', 'LIKE', "%$value%");
                                break;

                        }
            })->get();
        }

        $page = \Request::get('page', 1);

        return $this->paginate($results, 25, $page, compact('search_token'));
    }

    /**
     * Paginate the result items
     *
     * @param \Illuminate\Database\Eloquent\Collection|array $items
     * @param int                                            $perPage
     * @param int                                            $page
     * @param array|NULL                                     $query
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function paginate($items, int $perPage = 15, int $page = NULL, array $query = NULL)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        $version = 'v' . \APIResource::getVersion();
        $event = \Route::current()->parameter('event');
        $options['path'] = str_finish(env('APP_URL'), '/') . "api/$version/events/$event/reports/participants";

        if (NULL !== $query) $options['query'] = $query;

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
