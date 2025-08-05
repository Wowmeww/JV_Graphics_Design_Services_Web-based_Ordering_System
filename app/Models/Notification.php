<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    // one to many - belongs to
    public function from()
    {
        return $this->belongsTo(User::class, 'from');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver');
    }
}
