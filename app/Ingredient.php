<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredient';
    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'slug'
    ];
}
