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
            'email' => env("EMAIL_ADDRESS"),
            'password' => bcrypt(env('EMAIL_ADDRESS')),
            'email_verified_at' => Carbon::now(),
            'birth_date' => Carbon::now(),
            'address' => 'Bulan, Sorsogon, Philippines',
            'verified_at' => Carbon::now(),
            'avatar_url' => null,
            'phone' => env("PHONE_NUMBER"),
        ]);
        // User::factory()->create([
        //     'role' => 'customer',
        //     'name' => "Nico Bernard B. Firmanes",
        //     'email' => 'nbfirmanes@sorsu.edu.ph',
        //     'password' => bcrypt('123'),
        //     'email_verified_at' => Carbon::now(),
        //     'birth_date' => Carbon::now(),
        //     'address' => 'Bulusan, Sorsogon, Philippines',
        //     'verified_at' => Carbon::now(),
        //     'avatar_url' => null,
        // ]);

        $this->call([SystemSettingSeeder::class]);
    }
}
