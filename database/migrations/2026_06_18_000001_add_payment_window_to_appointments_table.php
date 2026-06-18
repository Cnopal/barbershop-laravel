<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('appointments', 'payment_expires_at')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->timestamp('payment_expires_at')->nullable()->after('stripe_session_id');
            });
        }

        if (!Schema::hasColumn('appointments', 'paid_at')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->timestamp('paid_at')->nullable()->after('payment_expires_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'paid_at')) {
                $table->dropColumn('paid_at');
            }

            if (Schema::hasColumn('appointments', 'payment_expires_at')) {
                $table->dropColumn('payment_expires_at');
            }
        });
    }
};
