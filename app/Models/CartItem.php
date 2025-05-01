<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartItemFactory> */
    use HasFactory;

    // one to many - belongs to
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // many to many - belongs to many
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
