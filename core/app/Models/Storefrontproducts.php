<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storefrontproducts extends Model {
    protected $table = "storefront_products";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }    
    public function pd()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }     
}
