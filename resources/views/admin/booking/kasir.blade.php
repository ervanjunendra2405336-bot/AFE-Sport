@extends('admin.layout')

@section('title', 'Sistem Kasir')
@section('page-title', 'Sistem Kasir - Pembayaran Cash')

@section('content')
<!-- Scan Booking -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header">
        <h2>🔍 Scan Booking Code</h2>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            <div style="flex: 1; max-width: 500px;">
                <input type="text" id="bookingCodeInput" class="form-control"
                       placeholder="Masukkan booking code (contoh: TAPEM-20251125-XXXX)"
                       style="font-size: 16px; padding: 15px;">
                <button onclick="scanBooking()" class="btn btn-primary" style="margin-top: 10px; width: 100%;">
                    🔍 Cari Booking
                </button>
            </div>

            <!-- Result Card -->
            <div id="scanResult" style="flex: 1; display: none;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border: 2px solid #667eea;">
                    <h3 style="margin-bottom: 15px; color: #333;">Detail Booking</h3>
                    <div id="bookingDetails"></div>
                    <button onclick="confirmPaymentFromScan()" id="confirmBtn" class="btn btn-success" style="margin-top: 15px; width: 100%;">
                        💰 Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>📋 Daftar Booking Cash</h2>
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
            </select>

            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}" style="max-width: 200px;">

            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.booking.kasir') }}" class="btn" style="background: #6c757d; color: white;">Reset</a>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr style="{{ $booking->status == 'confirmed' ? 'background: #f0fff4;' : '' }}">
                        <td><strong style="font-size: 15px; color: #667eea;">{{ $booking->booking_code }}</strong></td>
                        <td>
                            <div><strong>{{ $booking->nama_pemesan }}</strong></div>
                            <small style="color: #666;">📱 {{ $booking->no_hp }}</small>
                        </td>
                        <td>
                            <div>{{ $booking->lapangan->nama }}</div>
                            <small style="color: #666;">{{ $booking->lapangan->sportCategory->nama }}</small>
                        </td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</strong>
                            @if(\Carbon\Carbon::parse($booking->tanggal_booking)->isToday())
                                <span class="badge badge-info" style="margin-left: 5px;">Hari Ini</span>
                            @endif
                        </td>
                        <td><strong>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</strong></td>
                        <td>
                            <div style="font-size: 16px; font-weight: 700; color: #28a745;">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            @if($booking->status == 'confirmed')
                                <span class="badge badge-success">✓ Lunas</span>
                            @else
                                <span class="badge badge-warning">⏳ Belum Bayar</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status == 'pending')
                                <form action="{{ route('admin.booking.confirm-payment', $booking) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Konfirmasi pembayaran cash untuk booking {{ $booking->booking_code }}?')">
                                        💰 Terima Bayar
                                    </button>
                                </form>
                            @else
                                <span style="color: #28a745; font-weight: 600;">✓ Sudah Dibayar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                            Tidak ada booking cash hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        @if($bookings->count() > 0)
        <div style="margin-top: 25px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; color: white;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div>
                    <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">Total Booking</div>
                    <div style="font-size: 28px; font-weight: 700;">{{ $bookings->total() }}</div>
                </div>
                <div>
                    <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">Belum Dibayar</div>
                    <div style="font-size: 28px; font-weight: 700;">{{ $bookings->where('status', 'pending')->count() }}</div>
                </div>
                <div>
                    <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">Sudah Dibayar</div>
                    <div style="font-size: 28px; font-weight: 700;">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentBookingId = null;

async function scanBooking() {
    const bookingCode = document.getElementById('bookingCodeInput').value.trim().toUpperCase();

    if (!bookingCode) {
        alert('Masukkan booking code terlebih dahulu');
        return;
    }

    try {
        const response = await fetch('/admin/booking/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ booking_code: bookingCode })
        });

        const data = await response.json();

        if (data.success) {
            currentBookingId = data.booking.id;
            displayBookingDetails(data.booking);
            document.getElementById('scanResult').style.display = 'block';
        } else {
            alert(data.message);
            document.getElementById('scanResult').style.display = 'none';
        }
    } catch (error) {
        alert('Terjadi kesalahan saat mencari booking');
        console.error(error);
    }
}

function displayBookingDetails(booking) {
    const statusBadge = booking.status === 'confirmed'
        ? '<span class="badge badge-success">Sudah Dibayar</span>'
        : '<span class="badge badge-warning">Belum Dibayar</span>';

    const html = `
        <div style="font-size: 14px;">
            <div style="margin-bottom: 10px;">
                <strong>Booking Code:</strong><br>
                <span style="font-size: 16px; color: #667eea; font-weight: 600;">${booking.booking_code}</span>
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Customer:</strong><br>
                ${booking.customer_name}<br>
                <small>📱 ${booking.customer_phone}</small>
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Lapangan:</strong><br>
                ${booking.lapangan} (${booking.category})
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Jadwal:</strong><br>
                ${booking.tanggal} • ${booking.jam}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Total Bayar:</strong><br>
                <span style="font-size: 20px; color: #28a745; font-weight: 700;">Rp ${booking.total_harga}</span>
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Status:</strong><br>
                ${statusBadge}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Metode:</strong> ${booking.metode_pembayaran.toUpperCase()}
            </div>
        </div>
    `;

    document.getElementById('bookingDetails').innerHTML = html;

    const confirmBtn = document.getElementById('confirmBtn');
    if (booking.status === 'confirmed') {
        confirmBtn.style.display = 'none';
    } else {
        confirmBtn.style.display = 'block';
    }
}

function confirmPaymentFromScan() {
    if (!currentBookingId) return;

    if (confirm('Konfirmasi pembayaran cash untuk booking ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/booking/${currentBookingId}/confirm-payment`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Enter key to scan
document.getElementById('bookingCodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        scanBooking();
    }
});

// Auto focus on input
document.getElementById('bookingCodeInput').focus();
</script>
@endsection
