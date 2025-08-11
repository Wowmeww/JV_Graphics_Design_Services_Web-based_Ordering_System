<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserID extends Model
{
    protected $table = 'users_identification_cards';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
