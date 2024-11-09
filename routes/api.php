<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('/auth/get-auth', [AuthController::class, 'getAuth']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::get('/booking/user', [BookingController::class, 'getBookingUser']);

    Route::middleware('role:admin')->group(function(){
        Route::prefix('booking')->group(function(){
            Route::post('/approve/{id}', [AdminController::class, 'approveBookings']);
            Route::get('/', [AdminController::class, 'getAllBookings']);
            Route::patch('/status/{id}', [AdminController::class, 'updateStatusSurat']);
        });

        Route::prefix('room')->group(function(){
            Route::post('/', [AdminController::class, 'createRoom']);
        });
    });
});

// Not have a token
Route::get('/room', [AdminController::class, 'getAllRooms']);
Route::get('/booking/room/{room_id}', [BookingController::class, 'getRoomBookings']);
Route::get('/room/{id}', [AdminController::class, 'detailRoom']);
