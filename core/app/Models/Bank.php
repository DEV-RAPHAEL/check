<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model {
    protected $table = "bank";
    protected $guarded = [];

    public function dabank()
    {
        return $this->belongsTo('App\Models\Banksupported','bank_id');
    }
}
