<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rental_payout_destinations', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('user_id')->unique();
            $t->string('country', 2); $t->string('method', 20);
            $t->string('account', 30); $t->string('account_ref', 30)->nullable(); $t->timestamps();
        });
        Schema::table('rental_payouts', function (Blueprint $t) {
            $t->string('country', 2)->default('KE');
            $t->string('payout_currency', 3)->default('KES');
            $t->decimal('payout_amount', 20, 2)->nullable();
            $t->string('callback_token_hash', 64)->nullable();
        });
    }
    public function down(): void {
        Schema::table('rental_payouts', fn(Blueprint $t) => $t->dropColumn(['country','payout_currency','payout_amount','callback_token_hash']));
        Schema::dropIfExists('rental_payout_destinations');
    }
};
