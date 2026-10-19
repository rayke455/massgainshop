<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Panel rentals, part 2: branding, own prices, customer checkout through the platform's M-Pesa Paybill (C2B),
 * reseller earnings and payouts (B2C to a phone / B2B to a Paybill or Till).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('rental_panels', function (Blueprint $t) {
            if (!Schema::hasColumn('rental_panels', 'logo')) {
                $t->string('logo')->nullable();                       // path under assets/upload/rentals
                $t->string('color', 7)->default('#2563eb');
                $t->string('tagline', 160)->nullable();
                $t->string('whatsapp', 20)->nullable();
                $t->string('currency', 8)->nullable();                // display/checkout currency (null = KES if active, else base)
                $t->string('payment_mode', 20)->default('mpesa');     // mpesa = platform Paybill checkout, manual = request & confirm
            }
        });

        if (!Schema::hasTable('rental_panel_prices')) {
            Schema::create('rental_panel_prices', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('panel_id');
                $t->unsignedBigInteger('service_id');
                $t->decimal('price', 20, 6)->nullable();              // per 1,000, in the panel currency (null = markup)
                $t->boolean('hidden')->default(false);
                $t->timestamps();
                $t->unique(['panel_id', 'service_id']);
            });
        }

        Schema::table('rental_requests', function (Blueprint $t) {
            if (!Schema::hasColumn('rental_requests', 'phone')) {
                $t->string('phone', 20)->nullable();
                $t->string('payment_ref', 100)->nullable()->unique();  // M-Pesa CheckoutRequestID
                $t->decimal('paid_amount', 20, 4)->nullable();          // KES actually paid
                $t->timestamp('paid_at')->nullable();
                $t->decimal('cost', 20, 6)->nullable();                 // platform price, base currency
                $t->decimal('earning', 20, 6)->nullable();              // reseller profit credited, base currency
                $t->string('note')->nullable();
            }
        });

        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'rental_earnings')) {
                $t->decimal('rental_earnings', 20, 6)->default(0);      // withdrawable panel profit, base currency
            }
        });

        if (!Schema::hasTable('rental_payouts')) {
            Schema::create('rental_payouts', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->index();
                $t->decimal('amount', 20, 6);                            // base currency, taken from earnings
                $t->decimal('amount_kes', 20, 2);
                $t->string('method', 20);                                // b2c | paybill | till
                $t->string('account', 30);                               // phone, paybill or till number
                $t->string('account_ref', 30)->nullable();               // paybill account number
                $t->string('status', 20)->default('pending');            // pending | processing | paid | rejected | failed
                $t->string('conversation_id', 100)->nullable()->index();
                $t->string('mpesa_ref', 60)->nullable();
                $t->string('note')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_payouts');
        Schema::dropIfExists('rental_panel_prices');
        Schema::table('rental_panels', fn(Blueprint $t) => $t->dropColumn(['logo', 'color', 'tagline', 'whatsapp', 'currency', 'payment_mode']));
        Schema::table('rental_requests', fn(Blueprint $t) => $t->dropColumn(['phone', 'payment_ref', 'paid_amount', 'paid_at', 'cost', 'earning', 'note']));
        Schema::table('users', fn(Blueprint $t) => $t->dropColumn('rental_earnings'));
    }
};
