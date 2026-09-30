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
        Schema::create('api_providers', function (Blueprint $table) {
            $table->id();
            $table->string('api_name', 191)->nullable();
            $table->string('url', 191)->nullable();
            $table->string('api_key', 191)->nullable();
            $table->double('balance', 8,2)->default(0.0);
            $table->string('currency', 191)->nullable();
            $table->double('conversion_rate', '18', '8')->default(0.0);
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => inactive, 1 => active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_providers');
    }
};
