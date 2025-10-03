<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Booking::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        // 1. دریافت لیست providerهای فعال از سرویس provider-service
        $response = Http::get(env('PROVIDER_SERVICE_URL', 'http://127.0.0.1:9000/api/availble-providers'));
        $providers = $response->ok() ? $response->json('data') : [];

        if (empty($providers)) {
            return response()->json(['message' => 'هیچ سرویس‌دهنده فعالی یافت نشد'], 422);
        }



        $data = $request->validated();

        $booking = Booking::create($data);

        return response()->json($booking, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
