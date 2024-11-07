<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function approveBookings($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->is_approved) {
            return response()->json(
                [
                    'statusCode' => 400,
                    'message' => 'Peminjaman sudah disetujui',
                    'data' => null,
                ],
                400,
            );
        }

        $booking->update(['is_approved' => true]);

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Peminjaman berhasil disetujui',
                'data' => $booking,
            ],
            200,
        );
    }

    public function createRoom(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|unique:rooms,nama_ruangan',
            'kapasitas_ruangan' => 'nullable|string',
        ]);

        $room = Room::create([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas_ruangan' => $request->kapasitas_ruangan,
        ]);

        return response()->json(
            [
                'statusCode' => 201,
                'message' => 'Ruangan berhasil dibuat',
                'data' => $room,
            ],
            201,
        );
    }

    public function getAllBookings()
    {
        $bookings = Booking::with(['user', 'room'])
            ->get(['user_id', 'room_id', 'start_time', 'end_time', 'name', 'is_approved']);

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Data booking berhasil diambil',
                'data' => $bookings,
            ],
            200,
        );
    }

    public function getAllRooms()
    {
        $rooms = Room::all();

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Data booking berhasil diambil',
                'data' => $rooms,
            ],
            200,
        );
    }
}
