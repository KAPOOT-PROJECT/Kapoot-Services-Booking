<?php

use App\Http\Middleware\AuthJwtMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingStatusController;
use App\Http\Controllers\ProviderBookingController;
use App\Http\Controllers\TimeSlotController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Booking API Routes
|--------------------------------------------------------------------------
*/
Route::middleware([AuthJwtMiddleware::class])->group(function () {


    Route::prefix('bookings')->group(function () {
        // List and create bookings
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);

        // Single booking operations
        Route::get('/{booking}', [BookingController::class, 'show']);
        Route::put('/{booking}', [BookingController::class, 'update']);
        Route::delete('/{booking}', [BookingController::class, 'destroy']);

        // Booking actions
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel']);
        Route::post('/{booking}/reschedule', [BookingController::class, 'reschedule']);

        // Booking media
        Route::post('/{booking}/photos-before', [BookingController::class, 'uploadPhotosBefore']);
        Route::post('/{booking}/photos-after', [BookingController::class, 'uploadPhotosAfter']);

        // Booking history
        Route::get('/{booking}/status-history', [BookingController::class, 'statusHistory']);
    });

    // Provider-specific Booking Routes
    Route::prefix('provider/bookings')->group(function () {
        // Provider's bookings list
        Route::get('/', [ProviderBookingController::class, 'index']);
        Route::get('/{booking}', [ProviderBookingController::class, 'show']);

        // Booking acceptance and assignment
        Route::post('/{booking}/accept', [ProviderBookingController::class, 'accept']);
        Route::post('/{booking}/reject', [ProviderBookingController::class, 'reject']);
        Route::post('/{booking}/assign', [ProviderBookingController::class, 'assignStaff']);

        // Service execution
        Route::post('/{booking}/start', [ProviderBookingController::class, 'start']);
        Route::post('/{booking}/complete', [ProviderBookingController::class, 'complete']);
        Route::post('/{booking}/mark-no-show', [ProviderBookingController::class, 'markNoShow']);

        // Provider notes and documentation
        Route::post('/{booking}/notes', [ProviderBookingController::class, 'addNotes']);
        Route::post('/{booking}/photos-after', [ProviderBookingController::class, 'uploadPhotosAfter']);

        // Update pricing
        Route::put('/{booking}/pricing', [ProviderBookingController::class, 'updatePricing']);
    });

//     // Booking Status Routes (for tracking)
//     Route::prefix('bookings/{booking}')->group(function () {
//         Route::post('/status', [BookingStatusController::class, 'updateStatus']);
//         Route::get('/status/history', [BookingStatusController::class, 'history']);
//     });

//     // Time Slot Management Routes
//     Route::prefix('time-slots')->group(function () {
//         // Check availability
//         Route::get('/available', [TimeSlotController::class, 'available']);
//         Route::post('/check', [TimeSlotController::class, 'checkAvailability']);

//         // Provider time slot management
//         Route::get('/provider/{providerId}', [TimeSlotController::class, 'providerSlots']);
//         Route::post('/provider/{providerId}', [TimeSlotController::class, 'createSlots']);
//         Route::delete('/{timeSlot}', [TimeSlotController::class, 'destroy']);
//     });

//     // Statistics and Reports
//     Route::prefix('bookings/statistics')->group(function () {
//         Route::get('/customer', [BookingController::class, 'customerStatistics']);
//         Route::get('/provider', [ProviderBookingController::class, 'providerStatistics']);
//     });
// });

// // Admin Routes
// Route::middleware(['auth:api', 'role:admin|super_admin'])->group(function () {
//     Route::prefix('admin/bookings')->group(function () {
//         Route::get('/', [BookingController::class, 'adminIndex']);
//         Route::get('/{booking}', [BookingController::class, 'adminShow']);
//         Route::post('/{booking}/override-status', [BookingController::class, 'overrideStatus']);
//         Route::get('/statistics/overview', [BookingController::class, 'adminStatistics']);
//     });
});
