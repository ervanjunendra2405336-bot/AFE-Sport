@extends('layout')

@section('title', $lapangan->nama . ' - Detail Lapangan')

@section('content')
<section class="page-header">
    <div class="container">
        <h1>{{ $lapangan->nama }}</h1>
        <p>Detail dan informasi lengkap lapangan</p>
    </div>
</section>

<section class="lapangan-detail">
    <div class="container">
        <div class="detail-grid">
            <div class="detail-left">
                <div class="detail-image">
                    @if($lapangan->foto)
                    <img src="{{ asset($lapangan->foto) }}" alt="{{ $lapangan->nama }}">
                    @else
                    <img src="https://placehold.co/600x400/2ecc71/ffffff?text={{ urlencode($lapangan->nama) }}" alt="{{ $lapangan->nama }}">
                    @endif
                </div>
                <div class="detail-description">
                    <h2>Deskripsi</h2>
                    <p>{{ $lapangan->deskripsi }}</p>
                </div>
                <div class="detail-facilities">
                    <h2>Fasilitas</h2>
                    @php $fasilitasArray = is_string($lapangan->fasilitas) ? json_decode($lapangan->fasilitas, true) : $lapangan->fasilitas; @endphp
                    @if($fasilitasArray && is_array($fasilitasArray) && count($fasilitasArray) > 0)
                    <ul class="facilities-list">
                        @foreach($fasilitasArray as $fasilitas)
                        <li>✅ {{ $fasilitas }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p>Fasilitas standar tersedia</p>
                    @endif
                </div>
                @if($lapangan->aturan)
                <div class="detail-rules">
                    <h2>Peraturan</h2>
                    <p>{{ $lapangan->aturan }}</p>
                </div>
                @endif
            </div>
            <div class="detail-right">
                <div class="booking-card">
                    <h3>Informasi Booking</h3>
                    <div class="info-item">
                        <span class="info-label">Kategori:</span>
                        <span class="info-value">{{ $lapangan->sportCategory->icon }} {{ $lapangan->sportCategory->nama }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Lokasi:</span>
                        <span class="info-value">📍 {{ $lapangan->kota ?? 'Malang' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jumlah Lapangan:</span>
                        <span class="info-value">{{ $lapangan->jumlah_lapangan }} Lapangan</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tipe:</span>
                        <span class="info-value">
                            @if($lapangan->tipe == 'indoor')
                            🏠 Indoor
                            @else
                            🌳 Outdoor
                            @endif
                        </span>
                    </div>
                    @if($lapangan->ukuran)
                    <div class="info-item">
                        <span class="info-label">Ukuran:</span>
                        <span class="info-value">{{ $lapangan->ukuran }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Jam Operasional:</span>
                        <span class="info-value">🕐 {{ \Carbon\Carbon::parse($lapangan->jam_buka ?? '06:00:00')->format('H:i') }} - {{ \Carbon\Carbon::parse($lapangan->jam_tutup ?? '23:00:00')->format('H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Harga Weekday:</span>
                        <span class="info-value price-large">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                    </div>
                    @if($lapangan->harga_weekend)
                    <div class="info-item">
                        <span class="info-label">Harga Weekend:</span>
                        <span class="info-value price-large">Rp {{ number_format($lapangan->harga_weekend, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Rating:</span>
                        <span class="info-value">⭐ {{ $lapangan->rating }}.0 ({{ $lapangan->jumlah_review }} review)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            @if($lapangan->tersedia)
                            <span class="badge badge-success">Tersedia</span>
                            @else
                            <span class="badge badge-danger">Tidak Tersedia</span>
                            @endif
                        </span>
                    </div>
                    <hr>
                    <div class="booking-hours">
                        <h4>Jam Operasional</h4>
                        <p>Senin - Jumat: 08:00 - 23:00</p>
                        <p>Sabtu - Minggu: 06:00 - 24:00</p>
                    </div>
                    @if($lapangan->tersedia)
                    <a href="{{ route('booking.create', $lapangan->id) }}" class="btn btn-primary btn-block">Booking Sekarang</a>
                    @else
                    <button class="btn btn-disabled btn-block" disabled>Tidak Tersedia</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
