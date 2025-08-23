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

    // one to oen - belongs to

    public function transaction()
    {
        $this->hasOne(Transaction::class);
    }

    public function resource()
    {
        return $this->hasMany(OrderImage::class);
    }


    public function scopeFilter(Builder $query, array $filters): Builder
    {

        if ($filters['search'] ?? false) {
            $search = '%' . trim($filters['search']) . '%';
            $query->where('id', 'like', $search)
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where(function ($userSubQuery) use ($search) {
                        $userSubQuery->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search);
                    });
                });
        }

        if ($filters['status'] ?? false) {
            $query->where('status', $filters['status']);
        }

        $filters['sort'] = $filters['sort'] ?? 'Sort by date (new to old)';

        switch ($filters['sort']) {
            case 'Sort by price (low to high)':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'Sort by price (high to low)':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'Sort by date (new to old)':
                $query->orderBy('created_at', 'desc'); // fixed
                break;
            case 'Sort by date (old to new)':
                $query->orderBy('created_at', 'asc'); // fixed
                break;
            case 'Sort by status':
                $query->orderByRaw("FIELD(status, 'pending', 'processing', 'completed', 'cancelled')");
                break;
        }


        return $query;
    }
}
