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
        $users = User::where('role', 'customer')->get();

        foreach ($users as $user) {
            for ($i = 0; $i < env('ORDERS'); $i++) {
                // Pick a random product each time
                $product = Product::inRandomOrder()->first();
                $qty = rand(12, 24);

                $order = Order::factory()->create([
                    'user_id' => $user->id,
                    'quantity' => $qty,
                    'total_amount' => $qty * $product->price,
                    'product_id' => $product->id,
                ]);

            }
        }
    }
}
