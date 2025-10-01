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
        Schema::create('booking_time_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable()->index();
            $table->unsignedBigInteger('provider_id')->index();

            $table->date('date')->index();
            $table->time('start_time');
            $table->time('end_time');

            $table->boolean('is_available')->default(true)->index();

            $table->timestamps();

            // Foreign key
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('set null');

            // Composite indexes for slot availability queries
            $table->index(['provider_id', 'date', 'is_available']);
            $table->unique(['provider_id', 'date', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_time_slots');
    }
};
