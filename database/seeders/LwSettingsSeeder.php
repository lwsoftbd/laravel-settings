<?php

namespace LWSoftBD\LwSettings\Database\Seeders;

use Illuminate\Database\Seeder;
use LWSoftBD\LwSettings\Models\Setting;

class LwSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'general' => [
                ['key' => 'site_name', 'value' => 'My Awesome Site'],
                ['key' => 'site_email', 'value' => 'info@example.com'],
                ['key' => 'site_phone', 'value' => '+880123456789'],
                ['key' => 'site_address', 'value' => 'Dhaka, Bangladesh'],
            ],
            'appearance' => [
                ['key' => 'site_logo', 'value' => ''],
                ['key' => 'site_favicon', 'value' => ''], 
            ],
            'system' => [
                ['key' => 'site_timezone', 'value' => 'Asia/Dhaka'],
                ['key' => 'site_cache_enabled', 'value' => '1'],
            ],
        ];

        foreach ($defaults as $group => $settings) {
            foreach ($settings as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting['key']],
                    [
                        'value' => $setting['value'],
                        'group' => $group,
                        'type'  => $setting['type'] ?? 'text',
                    ]
                );

                // Clear cache for helper function
                cache()->forget("setting_{$setting['key']}");
            }
        }
    }
}