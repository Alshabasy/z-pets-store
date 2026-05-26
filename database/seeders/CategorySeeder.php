<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = ['Dogs', 'Cats', 'Birds', 'Small Pets', 'Fish & Aquatic'];
        
        $categoriesMap = [];
        
        foreach ($mainCategories as $index => $name) {
            $categoriesMap[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        $subcategories = [
            'Dogs' => ['Food', 'Accessories', 'Grooming', 'Health'],
            'Cats' => ['Food', 'Litter', 'Accessories', 'Grooming'],
        ];

        foreach ($subcategories as $parentName => $subs) {
            $parent = $categoriesMap[$parentName];
            foreach ($subs as $index => $subName) {
                Category::create([
                    'name' => $subName,
                    'slug' => Str::slug($parentName . ' ' . $subName),
                    'parent_id' => $parent->id,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
