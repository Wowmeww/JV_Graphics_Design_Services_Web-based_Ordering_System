<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Wishlist;
use Database\Factories\WishlistFactory;
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
            'birth_date' => '2004-02-25',
            'password' => bcrypt(123),
            'avatar_url' => 'https://static.ticimax.cloud/cdn-cgi/image/width=-,quality=85/47050/uploads/urunresimleri/buyuk/penguen-cocuklar-icin-sayilarla-boyama-3-ba2d.png'
        ]);
        User::factory()->create([
            'role' => 'admin',
            'name' => env('APP_NAME'),
            'email' => 'jvgraphicsdesignservices@mail.com',
            'password' => bcrypt(123),
            'avatar_url' => 'https://static.ticimax.cloud/cdn-cgi/image/width=-,quality=85/47050/uploads/urunresimleri/buyuk/penguen-cocuklar-icin-sayilarla-boyama-3-ba2d.png'
        ]);
        $customer = User::factory()->create([
            'name' => 'Nico Bernard B. Firmanes',
            'email' => 'nbfirmanes@sorsu.edu.ph',
            'sex' => 'male',
            'birth_date' => '2004-02-25',
            'password' => bcrypt(123),
            'avatar_url' => 'https://static.ticimax.cloud/cdn-cgi/image/width=-,quality=85/47050/uploads/urunresimleri/buyuk/penguen-cocuklar-icin-sayilarla-boyama-3-ba2d.png'
        ]);

        Cart::create([
            'user_id' => $customer->id
        ]);
        Wishlist::create([
            'user_id' => $customer->id
        ]);



        // CREATE USER WITH CART AND WISHLIST
        for ($i = 1; $i <= env('USERS', 14); $i++) {
            $user = User::factory()->create();
            Cart::create(['user_id' => $user->id]);
            Wishlist::create(['user_id' => $user->id]);
        }
    }
}
