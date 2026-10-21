<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up():void {
        // Widen only: preserve converted local-currency credits in the base-currency wallet.
        DB::statement('ALTER TABLE users MODIFY balance DECIMAL(20,8) NULL DEFAULT 0');
    }
    public function down():void {
        // Intentionally retain precision: shrinking this column would destroy wallet value.
    }
};
