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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('ticket', 255)->nullable();
            $table->integer('subject', 255)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 =>  Open, 1 => Answered, 2 => Replied, 3 => Closed');
            $table->dateTime('last_reply')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
