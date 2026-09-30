<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('child_panel_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('api_order_id')->nullable();
            $table->integer('api_refill_id')->nullable();
            $table->string('link')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->double('price', '18', '8')->nullable();
            $table->string('status')->nullable();
            $table->string('refill_status')->nullable();
            $table->string('status_description')->nullable();
            $table->text('reason')->nullable();
            $table->text('comments')->nullable();
            $table->tinyInteger('agree')->nullable();
            $table->bigInteger('start_counter')->nullable();
            $table->bigInteger('remains')->nullable();
            $table->tinyInteger('runs')->nullable();
            $table->tinyInteger('interval')->nullable();
            $table->tinyInteger('drip_feed')->nullable();
            $table->timestamp('refilled_at')->nullable();
            $table->timestamp('added_on')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
