<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 22/03/2019
 * Time: 10:06
 */

namespace Modules\Report\Services;

use App\Traits\PaginateTrait;
use Modules\Order\Models\Order;

class OrderService
{
    use PaginateTrait;

    private const PER_PAGE = 25;
    /**
     * @param string      $event_id
     * @param string|null $search
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(string $event_id, string $search = NULL)
    {
        $page = \Request::get('page', 1);

        if (filled($search)) {
            $results = Order::where('event_id', $event_id)
                ->where('status', '<>', Order::CANCELED)
                ->where(function ($query) use ($search) {
                    $query->where('_id', 'LIKE', "%$search%")
                        ->orWhere('costumer.name', 'LIKE', "%$search%")
                        ->orWhere('costumer.email', 'LIKE', "%$search%");
                })
                ->latest()
                ->get();

            $total = Order::where('event_id', $event_id)
                ->where('status', '<>', Order::CANCELED)
                ->where(function ($query) use ($search) {
                    $query->where('_id', 'LIKE', "%$search%")
                        ->orWhere('costumer.name', 'LIKE', "%$search%")
                        ->orWhere('costumer.email', 'LIKE', "%$search%");
                })
                ->count();
        } else {
            $results = Order::where('event_id', $event_id)
                ->where('status', '<>', Order::CANCELED)
                ->latest()
                ->get();

            $total = Order::where('event_id', $event_id)
                ->where('status', '<>', Order::CANCELED)
                ->count();
        }

        return $this->paginate($results, $total, self::PER_PAGE, $page);
    }
}
