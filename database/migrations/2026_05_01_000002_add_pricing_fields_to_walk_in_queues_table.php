<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('walk_in_queues', function (Blueprint $table) {
            $table->unsignedTinyInteger('recipient_age')->nullable()->after('customer_phone');
            $table->decimal('price', 8, 2)->default(0)->after('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('walk_in_queues', function (Blueprint $table) {
            $table->dropColumn(['recipient_age', 'price']);
        });
    }
};
