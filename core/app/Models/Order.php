<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $table = "orders";
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }      
    public function seller()
    {
        return $this->belongsTo('App\Models\User','seller_id');
    }     
    public function buyer()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }    
    public function lala()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }    
    public function ship()
    {
        return $this->belongsTo('App\Models\Shipping','ship_id');
    }
}
