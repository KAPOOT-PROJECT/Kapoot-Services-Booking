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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Core relationships
            $table->uuid('customer_id')->index();
            $table->uuid('vehicle_id')->index();
            $table->unsignedBigInteger('provider_id')->index();
            $table->unsignedBigInteger('assigned_staff_id')->nullable()->index();

            // Service details
            $table->json('service_ids');

            // Booking type and location
            $table->enum('type', ['scheduled', 'immediate', 'emergency'])
                  ->default('scheduled')
                  ->index();
            $table->enum('service_location', ['on_site', 'mobile'])
                  ->default('on_site');

            // Location data (for mobile services)
            $table->json('customer_location')->nullable()->comment('address, lat, lng, notes');

            // Scheduling
            $table->timestamp('scheduled_at')->nullable()->index();

            // Status tracking
            $table->enum('status', [
                'pending',
                'confirmed',
                'assigned',
                'in_progress',
                'completed',
                'cancelled',
                'no_show'
            ])->default('pending')->index();

            // Duration tracking (in minutes)
            $table->integer('estimated_duration')->nullable();
            $table->integer('actual_duration')->nullable();

            // Pricing
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            // Payment tracking
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'partial'
            ])->default('pending')->index();
            $table->string('payment_method')->nullable();
            $table->string('payment_transaction_id')->nullable()->index();

            // Notes and documentation
            $table->text('notes')->nullable()->comment('Customer instructions');
            $table->text('provider_notes')->nullable()->comment('Service provider notes');
            $table->json('photos_before')->nullable();
            $table->json('photos_after')->nullable();

            // Cancellation tracking
            $table->string('cancelled_by')->nullable();
            $table->string('cancelled_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Service execution timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Standard timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index(['customer_id', 'status']);
            $table->index(['provider_id', 'status']);
            $table->index(['scheduled_at', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
