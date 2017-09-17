<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    protected $hidden = [
        'pivot'
    ];
    protected $fillable = [
        'slug'
    ];

    const TABLE_TRANSLATION = 'tags_translation';
    const TABLE_TRANSLATION_ALIAS = 'tt';


    public function getTranslationTable()
    {
        return self::TABLE_TRANSLATION;
    }
    public function getTranslationAlias()
    {
        return self::TABLE_TRANSLATION_ALIAS;
    }
}
