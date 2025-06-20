<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOptionFactory> */
    use HasFactory;

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
}
