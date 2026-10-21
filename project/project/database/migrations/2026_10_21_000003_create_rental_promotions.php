<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rental_promotions', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('panel_id')->index();
            $table->string('code', 40); $table->decimal('amount', 20, 4);
            $table->unsignedInteger('max_uses'); $table->unsignedInteger('uses')->default(0);
            $table->boolean('active')->default(true); $table->timestamp('expires_at')->nullable();
            $table->timestamps(); $table->unique(['panel_id', 'code']);
        });
        Schema::create('rental_promotion_redemptions', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('promotion_id'); $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 20, 4); $table->timestamp('created_at');
            $table->unique(['promotion_id', 'user_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('rental_promotion_redemptions'); Schema::dropIfExists('rental_promotions'); }
};
