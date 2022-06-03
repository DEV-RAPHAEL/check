<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    protected $table = "invoices";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }   
    public function sender()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }    
}
