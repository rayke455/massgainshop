<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('admin_support_sessions', function (Blueprint $table) {
        $table->id(); $table->unsignedBigInteger('admin_id')->index(); $table->unsignedBigInteger('user_id')->index();
        $table->unsignedBigInteger('panel_id')->nullable(); $table->timestamp('started_at');
        $table->timestamp('expires_at'); $table->timestamp('ended_at')->nullable(); $table->string('end_reason', 30)->nullable();
    }); }
    public function down(): void { Schema::dropIfExists('admin_support_sessions'); }
};
