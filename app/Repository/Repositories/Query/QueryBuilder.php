<?php
namespace App\Repository\Repositories\Query;

class QueryBuilder
{
    /**
     * @param array $joins
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function addJoins(array $joins, \Illuminate\Database\Query\Builder $query)
    {
        foreach ($joins as $join) {
            if (!isset($join['type'])) {
                $join['type'] = 'join';
            }
            //todo check if it works with method replaced with array value
            $query->{$join['type']}("{$join['table']} as {$join['table_alias']}",
                function (\Illuminate\Database\Query\JoinClause $joinClause) use ($join, $query) {
                    $joinClause->on(
                        "{$join['table_alias']}.{$this->getModel()->getForeignKey()}",
                        $join['operator'],
                        "{$this->getModel()->getTable()}.id"
                    );

                    if (isset($join['where'])) {
                        foreach (array($join['where']) as $where) {
                            $this->_addWhere(
                                $joinClause,
                                $where['column'], $where['operator'], $where['value']
                            );
                        }
                    }
                });
        }
    }


    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function addWhere(\Illuminate\Database\Query\Builder $query, $column, $operator, $value) {//todo replace the query builder class with eloquent
        if ($operator == 'in    ') {
            $query->whereIn($column, array($value));
        } else {
            $query->where(
                $column, $operator, $value
            );
        };
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function resetQuery(\Illuminate\Database\Query\Builder $query)
    {
        $query->wheres = [];
        $query->columns = [];
        $query->joins = [];
    }
}















