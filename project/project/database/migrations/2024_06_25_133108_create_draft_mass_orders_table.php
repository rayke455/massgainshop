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
        Schema::create('draft_mass_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('child_panel_id')->nullable();
            $table->string('order_id', 50)->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('link', 255)->nullable();
            $table->double('price', '18', '8')->default(0.00);
            $table->text('remarks')->nullable();
            $table->boolean('status')->default(0)->comment(' 0 => invalid, 1 => valid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_mass_orders');
    }
};
