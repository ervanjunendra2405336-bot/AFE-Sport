@extends('layout')

@section('title', 'Beranda - Booking Lapangan Olahraga')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Booking Lapangan Olahraga</h1>
        <p class="hero-subtitle">Berbagai pilihan venue olahraga terbaik untuk Anda</p>
        <a href="{{ route('lapangan.index') }}" class="btn btn-primary btn-lg">Cari Lapangan</a>
    </div>
</section>

<!-- Categories Section -->
<section class="categories">
    <div class="container">
        <h2 class="section-title">Pilih Olahraga Favorit Anda</h2>
        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ route('lapangan.category', $category->slug) }}" class="category-card">
                <div class="category-icon">{{ $category->icon }}</div>
                <h3>{{ $category->nama }}</h3>
                <p>{{ $category->lapanganTersedia->count() }} venue</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <h2 class="section-title">Kenapa Memilih Kami?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🏟️</div>
                <h3>Multi-Sport Venue</h3>
                <p>Berbagai pilihan olahraga: Futsal, Basket, Tenis, Badminton, dan lainnya</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⭐</div>
                <h3>Venue Terpercaya</h3>
                <p>Semua venue terverifikasi dengan rating dan review dari pengguna</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚿</div>
                <h3>Fasilitas Lengkap</h3>
                <p>Kamar mandi, ruang ganti, parkir, kantin dan fasilitas pendukung lainnya</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Booking Mudah</h3>
                <p>Cari, bandingkan, dan booking venue dalam hitungan menit</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Venues Section -->
<section class="courts">
    <div class="container">
        <h2 class="section-title">Venue Rekomendasi</h2>
        @if($featuredLapangan->count() > 0)
        <div class="courts-grid">
            @foreach($featuredLapangan as $lap)
            <div class="court-card">
                <div class="court-image">
                    @if($lap->foto)
                    <img src="{{ asset($lap->foto) }}" alt="{{ $lap->nama }}">
                    @else
                    <img src="https://placehold.co/400x250/3498db/ffffff?text={{ urlencode($lap->nama) }}" alt="{{ $lap->nama }}">
                    @endif
                    <span class="badge badge-category">{{ $lap->sportCategory->nama }}</span>
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
                        @if($lap->fasilitas)
                        @php $fasilitasArray = is_string($lap->fasilitas) ? json_decode($lap->fasilitas, true) : $lap->fasilitas; @endphp
                        @if($fasilitasArray && is_array($fasilitasArray))
                        · {{ count($fasilitasArray) }} fasilitas
                        @endif
                        @endif
                    </p>
                    <p class="court-description">{{ Str::limit($lap->deskripsi, 80) }}</p>
                    <div class="court-footer">
                        <div>
                            <span class="court-price">Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}</span>
                            <span class="price-unit">/jam</span>
                        </div>
                        <a href="{{ route('lapangan.show', $lap->id) }}" class="btn btn-primary">Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('lapangan.index') }}" class="btn btn-outline">Lihat Semua Venue</a>
        </div>
        @else
        <p class="text-center">Belum ada venue tersedia.</p>
        @endif
    </div>
</section>

<!-- About Section -->
<section class="about" id="tentang" style="padding: 60px 0; background: white;">
    <div class="container">
        <h2 class="section-title">Tentang AFE Sport</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 18px; line-height: 1.8; color: #666; margin-bottom: 30px;">
                AFE Sport adalah platform booking venue olahraga terpercaya yang menyediakan berbagai pilihan lapangan untuk berbagai jenis olahraga. Kami berkomitmen memberikan pengalaman booking yang mudah, cepat, dan terpercaya.
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-top: 40px;">
                <div>
                    <div style="font-size: 36px; font-weight: 700; color: #e67e22;">{{ \App\Models\Lapangan::count() }}+</div>
                    <p style="color: #666; margin-top: 8px;">Venue Terdaftar</p>
                </div>
                <div>
                    <div style="font-size: 36px; font-weight: 700; color: #e67e22;">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}+</div>
                    <p style="color: #666; margin-top: 8px;">Booking Berhasil</p>
                </div>
                <div>
                    <div style="font-size: 36px; font-weight: 700; color: #e67e22;">{{ \App\Models\SportCategory::count() }}+</div>
                    <p style="color: #666; margin-top: 8px;">Jenis Olahraga</p>
                </div>
                <div>
                    <div style="font-size: 36px; font-weight: 700; color: #e67e22;">4.8★</div>
                    <p style="color: #666; margin-top: 8px;">Rating Pengguna</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <h2>Siap Bermain Olahraga Favoritmu?</h2>
        <p>Booking sekarang dan dapatkan lapangan terbaik untuk permainan Anda!</p>
        <a href="{{ route('lapangan.index') }}" class="btn btn-light btn-lg">Booking Sekarang</a>
    </div>
</section>

<!-- Contact Section -->
<section class="contact" id="kontak">
    <div class="container">
        <h2 class="section-title">Hubungi Kami</h2>
        <div class="contact-grid">
            <div class="contact-info">
                <h3>Informasi Kontak</h3>
                <div class="contact-item">
                    <strong>📞 Telepon:</strong>
                    <p>081252466876</p>
                </div>
                <div class="contact-item">
                    <strong>📧 Email:</strong>
                    <p>info@afesport.com</p>
                </div>
                <div class="contact-item">
                    <strong>📍 Alamat:</strong>
                    <p>Sumawe, Sumbermanjing Wetan, Kabupaten Malang, Jawa Timur</p>
                </div>
                <div class="contact-item">
                    <strong>🕐 Jam Operasional:</strong>
                    <p>Senin - Jumat: 08:00 - 23:00</p>
                    <p>Sabtu - Minggu: 06:00 - 24:00</p>
                </div>
            </div>
            <div class="contact-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126042.77447917344!2d112.58195757653064!3d-8.391095024869943!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd63c4e8cc9be65%3A0x4027a76e3529b20!2sSumbermanjing%20Wetan%2C%20Malang%20Regency%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1732508400000!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
