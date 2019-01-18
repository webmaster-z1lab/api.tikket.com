<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/11/2018
 * Time: 15:54
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait PaginateTrait
{
    /**
     * Paginate the result items
     *
     * @param Collection|array $items
     * @param string           $path
     * @param int              $perPage
     * @param int              $page
     * @param array|NULL       $query
     * @return LengthAwarePaginator
     */
    public function paginate($items, string $path, int $perPage = 15, int $page = NULL, array $query = NULL)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        $version = 'v' . config("api.version.$path", 1);
        $options['path'] = str_finish(env('APP_URL'), '/') . "api/$version/$path";

        if (NULL !== $query) $options['query'] = $query;

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
