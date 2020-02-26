<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    public $table = "photos";
    
    protected $fillable = [
        'id', 'name', 'url', 'owner_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

}
