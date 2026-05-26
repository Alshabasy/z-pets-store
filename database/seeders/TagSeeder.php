<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'New Arrival', 'color' => '#2D6A2D'],
            ['name' => 'Sale', 'color' => '#D97706'],
            ['name' => 'Popular', 'color' => '#1B4D1B'],
            ['name' => 'Staff Pick', 'color' => '#185FA5'],
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag['name'],
                'slug' => Str::slug($tag['name']),
                'color' => $tag['color'],
            ]);
        }
    }
}
