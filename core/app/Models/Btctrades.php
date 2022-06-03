<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Btctrades extends Model {
    protected $table = "btc_trades";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function dbank()
    {
        return $this->belongsTo('App\Models\Bank','bank');
    }
}
