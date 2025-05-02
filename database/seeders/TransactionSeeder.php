<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'customer')->get('id');
        $admin = User::where('role', 'admin')->first();

        foreach ($users as $user) {
            for ($i = 0; $i < env('TRANSACTIONS'); $i++) {
                Transaction::factory()->create(['user_id' => $user->id]);
            }
        }
    }
}
