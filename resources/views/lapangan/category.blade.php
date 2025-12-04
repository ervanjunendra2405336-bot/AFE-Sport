@extends('layout')

@section('title', $category->nama . ' - Booking Venue Olahraga')

@section('content')
<section class="page-header category-header">
    <div class="container">
        <div class="category-title">
            <span class="category-icon-large">{{ $category->icon }}</span>
            <div>
                <h1>{{ $category->nama }}</h1>
                <p>{{ $category->deskripsi }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="filter-section">
    <div class="container">
        <form action="{{ route('lapangan.category', $category->slug) }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="filter-item">
                    <label>Kota</label>
                    <select name="kota" class="form-select">
                        <option value="">Semua Kota</option>
                        @foreach($kotas as $kota)
                        <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>
                            {{ $kota }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Tipe Lapangan</label>
                    <select name="tipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="indoor" {{ request('tipe') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                        <option value="outdoor" {{ request('tipe') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="harga" {{ request('sort') == 'harga' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('lapangan.category', $category->slug) }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Browse Other Categories -->
<section class="other-categories">
    <div class="container">
        <h3>Cari Olahraga Lain</h3>
        <div class="category-tabs">
            @foreach($categories as $cat)
            <a href="{{ route('lapangan.category', $cat->slug) }}"
               class="category-tab {{ $cat->id == $category->id ? 'active' : '' }}">
                {{ $cat->icon }} {{ $cat->nama }}
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="lapangan-list">
    <div class="container">
        @if($lapangan->count() > 0)
        <div class="result-info">
            <p>{{ $lapangan->total() }} venue {{ $category->nama }} tersedia</p>
        </div>
        <div class="courts-grid">
            @foreach($lapangan as $lap)
            <div class="court-card">
                <div class="court-image">
                    @if($lap->foto)
                    <img src="{{ asset($lap->foto) }}" alt="{{ $lap->nama }}">
                    @else
                    <img src="https://placehold.co/400x250/3498db/ffffff?text={{ urlencode($lap->nama) }}" alt="{{ $lap->nama }}">
                    @endif
                    @if($lap->rating >= 4)
                    <span class="badge badge-rating">⭐ {{ $lap->rating }}.0</span>
                    @endif
                </div>
                <div class="court-info">
                    <h3>{{ $lap->nama }}</h3>
                    <p class="court-location">📍 {{ $lap->kota ?? 'Malang' }} · {{ $lap->jumlah_lapangan }} Lapangan</p>
                    <p class="court-type">
                        @if($lap->tipe == 'indoor')
                        🏠 Indoor
                        @else
                        🌳 Outdoor
                        @endif
                        @if($lap->ukuran)
                        · {{ $lap->ukuran }}
                        @endif
                    </p>
                    <p class="court-description">{{ Str::limit($lap->deskripsi, 100) }}</p>
                    @php $fasilitasArray = is_string($lap->fasilitas) ? json_decode($lap->fasilitas, true) : $lap->fasilitas; @endphp
                    @if($fasilitasArray && is_array($fasilitasArray) && count($fasilitasArray) > 0)
                    <div class="facilities">
                        @foreach(array_slice($fasilitasArray, 0, 3) as $fasilitas)
                        <span class="facility-badge">🏟️ {{ $fasilitas }}</span>
                        @endforeach
                        @if(count($fasilitasArray) > 3)
                        <span class="facility-badge">+{{ count($fasilitasArray) - 3 }}</span>
                        @endif
                    </div>
                    @endif
                    <div class="court-footer">
                        <div>
                            <span class="court-price">Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}</span>
                            <span class="price-unit">/jam</span>
                            @if($lap->harga_weekend)
                            <br><small class="text-muted">Weekend: Rp {{ number_format($lap->harga_weekend, 0, ',', '.') }}</small>
                            @endif
                        </div>
                        <a href="{{ route('lapangan.show', $lap->id) }}" class="btn btn-primary">Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination-wrapper">
            {{ $lapangan->links() }}
        </div>
        @else
        <div class="empty-state">
            <h3>Belum Ada Venue {{ $category->nama }}</h3>
            <p>Saat ini belum ada venue {{ $category->nama }} yang tersedia.</p>
            <a href="{{ route('lapangan.index') }}" class="btn btn-primary">Cari Olahraga Lain</a>
        </div>
        @endif
    </div>
</section>
@endsection
