<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    public function create(?User $user): bool
    {
        return $user->is_admin;
    }
    public function createVariant(User $user, Product $product): bool
    {

        return $product->type === 'Main product with variant';
    }
}
