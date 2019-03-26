<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 22/03/2019
 * Time: 16:14
 */

namespace Modules\Report\Services;

use App\Traits\PaginateTrait;
use Modules\Ticket\Models\Ticket;

class ParticipantService
{
    use PaginateTrait;

    private const PER_PAGE = 25;

    /**
     * @param string|NULL $search
     * @param string $event_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function search(string $event_id, string $search = NULL)
    {
        $page = intval(\Request::get('page', 1));

        if (filled($search)) {
            $results = Ticket::where('event.event_id', $event_id)
                ->where(function ($query) use ($search) {
                    $query->where('order_id', 'LIKE', "%$search%")
                        ->orWhere('code', 'LIKE', "%$search%")
                        ->orWhere('participant.name', 'LIKE', "%$search%")
                        ->orWhere('participant.email', 'LIKE', "%$search%");
                })->latest()
                ->skip(($page - 1) * self::PER_PAGE)
                ->take(self::PER_PAGE)
                ->get();

            $total = Ticket::where('event.event_id', $event_id)
                ->where(function ($query) use ($search) {
                    $query->where('order_id', 'LIKE', "%$search%")
                        ->orWhere('code', 'LIKE', "%$search%")
                        ->orWhere('participant.name', 'LIKE', "%$search%")
                        ->orWhere('participant.email', 'LIKE', "%$search%");
                })->count();
        } else {
            $results = Ticket::where('event.event_id', $event_id)
                ->latest()
                ->skip(($page - 1) * self::PER_PAGE)
                ->take(self::PER_PAGE)
                ->get();

            $total = Ticket::where('event.event_id', $event_id)->count();
        }

        return $this->paginate($results, $total,self::PER_PAGE, $page);
    }
}
