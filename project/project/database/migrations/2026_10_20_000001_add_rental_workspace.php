<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rental_panels', function (Blueprint $table) {
            $table->json('workspace_settings')->nullable();
        });
        Schema::create('rental_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panel_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('subject', 160);
            $table->string('status', 20)->default('open');
            $table->timestamps();
        });
        Schema::create('rental_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id')->index();
            $table->unsignedBigInteger('author_id');
            $table->string('author_role', 20);
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_ticket_messages');
        Schema::dropIfExists('rental_tickets');
        Schema::table('rental_panels', fn (Blueprint $table) => $table->dropColumn('workspace_settings'));
    }
};
