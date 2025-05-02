<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::find(rand(1, env('PRODUCTS')));
        $users = User::where('role', 'customer')->get();
        $qty = rand(1, 4) * 12;

        foreach ($users as $user) {
            for ($i = 0; $i < env('ORDERS'); $i++) {
                Order::factory()->create([
                    'user_id' => $user->id,
                    'quantity' => $qty,
                    'total_amount' => $qty * $product->price,
                ])->products()->attach($product);
            }
        }
    }
}
