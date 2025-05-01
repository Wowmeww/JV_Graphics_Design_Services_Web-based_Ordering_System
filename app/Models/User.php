<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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

    public function isAdmin() {
        return $this->role === 'admin';
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

    // one to many - has many
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function sentMessages() {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages() {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
    public function announcements() {
        return $this->hasMany(Announcement::class, 'admin_id');
    }
    public function notifications() {
        return $this->hasMany(Notification::class, 'admin_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }


}
