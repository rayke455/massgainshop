<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_providers', function (Blueprint $table) {
            if (!Schema::hasColumn('api_providers', 'low_balance_alert')) {
                // Warn the admin when the provider balance drops below this (provider currency).
                $table->decimal('low_balance_alert', 14, 2)->default(5);
                $table->timestamp('balance_checked_at')->nullable();
                $table->timestamp('balance_alerted_at')->nullable();
            }
        });

        Schema::table('basic_controls', function (Blueprint $table) {
            if (!Schema::hasColumn('basic_controls', 'alert_email')) {
                $table->string('alert_email')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_providers', function (Blueprint $table) {
            $table->dropColumn(['low_balance_alert', 'balance_checked_at', 'balance_alerted_at']);
        });
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->dropColumn('alert_email');
        });
    }
};
