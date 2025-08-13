<?php

namespace Database\Seeders;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if (env('APP_ENV') === 'production') {
            $this->call([SystemSettingSeeder::class, ProductionSeeder::class]);
        } else {
            $this->call([
                SystemSettingSeeder::class,
                UserSeeder::class,
                ProductSeeder::class,
                CartWishlistSeeder::class,
                OrderSeeder::class,
                NotificationSeeder::class,
                MessageSeeder::class,
                TransactionSeeder::class,
                RatingSeeder::class
            ]);
            Announcement::factory(env('ANNOUNCEMENTS'))->create();
        }
    }
}
