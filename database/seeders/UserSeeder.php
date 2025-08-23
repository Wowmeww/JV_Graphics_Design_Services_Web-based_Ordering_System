<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Wishlist;
use Database\Factories\WishlistFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penguinImageUrl = "https://models-online-persist.shakker.cloud/img/7d6730dea17347de84385f3aa4adef7b/428aeb864beb860a7883bfba4a310874416fb1514a73ef581aee30eef43b7155.png?x-oss-process=image/resize,w_764,m_lfit/format,webp";
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Nico Bernard B. Firmanes',
            'email' => 'nicobernardfirmanes@gmail.com',
            'sex' => 'male',
            'birth_date' => '2004-02-25',
            'password' => bcrypt(123),
            'avatar_url' => $penguinImageUrl,
            'email_verified_at' => Carbon::now(),
            'verified_at' => Carbon::now(),
        ]);
        User::factory()->create([
            'role' => 'admin',
            'name' => "JV Graphics Design Services",
            'email' => 'jvgraphicsdesignservices@mail.com',
            'sex' => 'male', // <-- add this
            'birth_date' => '2000-01-01', // <-- add this
            'password' => bcrypt(123),
            'avatar_url' => $penguinImageUrl,
            'email_verified_at' => Carbon::now(),
            'verified_at' => Carbon::now(),
        ]);

        $customer = User::factory()->create([
            'name' => 'Nico Bernard B. Firmanes',
            'email' => 'nbfirmanes@sorsu.edu.ph',
            'sex' => 'male',
            'birth_date' => '2004-02-25',
            'password' => bcrypt(123),
            'avatar_url' => $penguinImageUrl,
            // 'email_verified_at' => Carbon::now(),
            // 'verified_at' => Carbon::now(),
        ]);
        $customer->cart()->create();
        $customer->wishlist()->create();


        // CREATE USER WITH CART AND WISHLIST
        for ($i = 1; $i <= env('USERS', 14); $i++) {
            $user = User::factory()->create();
            Cart::create(['user_id' => $user->id]);
            Wishlist::create(['user_id' => $user->id]);
        }
    }
}
