<?php
namespace App\Repository;


class AbstractRepository implements RepositoryInterface
{
    private $model;


    /**
     * AbstractRepository constructor.
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(\Illuminate\Database\Eloquent\Model $model)
    {
        $this->model = $model;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all(){
        return $this->getModel()->getQuery()->get();
    }

    /**
     * @return mixed
     */
    public function find($id){
        return $this->getModel()->find($id);
    }

    /**
     * @param $field
     * @param $value
     * @return \Illuminate\Database\Eloquent\Collection | false
     */
    public function getBy($field, $value, $operator = '='){
        return $this->model
            ->where($field, $operator, $value)->get();
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function delete($field = 'id', $value){

    }

    /**
     * @param $field
     * @param $value
     * @param string $field
     * @param null $where
     * @return mixed
     */
    public function update($field = 'id', $values,  $operator = null, $where = null){
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
}