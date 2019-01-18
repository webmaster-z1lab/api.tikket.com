<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 12/11/2018
 * Time: 17:15
 */

namespace App;

use App\Traits\FilterHelpers;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    use FilterHelpers;

    protected $filters = [];

    /**
     * @param Builder $query
     * @param array   $data
     * @return Builder
     */
    public function apply(Builder $query, array $data)
    {
        $this->query = $query;
        foreach (array_only($data, $this->filters) as $field => $value) {
            if (!filled($value)) {
                $this->filterNull($field);
            } elseif (method_exists($this, $field)) {
                $this->$field($value);
            }
        }

        return $this->query;
    }
}
