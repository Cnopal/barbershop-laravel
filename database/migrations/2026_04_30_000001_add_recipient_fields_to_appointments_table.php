<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('booking_for')->default('self')->after('customer_id');
            $table->string('recipient_name')->nullable()->after('booking_for');
            $table->unsignedTinyInteger('recipient_age')->nullable()->after('recipient_name');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['booking_for', 'recipient_name', 'recipient_age']);
        });
    }
};
