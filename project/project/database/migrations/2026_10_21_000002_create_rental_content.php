<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rental_content', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panel_id')->index();
            $table->string('kind', 20);
            $table->string('slug', 100);
            $table->string('title', 160);
            $table->text('body')->nullable();
            $table->string('url', 2048)->nullable();
            $table->boolean('published')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            $table->unique(['panel_id', 'slug']);
        });
    }
    public function down(): void { Schema::dropIfExists('rental_content'); }
};
