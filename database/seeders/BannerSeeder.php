<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['title' => 'Welcome to Z-Pets', 'image' => 'banners/banner1.jpg', 'sort_order' => 1],
            ['title' => 'Big Sale on Dog Food', 'image' => 'banners/banner2.jpg', 'sort_order' => 2],
            ['title' => 'New Cat Toys Arrived', 'image' => 'banners/banner3.jpg', 'sort_order' => 3],
        ];

        foreach ($banners as $banner) {
            Banner::create([
                'title' => $banner['title'],
                'image' => $banner['image'],
                'sort_order' => $banner['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
