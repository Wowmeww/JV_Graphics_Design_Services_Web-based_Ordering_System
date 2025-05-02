<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'customer')->get('id');

        foreach ($users as $user) {
            for ($i = 0; $i < env('NOTIFICATIONS'); $i++) {
                Notification::factory()->create(['user_id' => $user->id]);
            }
        }
    }
}
