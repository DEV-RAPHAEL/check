<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model {
    protected $table = "subscribers";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }    
    public function plan()
    {
        return $this->belongsTo('App\Models\Plans', 'plan_id');
    }
}
