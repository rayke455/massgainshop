<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('two_fa')->default(0)->comment('0=>inactive, 1=>active')->after('remember_token');
            $table->boolean('two_fa_verify')->default(0)->comment('0=>inactive, 1=>active')->after('two_fa');
            $table->string('two_fa_code', 50)->nullable()->after('two_fa_verify');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('two_fa');
            $table->dropColumn('two_fa_verify');
            $table->dropColumn('two_fa_code');
        });
    }
};
