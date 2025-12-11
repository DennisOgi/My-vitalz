<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('storefront_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('storefront_order_items', 'marketplace_drug_id')) {
                $table->unsignedBigInteger('marketplace_drug_id')->nullable()->after('order_id');
            }
            
            // Also ensure defaults are set for price columns if they don't have them
            // We can't easily modify existing columns to add defaults in SQLite/MySQL compatible way 
            // without doctrine/dbal, so we'll just ensure the columns exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storefront_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('storefront_order_items', 'marketplace_drug_id')) {
                $table->dropColumn('marketplace_drug_id');
            }
        });
    }
};
