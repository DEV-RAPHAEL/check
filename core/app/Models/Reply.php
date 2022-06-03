<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model {
    protected $table = "reply_support";
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Admin','staff_id');
    }   
}
