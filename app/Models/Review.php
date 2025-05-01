<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    // one to many - belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many - belongs to
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
