@extends('admin.layout')

@section('title', 'Semua Booking')
@section('page-title', 'Semua Booking')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>📋 Daftar Semua Booking</h2>
    </div>

    <div class="card-body">
        <!-- Filter -->
        <form method="GET" style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
            <input type="text" name="search" class="form-control" placeholder="Cari booking code atau nama..."
                   value="{{ request('search') }}" style="max-width: 280px;">

            <select name="status" class="form-control" style="max-width: 180px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="metode" class="form-control" style="max-width: 180px;">
                <option value="">Semua Metode</option>
                <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Cash</option>
            </select>

            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" style="max-width: 200px;">

            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.booking.index') }}" class="btn" style="background: #6c757d; color: white;">Reset</a>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Customer</th>
                        <th>Lapangan</th>
                        <th>Tanggal Main</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <strong style="color: #667eea;">{{ $booking->booking_code }}</strong><br>
                            <small style="color: #999;">{{ $booking->created_at->format('d M Y H:i') }}</small>
                        </td>
                        <td>
                            <div><strong>{{ $booking->nama_pemesan }}</strong></div>
                            <small style="color: #666;">{{ $booking->no_hp }}</small>
                        </td>
                        <td>
                            <div>{{ $booking->lapangan->nama }}</div>
                            <small style="color: #666;">{{ $booking->lapangan->sportCategory->nama }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</td>
                        <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                        <td><strong>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($booking->metode_pembayaran == 'transfer')
                                <span class="badge badge-info">Transfer</span>
                            @else
                                <span class="badge" style="background: #6c757d; color: white;">Cash</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status == 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($booking->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                            Tidak ada data booking
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
