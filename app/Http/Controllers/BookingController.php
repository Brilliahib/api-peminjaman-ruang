<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'name' => 'required|string'
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_approved' => false,
            'name' => $request->name
        ]);

        return response()->json(
            [
                'statusCode' => 201,
                'message' => 'Peminjaman berhasil diajukan',
                'data' => $booking,
            ],
            201,
        );
    }

    public function getRoomBookings($room_id)
    {
        $bookings = Booking::with(['user', 'room']) 
            ->where('room_id', $room_id)
            ->where('is_approved', true)
            ->orderBy('start_time')
            ->get(['user_id', 'room_id', 'start_time', 'end_time', 'name', 'is_approved']);

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Daftar peminjaman ruangan',
                'data' => $bookings,
            ],
            200,
        );
    }
}
