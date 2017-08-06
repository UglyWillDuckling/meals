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


}
