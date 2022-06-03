<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentlink extends Model {
    protected $table = "payment_link";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    } 
}
