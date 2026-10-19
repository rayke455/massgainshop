<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up():void { Schema::table('guest_orders',function(Blueprint $t){
        $t->unsignedBigInteger('user_id')->nullable()->index();
        $t->boolean('auto_account')->default(false);
        $t->decimal('wallet_credit',20,8)->default(0);
        $t->unsignedBigInteger('order_amount_kes')->nullable();
    }); }
    public function down():void { Schema::table('guest_orders',fn(Blueprint $t)=>$t->dropColumn(['user_id','auto_account','wallet_credit','order_amount_kes'])); }
};
