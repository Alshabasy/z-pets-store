<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = $connection->select("PRAGMA index_list('{$table}')");
            foreach ($indexes as $idx) {
                if (($idx->name ?? null) === $index) {
                    return true;
                }
            }

            return false;
        }

        $indexes = DB::select('SHOW INDEX FROM '.$table.' WHERE Key_name = ?', [$index]);

        return count($indexes) > 0;
    }

    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (! $this->indexExists('categories', 'categories_parent_id_index')) {
                $table->index('parent_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! $this->indexExists('orders', 'orders_status_index')) {
                $table->index('status');
            }
            if (! $this->indexExists('orders', 'orders_created_at_index')) {
                $table->index('created_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! $this->indexExists('users', 'users_role_index')) {
                $table->index('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if ($this->indexExists('categories', 'categories_parent_id_index')) {
                $table->dropIndex(['parent_id']);
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if ($this->indexExists('orders', 'orders_status_index')) {
                $table->dropIndex(['status']);
            }
            if ($this->indexExists('orders', 'orders_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'users_role_index')) {
                $table->dropIndex(['role']);
            }
        });
    }
};
