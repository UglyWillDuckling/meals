<?php
namespace App\Repository;

use App\Meal;
use Illuminate\Database\Query\Grammars\MySqlGrammar;


class AbstractRepository implements RepositoryInterface
{
    private $model;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $mealQuery;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;


    /**
     * AbstractRepository constructor.
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(\Illuminate\Database\Eloquent\Model $model)
    {
        $this->model = $model;
        $this->mealQuery = Meal::query();
        $this->query = app()->make('Illuminate\Database\Eloquent\Builder');
        $this->query->getQuery()->grammar = new MySqlGrammar();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->getModel()->all();
    }

    /**
     * @return mixed
     */
    public function find($id)
    {
        return $this->getModel()->find($id);
    }

    /**
     * @param $field
     * @param $value
     * @return \Illuminate\Database\Eloquent\Collection | false
     */
    public function getBy($field, $value, $operator = '=')
    {
        return $this->model
            ->where($field, $operator, $value)->get();
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function delete($field = 'id', $values)
    {

    }

    /**
     * @param $field
     * @param $value
     * @param string $field
     * @param null $where
     * @return mixed
     */
    public function update($field = 'id', $values, $operator = null, $where = null)
    {
        $this->getModel()
            ->where($field, $operator, $where)
            ->update($values);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function create(array $attributes = [])
    {
        $this->getModel()->create($attributes);
    }

    /**
     * @param array $conditions
     * @param array $joins
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function where(array $conditions, array $columns = array(), array $joins = array())
    {
        $this->_resetMealQuery();
        $query = $this->getQuery();
        foreach ($conditions as $condition) {
            $this->_addWhere(
                $query,
                $condition['column'],
                $condition['operator'], $condition['value']
            );
        }
        if ($joins) {
            $this->_addJoins($joins);
        }
        return $query
            ->addSelect(
                $columns ?: '*')
            ->get();
    }

    /**
     * @param array $joins
     */
    private function _addJoins(array $joins)
    {
        $query = $this->getQuery();
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

    protected function getQuery()
    {
        return $this->mealQuery;
    }


    /**
     * @param $query
     * @param $column
     * @param $operator
     * @param $value
     */
    private function _addWhere($query, $column, $operator, $value) {
        if ($operator == 'in    ') {
            $query->whereIn($column, array($value));
        } else {
            $query->where(
                $column, $operator, $value
            );
        };
    }

    private function _resetMealQuery()
    {
        $query = $this->mealQuery;
        $query->getQuery()->wheres = [];
        $query->getQuery()->columns = [];
        $query->getQuery()->joins = [];
    }

    private function _resetQuery()
    {
        $query = $this->query;
        $query->getQuery()->wheres = [];
        $query->getQuery()->columns = [];
        $query->getQuery()->joins = [];
    }
}