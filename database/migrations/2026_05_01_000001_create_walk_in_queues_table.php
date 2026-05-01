<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('walk_in_queues', function (Blueprint $table) {
            $table->id();
            $table->date('queue_date')->index();
            $table->unsignedInteger('queue_number');
            $table->string('queue_code')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 30)->nullable();
            $table->foreignId('barber_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->unsignedSmallInteger('estimated_wait_minutes')->default(0);
            $table->enum('status', ['waiting', 'serving', 'completed', 'skipped'])->default('waiting')->index();
            $table->text('notes')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('skipped_at')->nullable();
            $table->timestamps();

            $table->unique(['queue_date', 'queue_number']);
            $table->index(['queue_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walk_in_queues');
    }
};
