<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['lapangan.sportCategory']);

        // Filter berdasarkan periode
        $periode = $request->get('periode', 'hari-ini');

        switch($periode) {
            case 'hari-ini':
                $query->whereDate('created_at', today());
                break;
            case 'minggu-ini':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'bulan-ini':
                $query->whereMonth('created_at', date('m'))
                      ->whereYear('created_at', date('Y'));
                break;
            case 'semua':
                // Tidak ada filter
                break;
            default:
                $query->whereDate('created_at', today());
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        // Filter berdasarkan tanggal custom
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        $transaksi = $query->latest()->paginate(20);

        // Statistik berdasarkan periode
        $stats = $this->getStats($periode, $request);

        return view('admin.transaksi.index', compact('transaksi', 'stats', 'periode'));
    }

    private function getStats($periode, $request)
    {
        $query = Booking::query();

        switch($periode) {
            case 'hari-ini':
                $query->whereDate('created_at', today());
                break;
            case 'minggu-ini':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'bulan-ini':
                $query->whereMonth('created_at', date('m'))
                      ->whereYear('created_at', date('Y'));
                break;
        }

        // Custom date range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        return [
            'total_transaksi' => $query->count(),
            'total_pendapatan' => $query->where('status', 'confirmed')->sum('total_harga'),
            'transaksi_pending' => $query->where('status', 'pending')->count(),
            'transaksi_confirmed' => $query->where('status', 'confirmed')->count(),
            'transaksi_cancelled' => $query->where('status', 'cancelled')->count(),
            'pembayaran_transfer' => $query->where('metode_pembayaran', 'transfer')->count(),
            'pembayaran_cash' => $query->where('metode_pembayaran', 'cash')->count(),
        ];
    }
}
