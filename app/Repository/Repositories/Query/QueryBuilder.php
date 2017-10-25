<?php
namespace App\Repository\Repositories\Query;

class QueryBuilder
{
    /**
     * @param array $joins
     * @param  $query
     */
    public function addJoins(array $joins, $query)
    {
        foreach ($joins as $join) {
            if (!isset($join['type'])) {
                $join['type'] = 'join';
            }
            //todo check if it works with method replaced with array value
            $query->{$join['type']}("{$join['table']} as {$join['table_alias']}",
                function (\Illuminate\Database\Query\JoinClause $joinClause) use ($join, $query) {
                    $joinClause->on(
                        "{$join['table_alias']}.{$query->getModel()->getForeignKey()}",
                        $join['operator'],
                        "{$query->getModel()->getTable()}.id"
                    );

                    if (isset($join['where'])) {
                        foreach (array($join['where']) as $where) {
                            $this->addWhere(
                                $joinClause,
                                $where['column'], $where['operator'], $where['value']
                            );
                        }
                    }
                });
        }
    }
    /**
     * @param $query
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function addWhere($query, $column, $operator, $value) {
        if ($operator == 'in') {
            $query->whereIn($column, array($value));
        } else {
            $query->where(
                $column, $operator, $value
            );
        };
    }

    public function resetQuery($query)
    {
        $query = $query->getQuery();
        $query->wheres = [];
        $query->columns = [];
        $query->joins = [];
    }
}















