<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik
        $totalLapangan = Lapangan::count();
        $totalBooking = Booking::count();
        $pendingCount = Booking::where('status', 'pending')->count();
        $bookingConfirmed = Booking::where('status', 'confirmed')->count();

        // Pendapatan
        $totalPendapatan = Booking::where('status', 'confirmed')->sum('total_harga');
        $pendapatanBulanIni = Booking::where('status', 'confirmed')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_harga');

        // Booking terbaru yang perlu verifikasi
        $bookingPending = Booking::with(['lapangan', 'user'])
            ->where('status', 'pending')
            ->where('metode_pembayaran', 'transfer')
            ->whereNotNull('bukti_pembayaran')
            ->latest()
            ->take(5)
            ->get();

        // Booking hari ini
        $bookingHariIni = Booking::with(['lapangan', 'user'])
            ->whereDate('tanggal_booking', today())
            ->latest()
            ->take(10)
            ->get();

        // Lapangan populer
        $lapanganPopuler = Lapangan::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLapangan',
            'totalBooking',
            'pendingCount',
            'bookingConfirmed',
            'totalPendapatan',
            'pendapatanBulanIni',
            'bookingPending',
            'bookingHariIni',
            'lapanganPopuler'
        ));
    }
}
