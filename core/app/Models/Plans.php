<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model {
    protected $table = "plan";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function real()
    {
        return $this->belongsTo('App\Models\Countrysupported','currency');
    }
    public function coin()
    {
        return $this->belongsTo('App\Models\Currency','coin_id');
    }
}
