<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|uuid',
            'vehicle_id' => 'required|uuid',
            'provider_id' => 'required|integer',
            'assigned_staff_id' => 'nullable|integer',
            'service_ids' => 'required|array',
            'type' => 'required|in:scheduled,immediate,emergency',
            'service_location' => 'required|in:on_site,mobile',
            'customer_location' => 'nullable|array',
            'scheduled_at' => 'nullable|date',
            'status' => 'in:pending,confirmed,assigned,in_progress,completed,cancelled,no_show',
            'estimated_duration' => 'nullable|integer',
            'actual_duration' => 'nullable|integer',
            'estimated_price' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'payment_status' => 'in:pending,paid,failed,refunded,partial',
            'payment_method' => 'nullable|string',
            'payment_transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
            'provider_notes' => 'nullable|string',
            'photos_before' => 'nullable|array',
            'photos_after' => 'nullable|array',
            'cancelled_by' => 'nullable|string',
            'cancelled_reason' => 'nullable|string',
            'cancelled_at' => 'nullable|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ];
    }
}
