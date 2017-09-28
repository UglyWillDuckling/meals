<?php
namespace App\Interfaces;

interface  TableInterface
{
    /**
     * @return string
     */
    public static function getTableName();

    /**
     * @return string
     */
    public static function getTableAlias();
}
