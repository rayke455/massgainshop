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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_title', 255)->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('min_amount')->nullable();
            $table->integer('max_amount')->nullable();
            $table->string('price', 191)->nullable();
            $table->string('service_type', 191)->nullable();
            $table->boolean('service_status')->default(1)->comment('0 => inactive, 1 => active');
            $table->integer('api_provider_id')->nullable();
            $table->integer('api_service_id')->nullable();
            $table->string('api_provider_price', 191)->nullable();
            $table->tinyInteger('drip_feed')->nullable();
            $table->tinyInteger('refill')->default(0);
            $table->boolean('is_refill_automatic')->default(0)->comment('0 => manual, 1 => automatic');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
