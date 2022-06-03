<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "cart";
    protected $fillable = ['uniqueid', 'title', 'product', 'quantity','store', 'cost', 'total'];
    public $timestamps = false;
}
