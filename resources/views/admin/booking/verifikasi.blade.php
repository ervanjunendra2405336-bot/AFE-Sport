@extends('admin.layout')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran Transfer')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>🔍 Verifikasi Pembayaran Transfer</h2>
    </div>

    <div class="card-body">
        <!-- Filter -->
        <form method="GET" style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
            <input type="text" name="search" class="form-control" placeholder="Cari booking code..."
                   value="{{ request('search') }}" style="max-width: 250px;">

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
            <a href="{{ route('admin.booking.verifikasi') }}" class="btn" style="background: #6c757d; color: white;">Reset</a>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                        <td>
                            <div>{{ $booking->nama_pemesan }}</div>
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
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                @if($booking->metode_pembayaran == 'transfer' && $booking->bukti_pembayaran)
                                    <button onclick="showProof('{{ asset($booking->bukti_pembayaran) }}', '{{ $booking->booking_code }}')"
                                            class="btn btn-primary btn-sm">
                                        🖼️ Lihat Bukti
                                    </button>
                                @endif

                                @if($booking->status == 'pending')
                                    <form action="{{ route('admin.booking.approve', $booking) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Konfirmasi booking ini?')">
                                            ✓ Approve
                                        </button>
                                    </form>

                                    <button onclick="showRejectForm({{ $booking->id }})" class="btn btn-danger btn-sm">
                                        ✗ Reject
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
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

<!-- Modal Bukti Pembayaran -->
<div id="proofModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px; max-height: 90vh; overflow: auto; position: relative;">
        <button onclick="closeProof()" style="position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; font-size: 18px;">×</button>
        <h3 style="margin-bottom: 15px;">Bukti Pembayaran</h3>
        <p id="proofBookingCode" style="margin-bottom: 15px; color: #666;"></p>
        <img id="proofImage" src="" alt="Bukti Pembayaran" style="width: 100%; border-radius: 8px;">
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px;">Reject Booking</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="4" placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-danger">Reject Booking</button>
                <button type="button" onclick="closeReject()" class="btn" style="background: #6c757d; color: white;">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showProof(imageUrl, bookingCode) {
    document.getElementById('proofImage').src = imageUrl;
    document.getElementById('proofBookingCode').textContent = 'Booking: ' + bookingCode;
    document.getElementById('proofModal').style.display = 'flex';
}

function closeProof() {
    document.getElementById('proofModal').style.display = 'none';
}

function showRejectForm(bookingId) {
    const form = document.getElementById('rejectForm');
    form.action = '/admin/booking/' + bookingId + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeReject() {
    document.getElementById('rejectModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('proofModal').addEventListener('click', function(e) {
    if (e.target === this) closeProof();
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeReject();
});
</script>
@endsection
