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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->integer('author_id')->nullable();
            $table->string('views')->nullable();
            $table->string('blog_image')->nullable();
            $table->string('blog_image_driver')->nullable();
            $table->string('breadcrumb_status')->nullable();
            $table->string('breadcrumb_image')->nullable();
            $table->string('breadcrumb_image_driver')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
