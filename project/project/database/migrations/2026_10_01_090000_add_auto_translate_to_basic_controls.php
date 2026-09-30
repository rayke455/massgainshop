<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('basic_controls', 'auto_translate')) {
            Schema::table('basic_controls', function (Blueprint $table) {
                // Translate the site into the visitor's language (by country) with Google's page translator.
                $table->boolean('auto_translate')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('basic_controls', 'auto_translate')) {
            Schema::table('basic_controls', function (Blueprint $table) {
                $table->dropColumn('auto_translate');
            });
        }
    }
};
