<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storefront extends Model {
    protected $table = "storefront";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }     
}
