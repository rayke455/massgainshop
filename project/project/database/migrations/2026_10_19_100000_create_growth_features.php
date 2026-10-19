<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Growth features: coupons & deposit bonuses, VIP levels, favourites, reviews, free trial,
 * backup provider per service, and a key/value settings table for all of their options.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $t) {
                $t->id();
                $t->string('key')->unique();
                $t->text('value')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $t) {
                $t->id();
                $t->string('code', 40)->unique();
                $t->enum('type', ['percent', 'fixed'])->default('percent'); // bonus added on top of the deposit
                $t->decimal('value', 14, 4);
                $t->decimal('min_deposit', 14, 4)->default(0);              // base currency
                $t->unsignedInteger('max_uses')->nullable();                // null = unlimited
                $t->unsignedInteger('per_user')->default(1);
                $t->boolean('first_deposit_only')->default(false);
                $t->timestamp('expires_at')->nullable();
                $t->boolean('status')->default(true);
                $t->unsignedInteger('used_count')->default(0);
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_redemptions')) {
            Schema::create('coupon_redemptions', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('coupon_id')->index();
                $t->unsignedBigInteger('user_id')->index();
                $t->unsignedBigInteger('deposit_id')->unique();
                $t->decimal('bonus', 14, 4)->default(0);
                $t->timestamps();
            });
        }

        Schema::table('deposits', function (Blueprint $t) {
            if (!Schema::hasColumn('deposits', 'coupon_id')) {
                $t->unsignedBigInteger('coupon_id')->nullable();
                $t->decimal('bonus_amount', 14, 4)->nullable();             // credited bonus, base currency
            }
        });

        if (!Schema::hasTable('vip_levels')) {
            Schema::create('vip_levels', function (Blueprint $t) {
                $t->id();
                $t->string('name', 60);
                $t->decimal('min_spent', 14, 4)->default(0);                // lifetime spend, base currency
                $t->decimal('discount_percent', 5, 2)->default(0);
                $t->string('color', 20)->default('#2563eb');
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('favorite_services')) {
            Schema::create('favorite_services', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id');
                $t->unsignedBigInteger('service_id');
                $t->timestamps();
                $t->unique(['user_id', 'service_id']);
            });
        }

        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->index();
                $t->unsignedBigInteger('order_id')->nullable()->unique();   // one review per order
                $t->unsignedTinyInteger('rating');
                $t->text('comment');
                $t->string('display_name', 60);
                $t->string('country', 60)->nullable();
                $t->unsignedTinyInteger('status')->default(0);              // 0 pending, 1 approved, 2 rejected
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('free_trials')) {
            Schema::create('free_trials', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->unique();
                $t->string('phone', 40)->nullable()->index();
                $t->string('ip', 64)->nullable()->index();
                $t->unsignedBigInteger('order_id')->nullable();
                $t->timestamps();
            });
        }

        Schema::table('services', function (Blueprint $t) {
            if (!Schema::hasColumn('services', 'backup_api_provider_id')) {
                $t->unsignedBigInteger('backup_api_provider_id')->nullable();
                $t->unsignedBigInteger('backup_api_service_id')->nullable();
            }
        });

        Schema::table('orders', function (Blueprint $t) {
            if (!Schema::hasColumn('orders', 'api_provider_id')) {
                $t->unsignedBigInteger('api_provider_id')->nullable();      // provider that actually took the order
            }
        });

        // "Order completed" message to the customer.
        if (!DB::table('notification_templates')->where('template_key', 'ORDER_COMPLETED')->exists()) {
            DB::table('notification_templates')->insert([
                'language_id' => 1,
                'name' => 'Order Completed',
                'email_from' => null,
                'template_key' => 'ORDER_COMPLETED',
                'subject' => 'Your order is complete',
                'short_keys' => json_encode(['order_id' => 'Order id', 'service' => 'Service name', 'quantity' => 'Quantity']),
                'email' => 'Good news! Your order #[[order_id]] ([[quantity]] x [[service]]) has been completed.',
                'sms' => 'Order #[[order_id]] is complete. Thank you for ordering!',
                'in_app' => 'Order #[[order_id]] ([[service]]) is complete.',
                'push' => 'Order #[[order_id]] is complete.',
                'status' => json_encode(['mail' => '1', 'sms' => '0', 'in_app' => '1', 'push' => '1']),
                'notify_for' => 0,
                'lang_code' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Sensible starting VIP levels (editable in admin).
        if (DB::table('vip_levels')->count() === 0) {
            DB::table('vip_levels')->insert([
                ['name' => 'Bronze', 'min_spent' => 0, 'discount_percent' => 0, 'color' => '#b45309', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Silver', 'min_spent' => 50, 'discount_percent' => 3, 'color' => '#64748b', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Gold', 'min_spent' => 250, 'discount_percent' => 6, 'color' => '#ca8a04', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Platinum', 'min_spent' => 1000, 'discount_percent' => 10, 'color' => '#7c3aed', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        foreach (['site_settings', 'coupons', 'coupon_redemptions', 'vip_levels', 'favorite_services', 'reviews', 'free_trials'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('deposits', fn(Blueprint $t) => $t->dropColumn(['coupon_id', 'bonus_amount']));
        Schema::table('services', fn(Blueprint $t) => $t->dropColumn(['backup_api_provider_id', 'backup_api_service_id']));
        Schema::table('orders', fn(Blueprint $t) => $t->dropColumn('api_provider_id'));
        DB::table('notification_templates')->where('template_key', 'ORDER_COMPLETED')->delete();
    }
};
