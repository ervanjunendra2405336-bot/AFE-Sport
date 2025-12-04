@extends('admin.layout')

@section('title', 'Kasir - Walk-in Booking')
@section('page-title', 'Kasir')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>💰 Kasir - Walk-in Booking</h2>
        <a href="{{ route('admin.kasir.create') }}" class="btn btn-primary">
            ➕ Buat Booking Baru
        </a>
    </div>

    <div class="card-body">
        <!-- Filter -->
        <div style="background: #ecf0f1; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <form method="GET" action="{{ route('admin.kasir.index') }}">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 10px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.9rem; margin-bottom: 5px;">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ request('tanggal', date('Y-m-d')) }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.9rem; margin-bottom: 5px;">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.9rem; margin-bottom: 5px;">Cari Kode Booking</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="AFE-XXXXXXXX" value="{{ request('search') }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.9rem; margin-bottom: 5px;">&nbsp;</label>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            🔍 Filter
                        </button>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.9rem; margin-bottom: 5px;">&nbsp;</label>
                        <a href="{{ route('admin.kasir.index') }}" class="btn"
                           style="background: #95a5a6; color: white; width: 100%;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($bookings->isEmpty())
        <div style="text-align: center; padding: 60px 20px; color: #7f8c8d;">
            <div style="font-size: 4rem; margin-bottom: 15px;">📭</div>
            <h3>Belum Ada Booking</h3>
            <p>Belum ada booking walk-in untuk hari ini</p>
            <a href="{{ route('admin.kasir.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                ➕ Buat Booking Pertama
            </a>
        </div>
        @else
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Kode Booking</th>
                        <th style="min-width: 150px;">Customer</th>
                        <th style="min-width: 150px;">Lapangan</th>
                        <th style="min-width: 130px;">Tanggal & Waktu</th>
                        <th style="min-width: 80px;">Durasi</th>
                        <th style="min-width: 100px;">Total</th>
                        <th style="min-width: 100px;">Metode</th>
                        <th style="min-width: 110px;">Status</th>
                        <th style="min-width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>
                            <strong style="color: #e67e22;">{{ $booking->booking_code }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $booking->nama_pemesan }}</div>
                            <small style="color: #7f8c8d;">{{ $booking->no_hp }}</small>
                        </td>
                        <td>
                            <div>{{ $booking->lapangan->nama }}</div>
                            <small style="color: #7f8c8d;">{{ $booking->lapangan->sportCategory->nama }}</small>
                        </td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</div>
                            <small style="color: #7f8c8d;">
                                {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                            </small>
                        </td>
                        <td>
                            <span class="badge" style="background: #3498db;">
                                {{ $booking->durasi_jam }} Jam
                            </span>
                        </td>
                        <td>
                            <strong>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($booking->metode_pembayaran == 'cash')
                            <span class="badge" style="background: #27ae60;">💵 Cash</span>
                            @else
                            <span class="badge" style="background: #3498db;">🏦 Transfer</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status == 'confirmed')
                            <span class="badge badge-success">✅ Confirmed</span>
                            @elseif($booking->status == 'pending')
                            <span class="badge badge-warning">⏳ Pending</span>
                            @elseif($booking->status == 'completed')
                            <span class="badge" style="background: #9b59b6;">✓ Completed</span>
                            @else
                            <span class="badge badge-danger">❌ Cancelled</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: nowrap; white-space: nowrap;">
                                <a href="{{ route('admin.kasir.show', $booking->id) }}"
                                   class="btn btn-sm" style="background: #3498db; color: white; padding: 6px 10px;"
                                   title="Lihat Detail">
                                    👁️
                                </a>

                                @if($booking->status == 'pending')
                                <form action="{{ route('admin.kasir.confirm', $booking->id) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="padding: 6px 10px;"
                                            title="Konfirmasi Pembayaran"
                                            onclick="return confirm('Konfirmasi pembayaran untuk booking ini?')">
                                        ✓
                                    </button>
                                </form>
                                @endif

                                @if($booking->status != 'cancelled')
                                <form action="{{ route('admin.booking.cancel', $booking->id) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 6px 10px;"
                                            title="Batalkan Booking"
                                            onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                                        ❌
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $bookings->appends(request()->query())->links() }}
        </div>

        <!-- Summary Stats -->
        <div style="background: #ecf0f1; padding: 20px; border-radius: 8px; margin-top: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">📊 Ringkasan Hari Ini</h3>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #e67e22;">
                        {{ $bookings->where('status', 'confirmed')->count() }}
                    </div>
                    <small style="color: #7f8c8d;">Confirmed</small>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #f39c12;">
                        {{ $bookings->where('status', 'pending')->count() }}
                    </div>
                    <small style="color: #7f8c8d;">Pending</small>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #27ae60;">
                        Rp {{ number_format($bookings->where('status', 'confirmed')->sum('total_harga'), 0, ',', '.') }}
                    </div>
                    <small style="color: #7f8c8d;">Total Pendapatan</small>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #3498db;">
                        {{ $bookings->where('metode_pembayaran', 'cash')->count() }}
                    </div>
                    <small style="color: #7f8c8d;">Cash Payment</small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.table th {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    padding: 12px;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.badge {
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
    display: inline-block;
}

.badge-success {
    background: #27ae60;
}

.badge-warning {
    background: #f39c12;
}

.badge-danger {
    background: #e74c3c;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.85rem;
}

.form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
}

.form-control:focus {
    outline: none;
    border-color: #e67e22;
    box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
}

.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}
</style>
@endsection
