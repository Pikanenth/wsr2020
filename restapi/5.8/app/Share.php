<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    
    public $table = "shares";

    protected $fillable = [
        'id', 'photo_id', 'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function photo() {
        return $this->belongsTo("App\Photo");
    }

}
