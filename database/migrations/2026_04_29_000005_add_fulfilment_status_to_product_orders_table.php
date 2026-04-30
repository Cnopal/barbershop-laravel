<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->enum('order_status', [
                'pending',
                'processing',
                'ready_for_pickup',
                'received',
                'needs_review',
                'cancelled',
            ])->default('pending')->after('payment_status');
            $table->timestamp('stock_reduced_at')->nullable()->after('paid_at');
            $table->timestamp('received_at')->nullable()->after('stock_reduced_at');

            $table->index(['order_type', 'order_status']);
        });

        DB::table('product_orders')
            ->where('payment_status', 'cancelled')
            ->update(['order_status' => 'cancelled']);

        DB::table('product_orders')
            ->where('payment_status', 'paid')
            ->where('order_type', 'online')
            ->update([
                'order_status' => 'processing',
                'stock_reduced_at' => DB::raw('paid_at'),
            ]);

        DB::table('product_orders')
            ->where('payment_status', 'paid')
            ->where('order_type', 'pos')
            ->update([
                'order_status' => 'received',
                'stock_reduced_at' => DB::raw('paid_at'),
                'received_at' => DB::raw('paid_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropIndex(['order_type', 'order_status']);
            $table->dropColumn(['order_status', 'stock_reduced_at', 'received_at']);
        });
    }
};
