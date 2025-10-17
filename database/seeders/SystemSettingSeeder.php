<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\User;
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
            ['key' => 'app_email', 'value' => User::first()->email],
            ['key' => 'app_phone', 'value' => User::first()->phone],
            ['key' => 'app_logo', 'value' => ''],
            ['key' => 'daily_visitors', 'value' => ''],
            ['key' => 'landing_page_title', 'value' => 'Services. Offers. Products'],
            [
                'key' => 'landing_page_subtitle',
                'value' => 'Sublimation Sportswear / T-shirt Printing / Tarpaulin Printing / Panaflex Signages'
            ],
            [
                'key' => 'app_about',
                'value' => json_encode([
                    ['header' => 'Our Story', 'content' => ""],
                    ['header' => 'Our Mission', 'content' => ""],
                ])
            ],
            [
                'key' => 'facebook_page',
                'value' => 'https://www.facebook.com/jaivee.gds.sorsogonsportswear'
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],  // prevent duplicates
                ['value' => $setting['value']]
            );
        }
    }
}
