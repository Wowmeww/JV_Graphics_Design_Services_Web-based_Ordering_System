<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /** @use HasFactory<\Database\Factories\WishlistFactory> */
    use HasFactory;

    // one to one - belongs to
    public function user() {
        return $this->belongsTo(User::class);
    }

    // one to many - has many
    public function wishlistItems() {
        return $this->hasMany(WishlistItem::class);
    }
}
