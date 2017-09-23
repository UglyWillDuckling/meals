<?php

namespace App\Repository;


interface RepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * @return mixed
     */
    public function find($id);

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getBy($field, $value, $operator);


    /**
     * @param array $conditions
     * @param array $joins
     * @return mixed
     */
    public function where(array $conditions,  array $columns, array $joins);

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function delete($field, $value);

    /**
     * @param $field
     * @param $value
     * @param string $field
     * @param null $where
     * @return mixed
     */
    public function update($field, $values);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes = []);

    /**
     * @return mixed
     */
    public function getModel();
}