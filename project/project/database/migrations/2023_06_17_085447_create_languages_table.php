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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->nullable();
            $table->string('short_name', 20)->nullable();
            $table->string('flag', 191)->nullable();
            $table->string('flag_driver', 50)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('rtl')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('default_status')->default(0)->comment('0 => inactive, 1 => active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
