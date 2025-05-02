<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    /** @use HasFactory<\Database\Factories\WishlistItemFactory> */
    use HasFactory;

    // one to many - belongs to
    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }

    // many to many - belongs to many
    public function products()
    {
        return $this->belongsToMany(Product::class, 'wishlist_item_product')->withTimestamps();
    }

}
