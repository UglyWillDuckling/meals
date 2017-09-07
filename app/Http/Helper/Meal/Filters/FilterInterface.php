<?php
namespace App\Http\Helper\Meal\Filters;

interface FilterInterface
{
    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public static function filter(\Illuminate\Database\Eloquent\Builder $query, $value);
}

?>