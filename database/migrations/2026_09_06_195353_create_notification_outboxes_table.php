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
        Schema::create('notification_outboxes', function (Blueprint $table) {
            $table->id();
            $table->string('event_key', 160)->unique();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('event_type', 80);
            $table->string('channel', 30)->default('WEB');
            $table->json('payload');
            $table->string('status', 20)->default('PENDING');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_outboxes');
    }
};
