<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 22/03/2019
 * Time: 10:06
 */

namespace Modules\Report\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Modules\Order\Models\Order;

class OrderReportService
{
    /**
     * @param string $event_id
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(string $event_id)
    {
        $search_token = \Request::get('search_token', Str::uuid()->toString());

        $results = \Cache::remember($search_token, 10, function () use ($event_id) {
            return Order::where('event_id', $event_id)
                ->where('status', '<>', Order::CANCELED)
                ->latest()
                ->get();
        });

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
    public function paginate($items, int $perPage = 15, int $page = NULL, array $query = NULL)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        $version = 'v' . \APIResource::getVersion();
        $event = \Route::current()->parameter('event');
        $options['path'] = str_finish(env('APP_URL'), '/') . "api/$version/events/$event/reports";

        if (NULL !== $query) $options['query'] = $query;

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
