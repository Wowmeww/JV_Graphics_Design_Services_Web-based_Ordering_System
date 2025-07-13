<?php

namespace App\Policies;

use App\Models\User;

class ShopPolicy
{
    /**
     * Create a new policy instance.
     */
    public function shop(?User $user): bool
    {
        return $user && $user->role === 'customer';
    }
}

