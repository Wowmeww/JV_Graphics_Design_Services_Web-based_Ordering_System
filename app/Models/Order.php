<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    // one to many - belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // many to many - belongs to many
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
