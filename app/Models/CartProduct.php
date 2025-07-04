<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProduct extends Pivot
{
    protected $table = 'cart_product';

    public $incrementing = true; // should be true if using auto-increment

    protected $primaryKey = 'id'; // default, but add it if custom


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }
}
