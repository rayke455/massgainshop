<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Customer accounts on rented panels: users and orders that belong to a panel. */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'rental_panel_id')) {
                $t->unsignedBigInteger('rental_panel_id')->nullable()->index();   // null = customer of the main site
            }
        });
        Schema::table('orders', function (Blueprint $t) {
            if (!Schema::hasColumn('orders', 'rental_panel_id')) {
                $t->unsignedBigInteger('rental_panel_id')->nullable()->index();
                $t->decimal('rental_earning', 20, 6)->nullable();                 // reseller profit credited for this order
            }
        });
        // Existing panels get the new "customer accounts" feature switched on.
        foreach (DB::table('rental_panels')->get(['id', 'features']) as $p) {
            $f = json_decode($p->features ?: '[]', true) ?: [];
            if (!in_array('accounts', $f, true)) {
                $f[] = 'accounts';
                DB::table('rental_panels')->where('id', $p->id)->update(['features' => json_encode(array_values($f))]);
            }
        }
        $row = DB::table('rental_settings')->where('id', 1)->first();
        if ($row) {
            $s = json_decode($row->settings, true) ?: [];
            if (!in_array('accounts', $s['features'] ?? [], true)) {
                $s['features'][] = 'accounts';
                DB::table('rental_settings')->where('id', 1)->update(['settings' => json_encode($s)]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', fn(Blueprint $t) => $t->dropColumn('rental_panel_id'));
        Schema::table('orders', fn(Blueprint $t) => $t->dropColumn(['rental_panel_id', 'rental_earning']));
    }
};
