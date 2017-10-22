<?php
namespace App\Laravel\Eloquent\Query;

use \Illuminate\Database\Eloquent\Builder as Eloquent;


class Builder extends Eloquent
{
    public function __construct() {
        $builder = app()->make('Illuminate\Database\Query\Builder');
        parent::__construct($builder);
    }

    protected function eagerLoadRelation(array $models, $name, \Closure $constraints)
    {
        $relation = $this->getRelation($name);

        $relation->addEagerConstraints($models);

        $event_prefix = $this->getModel()->getTable();//todo add event prefix
        event($event_prefix  . '.relation_load',new \App\Events\Meal_Relation_Event($relation));

        $constraints($relation);

        return $relation->match(
            $relation->initRelation($models, $name),
            $relation->getEager(), $name
        );
    }
}