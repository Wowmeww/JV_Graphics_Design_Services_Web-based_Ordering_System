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
        return $user && $user?->role === 'customer';
    }

    public function accessAsAdmin(User $user): bool
    {
        return true;
    }
}
