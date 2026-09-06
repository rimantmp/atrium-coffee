<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('sku', 40)->unique();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->unsignedSmallInteger('estimated_prep_minutes')->default(5);
            $table->string('image_path', 500)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'is_active', 'is_available']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->string('tracking_token', 64)->unique();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name', 100);
            $table->string('customer_phone', 25)->nullable();
            $table->string('table_number', 30)->nullable();
            $table->string('order_channel', 20)->default('QR');
            $table->string('order_type', 20)->default('DINE_IN');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->string('status', 30)->default('PENDING_PAYMENT');
            $table->string('payment_status', 30)->default('UNPAID');
            $table->unsignedTinyInteger('base_priority')->default(3);
            $table->unsignedSmallInteger('estimated_processing_minutes')->default(5);
            $table->string('notes', 500)->nullable();
            $table->timestamp('ordered_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason', 500)->nullable();
            $table->timestamps();
            $table->index(['status', 'ordered_at']);
            $table->index(['payment_status', 'ordered_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->nullable()->constrained()->nullOnDelete();
            $table->string('menu_name_snapshot', 120);
            $table->decimal('unit_price', 15, 2);
            $table->unsignedSmallInteger('quantity');
            $table->decimal('subtotal', 15, 2);
            $table->unsignedSmallInteger('estimated_prep_minutes');
            $table->string('notes', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->string('payment_number', 40)->unique();
            $table->string('method', 30);
            $table->decimal('amount', 15, 2);
            $table->string('status', 30)->default('PENDING');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['order_id', 'status']);
        });

        Schema::create('daily_counters', function (Blueprint $table) {
            $table->date('counter_date');
            $table->string('counter_type', 20);
            $table->unsignedInteger('last_value')->default(0);
            $table->timestamp('updated_at')->nullable();
            $table->primary(['counter_date', 'counter_type']);
        });

        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->restrictOnDelete();
            $table->date('queue_date');
            $table->unsignedInteger('sequence_number');
            $table->string('queue_number', 20);
            $table->unsignedTinyInteger('base_priority')->default(3);
            $table->string('status', 20)->default('WAITING');
            $table->timestamp('queued_at');
            $table->foreignId('claimed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedSmallInteger('estimated_wait_minutes')->nullable();
            $table->timestamps();
            $table->unique(['queue_date', 'sequence_number']);
            $table->unique(['queue_date', 'queue_number']);
            $table->index(['status', 'base_priority', 'queued_at', 'id']);
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason', 500)->nullable();
            $table->timestamp('created_at');
            $table->index(['order_id', 'created_at']);
        });

        Schema::create('queue_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained()->restrictOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason', 500)->nullable();
            $table->timestamp('created_at');
        });

        Schema::create('priority_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('queue_id')->nullable()->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('old_priority');
            $table->unsignedTinyInteger('new_priority');
            $table->string('reason', 500);
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at');
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('value');
            $table->string('description')->nullable();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 80);
            $table->string('entity_type', 100);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('reason', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at');
            $table->index(['entity_type', 'entity_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('priority_change_logs');
        Schema::dropIfExists('queue_histories');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('queues');
        Schema::dropIfExists('daily_counters');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('categories');
    }
};
