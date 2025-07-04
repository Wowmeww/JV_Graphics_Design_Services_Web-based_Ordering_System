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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    // many to many - belongs to many

}
