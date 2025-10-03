<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $response = Http::get(config('services.provider_service.host') . config('services.provider_service.availble_endpoint'));
        $providers = $response->ok() ? $response->json('data') : [];

        if (empty($providers)) {
            return response()->json(['message' => 'هیچ سرویس‌دهنده فعالی یافت نشد'], 422);
        }


        $data = $request->validated();
        $lockName = $providers[0]['id'];
        $booking = null;

        $lock = Cache::lock($lockName, 10);

        if ($lock->get()) {
            try {
                DB::beginTransaction();
                $booking = Booking::create($data);
                DB::commit();
                //TODO  fire event for notify provider
            } catch (\Throwable $e) {
                DB::rollBack();
            } finally {
                $lock->release();
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'داداش جون من قفله تلاش نکن ستونم',
                'data' => [],
                'errors' => [],
            ], 423);
        }
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
