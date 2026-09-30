<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * double(8,2) rounded real market rates (0.3087 KWD, 0.0000119 BTC) to two decimals or zero.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `currencies` MODIFY `conversion_rate` DECIMAL(24,10) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `currencies` MODIFY `conversion_rate` DOUBLE(8,2) NOT NULL DEFAULT 0.00');
    }
};
