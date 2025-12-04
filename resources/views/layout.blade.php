<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking Lapangan Futsal')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 10px;">
                    <img src="{{ asset('images/logo.png') }}" alt="AFE Sport" style="height: 35px; width: auto;">
                    <span>AFE SPORT</span>
                </a>
            </div>
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('lapangan.index') }}" class="{{ request()->routeIs('lapangan.*') ? 'active' : '' }}">Lapangan</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('images/logo.png') }}" alt="AFE Sport" style="height: 30px; width: auto;">
                        AFE SPORT
                    </h3>
                    <p>Venue olahraga terbaik dengan berbagai pilihan lapangan dan fasilitas lengkap.</p>
                </div>
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p>📞 081252466876</p>
                    <p>📧 info@afesport.com</p>
                    <p>📍 Sumawe</p>
                </div>
                <div class="footer-section">
                    <h4>Jam Operasional</h4>
                    <p>Senin - Jumat: 08:00 - 23:00</p>
                    <p>Sabtu - Minggu: 06:00 - 24:00</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 AFE Sport. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Smooth scroll untuk link anchor
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>
