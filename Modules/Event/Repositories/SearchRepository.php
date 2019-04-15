<?php


namespace Modules\Event\Repositories;

use App\Traits\PaginateTrait;
use Modules\Event\Models\Event;

class SearchRepository
{
    use PaginateTrait;

    private const PER_PAGE = 25;

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function search()
    {
        $page = \Request::get('page', 1);

        $results = Event::whereIn('status', [Event::PUBLISHED, Event::CANCELED])
            ->search(\Request::query('keywords'))
            ->city(\Request::query('city'))
            ->period(\Request::query('period'))
            ->category(\Request::query('category'))
            ->skip(($page - 1) * self::PER_PAGE)
            ->take(self::PER_PAGE)
            ->get();

        $total = Event::whereIn('status', [Event::PUBLISHED, Event::CANCELED])
            ->search(\Request::query('keywords'))
            ->city(\Request::query('city'))
            ->period(\Request::query('period'))
            ->category(\Request::query('category'))
            ->count();

        return $this->paginate($results, $total, self::PER_PAGE, $page);
    }
}
