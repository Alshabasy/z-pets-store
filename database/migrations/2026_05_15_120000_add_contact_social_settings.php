<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        DB::table('settings')->insertOrIgnore([
            ['key' => 'shop_address', 'value' => 'Cairo, Egypt', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'social_facebook', 'value' => '', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'social_instagram', 'value' => '', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'social_tiktok', 'value' => '', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'shop_address',
            'social_facebook',
            'social_instagram',
            'social_tiktok',
        ])->delete();
    }
};
