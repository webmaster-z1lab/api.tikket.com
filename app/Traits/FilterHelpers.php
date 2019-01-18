<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 12/11/2018
 * Time: 17:01
 */

namespace App\Traits;


trait FilterHelpers
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * @param string $field
     * @param        $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterEqual(string $field, $value)
    {
        return $this->query->where($field, $value);
    }

    /**
     * @param string $field
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterLike(string $field, string $value)
    {
        return $this->query->where($field, 'LIKE', "%$value%");
    }

    /**
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterNull(string $field)
    {
        return $this->query->whereNull($field);
    }
}
