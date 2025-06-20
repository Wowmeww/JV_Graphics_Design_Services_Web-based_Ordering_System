<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionImage extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOptionImageFactory> */
    use HasFactory;

    // one to many - belongs to
    public function option()
    {
        return $this->belongsTo(ProductOption::class);
    }
}
