<?php
namespace App\Http\Helper\Meal\Filters;

class Tags implements FilterInterface
{
    /**
     * @param $query
     * @param $value
     */
    public static function filter(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        $query
            ->join('meals_tags as mtgs', function (\Illuminate\Database\Query\JoinClause $join) use ($value) {
                $join->on(
                    'mtgs.meal_id', '=', 'meals.id');
                $join->whereIn(
                    'mtgs.tag_id', $value);
            });
    }
}

?>

