<?php
namespace App\Http\Helper\Meal\Filters;

class Category implements FilterInterface
{
    /**
     * @param $query
     * @param $value
     */
    public static function filter(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        $query->where('category_id', '=', $value);
    }
}

?>

