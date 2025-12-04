@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="stat-card orange">
        <div class="stat-card-content">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($totalPendapatan / 1000, 0, ',', '.') }}.000</div>
        </div>
        <div class="stat-card-icon">💰</div>
    </div>

    <div class="stat-card orange-red">
        <div class="stat-card-content">
            <h3>Total Lapangan</h3>
            <div class="value">{{ $totalLapangan }}</div>
        </div>
        <div class="stat-card-icon">🏟️</div>
    </div>

    <div class="stat-card green">
        <div class="stat-card-content">
            <h3>Total Booking</h3>
            <div class="value">{{ $totalBooking }}</div>
        </div>
        <div class="stat-card-icon">🛒</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
    <!-- Booking Terbaru -->
    <div class="card">
        <div class="card-header">
            <h2>Booking Terbaru</h2>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($bookingHariIni->count() > 0)
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ecf0f1;">No. Booking</th>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ecf0f1;">Tanggal</th>
                            <th style="padding: 15px; text-align: right; border-bottom: 2px solid #ecf0f1;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookingHariIni->take(5) as $booking)
                        <tr>
                            <td style="padding: 15px; border-bottom: 1px solid #ecf0f1;">{{ $booking->booking_code }}</td>
                            <td style="padding: 15px; border-bottom: 1px solid #ecf0f1;">{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td style="padding: 15px; text-align: right; border-bottom: 1px solid #ecf0f1; color: #e67e22; font-weight: 600;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 50px 20px; text-align: center; color: #95a5a6;">
                    <p>Belum ada booking</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Lapangan Terlaris -->
    <div class="card">
        <div class="card-header">
            <h2>Lapangan Terlaris</h2>
        </div>
        <div class="card-body" style="padding: 20px;">
            @if($lapanganPopuler->count() > 0)
                @foreach($lapanganPopuler as $lapangan)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #ecf0f1;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <img src="{{ asset($lapangan->foto) }}" alt="{{ $lapangan->nama }}"
                             style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                        <div>
                            <strong style="display: block; color: #2c3e50; margin-bottom: 5px;">{{ $lapangan->nama }}</strong>
                            <small style="color: #7f8c8d;">Terjual: {{ $lapangan->bookings_count }}</small>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <strong style="color: #e67e22; font-size: 16px;">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</strong>
                    </div>
                </div>
                @endforeach
            @else
                <div style="padding: 50px 20px; text-align: center; color: #95a5a6;">
                    <p>Belum ada data lapangan</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
