<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rental_billing_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('panel_id')->primary();
            $table->decimal('balance', 20, 6)->default(0);
            $table->unsignedInteger('grace_hours')->default(72);
            $table->timestamp('grace_ends_at')->nullable();
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });
        Schema::create('rental_billing_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panel_id')->index();
            $table->unsignedBigInteger('order_id')->unique();
            $table->decimal('profit', 20, 6);
            $table->decimal('rate', 8, 4);
            $table->decimal('fee', 20, 6);
            $table->decimal('charged', 20, 6)->default(0);
            $table->unsignedInteger('revision')->default(0);
            $table->timestamps();
        });
        Schema::create('rental_billing_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panel_id')->index();
            $table->string('reference', 100)->unique();
            $table->string('kind', 30);
            $table->decimal('amount', 20, 6);
            $table->decimal('balance', 20, 6);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->timestamp('created_at');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('rental_billing_ledger');
        Schema::dropIfExists('rental_billing_charges');
        Schema::dropIfExists('rental_billing_accounts');
    }
};
