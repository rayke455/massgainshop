<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * What the provider charged us for the order (base currency), captured when the order is placed,
     * so profit = price - provider_cost stays correct even if provider prices change later.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'provider_cost')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('provider_cost', 18, 8)->nullable()->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'provider_cost')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('provider_cost');
            });
        }
    }
};
