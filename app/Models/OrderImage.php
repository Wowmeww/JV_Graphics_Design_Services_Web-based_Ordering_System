<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderImage extends Model
{
    protected $table = 'orders_resource_images';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
