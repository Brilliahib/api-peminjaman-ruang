<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Student;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'name' => 'required|string',
            'students.*.name' => 'required|string',
            'students.*.nim' => 'required|string',
            'students.*.tanda_tangan' => 'required|file|mimes:jpg,png,pdf|max:2048', // validasi file
        ]);

        // Buat booking baru
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_approved' => false,
            'name' => $request->name,
        ]);

        // Simpan data mahasiswa beserta tanda tangan
        foreach ($request->students as $studentData) {
            $tandaTanganPath = $studentData['tanda_tangan']->store('tanda_tangan', 'public'); // simpan file

            $booking->students()->create([
                'name' => $studentData['name'],
                'nim' => $studentData['nim'],
                'tanda_tangan' => $tandaTanganPath,
            ]);
        }

        return response()->json(
            [
                'statusCode' => 201,
                'message' => 'Peminjaman berhasil diajukan',
                'data' => $booking->load('students'), // mengembalikan data booking beserta mahasiswa
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
            ->get(['id', 'user_id', 'room_id', 'start_time', 'end_time', 'name', 'is_approved']);

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Daftar peminjaman ruangan',
                'data' => $bookings,
            ],
            200,
        );
    }

    public function getBookingUser()
    {
        $bookings = Booking::with(['room', 'students'])
            ->where('user_id', auth()->id()) // Menyaring berdasarkan user yang sedang login
            ->orderBy('start_time')
            ->get(['id', 'user_id', 'room_id', 'start_time', 'end_time', 'name', 'is_approved', 'status_surat']);

        return response()->json(
            [
                'statusCode' => 200,
                'message' => 'Daftar peminjaman oleh user yang sedang login',
                'data' => $bookings,
            ],
            200,
        );
    }
}
