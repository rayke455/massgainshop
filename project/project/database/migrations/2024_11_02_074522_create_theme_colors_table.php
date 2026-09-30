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
        Schema::create('theme_colors', function (Blueprint $table) {
            $table->id();
            $table->string('light_green_primary_color', 100)->nullable();
            $table->string('light_green_secondary_color', 100)->nullable();
            $table->string('light_green_hero_color', 100)->nullable();
            $table->string('dark_violet_primary_color', 100)->nullable();
            $table->string('dark_violet_secondary_color', 100)->nullable();
            $table->string('minimal_primary_color', 100)->nullable();
            $table->string('minimal_secondary_color', 100)->nullable();
            $table->string('minimal_sub_heading_color', 100)->nullable();
            $table->string('minimal_bg_left_color', 100)->nullable();
            $table->string('minimal_bg_right_color', 100)->nullable();
            $table->string('minimal_button_left_color', 100)->nullable();
            $table->string('minimal_bg_left_two_color', 100)->nullable();
            $table->string('minimal_copy_right_bg_color', 100)->nullable();
            $table->string('deep_blue_primary_color', 100)->nullable();
            $table->string('deep_blue_secondary_color', 100)->nullable();
            $table->string('dark_mode_primary_color', 100)->nullable();
            $table->string('dark_mode_secondary_color', 100)->nullable();
            $table->string('light_orange_primary_color', 100)->nullable();
            $table->string('light_orange_theme_light_color', 100)->nullable();
            $table->string('light_orange_secondary_color', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_colors');
    }
};
