<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = Category::whereNull('parent_id')->get();
        
        if ($mainCategories->isEmpty()) {
            return;
        }

        $tags = Tag::all();

        $count = 1;
        foreach ($mainCategories as $category) {
            for ($i = 1; $i <= 4; $i++) {
                $price = rand(50, 1000);
                
                // 6 products in total will be featured out of 20
                $isFeatured = $count <= 6;
                
                // 4 products in total will be on sale
                $salePrice = null;
                if ($count <= 4) {
                    $salePrice = $price * 0.8; // 20% off
                }

                $product = Product::create([
                    'name' => "Premium {$category->name} Product {$i}",
                    'description' => "This is a full description for Premium {$category->name} Product {$i}. It contains all the necessary details about the product, its ingredients, and usage instructions.",
                    'short_description' => "A high-quality product for your {$category->name}.",
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'category_id' => $category->id, // Assigning to main category for simplicity, can also assign to subcategory if needed
                    'in_stock' => true,
                    'is_featured' => $isFeatured,
                    'is_active' => true,
                    'sort_order' => $i,
                ]);

                // Create a placeholder main image
                $product->images()->create([
                    'path' => 'products/placeholder.jpg',
                    'is_main' => true,
                    'sort_order' => 1,
                ]);

                // Attach some random tags
                if ($tags->isNotEmpty()) {
                    $randomTags = $tags->random(rand(1, 2))->pluck('id');
                    $product->tags()->attach($randomTags);
                }

                $count++;
            }
        }
    }
}
