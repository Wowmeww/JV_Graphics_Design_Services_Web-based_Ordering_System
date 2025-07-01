<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    // APPENDED ATTRIBUTE
    protected $appends = ['items'];

    public function getItemsAttribute()
    {
        $products = CartProduct::latest('updated_at')
            ->with(['product.images', 'option.images'])
            ->where('cart_id', $this->id)
            ->get();
        return  $products;
    }



    // one to one - belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many - belongs to many
    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_product')
            ->using(CartProduct::class)
            ->withPivot(['option_id', 'quantity', 'total_amount'])
            ->wherePivot('option_id', null)
            ->withTimestamps();
    }

    public function options()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'cart_product',
            'cart_id',
            'option_id'
        )
            ->using(CartProduct::class)
            ->withPivot(['product_id', 'quantity', 'total_amount']) // product_id is now needed
            ->wherePivotNotNull('option_id') // Optional: filter only actual option rows
            ->withTimestamps();
    }


    // CUSTOM METHODS

    public function addItem(Product $product, ?ProductOption $option = null, int $quantity = 12): void
    {
        $price = $option ? $option->price : $product->price;
        CartProduct::updateOrInsert(
            [ // keys to find existing row
                'cart_id' => $this->id,
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
        CartProduct::where('cart_id', $this->id)
            ->where('product_id', $product->id)
            ->where('option_id', $option?->id)
            ->delete();
    }
}
