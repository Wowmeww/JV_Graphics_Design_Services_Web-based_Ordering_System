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
            $admins = User::where('role', 'admin')->get('id');

            foreach ($admins as $admin) {
                Notification::factory()->create(['from' => $user->id, 'receiver' => $admin->id]);
            }

            for ($i = 0; $i < env('NOTIFICATIONS'); $i++) {
                $admin = User::where('role', 'admin')->inRandomOrder()->first();
                Notification::factory()->create(['from' => $admin->id, 'receiver' => $user->id]);
            }
        }
    }
}
