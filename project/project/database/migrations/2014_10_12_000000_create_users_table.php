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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 191)->nullable();
            $table->string('lastname', 191)->nullable();
            $table->string('username', 100)->nullable();
            $table->integer('child_panel_id')->nullable();
            $table->integer('referral_id')->nullable();
            $table->integer('language_id')->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('country_code', 20)->nullable();
            $table->string('country', 191)->nullable();
            $table->string('phone_code', 20)->nullable();
            $table->string('phone', 50)->nullable();
            $table->decimal('balance', 11, 2)->default(0.00);
            $table->string('image', 255)->nullable();
            $table->string('image_driver', 50)->nullable();
            $table->string('state', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('zip_code', 50)->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->string('provider', 191)->nullable();
            $table->string('provider_id', 191)->nullable();
            $table->boolean('status')->default(1)->comment('0 => inactive, 1 => active');
            $table->boolean('two_fa')->default(1)->comment('0 => inactive, 1 => active');
            $table->boolean('two_fa_verify')->default(1)->comment('0 => inactive, 1 => active');
            $table->string('two_fa_code', 50)->nullable();
            $table->boolean('email_verification')->default(1)->comment('0 => unverified, 1 => verified');
            $table->boolean('sms_verification')->default(1)->comment('0 => unverified, 1 => verified');
            $table->string('verify_code', 50)->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_seen')->nullable();
            $table->string('time_zone', 191)->nullable();
            $table->string('password', 191)->nullable();
            $table->string('currency', 50)->nullable();
            $table->string('api_token', 191)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
