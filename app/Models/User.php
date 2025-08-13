<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;


class User extends Authenticatable  implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // APPENDED ATTRIBUTE
    protected $appends = ['is_admin', 'messages', 'age'];
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }
    public function getMessagesAttribute()
    {
        return [];
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }


    // one to one - has one
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }
    public function idCard()
    {
        return $this->hasOne(UserID::class);
    }

    // one to many - has many
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'admin_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver');
    }
    public function notifies()
    {
        return $this->hasMany(Notification::class, 'from');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }




    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $user = Auth::user();
        $search = isset($filters['search']) ? '%' . $filters['search'] . '%' : '';
        if ($filters['search'] ?? false) {
            $query->whereAny(['name', 'email', 'role'], 'like', $search);
        }

        if ($filters['role'] ?? false) {
            $query->where('role', $filters['role']);
        }

        if ($filters['contacts'] ?? false) {
            if ($user->is_admin) {
                $query->where('id', '!=', $user->id);
            } else {
                $query->where('role', 'admin');
            }

            $query->when($search, fn($q)=> $q->whereAny(['name', 'email', 'role'], 'like', $search))->orderBy('id');
        }

        return $query;
    }


    // CUSTOM METHODS
    public function addOrder(array $data): Order
    {
        return $this->orders()->create($data);
    }

    public function sendMessage($receiver_id, $content): Message
    {
        return $this->sentMessages()->create([
            'receiver_id' => $receiver_id,
            'content' => $content,
        ]);
    }
}
