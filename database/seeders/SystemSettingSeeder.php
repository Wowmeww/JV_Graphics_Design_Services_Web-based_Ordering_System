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
            ['key' => 'app_email', 'value' => env("EMAIL_ADDRESS")],
            ['key' => 'app_phone', 'value' => env("PHONE_NUMBER")],
            ['key' => 'app_logo', 'value' => ''],
            ['key' => 'daily_visitors', 'value' => ''],
            ['key' => 'landing_page_title', 'value' => 'Services. Offers. Products'],
            [
                'key' => 'landing_page_subtitle',
                'value' => 'Sublimation Sportswear / T-shirt Printing / Tarpaulin Printing / Panaflex Signages'
            ],
            [
                'key' => 'app_about',
                'value' => json_encode(
                    [
                        [
                            "header" => "Mission",
                            "content" => "To make clients INVALUABLE",
                        ],

                        [
                            "header" => "Who We Are",
                            "content" => "JV Graphics Design Services is a trusted printing business based in Zone 8, Bulan, Sorsogon, providing high-quality and customized printing services for over 13 years.",
                        ],

                        [
                            "header" => "What We Do",
                            "content" => "We specialize in t-shirt and jersey printing, as well as other personalized items. Our focus is on delivering creative, durable, and meaningful designs that bring our clients’ ideas to life.",
                        ],

                        [
                            "header" => "Our Commitment",
                            "content" => "With four branches working together under one mission—to make clients invaluable—we are committed to excellence, integrity, and customer satisfaction. As the first Atexco user in Sorsogon, equipped with the country’s most powerful sublimation printer, we ensure every print meets the highest standards.",
                        ],

                        [
                            "header" => "Why We Matter",
                            "content" => "At JV Graphics Design Services, we combine passion, technology, and experience to produce prints that make a lasting impression. Whether for personal use, business branding, or special events, we help clients stand out with designs that truly represent them.",
                        ],
                    ]
                )
            ],
            [
                'key' => 'facebook_page',
                'value' => 'https://www.facebook.com/jaivee.gds.sorsogonsportswear'
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
