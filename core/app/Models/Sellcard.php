<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sellcard extends Model {
    protected $table = "sell_cards";
    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo('App\Models\Plans','plan_id');
    }   
    
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function dbank()
    {
        return $this->belongsTo('App\Models\Bank','bank');
    }
}
