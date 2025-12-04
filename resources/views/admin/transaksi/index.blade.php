@extends('admin.layout')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">💳 Manajemen Transaksi</h2>
    </div>

    <!-- Filter Periode -->
    <div class="card mb-4" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-body">
            <div class="d-flex gap-2 mb-3 flex-wrap">
                <a href="{{ route('admin.transaksi') }}?periode=hari-ini"
                   class="btn {{ $periode == 'hari-ini' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    📅 Hari Ini
                </a>
                <a href="{{ route('admin.transaksi') }}?periode=minggu-ini"
                   class="btn {{ $periode == 'minggu-ini' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    📆 Minggu Ini
                </a>
                <a href="{{ route('admin.transaksi') }}?periode=bulan-ini"
                   class="btn {{ $periode == 'bulan-ini' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    🗓️ Bulan Ini
                </a>
                <a href="{{ route('admin.transaksi') }}?periode=semua"
                   class="btn {{ $periode == 'semua' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    📊 Semua
                </a>
            </div>

            <!-- Custom Date Range -->
            <form method="GET" class="row g-3">
                <input type="hidden" name="periode" value="custom">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                           value="{{ request('tanggal_selesai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Metode</label>
                    <select name="metode_pembayaran" class="form-select">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">🔍 Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Total Transaksi</h6>
                            <h2 class="card-title mb-0">{{ $stats['total_transaksi'] }}</h2>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.3;">💳</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Total Pendapatan</h6>
                            <h2 class="card-title mb-0">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h2>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.3;">💰</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Confirmed</h6>
                            <h2 class="card-title mb-0">{{ $stats['transaksi_confirmed'] }}</h2>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">✅</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Pending</h6>
                            <h2 class="card-title mb-0">{{ $stats['transaksi_pending'] }}</h2>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">⏳</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2" style="opacity: 0.9;">Cancelled</h6>
                            <h2 class="card-title mb-0">{{ $stats['transaksi_cancelled'] }}</h2>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">❌</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Stats -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h5 class="card-title">💵 Pembayaran Cash</h5>
                    <h3 class="text-success">{{ $stats['pembayaran_cash'] }} Transaksi</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h5 class="card-title">🏦 Pembayaran Transfer</h5>
                    <h3 class="text-primary">{{ $stats['pembayaran_transfer'] }} Transaksi</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $item)
                        <tr>
                            <td><strong>{{ $item->booking_code ?? '-' }}</strong></td>
                            <td>{{ $item->nama }}</td>
                            <td>
                                {{ $item->email }}<br>
                                <small class="text-muted">{{ $item->no_hp ?? $item->telepon }}</small>
                            </td>
                            <td>
                                {{ $item->lapangan->nama }}<br>
                                <small class="text-muted">{{ $item->lapangan->sportCategory->nama }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                            <td><strong>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($item->metode_pembayaran == 'cash')
                                    <span class="badge bg-success">💵 Cash</span>
                                @else
                                    <span class="badge bg-primary">🏦 Transfer</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 'confirmed')
                                    <span class="badge bg-success">✅ Confirmed</span>
                                @elseif($item->status == 'pending')
                                    <span class="badge bg-warning text-dark">⏳ Pending</span>
                                @else
                                    <span class="badge bg-danger">❌ Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $transaksi->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    background-color: #e67e22;
    border-color: #e67e22;
}
.btn-primary:hover {
    background-color: #d35400;
    border-color: #d35400;
}
.btn-outline-secondary {
    color: #6c757d;
    border-color: #dee2e6;
}
.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #495057;
}
</style>
@endsection
