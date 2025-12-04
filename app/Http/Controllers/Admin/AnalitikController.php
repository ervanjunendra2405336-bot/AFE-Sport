<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SportCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalitikController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', '30'); // Default 30 hari

        // Grafik pendapatan per hari
        $pendapatanHarian = $this->getPendapatanHarian($periode);

        // Lapangan terpopuler
        $lapanganTerpopuler = $this->getLapanganTerpopuler($periode);

        // Kategori terpopuler
        $kategoriTerpopuler = $this->getKategoriTerpopuler($periode);

        // Jam tersibuk
        $jamTersibuk = $this->getJamTersibuk($periode);

        // Metode pembayaran
        $metodePembayaran = $this->getMetodePembayaran($periode);

        // Total statistik
        $totalStats = $this->getTotalStats($periode);

        return view('admin.analitik.index', compact(
            'pendapatanHarian',
            'lapanganTerpopuler',
            'kategoriTerpopuler',
            'jamTersibuk',
            'metodePembayaran',
            'totalStats',
            'periode'
        ));
    }

    private function getPendapatanHarian($periode)
    {
        $startDate = Carbon::now()->subDays($periode);

        return Booking::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    private function getLapanganTerpopuler($periode)
    {
        $startDate = Carbon::now()->subDays($periode);

        return Booking::with('lapangan')
            ->where('created_at', '>=', $startDate)
            ->select('lapangan_id',
                DB::raw('COUNT(*) as total_booking'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('lapangan_id')
            ->orderBy('total_booking', 'desc')
            ->limit(10)
            ->get();
    }

    private function getKategoriTerpopuler($periode)
    {
        $startDate = Carbon::now()->subDays($periode);

        return Booking::join('lapangan', 'bookings.lapangan_id', '=', 'lapangan.id')
            ->join('sport_categories', 'lapangan.sport_category_id', '=', 'sport_categories.id')
            ->where('bookings.created_at', '>=', $startDate)
            ->select(
                'sport_categories.nama',
                DB::raw('COUNT(*) as total_booking'),
                DB::raw('SUM(bookings.total_harga) as total_pendapatan')
            )
            ->groupBy('sport_categories.id', 'sport_categories.nama')
            ->orderBy('total_booking', 'desc')
            ->get();
    }

    private function getJamTersibuk($periode)
    {
        $startDate = Carbon::now()->subDays($periode);

        return Booking::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('CAST(strftime("%H", jam_mulai) AS INTEGER) as jam'),
                DB::raw('COUNT(*) as total_booking')
            )
            ->groupBy('jam')
            ->orderBy('total_booking', 'desc')
            ->get();
    }

    private function getMetodePembayaran($periode)
    {
        $startDate = Carbon::now()->subDays($periode);

        return Booking::where('created_at', '>=', $startDate)
            ->select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy('metode_pembayaran')
            ->get();
    }

    private function getTotalStats($periode)
    {
        $startDate = Carbon::now()->subDays($periode);
        $query = Booking::where('created_at', '>=', $startDate);

        return [
            'total_booking' => $query->count(),
            'total_pendapatan' => $query->where('status', 'confirmed')->sum('total_harga'),
            'rata_rata_transaksi' => $query->where('status', 'confirmed')->avg('total_harga'),
            'customer_unik' => $query->distinct('email')->count('email'),
        ];
    }
}
