<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->id)) {
                do {
                    $id = Str::lower(Str::random(8));
                } while (self::where('id', $id)->exists());

                $order->id = $id;
            }
        });
    }

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



    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? false, function ($q) use ($filters) {
                $search = '%' . trim($filters['search']) . '%';
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', 'like', $search)
                        ->orWhere('type', 'like', $search)
                        ->orWhere('note', 'like', $search)
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where(function ($userSubQuery) use ($search) {
                                $userSubQuery->where('name', 'like', $search)
                                    ->orWhere('email', 'like', $search);
                            });
                        });
                });
            })
            ->when($filters['status'] ?? false, function ($q) use ($filters) {
                $q->where('status', 'like', '%' . $filters['status'] . '%');
            })->when(
                $filters['date_from'] ?? false,
                fn($q) =>
                $q->whereDate('created_at', '>=', $filters['date_from'])
            )
            ->when(
                $filters['date_to'] ?? false,
                fn($q) =>
                $q->whereDate('created_at', '<=', $filters['date_to'])
            );
    }
}
