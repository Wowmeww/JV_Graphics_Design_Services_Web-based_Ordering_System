<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    // one to many - has many
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    // one to many - belongs to
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // many to many - belongs to many
    public function wishlistItems()
    {
        return $this->belongsToMany(WishlistItem::class, 'wishlist_item_product')->withTimestamps();
    }
    public function cartItems()
    {
        return $this->belongsToMany(CartItem::class)->withTimestamps();
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }

    
}
