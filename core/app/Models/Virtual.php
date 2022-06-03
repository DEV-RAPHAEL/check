<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Virtual extends Model {
    protected $table = "virtual_cards";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }   
}
