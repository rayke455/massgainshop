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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('social_media_id')->nullable();
            $table->integer('sort_by')->default(1);
            $table->string('category_title', 191)->nullable();
            $table->string('image', 191)->nullable();
            $table->string('image_driver', 50)->nullable();
            $table->boolean('status')->default(1)->comment('0 => inactive, 1 => active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
