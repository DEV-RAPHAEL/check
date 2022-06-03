<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countrysupported extends Model {
    protected $table = "country_supported";
    protected $guarded = [];

    public function real()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }
    public function coin()
    {
        return $this->belongsTo('App\Models\Currency','coin_id');
    }
}
