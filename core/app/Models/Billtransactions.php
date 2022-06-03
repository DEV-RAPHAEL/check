<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billtransactions extends Model {
    protected $table = "bill_transactions";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }     

}
