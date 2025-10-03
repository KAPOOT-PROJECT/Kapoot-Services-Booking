<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'provider_id',
        'assigned_staff_id',
        'service_ids',
        'type',
        'service_location',
        'customer_location',
        'scheduled_at',
        'status',
        'estimated_duration',
        'actual_duration',
        'estimated_price',
        'total_price',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
        'notes',
        'provider_notes',
        'photos_before',
        'photos_after',
        'cancelled_by',
        'cancelled_reason',
        'cancelled_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'service_ids' => 'array',
        'customer_location' => 'array',
        'photos_before' => 'array',
        'photos_after' => 'array',
        'scheduled_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_duration' => 'integer',
        'actual_duration' => 'integer',
        'estimated_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    // Type constants
    const TYPE_SCHEDULED = 'scheduled';
    const TYPE_IMMEDIATE = 'immediate';
    const TYPE_EMERGENCY = 'emergency';

    // Service location constants
    const LOCATION_ON_SITE = 'on_site';
    const LOCATION_MOBILE = 'mobile';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';
    const PAYMENT_PARTIAL = 'partial';

    /**
     * Get valid status values
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_ASSIGNED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_NO_SHOW,
        ];
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_ASSIGNED,
        ]);
    }

    /**
     * Check if booking can be started
     */
    public function canBeStarted(): bool
    {
        return in_array($this->status, [
            self::STATUS_CONFIRMED,
            self::STATUS_ASSIGNED,
        ]);
    }

    /**
     * Check if booking can be completed
     */
    public function canBeCompleted(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if booking is active
     */
    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_ASSIGNED,
            self::STATUS_IN_PROGRESS,
        ]);
    }

    /**
     * Relationships
     */
    public function statusHistories()
    {
        return $this->hasMany(BookingStatusHistory::class);
    }

    public function timeSlot()
    {
        return $this->hasOne(BookingTimeSlot::class);
    }

    /**
     * Scopes
     */
    public function scopeForCustomer($query, string $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeForProvider($query, string $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_ASSIGNED,
            self::STATUS_IN_PROGRESS,
        ]);
    }

    public function scopeScheduledBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('scheduled_at', [$startDate, $endDate]);
    }
}
