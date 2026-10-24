<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->boolean('activity_popup_enabled')->default(true);
        });
    }
    public function down(): void {
        Schema::table('basic_controls', fn (Blueprint $table) => $table->dropColumn('activity_popup_enabled'));
    }
};
