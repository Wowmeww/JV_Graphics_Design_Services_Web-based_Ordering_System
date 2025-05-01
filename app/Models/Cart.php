<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    // one to one - belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many - has many
    function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
