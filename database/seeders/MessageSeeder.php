<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->pluck('id')->first();
        $userIds = User::where('role', 'customer')->pluck('id');

        foreach ($userIds as $userId) {
            for ($i = 0; $i < env('MESSAGES_SENT'); $i++) {
                if (rand(0, 1)) {
                    Message::factory()->create([
                        'sender_id' => $userId,
                        'receiver_id' => $adminId,
                        'content' => fake()->sentence(),
                        'created_at' => now()->subMinutes(rand(1, 1000)),
                    ]);
                    Message::factory()->create([
                        'sender_id' => $adminId,
                        'receiver_id' => $userId,
                        'content' => fake()->sentence(),
                        'created_at' => now()->subMinutes(rand(1, 1000)),
                    ]);
                } else {
                    Message::factory()->create([
                        'sender_id' => $adminId,
                        'receiver_id' => $userId,
                        'content' => fake()->sentence(),
                        'created_at' => now()->subMinutes(rand(1, 1000)),
                    ]);
                    Message::factory()->create([
                        'sender_id' => $userId,
                        'receiver_id' => $adminId,
                        'content' => fake()->sentence(),
                        'created_at' => now()->subMinutes(rand(1, 1000)),
                    ]);
                }
            }
        }
    }
}
