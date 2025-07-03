<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /** @use HasFactory<\Database\Factories\WishlistFactory> */
    use HasFactory;

    // APPENDED ATTRIBUTE
    protected $appends = ['items'];

    public function getItemsAttribute()
    {
        $products = WishlistProduct::latest('updated_at')
            ->with(['product.images', 'option.images'])
            ->where('wishlist_id', $this->id)
            ->get();
        return  $products;
    }

    // one to one - belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many - has many
    public function products()
    {
        return $this->belongsToMany(Product::class,  'wishlist_product')->where('option_id', null)->withTimestamps();
    }
    public function options()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'wishlist_product',
            'wishlist_id',
            'option_id'
        );
    }


    // CUSTOM METHODS

    public function addItem(Product $product, ?ProductOption $option = null, int $quantity = 12): void
    {
        $price = $option ? $option->price : $product->price;
        WishlistProduct::updateOrInsert(
            [ // keys to find existing row
                'wishlist_id' => $this->id,
                'product_id' => $product->id,
                'option_id' => $option?->id,
            ],
            [ // fields to update or insert
                'quantity' => $quantity,
                'total_amount' => $price * $quantity,
                'updated_at' => now(),
            ]
        );
    }
    public function deleteItem(Product $product, ?ProductOption $option = null): void
    {
        WishlistProduct::where('wishlist_id', $this->id)
            ->where('product_id', $product->id)
            ->where('option_id', $option?->id)
            ->delete();
    }
}
