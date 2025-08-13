<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // can ypu recommend any settings key inhere
        $settings = [
            ['key' => 'app_name', 'value' => 'JV Graphics Design Services'],
            ['key' => 'daily_visitor', 'value' => 0],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],  // prevent duplicates
                ['value' => $setting['value']]
            );
        }
    }
}
