<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_orders', 'payment_expires_at')) {
            Schema::table('product_orders', function (Blueprint $table) {
                $table->timestamp('payment_expires_at')->nullable()->after('stripe_session_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_orders', 'payment_expires_at')) {
            Schema::table('product_orders', function (Blueprint $table) {
                $table->dropColumn('payment_expires_at');
            });
        }
    }
};
