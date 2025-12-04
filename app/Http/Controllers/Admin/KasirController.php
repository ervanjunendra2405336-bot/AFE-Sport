<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['lapangan.sportCategory'])
            ->whereDate('tanggal_booking', now()->toDateString())
            ->where('metode_pembayaran', 'cash');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->tanggal) {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        // Search by booking code
        if ($request->search) {
            $query->where('booking_code', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.kasir.index', compact('bookings'));
    }

    public function create()
    {
        $lapangan = Lapangan::with('sportCategory')
            ->where('tersedia', true)
            ->get();

        return view('admin.kasir.create', compact('lapangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'nama_pemesan' => 'required|string|max:255',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'durasi_jam' => 'required|integer|min:1|max:8',
            'metode_pembayaran' => 'required|in:cash,transfer',
        ]);

        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);

        // Calculate jam_selesai and total_harga
        $jam_mulai = \Carbon\Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $jam_selesai = $jam_mulai->copy()->addHours((int)$validated['durasi_jam']);
        $total_harga = $lapangan->harga_per_jam * (int)$validated['durasi_jam'];

        // Validate jam operasional
        $jam_buka = \Carbon\Carbon::createFromFormat('H:i:s', $lapangan->jam_buka ?? '06:00:00');
        $jam_tutup = \Carbon\Carbon::createFromFormat('H:i:s', $lapangan->jam_tutup ?? '23:00:00');

        if ($jam_mulai->format('H:i') < $jam_buka->format('H:i')) {
            return back()->withErrors([
                'jam_mulai' => "Lapangan baru buka jam {$jam_buka->format('H:i')}."
            ])->withInput();
        }

        if ($jam_selesai->format('H:i') > $jam_tutup->format('H:i')) {
            $maxDurasi = $jam_tutup->diffInHours($jam_mulai);
            return back()->withErrors([
                'durasi_jam' => "Lapangan tutup jam {$jam_tutup->format('H:i')}. Maksimal durasi: {$maxDurasi} jam."
            ])->withInput();
        }

        // Check for overbooking
        $existingBookings = Booking::where('lapangan_id', $validated['lapangan_id'])
            ->where('tanggal_booking', $validated['tanggal_booking'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) use ($jam_mulai, $jam_selesai) {
                $query->where(function($q) use ($jam_mulai, $jam_selesai) {
                    $q->where('jam_mulai', '<', $jam_selesai->format('H:i:s'))
                      ->where('jam_selesai', '>', $jam_mulai->format('H:i:s'));
                });
            })
            ->count();

        if ($existingBookings >= $lapangan->jumlah_lapangan) {
            return back()->withErrors([
                'jam_mulai' => "Semua lapangan penuh pada jam tersebut."
            ])->withInput();
        }

        // Generate booking code
        $bookingCode = 'AFE-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        // Create booking with confirmed status for cash payment
        $booking = Booking::create([
            'lapangan_id' => $validated['lapangan_id'],
            'user_id' => null,
            'booking_code' => $bookingCode,
            'nama_pemesan' => $validated['nama_pemesan'],
            'email' => $request->email ?? 'walk-in@afesport.com',
            'no_hp' => $validated['no_hp'],
            'telepon' => $validated['no_hp'],
            'tanggal_booking' => $validated['tanggal_booking'],
            'jam_mulai' => $jam_mulai->format('H:i:s'),
            'jam_selesai' => $jam_selesai->format('H:i:s'),
            'durasi_jam' => $validated['durasi_jam'],
            'total_harga' => $total_harga,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status' => 'confirmed', // Langsung confirmed untuk kasir
            'catatan' => 'Walk-in booking by admin/kasir',
        ]);

        return redirect()->route('admin.kasir.show', $booking->id)
            ->with('success', 'Booking berhasil dibuat! Booking Code: ' . $bookingCode);
    }

    public function show($id)
    {
        $booking = Booking::with(['lapangan.sportCategory'])->findOrFail($id);
        return view('admin.kasir.show', compact('booking'));
    }

    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
            return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        return back()->with('info', 'Booking sudah dikonfirmasi sebelumnya.');
    }

    public function getAvailableSlots(Request $request)
    {
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $tanggal = $request->tanggal;

        // Get all bookings for this lapangan on the date
        $bookings = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['jam_mulai', 'jam_selesai']);

        $jam_buka = \Carbon\Carbon::parse($lapangan->jam_buka)->format('H:i');
        $jam_tutup = \Carbon\Carbon::parse($lapangan->jam_tutup)->format('H:i');

        return response()->json([
            'bookings' => $bookings,
            'jam_buka' => $jam_buka,
            'jam_tutup' => $jam_tutup,
            'jumlah_lapangan' => $lapangan->jumlah_lapangan
        ]);
    }
}
