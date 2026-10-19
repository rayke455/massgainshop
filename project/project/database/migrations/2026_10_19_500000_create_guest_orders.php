<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('guest_orders', function (Blueprint $t) {
            $t->id(); $t->uuid('token')->unique(); $t->uuid('checkout_key')->unique();
            $t->unsignedBigInteger('service_id'); $t->unsignedBigInteger('order_id')->nullable()->unique();
            $t->string('email'); $t->string('phone',20); $t->text('link'); $t->unsignedInteger('quantity');
            $t->decimal('base_amount',20,8); $t->unsignedBigInteger('amount_kes');
            $t->string('payment_ref',100)->nullable()->unique(); $t->string('status',30)->default('starting');
            $t->timestamp('paid_at')->nullable(); $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('guest_orders'); }
};
