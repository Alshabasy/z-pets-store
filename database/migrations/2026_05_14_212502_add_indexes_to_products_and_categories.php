<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug', 'products_slug_index');
            $table->index('category_id', 'products_category_id_index');
            $table->index('is_active', 'products_is_active_index');
            $table->index('is_featured', 'products_is_featured_index');
            $table->index('in_stock', 'products_in_stock_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug', 'categories_slug_index');
            $table->index('is_active', 'categories_is_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_slug_index');
            $table->dropIndex('products_category_id_index');
            $table->dropIndex('products_is_active_index');
            $table->dropIndex('products_is_featured_index');
            $table->dropIndex('products_in_stock_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_slug_index');
            $table->dropIndex('categories_is_active_index');
        });
    }
};
