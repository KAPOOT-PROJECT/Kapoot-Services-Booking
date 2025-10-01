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
        Schema::create('booking_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->index();

            $table->string('from_status')->nullable();
            $table->string('to_status');

            // Who made the change
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('changed_by_role')->nullable(); // customer, provider, admin, system

            // Why the change was made
            $table->text('reason')->nullable();

            // Additional context
            $table->json('metadata')->nullable();

            $table->timestamp('created_at');

            // Foreign key
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('cascade');

            // Indexes
            $table->index(['booking_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_histories');
    }
};
