<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProduct extends Pivot
{
    protected $table = 'cart_product';

    public $incrementing = true; // should be true if using auto-increment

    protected $primaryKey = 'id'; // default, but add it if custom

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }


    public function resource()
    {
        return $this->hasMany(OrderImage::class, 'cart_product_id');
    }
}
