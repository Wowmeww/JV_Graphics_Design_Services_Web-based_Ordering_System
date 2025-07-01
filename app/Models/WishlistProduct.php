<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


class WishlistProduct extends Pivot
{
    protected $table = 'wishlist_product';
}
