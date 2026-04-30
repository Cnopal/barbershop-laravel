<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('order_type', ['online', 'pos'])->default('online');
            $table->enum('payment_method', ['stripe', 'cash'])->default('stripe');
            $table->enum('payment_status', ['pending_payment', 'paid', 'cancelled'])->default('pending_payment');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('stripe_session_id')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['order_type', 'payment_status']);
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
