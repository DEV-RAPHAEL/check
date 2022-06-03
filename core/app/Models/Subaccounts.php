<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subaccounts extends Model {
    protected $table = "subaccounts";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function dbank()
    {
        return $this->belongsTo('App\Models\Banksupported','bank_id');
    }    
    

}
