<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $appends = ['rating'];
    public function getRatingAttribute()
    {
        $ratings = $this->ratings->pluck('stars');
        $total = $ratings->sum();
        $count = $ratings->count();

        return $count > 0 ? $total / $count : 0;
    }

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
    public function ratings()
    {
        return $this->hasMany(Rating::class)->where(['option_id' => null]);
    }
    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    // one to many - belongs to
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // many to many - belongs to many
    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class)->withTimestamps();
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withTimestamps();
    }



    // ---------- Filters ----------------------------------------------------------------

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? false, function ($q) use ($filters) {
                $search = '%' . $filters['search'] . '%';
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', $search)
                        ->orWhereLike('price', $search); // Ensure your whereLike macro handles numbers
                });
            })
            ->when($filters['category'] ?? false, function ($q) use ($filters) {
                $q->whereHas(
                    'category',
                    fn($categoryQuery) =>
                    $categoryQuery->where('name', $filters['category'])
                );
            })
            ->when($filters['stock'] ?? false, function ($q) use ($filters) {
                match ($filters['stock']) {
                    'Low inventory (10-)' => $q->where('stock', '<=', 10),
                    'Out of stock' => $q->where('stock', '<', 1),
                    default => $q->where('stock', '>=', 10)
                };
            })
            ->when($filters['sort'] ?? false, function ($q) use ($filters) {
                match ($filters['sort']) {
                    'Sort by name (A-Z)' => $q->orderBy('name'),
                    'Sort by name (Z-A)' => $q->orderByDesc('name'),
                    'Sort by price (low to high)' => $q->orderBy('price'),
                    'Sort by price (high to low)' => $q->orderByDesc('price'),
                    'Sort by stock (low to high)' => $q->orderBy('stock'),
                    'Sort by stock (high to low)' => $q->orderByDesc('stock'),
                    'Sort by date (new to old)' => $q->latest(),
                    'Sort by date (old to new)' => $q->oldest(),
                    default => $q->latest()
                };
            })
            ->when(
                $filters['type'] ?? false,
                fn($q) =>
                $q->where('type', $filters['type'])
            );
    }
    // public function scopeFilter($query, array $filters)
    // {

    //     if ($filters['search'] ?? false) {
    //         //   dd($filters['search'] == request('search'));
    //         $query->where(function ($q) use ($filters) {
    //             // dd($q->get());
    //             $q->where('name', 'like', '%' . $filters['search'] . '%')->orWhereLike('price', '%' . $filters['search'] . '%');
    //         });
    //     }

    //     if ($filters['category'] ?? false) {
    //         $query->whereHas('category', function ($q) use ($filters) {
    //             $q->where('name', $filters['category']);
    //         });
    //     }

    //     if ($filters['stock'] ?? false) {

    //         switch ($filters['stock']) {
    //             case 'Low inventory (10-)':
    //                 $query->where('stock', '<=', 10);
    //                 break;

    //             case 'Out of stock':
    //                 $query->where('stock', '<', 1);
    //                 break;

    //             default:
    //                 $query->where('stock', '>=', 10);
    //         }
    //     }


    //     if ($filters['sort'] ?? false) {
    //         // Sort by name (A-Z)
    //         // Sort by name (Z-A)
    //         // Sort by price (low to high)
    //         // Sort by price (high to low)
    //         // Sort by stock (low to high)
    //         // Sort by stock (high to low)
    //         // Sort by date (new to old)
    //         // Sort by date (old to new)
    //         switch ($filters['sort']) {
    //             case 'Sort by name (A-Z)':
    //                 $query->orderBy('name', 'asc');
    //                 break;

    //             case 'Sort by name (Z-A)':
    //                 $query->orderBy('name', 'desc');
    //                 break;

    //             case 'Sort by price (low to high)':
    //                 $query->orderBy('price', 'asc');
    //                 break;

    //             case 'Sort by price (high to low)':
    //                 $query->orderBy('price', 'desc');
    //                 break;

    //             case 'Sort by stock (low to high)':
    //                 $query->orderBy('stock', 'asc');
    //                 break;

    //             case 'Sort by stock (high to low)':
    //                 $query->orderBy('stock', 'desc');
    //                 break;

    //             case 'Sort by date (new to old)':
    //                 $query->orderBy('created_at', 'desc');
    //                 break;

    //             case 'Sort by date (old to new)':
    //                 $query->orderBy('created_at', 'asc');
    //                 break;

    //             default:
    //                 $query->latest('created_at');
    //         }
    //     }

    //     if ($filters['type'] ?? false) {
    //         $query->where('type', $filters['type']);
    //     }
    // }
}
