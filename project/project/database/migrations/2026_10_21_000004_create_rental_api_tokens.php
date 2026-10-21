<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rental_api_tokens', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('panel_id')->index();
            $table->unsignedBigInteger('user_id')->unique(); $table->string('token_hash',64)->unique();
            $table->timestamp('expires_at'); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rental_api_tokens'); }
};
