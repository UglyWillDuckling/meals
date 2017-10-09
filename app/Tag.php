<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\TableInterface;



class Tag extends Model implements TableInterface
{
    public static $tableName = 'tag';
    public static $tableAlias = 'tag';


    protected $table = 'tag';
    protected $hidden = [
        'pivot'
    ];
    protected $fillable = [
        'slug'
    ];

    const TABLE_TRANSLATION = 'tags_translation';
    const TABLE_TRANSLATION_ALIAS = 'tt';
    const PIVOT_FOREIGN_KEY = 'tag_id';


    public function getTranslationTable()
    {
        return self::TABLE_TRANSLATION;
    }
    public function getTranslationAlias()
    {
        return self::TABLE_TRANSLATION_ALIAS;
    }

    public function getMealsForeignKey()
    {
        return self::PIVOT_FOREIGN_KEY;
    }

    public static function getTableName()
    {
        return self::$tableName;
    }

    public static function getTableAlias()
    {
        return self::$tableAlias;
    }
}
