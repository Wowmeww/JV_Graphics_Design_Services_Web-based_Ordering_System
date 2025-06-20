<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => 1,
            'role' => 'admin',
            'name' => 'Nico Bernard B. Firmanes',
            'email' => 'nicobernardfirmanes@gmail.com',
            'sex' => 'male',
            'birth_date' => '02-25-2004',
            'password' => bcrypt(123),
            'avatar_url' => 'https://static.ticimax.cloud/cdn-cgi/image/width=-,quality=85/47050/uploads/urunresimleri/buyuk/penguen-cocuklar-icin-sayilarla-boyama-3-ba2d.png'
        ]);

        // CREATE USER WITH CART AND WISHLIST
        for ($i = 1; $i <= env('USERS', 14); $i++) {
            $user = User::factory()->create();
            Cart::create(['user_id' => $user->id]);
            Wishlist::create(['user_id' => $user->id]);
        }
    }
}
