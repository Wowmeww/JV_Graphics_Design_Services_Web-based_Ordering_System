<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOptionFactory> */
    use HasFactory;

    protected $appends = ['rating', 'show_size'];
    public function getRatingAttribute()
    {
        $ratings = $this->ratings->pluck('stars');
        $total = $ratings->sum();
        $count = $ratings->count();

        return $count > 0 ? $total / $count : 0;
    }
    public function getShowSizeAttribute()
    {
        $size = json_decode($this->size);
        return  $size?->size ?
            ($size?->unit ? $size->size . " " . $size?->unit : $size->size) :
            null;
    }

    // one to many - belongs to
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
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

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }
}
