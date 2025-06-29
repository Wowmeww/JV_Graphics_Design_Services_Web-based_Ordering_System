<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOptionFactory> */
    use HasFactory;

    protected $appends = ['rating'];
    public function getRatingAttribute()
    {
        $ratings = $this->ratings->pluck('stars');
        $total = $ratings->sum();
        $count = $ratings->count();

        return $count > 0 ? $total / $count : 0;
    }

    // one to many - belongs to
    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    // one to many - has many
    public function images()
    {
        return $this->hasMany(ProductOptionImage::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'option_id');
    }
}
