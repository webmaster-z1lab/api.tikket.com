<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/11/2018
 * Time: 15:54
 */

namespace App\Traits;

use Illuminate\Support\Arr;
use \Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginateTrait
{
    /**
     * Paginate the result items
     *
     * @param \Illuminate\Support\Collection $items
     * @param int                                            $total
     * @param int                                            $perPage
     * @param int                                            $page
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function paginate(Collection $items, int $total, int $perPage, int $page)
    {
        $options['path'] = \Request::url();

        $query = \Request::query();
        if (NULL !== $query) {
            $query = Arr::except($query, 'page');
            if (filled($query))
                $options['query'] = $query;
        }

        return new LengthAwarePaginator($items, $total, $perPage, $page, $options);
    }
}
