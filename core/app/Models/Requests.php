<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model {
    protected $table = "request";
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo('App\Models\User','email');
    }    
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
