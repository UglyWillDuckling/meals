<?php
namespace App\Repository;

use App\Meal;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use App\Repository\Repositories\Query\QueryBuilder;


class AbstractRepository implements RepositoryInterface
{
    private $model;

    private $mealQuery;

    private $query;

    private $queryBuilder;


    /**
     * AbstractRepository constructor.
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(\Illuminate\Database\Eloquent\Model $model, QueryBuilder $queryBuilder)
    {
        $this->model = $model;
        $this->mealQuery = Meal::query();
        $this->query = app()->make('Illuminate\Database\Eloquent\Builder');
        $this->query->getQuery()->grammar = new MySqlGrammar();
        $this->queryBuilder = $queryBuilder;
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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function where(array $conditions)
    {
        return $this->_where($conditions);
    }


    /**
     * @param array $condtions
     * @param array $relations
     */
    public function whereWithRelations(array $conditions, array $relations)
    {
        $this->_where($conditions, '*', $relations);
    }

    /**
     * @param array $conditions
     * @param array $joins
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function _where(array $conditions, $columns = '*', array $joins = array())
    {
        $query = $this->getQuery();
        $this->queryBuilder->resetQuery($query);

        foreach ($conditions as $condition) {
            $this->queryBuilder->addWhere(
                $query,
                $condition['column'],
                $condition['operator'], $condition['value']
            );
        }
        if ($joins) {
            $this->queryBuilder->addJoins($joins, $query);
        }
        return $query
            ->addSelect($columns ?: '*')
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

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery(){
        return $this->mealQuery;
    }

    protected function getDefaultLanguage(){
        return 1;
    }

    private function _hasTableInterface(array $interfaces = [])
    {
        return in_array('TableInterface', $interfaces);
    }

    private function _hasTranslatableInterface(array $interfaces = [])
    {
        return in_array('TranslatableInterface', $interfaces);
   }
}