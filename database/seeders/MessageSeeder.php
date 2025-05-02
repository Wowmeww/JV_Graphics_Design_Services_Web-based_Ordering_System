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
        $admin = User::where('role', 'admin')->get('id')->first();
        $users = User::where('role', 'customer')->get('id');

        foreach ($users as $user) {
            for ($i = 0; $i < env('MESSAGES_SENT'); $i++) {
                if (rand(0, 1)) {
                    Message::factory()->create([
                        'sender_id' => $user->id,
                        'receiver_id' => $admin->id
                    ]);
                    Message::factory()->create([
                        'sender_id' => $admin->id,
                        'receiver_id' => $user->id
                    ]);
                } else {
                    Message::factory()->create([
                        'sender_id' => $admin->id,
                        'receiver_id' => $user->id
                    ]);
                    Message::factory()->create([
                        'sender_id' => $user->id,
                        'receiver_id' => $admin->id
                    ]);
                }
            }
        }
    }
}
