<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'name' => "JV Graphics Design Services",
            'email' => 'jaiveegds2024@gmail.com.test',
            'password' => bcrypt('jaiveegds2024@gmail.com.test'),
            'email_verified_at' => Carbon::now(),
            'birth_date' => Carbon::now(),
            'address' => 'Bulan, Sorsogon, Philippines',
            'verified_at' => Carbon::now(),
            'avatar_url' => null,
            'phone' => '09129319355'
        ]);

        $this->call([SystemSettingSeeder::class]);
    }
}
