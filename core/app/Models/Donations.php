<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donations extends Model {
    protected $table = "donations";
    protected $guarded = [];

    public function ddlink()
    {
        return $this->belongsTo('App\Models\Paymentlink', 'payment_link');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    } 
}
