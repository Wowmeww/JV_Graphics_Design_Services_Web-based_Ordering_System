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
            'email' => 'jvgraphicsdesignservices@mail.com',
            'password' => bcrypt(123),
            'email_verified_at' => Carbon::now(),
            'verified_at' => Carbon::now(),
            'avatar_url' => 'https://static.ticimax.cloud/cdn-cgi/image/width=-,quality=85/47050/uploads/urunresimleri/buyuk/penguen-cocuklar-icin-sayilarla-boyama-3-ba2d.png'
        ]);

        $this->call([SystemSettingSeeder::class,]);
    }
}
