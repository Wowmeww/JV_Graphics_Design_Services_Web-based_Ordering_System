<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    // one to many - has many
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    // one to many - belongs to
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // many to many - belongs to many
    public function wishlistItems()
    {
        return $this->belongsToMany(WishlistItem::class, 'wishlist_item_product')->withTimestamps();
    }
    public function cartItems()
    {
        return $this->belongsToMany(CartItem::class)->withTimestamps();
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }


    // ---------- Filters ----------------------------------------------------------------

    public function scopeFilter($query, array $filters)
    {

        if ($filters['search'] ?? false) {
            //   dd($filters['search'] == request('search'));
            $query->where(function ($q) use ($filters) {
                // dd($q->get());
                $q->where('name', 'like', '%' . $filters['search'] . '%')->orWhereLike('price', '%' . $filters['search'] . '%');
            });
        }

        if ($filters['category'] ?? false) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', $filters['category']);
            });
        }

        if ($filters['stock'] ?? false) {

            switch ($filters['stock']) {
                case 'Low inventory (10-)':
                    $query->where('stock', '<=', 10);
                    break;

                case 'Out of stock':
                    $query->where('stock', '<', 1);
                    break;

                default:
                    $query->where('stock', '>=', 10);
            }
        }


        if ($filters['sort'] ?? false) {
            switch ($filters['sort']) {
                case 'Sort by name (A-Z)':
                    $query->orderBy('name', 'asc');
                    break;

                case 'Sort by name (Z-A)':
                    $query->orderBy('name', 'desc');
                    break;

                case 'Sort by price (low to high)':
                    $query->orderBy('price', 'asc');
                    break;

                case 'Sort by price (high to low)':
                    $query->orderBy('price', 'desc');
                    break;

                case 'Sort by stock (low to high)':
                    $query->orderBy('stock', 'asc');
                    break;

                case 'Sort by stock (high to low)':
                    $query->orderBy('stock', 'desc');
                    break;

                case 'Sort by date (new to old)':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'Sort by date (old to new)':
                    $query->orderBy('created_at', 'asc');
                    break;

                default: $query->latest('created_at');
            }
        }
    }
}
