<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'whatsapp_number', 'value' => '201000000000'],
            ['key' => 'store_name', 'value' => 'Z-Pets Store'],
            ['key' => 'store_tagline', 'value' => "Egypt's Favourite Pet Store"],
            ['key' => 'free_delivery_above', 'value' => '2000'],
            ['key' => 'delivery_note', 'value' => 'Free delivery on orders over 2000 EGP (Cairo & Giza)'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
