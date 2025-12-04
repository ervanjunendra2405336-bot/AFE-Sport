@extends('admin.layout')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>🧾 Detail Booking</h2>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn" style="background: #3498db; color: white;">
                🖨️ Cetak
            </button>
            <a href="{{ route('admin.kasir.index') }}" class="btn" style="background: #95a5a6; color: white;">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="card-body" id="receiptContent">
        <!-- Header Receipt -->
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ asset('images/logo.png') }}" alt="AFE SPORT" style="height: 80px; margin-bottom: 10px;">
            <h1 style="color: #e67e22; margin: 0; font-size: 2.5rem;">AFE SPORT</h1>
            <p style="color: #7f8c8d; margin: 5px 0;">Jl Bayam 2 nomor 3, Malang</p>
            <p style="color: #7f8c8d; margin: 5px 0;">Telp: 081252466876</p>
            <div style="height: 2px; background: linear-gradient(to right, #e67e22, #d35400); margin: 20px auto; width: 80%;"></div>
        </div>

        <!-- Booking Code -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); color: white; padding: 20px; border-radius: 12px; display: inline-block;">
                <small style="opacity: 0.9;">Kode Booking</small>
                <h1 style="margin: 5px 0; font-size: 2rem; letter-spacing: 2px;">{{ $booking->booking_code }}</h1>
            </div>
        </div>

        <!-- Status Badge -->
        <div style="text-align: center; margin-bottom: 30px;">
            @if($booking->status == 'confirmed')
            <span class="badge" style="background: #27ae60; font-size: 1.2rem; padding: 10px 30px;">
                ✅ CONFIRMED
            </span>
            @elseif($booking->status == 'pending')
            <span class="badge" style="background: #f39c12; font-size: 1.2rem; padding: 10px 30px;">
                ⏳ PENDING
            </span>
            @elseif($booking->status == 'completed')
            <span class="badge" style="background: #9b59b6; font-size: 1.2rem; padding: 10px 30px;">
                ✓ COMPLETED
            </span>
            @else
            <span class="badge" style="background: #e74c3c; font-size: 1.2rem; padding: 10px 30px;">
                ❌ CANCELLED
            </span>
            @endif
        </div>

        <!-- Customer Info -->
        <div style="background: #ecf0f1; padding: 25px; border-radius: 10px; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                👤 Data Customer
            </h3>
            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 12px;">
                <div style="color: #7f8c8d;">Nama</div>
                <div style="font-weight: 600;">{{ $booking->nama_pemesan }}</div>

                <div style="color: #7f8c8d;">No. HP</div>
                <div style="font-weight: 600;">{{ $booking->no_hp }}</div>

                <div style="color: #7f8c8d;">Email</div>
                <div style="font-weight: 600;">{{ $booking->email }}</div>
            </div>
        </div>

        <!-- Booking Details -->
        <div style="background: #ecf0f1; padding: 25px; border-radius: 10px; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                🏟️ Detail Lapangan
            </h3>
            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 12px;">
                <div style="color: #7f8c8d;">Lapangan</div>
                <div style="font-weight: 600;">{{ $booking->lapangan->nama }}</div>

                <div style="color: #7f8c8d;">Kategori</div>
                <div style="font-weight: 600;">{{ $booking->lapangan->sportCategory->nama }}</div>

                <div style="color: #7f8c8d;">Lokasi</div>
                <div style="font-weight: 600;">{{ $booking->lapangan->lokasi }}</div>

                <div style="color: #7f8c8d;">Tanggal Main</div>
                <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('l, d F Y') }}</div>

                <div style="color: #7f8c8d;">Waktu</div>
                <div style="font-weight: 600;">
                    {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }} WIB
                </div>

                <div style="color: #7f8c8d;">Durasi</div>
                <div style="font-weight: 600;">{{ $booking->durasi_jam }} Jam</div>
            </div>
        </div>

        <!-- Payment Details -->
        <div style="background: #ecf0f1; padding: 25px; border-radius: 10px; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                💳 Rincian Pembayaran
            </h3>
            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 12px;">
                <div style="color: #7f8c8d;">Harga per Jam</div>
                <div style="font-weight: 600;">Rp {{ number_format($booking->lapangan->harga_per_jam, 0, ',', '.') }}</div>

                <div style="color: #7f8c8d;">Durasi</div>
                <div style="font-weight: 600;">{{ $booking->durasi_jam }} Jam</div>

                <div style="color: #7f8c8d;">Metode Pembayaran</div>
                <div style="font-weight: 600;">
                    @if($booking->metode_pembayaran == 'cash')
                    💵 Cash
                    @else
                    🏦 Transfer Bank
                    @endif
                </div>

                <div style="height: 1px; background: #bdc3c7; grid-column: 1 / -1;"></div>

                <div style="color: #2c3e50; font-weight: 700; font-size: 1.1rem;">Total Bayar</div>
                <div style="font-weight: 700; font-size: 1.5rem; color: #e67e22;">
                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($booking->catatan)
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #f39c12; margin-bottom: 20px;">
            <strong style="color: #856404;">📝 Catatan:</strong>
            <p style="margin: 5px 0 0 0; color: #856404;">{{ $booking->catatan }}</p>
        </div>
        @endif

        <!-- Booking Info -->
        <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <small style="color: #2e7d32;">
                <strong>Dibuat:</strong> {{ \Carbon\Carbon::parse($booking->created_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
            </small>
        </div>

        <!-- Actions -->
        @if($booking->status == 'pending')
        <div style="text-align: center; margin-top: 30px;">
            <form action="{{ route('admin.kasir.confirm', $booking->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success" style="padding: 15px 40px; font-size: 1.1rem;"
                        onclick="return confirm('Konfirmasi pembayaran untuk booking ini?')">
                    ✅ Konfirmasi Pembayaran
                </button>
            </form>
        </div>
        @endif

        <!-- Footer -->
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px dashed #bdc3c7;">
            <p style="color: #7f8c8d; margin: 5px 0;">Terima kasih telah menggunakan layanan kami!</p>
            <p style="color: #7f8c8d; margin: 5px 0; font-size: 0.9rem;">Harap tunjukkan bukti booking ini saat datang ke lapangan</p>
            <img src="{{ asset('images/logo.png') }}" alt="AFE SPORT" style="height: 40px; margin-top: 15px;">
            <p style="color: #e67e22; font-weight: 600; margin: 10px 0 0 0;">AFE SPORT - Your Sports Partner</p>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header,
    .btn,
    nav,
    aside,
    header,
    footer {
        display: none !important;
    }

    #receiptContent {
        padding: 20px;
    }

    body {
        background: white !important;
    }

    .card {
        box-shadow: none !important;
        border: none !important;
    }
}

.badge {
    padding: 8px 20px;
    border-radius: 6px;
    font-weight: 600;
    color: white;
    display: inline-block;
}
</style>
@endsection
