<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exttransfer extends Model {
    protected $table = "ext_transfer";
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }        
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','receiver_id');
    }    
    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant','merchant_key');
    }
}
