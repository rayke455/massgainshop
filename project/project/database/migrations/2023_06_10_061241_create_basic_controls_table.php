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
        Schema::create('basic_controls', function (Blueprint $table) {
            $table->id();
            $table->string('theme', 50)->nullable();
            $table->string('user_dashboard', 255)->nullable();
            $table->string('site_title', 255)->nullable();
            $table->string('time_zone', 50)->nullable();
            $table->string('base_currency', 20)->nullable();
            $table->string('currency_symbol', 20)->nullable();
            $table->double('child_panel_price', '8', '2')->default(0);
            $table->string('admin_prefix', 191)->nullable();
            $table->string('is_currency_position', 191)->nullable();
            $table->string('is_currency_position', 50)->nullable();
            $table->tinyInteger('has_space_between_currency_and_amount')->default(0)->comment('0 => no space, 1 => space');
            $table->tinyInteger('is_force_ssl')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('is_maintenance_mode')->default(0)->comment('0 => inactive, 1 => active');
            $table->integer('paginate')->nullable();
            $table->tinyInteger('strong_password')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('registration')->default(0)->comment('0 => inactive, 1 => active');
            $table->integer('fraction_number')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_email_name')->nullable();
            $table->text('email_description')->nullable();
            $table->tinyInteger('push_notification')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('in_app_notification')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('email_notification')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('email_verification')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('sms_notification')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('sms_verification')->default(0)->comment('0 => inactive, 1 => active');
            $table->string('tawk_id', 255)->nullable();
            $table->tinyInteger('tawk_status')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('fb_messenger_status')->default(0)->comment('0 => inactive, 1 => active');
            $table->string('fb_app_id', 255)->nullable();
            $table->string('fb_page_id', 255)->nullable();
            $table->tinyInteger('manual_recaptcha')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('google_recaptcha')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('manual_recaptcha_admin_login')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('manual_recaptcha_login')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('manual_recaptcha_register')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('google_recaptcha_admin_login')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('google_recaptcha_login')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('google_recaptcha_register')->default(0)->comment('0 => inactive, 1 => active');
            $table->string('measurement_id', 255)->nullable();
            $table->tinyInteger('analytic_status')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('error_log')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('is_active_cron_notification')->default(0)->comment('0 => inactive, 1 => active');
            $table->string('logo', 255)->nullable();
            $table->string('logo_driver', 50)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('favicon_driver', 50)->nullable();
            $table->string('admin_logo', 255)->nullable();
            $table->string('admin_logo_driver', 50)->nullable();
            $table->string('admin_dark_mode_logo', 255)->nullable();
            $table->string('admin_dark_mode_logo_driver', 50)->nullable();
            $table->string('currency_layer_access_key', 255)->nullable();
            $table->string('currency_layer_auto_update_at', 255)->nullable();
            $table->string('currency_layer_auto_update', 255)->nullable();
            $table->string('coin_market_cap_app_key', 255)->nullable();
            $table->string('coin_market_cap_auto_update_at', 255)->nullable();
            $table->tinyInteger('coin_market_cap_auto_update')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('automatic_payment_permission')->default(0)->comment('0 => inactive, 1 => active');
            $table->string('date_time_format', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_controls');
    }
};
