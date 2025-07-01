<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /** @use HasFactory<\Database\Factories\WishlistFactory> */
    use HasFactory;

    // APPENDED ATTRIBUTE
    protected $appends = ['items'];

    public function getItemsAttribute()
    {
        $products = $this->products ?? collect();
        $options = $this->options ?? collect();

        return $products->concat($options)->values();
    }

    // one to one - belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many - has many
    public function products()
    {
        return $this->belongsToMany(Product::class,  'wishlist_product')->where('option_id', null)->withTimestamps();
    }
    public function options()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'wishlist_product',
            'wishlist_id',
            'option_id'
        );
    }
}
