<?php
namespace App\Repository\Repositories;


use App\Repository\RepositoryInterface;

interface MealRepositoryInterface extends RepositoryInterface

{
    public function attachTranslations($meals);
    public function getAllWithTranslations();
}
