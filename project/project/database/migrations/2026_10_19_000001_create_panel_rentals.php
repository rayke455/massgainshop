<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rental_settings', function (Blueprint $t) { $t->id(); $t->json('settings'); $t->timestamps(); });
        Schema::create('rental_panels', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('owner_id')->index(); $t->string('slug', 40)->unique();
            $t->string('name', 100); $t->string('subdomain')->nullable()->unique(); $t->string('custom_domain')->nullable()->unique();
            $t->string('domain_token', 64); $t->timestamp('domain_verified_at')->nullable();
            $t->string('status', 20)->default('active'); $t->json('features'); $t->decimal('markup', 8, 2)->default(20);
            $t->timestamp('expires_at'); $t->timestamps();
        });
        Schema::create('rental_requests', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('panel_id')->index(); $t->uuid('token')->unique();
            $t->string('name', 100); $t->string('email'); $t->unsignedBigInteger('service_id');
            $t->unsignedInteger('quantity'); $t->text('link'); $t->decimal('quote', 20, 6); $t->string('currency', 8);
            $t->unsignedBigInteger('provider_order_id')->nullable()->unique(); $t->string('status', 20)->default('requested'); $t->timestamps();
        });
        Schema::create('rental_audit', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('panel_id')->nullable()->index(); $t->string('actor'); $t->string('action'); $t->json('details')->nullable(); $t->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('rental_audit'); Schema::dropIfExists('rental_requests'); Schema::dropIfExists('rental_panels'); Schema::dropIfExists('rental_settings'); }
};
