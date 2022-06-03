<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banksupported extends Model {
    protected $table = "bank_supported";
    protected $guarded = [];

    public function creal()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }
}
