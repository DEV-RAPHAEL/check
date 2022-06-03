<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model {
    protected $table = "transactions";
    protected $guarded = [];

    public function ddlink()
    {
        return $this->belongsTo('App\Models\Paymentlink', 'payment_link');
    }       
    public function inplan()
    {
        return $this->belongsTo('App\Models\Invoice', 'payment_link');
    }
    public function sender()
    {
        return $this->belongsTo('App\Models\User','sender_id');
    }    
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','receiver_id');
    }
}
