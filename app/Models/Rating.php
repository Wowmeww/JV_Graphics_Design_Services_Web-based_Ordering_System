<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\RatingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;
    // one to many - belongs to
    public function product()
    {
        return $this->belongsTo(Product::class)->whereNot('option_id');
    }
    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
