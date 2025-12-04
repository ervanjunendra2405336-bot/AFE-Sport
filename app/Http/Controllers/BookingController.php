<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create($lapangan_id)
    {
        $lapangan = Lapangan::findOrFail($lapangan_id);
        return view('booking.create', compact('lapangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'nama_pemesan' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|numeric|digits_between:10,15',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'durasi_jam' => 'required|integer|min:1|max:8',
            'catatan' => 'nullable|string',
        ]);

        // Check if slot is available
        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);

        // Calculate jam_selesai and total_harga
        $jam_mulai = \Carbon\Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $jam_selesai = $jam_mulai->copy()->addHours((int)$validated['durasi_jam']);
        $total_harga = $lapangan->harga_per_jam * (int)$validated['durasi_jam'];

        // Validate jam operasional - check if booking exceeds closing time
        $jam_buka = \Carbon\Carbon::createFromFormat('H:i:s', $lapangan->jam_buka ?? '06:00:00');
        $jam_tutup = \Carbon\Carbon::createFromFormat('H:i:s', $lapangan->jam_tutup ?? '23:00:00');

        if ($jam_mulai->format('H:i') < $jam_buka->format('H:i')) {
            return back()->withErrors([
                'jam_mulai' => "Lapangan baru buka jam {$jam_buka->format('H:i')}. Silakan pilih waktu setelah jam buka."
            ])->withInput();
        }

        if ($jam_selesai->format('H:i') > $jam_tutup->format('H:i')) {
            $maxDurasi = $jam_tutup->diffInHours($jam_mulai);
            return back()->withErrors([
                'durasi_jam' => "Lapangan tutup jam {$jam_tutup->format('H:i')}. Dari jam {$jam_mulai->format('H:i')}, maksimal durasi adalah {$maxDurasi} jam."
            ])->withInput();
        }

        // Check for overbooking - count existing bookings at the same time
        $existingBookings = Booking::where('lapangan_id', $validated['lapangan_id'])
            ->where('tanggal_booking', $validated['tanggal_booking'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) use ($jam_mulai, $jam_selesai) {
                // Check for time overlap
                $query->where(function($q) use ($jam_mulai, $jam_selesai) {
                    $q->where('jam_mulai', '<', $jam_selesai->format('H:i:s'))
                      ->where('jam_selesai', '>', $jam_mulai->format('H:i:s'));
                });
            })
            ->count();

        // If existing bookings >= jumlah lapangan, it's full
        if ($existingBookings >= $lapangan->jumlah_lapangan) {
            return back()->withErrors([
                'jam_mulai' => "Maaf, semua lapangan ({$lapangan->jumlah_lapangan} lapangan) sudah penuh pada jam {$jam_mulai->format('H:i')} - {$jam_selesai->format('H:i')}. Silakan pilih waktu lain."
            ])->withInput();
        }

        // Generate unique booking code
        $bookingCode = 'AFE-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $booking = Booking::create([
            'lapangan_id' => $validated['lapangan_id'],
            'user_id' => null, // Customer tidak perlu login
            'booking_code' => $bookingCode,
            'nama_pemesan' => $validated['nama_pemesan'],
            'email' => $validated['email'],
            'no_hp' => $validated['telepon'],
            'telepon' => $validated['telepon'],
            'tanggal_booking' => $validated['tanggal_booking'],
            'jam_mulai' => $jam_mulai->format('H:i:s'),
            'jam_selesai' => $jam_selesai->format('H:i:s'),
            'durasi_jam' => $validated['durasi_jam'],
            'total_harga' => $total_harga,
            'catatan' => $validated['catatan'],
            'status' => 'pending',
        ]);

        return redirect()->route('booking.payment', $booking->id);
    }

    public function payment($id)
    {
        $booking = Booking::with('lapangan')->findOrFail($id);

        if ($booking->status != 'pending') {
            return redirect()->route('booking.success', $booking->id);
        }

        return view('booking.payment', compact('booking'));
    }

    public function processPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:transfer,cash',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->metode_pembayaran = $validated['metode_pembayaran'];

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'payment_' . $booking->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);
            $booking->bukti_pembayaran = 'uploads/payments/' . $filename;
        }

        $booking->save();

        return redirect()->route('booking.success', $booking->id)->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function success($id)
    {
        $booking = Booking::with('lapangan')->findOrFail($id);
        return view('booking.success', compact('booking'));
    }

    public function checkAvailability(Request $request)
    {
        try {
            \Log::info('Check availability request', $request->all());

            $lapangan = Lapangan::findOrFail($request->lapangan_id);
            $tanggal = $request->tanggal_booking;
            $jamMulai = $request->jam_mulai;
            $durasi = (int)$request->durasi_jam;

            if (!$tanggal || !$jamMulai || !$durasi) {
                \Log::warning('Incomplete data', compact('tanggal', 'jamMulai', 'durasi'));
                return response()->json(['error' => 'Data tidak lengkap'], 400);
            }

            $jamMulaiCarbon = \Carbon\Carbon::createFromFormat('H:i', $jamMulai);
            $jamSelesaiCarbon = $jamMulaiCarbon->copy()->addHours($durasi);

        // Validate jam operasional
        $jam_buka = \Carbon\Carbon::createFromFormat('H:i:s', $lapangan->jam_buka ?? '06:00:00');
        $jam_tutup = \Carbon\Carbon::createFromFormat('H:i:s', $lapangan->jam_tutup ?? '23:00:00');

        // Check if before opening time
        if ($jamMulaiCarbon->format('H:i') < $jam_buka->format('H:i')) {
            return response()->json([
                'available' => false,
                'slot_tersedia' => 0,
                'total_lapangan' => $lapangan->jumlah_lapangan,
                'message' => "❌ Lapangan baru buka jam {$jam_buka->format('H:i')}"
            ]);
        }

        // Check if exceeds closing time
        if ($jamSelesaiCarbon->format('H:i') > $jam_tutup->format('H:i')) {
            $maxDurasi = $jam_tutup->diffInHours($jamMulaiCarbon);
            return response()->json([
                'available' => false,
                'slot_tersedia' => 0,
                'total_lapangan' => $lapangan->jumlah_lapangan,
                'message' => "❌ Lapangan tutup jam {$jam_tutup->format('H:i')}. Maksimal durasi: {$maxDurasi} jam"
            ]);
        }

        // Count existing confirmed/pending bookings at the same time
        $existingBookings = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) use ($jamMulaiCarbon, $jamSelesaiCarbon) {
                $query->where(function($q) use ($jamMulaiCarbon, $jamSelesaiCarbon) {
                    $q->where('jam_mulai', '<', $jamSelesaiCarbon->format('H:i:s'))
                      ->where('jam_selesai', '>', $jamMulaiCarbon->format('H:i:s'));
                });
            })
            ->count();

        $slotTersedia = $lapangan->jumlah_lapangan - $existingBookings;
        $isAvailable = $slotTersedia > 0;

        return response()->json([
            'available' => $isAvailable,
            'slot_tersedia' => $slotTersedia,
            'total_lapangan' => $lapangan->jumlah_lapangan,
            'existing_bookings' => $existingBookings,
            'message' => $isAvailable
                ? "✅ Tersedia {$slotTersedia} dari {$lapangan->jumlah_lapangan} lapangan"
                : "❌ Semua lapangan penuh pada waktu ini"
        ]);
        } catch (\Exception $e) {
            \Log::error('Check availability error: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'slot_tersedia' => 0,
                'total_lapangan' => 0,
                'message' => '❌ Gagal mengecek ketersediaan: ' . $e->getMessage()
            ], 500);
        }
    }
}
