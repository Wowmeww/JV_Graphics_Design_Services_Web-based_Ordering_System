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
            ['key' => 'app_name_short', 'value' => 'JV Graphics'],
            ['key' => 'app_logo', 'value' => ''],
            ['key' => 'daily_visitors', 'value' => ''],
            ['key' => 'landing_page_title', 'value' => 'Services. Offers. Products'],
            ['key' => 'landing_page_subtitle', 'value' => 'Explore our latest offers and services today.'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],  // prevent duplicates
                ['value' => $setting['value']]
            );
        }
    }
}
