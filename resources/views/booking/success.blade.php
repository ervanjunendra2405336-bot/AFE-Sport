@extends('layout')

@section('title', 'Booking Berhasil')

@section('content')
<section class="success-page">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">✓</div>
            <h1>Booking Berhasil!</h1>
            <p class="success-message">Terima kasih, booking Anda telah kami terima.</p>

            <div class="booking-details">
                <h3>Detail Booking</h3>
                <div class="detail-row">
                    <span class="detail-label">Kode Booking:</span>
                    <span class="detail-value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lapangan:</span>
                    <span class="detail-value">{{ $booking->lapangan->nama }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Pemesan:</span>
                    <span class="detail-value">{{ $booking->nama_pemesan }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $booking->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Telepon:</span>
                    <span class="detail-value">{{ $booking->telepon }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu:</span>
                    <span class="detail-value">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} WIB</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Durasi:</span>
                    <span class="detail-value">{{ $booking->durasi_jam }} Jam</span>
                </div>
                <div class="detail-row total">
                    <span class="detail-label">Total Harga:</span>
                    <span class="detail-value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                @if($booking->catatan)
                <div class="detail-row">
                    <span class="detail-label">Catatan:</span>
                    <span class="detail-value">{{ $booking->catatan }}</span>
                </div>
                @endif
            </div>

            <div class="success-info">
                <h4>Informasi Penting:</h4>
                <ul>
                    <li>📧 Konfirmasi booking telah dikirim ke email Anda</li>
                    <li>💳 Pembayaran dilakukan saat kedatangan</li>
                    <li>⏰ Harap datang 15 menit sebelum waktu booking</li>
                    <li>📞 Hubungi kami jika ada perubahan atau pembatalan</li>
                </ul>
            </div>

            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
                <a href="{{ route('lapangan.index') }}" class="btn btn-outline">Booking Lagi</a>
            </div>
        </div>
    </div>
</section>
@endsection
