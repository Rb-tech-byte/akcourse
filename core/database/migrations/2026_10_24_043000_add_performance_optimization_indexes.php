<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Index on products.slug
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'slug')) {
            $this->addIndexIfNotExists('products', 'slug', 'products_slug_index');
        }

        // 2. Indexes on frontends (data_keys, slug)
        if (Schema::hasTable('frontends')) {
            if (Schema::hasColumn('frontends', 'data_keys')) {
                $this->addIndexIfNotExists('frontends', 'data_keys', 'frontends_data_keys_index');
            }
            if (Schema::hasColumn('frontends', 'slug')) {
                $this->addIndexIfNotExists('frontends', 'slug', 'frontends_slug_index');
            }
        }

        // 3. Indexes on order_items (purchase_code, user_id)
        if (Schema::hasTable('order_items')) {
            if (Schema::hasColumn('order_items', 'purchase_code')) {
                $this->addIndexIfNotExists('order_items', 'purchase_code', 'order_items_purchase_code_index');
            }
            if (Schema::hasColumn('order_items', 'user_id')) {
                $this->addIndexIfNotExists('order_items', 'user_id', 'order_items_user_id_index');
            }
        }

        // 4. Index on orders.trx
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'trx')) {
            $this->addIndexIfNotExists('orders', 'trx', 'orders_trx_index');
        }

        // 5. Indexes on carts (user_id, session_id)
        if (Schema::hasTable('carts')) {
            if (Schema::hasColumn('carts', 'user_id')) {
                $this->addIndexIfNotExists('carts', 'user_id', 'carts_user_id_index');
            }
            if (Schema::hasColumn('carts', 'session_id')) {
                $this->addIndexIfNotExists('carts', 'session_id', 'carts_session_id_index');
            }
        }

        // 6. Index on categories.slug
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'slug')) {
            $this->addIndexIfNotExists('categories', 'slug', 'categories_slug_index');
        }
    }

    /**
     * Helper to safely add an index if not already present.
     */
    protected function addIndexIfNotExists(string $table, string $column, string $indexName): void
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        if (empty($indexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($column, $indexName) {
                $tableBlueprint->index($column, $indexName);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $drops = [
            'products'    => 'products_slug_index',
            'frontends'   => 'frontends_data_keys_index',
            'order_items' => 'order_items_purchase_code_index',
            'orders'      => 'orders_trx_index',
            'carts'       => 'carts_user_id_index',
            'categories'  => 'categories_slug_index',
        ];

        foreach ($drops as $table => $index) {
            if (Schema::hasTable($table)) {
                $existing = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]);
                if (!empty($existing)) {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($index) {
                        $tableBlueprint->dropIndex($index);
                    });
                }
            }
        }
    }
};
