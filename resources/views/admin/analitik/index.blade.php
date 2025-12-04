@extends('admin.layout')

@section('title', 'Analitik & Laporan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📊 Analitik & Laporan</h2>
        <div>
            <select class="form-select" id="periodeSelect" onchange="window.location.href='?periode='+this.value">
                <option value="7" {{ $periode == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="60" {{ $periode == 60 ? 'selected' : '' }}>60 Hari Terakhir</option>
                <option value="90" {{ $periode == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
            </select>
        </div>
    </div>

    <!-- Total Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Total Booking</h6>
                    <h2 class="mb-0">{{ $totalStats['total_booking'] }}</h2>
                    <small style="opacity: 0.8;">Transaksi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Total Pendapatan</h6>
                    <h2 class="mb-0">Rp {{ number_format($totalStats['total_pendapatan'], 0, ',', '.') }}</h2>
                    <small style="opacity: 0.8;">Revenue</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Rata-rata Transaksi</h6>
                    <h2 class="mb-0">Rp {{ number_format($totalStats['rata_rata_transaksi'], 0, ',', '.') }}</h2>
                    <small style="opacity: 0.8;">Per Booking</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Customer Unik</h6>
                    <h2 class="mb-0">{{ $totalStats['customer_unik'] }}</h2>
                    <small style="opacity: 0.8;">Email Berbeda</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Pendapatan Harian -->
    <div class="card mb-4" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-body">
            <h5 class="card-title mb-4">📈 Grafik Pendapatan Harian</h5>
            <canvas id="pendapatanChart" height="60"></canvas>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Lapangan Terpopuler -->
        <div class="col-md-6 mb-3">
            <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 100%;">
                <div class="card-body">
                    <h5 class="card-title mb-4">🏟️ Lapangan Terpopuler</h5>
                    <div style="max-height: 250px; position: relative;">
                        <canvas id="lapanganChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Terpopuler -->
        <div class="col-md-6 mb-3">
            <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 100%;">
                <div class="card-body">
                    <h5 class="card-title mb-4">⚽ Kategori Olahraga</h5>
                    <div style="max-height: 250px; position: relative;">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Jam Tersibuk -->
        <div class="col-md-6 mb-3">
            <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 100%;">
                <div class="card-body">
                    <h5 class="card-title mb-4">⏰ Jam Tersibuk</h5>
                    <div style="max-height: 250px; position: relative;">
                        <canvas id="jamChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="col-md-6 mb-3">
            <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 100%;">
                <div class="card-body">
                    <h5 class="card-title mb-4">💳 Metode Pembayaran</h5>
                    <div style="max-height: 250px; position: relative;">
                        <canvas id="metodeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Tabel Lapangan -->
    <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-body">
            <h5 class="card-title mb-4">📊 Detail Lapangan</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th>Lapangan</th>
                            <th>Total Booking</th>
                            <th>Total Pendapatan</th>
                            <th>Rata-rata per Booking</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lapanganTerpopuler as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->lapangan->nama }}</strong><br>
                                <small class="text-muted">{{ $item->lapangan->sportCategory->nama }}</small>
                            </td>
                            <td>{{ $item->total_booking }} kali</td>
                            <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->total_pendapatan / $item->total_booking, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js default colors
const colors = {
    orange: '#e67e22',
    blue: '#3498db',
    green: '#27ae60',
    red: '#e74c3c',
    purple: '#9b59b6',
    yellow: '#f39c12',
    teal: '#1abc9c',
    pink: '#e91e63'
};

// Grafik Pendapatan Harian
const pendapatanData = @json($pendapatanHarian);
const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
new Chart(pendapatanCtx, {
    type: 'line',
    data: {
        labels: pendapatanData.map(item => {
            const date = new Date(item.tanggal);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        }),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: pendapatanData.map(item => item.total_pendapatan),
            borderColor: colors.orange,
            backgroundColor: 'rgba(230, 126, 34, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Jumlah Transaksi',
            data: pendapatanData.map(item => item.jumlah_transaksi),
            borderColor: colors.blue,
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    callback: function(value) {
                        return value + ' booking';
                    }
                }
            }
        }
    }
});

// Grafik Lapangan Terpopuler
const lapanganData = @json($lapanganTerpopuler);
const lapanganCtx = document.getElementById('lapanganChart').getContext('2d');
new Chart(lapanganCtx, {
    type: 'bar',
    data: {
        labels: lapanganData.map(item => item.lapangan.nama),
        datasets: [{
            label: 'Total Booking',
            data: lapanganData.map(item => item.total_booking),
            backgroundColor: colors.orange,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Grafik Kategori
const kategoriData = @json($kategoriTerpopuler);
const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
new Chart(kategoriCtx, {
    type: 'doughnut',
    data: {
        labels: kategoriData.map(item => item.nama),
        datasets: [{
            data: kategoriData.map(item => item.total_booking),
            backgroundColor: [colors.orange, colors.blue, colors.green, colors.purple, colors.yellow],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
    }
});

// Grafik Jam Tersibuk
const jamData = @json($jamTersibuk);
const jamCtx = document.getElementById('jamChart').getContext('2d');
new Chart(jamCtx, {
    type: 'bar',
    data: {
        labels: jamData.map(item => item.jam + ':00'),
        datasets: [{
            label: 'Total Booking',
            data: jamData.map(item => item.total_booking),
            backgroundColor: colors.blue,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Grafik Metode Pembayaran
const metodeData = @json($metodePembayaran);
const metodeCtx = document.getElementById('metodeChart').getContext('2d');
new Chart(metodeCtx, {
    type: 'pie',
    data: {
        labels: metodeData.map(item => item.metode_pembayaran == 'cash' ? 'Cash' : 'Transfer'),
        datasets: [{
            data: metodeData.map(item => item.jumlah),
            backgroundColor: [colors.green, colors.blue],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
    }
});
</script>
@endsection
