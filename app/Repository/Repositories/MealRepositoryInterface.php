<?php
namespace App\Repository\Repositories;


use App\Repository\RepositoryInterface;

interface MealRepositoryInterface extends RepositoryInterface
{
    public function getAllWithTranslations();
}
