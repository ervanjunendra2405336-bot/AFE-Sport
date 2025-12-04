<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Verifikasi Pembayaran Transfer
    public function verifikasi(Request $request)
    {
        $query = Booking::with(['lapangan.sportCategory', 'user']);

        // Filter berdasarkan status pembayaran
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan pending dan confirmed
            $query->whereIn('status', ['pending', 'confirmed']);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->has('metode') && $request->metode != '') {
            $query->where('metode_pembayaran', $request->metode);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        // Search booking code atau nama
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.booking.verifikasi', compact('bookings'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
            return back()->with('success', 'Booking berhasil dikonfirmasi.');
        }

        return back()->with('error', 'Booking tidak dapat dikonfirmasi.');
    }

    public function reject(Request $request, Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update([
                'status' => 'cancelled',
                'catatan' => $request->catatan ?? 'Pembayaran ditolak oleh admin.'
            ]);
            return back()->with('success', 'Booking berhasil ditolak.');
        }

        return back()->with('error', 'Booking tidak dapat ditolak.');
    }

    // Sistem Kasir untuk Pembayaran Cash
    public function kasir(Request $request)
    {
        $query = Booking::with(['lapangan.sportCategory', 'user'])
            ->where('metode_pembayaran', 'cash')
            ->whereDate('tanggal_booking', '>=', today());

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan pending
            $query->where('status', 'pending');
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal_booking', $request->tanggal);
        } else {
            // Default: hari ini
            $query->whereDate('tanggal_booking', today());
        }

        // Search booking code
        if ($request->has('search') && $request->search != '') {
            $query->where('booking_code', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->orderBy('jam_mulai')->paginate(20);

        return view('admin.booking.kasir', compact('bookings'));
    }

    public function confirmPayment(Booking $booking)
    {
        if ($booking->metode_pembayaran === 'cash' && $booking->status === 'pending') {
            $booking->update([
                'status' => 'confirmed',
                'catatan' => 'Pembayaran cash diterima oleh kasir pada ' . now()->format('d/m/Y H:i')
            ]);

            return back()->with('success', 'Pembayaran berhasil dikonfirmasi. Booking code: ' . $booking->booking_code);
        }

        return back()->with('error', 'Pembayaran tidak dapat dikonfirmasi.');
    }

    // Scan booking untuk kasir
    public function scanBooking(Request $request)
    {
        $bookingCode = strtoupper($request->booking_code);

        $booking = Booking::with(['lapangan.sportCategory', 'user'])
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.'
            ]);
        }

        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->nama_pemesan,
                'customer_phone' => $booking->no_hp,
                'lapangan' => $booking->lapangan->nama,
                'category' => $booking->lapangan->sportCategory->nama,
                'tanggal' => Carbon::parse($booking->tanggal_booking)->format('d M Y'),
                'jam' => $booking->jam_mulai . ' - ' . $booking->jam_selesai,
                'total_harga' => number_format($booking->total_harga, 0, ',', '.'),
                'status' => $booking->status,
                'metode_pembayaran' => $booking->metode_pembayaran,
            ]
        ]);
    }

    // Lihat semua booking
    public function index(Request $request)
    {
        $query = Booking::with(['lapangan.sportCategory', 'user']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', '%' . $search . '%')
                  ->orWhere('nama_pemesan', 'like', '%' . $search . '%');
            });
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.booking.index', compact('bookings'));
    }

    // Cancel booking
    public function cancel(Booking $booking)
    {
        try {
            $booking->update(['status' => 'cancelled']);

            return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }
}
