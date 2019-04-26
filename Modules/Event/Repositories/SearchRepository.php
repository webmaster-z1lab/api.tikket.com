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
            ->skip(($page - 1) * self::PER_PAGE)
            ->take(self::PER_PAGE)
            ->get();

        $total = Event::whereIn('status', [Event::PUBLISHED, Event::CANCELED])
            ->search(\Request::query('keywords'))
            ->city(\Request::query('city'))
            ->period(\Request::query('period'))
            ->count();

        return $this->paginate($results, $total, self::PER_PAGE, $page);
    }

    /**
     * @return array
     */
    public function user_ip()
    {
        $user_ip = getenv('REMOTE_ADDR');

        return \Cache::rememberForever($user_ip, function () use ($user_ip) {
            return json_decode(file_get_contents("https://extreme-ip-lookup.com/json/$user_ip"));
        });
    }
}
