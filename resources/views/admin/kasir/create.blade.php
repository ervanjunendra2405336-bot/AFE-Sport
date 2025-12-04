@extends('admin.layout')

@section('title', 'Buat Booking Baru')
@section('page-title', 'Kasir - Buat Booking')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>📝 Buat Booking Baru (Walk-in)</h2>
        <a href="{{ route('admin.kasir.index') }}" class="btn" style="background: #95a5a6; color: white;">
            ← Kembali
        </a>
    </div>

    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.kasir.store') }}" method="POST" id="kasirBookingForm">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Left Column -->
                <div>
                    <h3 style="color: #e67e22; margin-bottom: 15px;">📋 Data Customer</h3>

                    <div class="form-group">
                        <label>Nama Pemesan *</label>
                        <input type="text" name="nama_pemesan" class="form-control"
                               value="{{ old('nama_pemesan') }}" required
                               placeholder="Nama lengkap customer">
                    </div>

                    <div class="form-group">
                        <label>Nomor HP *</label>
                        <input type="tel" name="no_hp" id="no_hp" class="form-control"
                               value="{{ old('no_hp') }}" required
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]*"
                               inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               minlength="10"
                               maxlength="15">
                    </div>

                    <div class="form-group">
                        <label>Email (Opsional)</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}"
                               placeholder="customer@example.com">
                    </div>

                    <div class="form-group">
                        <label>Metode Pembayaran *</label>
                        <select name="metode_pembayaran" class="form-control" required>
                            <option value="cash" selected>💵 Cash</option>
                            <option value="transfer">🏦 Transfer</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <h3 style="color: #e67e22; margin-bottom: 15px;">🏟️ Detail Booking</h3>

                    <div class="form-group">
                        <label>Pilih Lapangan *</label>
                        <select name="lapangan_id" id="lapangan_id" class="form-control" required>
                            <option value="">-- Pilih Lapangan --</option>
                            @foreach($lapangan as $lap)
                            <option value="{{ $lap->id }}"
                                    data-harga="{{ $lap->harga_per_jam }}"
                                    data-jam-buka="{{ \Carbon\Carbon::parse($lap->jam_buka)->format('H:i') }}"
                                    data-jam-tutup="{{ \Carbon\Carbon::parse($lap->jam_tutup)->format('H:i') }}">
                                {{ $lap->nama }} ({{ $lap->sportCategory->nama }}) -
                                Rp {{ number_format($lap->harga_per_jam, 0, ',', '.') }}/jam
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Main *</label>
                        <input type="date" name="tanggal_booking" id="tanggal_booking"
                               class="form-control" value="{{ old('tanggal_booking', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Jam Mulai *</label>
                            <select name="jam_mulai" id="jam_mulai" class="form-control" required>
                                <option value="">Pilih Jam</option>
                                @for($i = 6; $i <= 22; $i++)
                                <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                                @endfor
                            </select>
                            <small id="jam_operasional_info" class="form-text" style="color: #7f8c8d;"></small>
                        </div>

                        <div class="form-group">
                            <label>Durasi (Jam) *</label>
                            <select name="durasi_jam" id="durasi_jam" class="form-control" required>
                                <option value="">Pilih Durasi</option>
                                @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }} Jam</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div id="availabilityStatus" class="alert" style="display: none; margin-top: 15px;"></div>
                </div>
            </div>

            <!-- Price Summary -->
            <div id="priceSummary" style="display: none; background: #ecf0f1; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h3 style="color: #2c3e50; margin-bottom: 15px;">💰 Ringkasan Pembayaran</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <small style="color: #7f8c8d;">Harga per Jam</small>
                        <div style="font-size: 1.2rem; font-weight: bold; color: #2c3e50;" id="harga_per_jam">-</div>
                    </div>
                    <div>
                        <small style="color: #7f8c8d;">Durasi</small>
                        <div style="font-size: 1.2rem; font-weight: bold; color: #2c3e50;" id="durasi_display">-</div>
                    </div>
                    <div>
                        <small style="color: #7f8c8d;">Total Bayar</small>
                        <div style="font-size: 1.5rem; font-weight: bold; color: #e67e22;" id="total_harga">Rp 0</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    💳 Proses Booking & Bayar
                </button>
                <a href="{{ route('admin.kasir.index') }}" class="btn" style="background: #95a5a6; color: white;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #e67e22;
    box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    border-left: 4px solid;
}

.alert-success {
    background: #d4edda;
    border-left-color: #28a745;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border-left-color: #dc3545;
    color: #721c24;
}

.alert-info {
    background: #d1ecf1;
    border-left-color: #17a2b8;
    color: #0c5460;
}
</style>

@push('scripts')
<script>
const lapanganSelect = document.getElementById('lapangan_id');
const tanggalInput = document.getElementById('tanggal_booking');
const jamMulaiSelect = document.getElementById('jam_mulai');
const durasiSelect = document.getElementById('durasi_jam');
const availabilityStatus = document.getElementById('availabilityStatus');
const priceSummary = document.getElementById('priceSummary');
const submitBtn = document.getElementById('submitBtn');

let currentHargaPerJam = 0;
let jamTutup = 23;

// Update jam operasional info when lapangan selected
lapanganSelect.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.value) {
        currentHargaPerJam = parseInt(option.dataset.harga);
        const jamBuka = option.dataset.jamBuka;
        jamTutup = parseInt(option.dataset.jamTutup.split(':')[0]);

        document.getElementById('jam_operasional_info').textContent =
            `Jam Operasional: ${jamBuka} - ${option.dataset.jamTutup}`;

        updateJamMulaiOptions();
        calculatePrice();
        checkAvailability();
    }
});

// Update available hours based on closing time
function updateJamMulaiOptions() {
    const selectedJam = jamMulaiSelect.value;
    jamMulaiSelect.innerHTML = '<option value="">Pilih Jam</option>';

    for (let i = 6; i < jamTutup; i++) {
        const jam = String(i).padStart(2, '0') + ':00';
        const option = document.createElement('option');
        option.value = jam;
        option.textContent = jam;
        if (jam === selectedJam) option.selected = true;
        jamMulaiSelect.appendChild(option);
    }
}

// Update duration options based on selected time
jamMulaiSelect.addEventListener('change', function() {
    updateDurasiOptions();
    checkAvailability();
});

function updateDurasiOptions() {
    if (!jamMulaiSelect.value) return;

    const jamMulai = parseInt(jamMulaiSelect.value.split(':')[0]);
    const maxDurasi = jamTutup - jamMulai;

    const selectedDurasi = durasiSelect.value;
    durasiSelect.innerHTML = '<option value="">Pilih Durasi</option>';

    for (let i = 1; i <= Math.min(maxDurasi, 8); i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i + ' Jam';
        if (i == selectedDurasi) option.selected = true;
        durasiSelect.appendChild(option);
    }
}

// Calculate and display price
durasiSelect.addEventListener('change', function() {
    calculatePrice();
    checkAvailability();
});

function calculatePrice() {
    const durasi = parseInt(durasiSelect.value);

    if (durasi > 0 && currentHargaPerJam > 0) {
        const total = durasi * currentHargaPerJam;

        priceSummary.style.display = 'block';
        document.getElementById('harga_per_jam').textContent =
            'Rp ' + currentHargaPerJam.toLocaleString('id-ID');
        document.getElementById('durasi_display').textContent = durasi + ' Jam';
        document.getElementById('total_harga').textContent =
            'Rp ' + total.toLocaleString('id-ID');
    } else {
        priceSummary.style.display = 'none';
    }
}

// Check availability
tanggalInput.addEventListener('change', checkAvailability);

function checkAvailability() {
    const lapanganId = lapanganSelect.value;
    const tanggal = tanggalInput.value;
    const jamMulai = jamMulaiSelect.value;
    const durasi = durasiSelect.value;

    if (!lapanganId || !tanggal || !jamMulai || !durasi) {
        availabilityStatus.style.display = 'none';
        return;
    }

    availabilityStatus.style.display = 'block';
    availabilityStatus.className = 'alert alert-info';
    availabilityStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengecek ketersediaan...';

    fetch('{{ route("admin.kasir.check-slots") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            lapangan_id: lapanganId,
            tanggal_booking: tanggal,
            jam_mulai: jamMulai,
            durasi_jam: durasi
        })
    })
    .then(response => response.json())
    .then(data => {
        // Use same availability check as customer booking
        fetch('{{ route("booking.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lapangan_id: lapanganId,
                tanggal_booking: tanggal,
                jam_mulai: jamMulai,
                durasi_jam: durasi
            })
        })
        .then(response => response.json())
        .then(data => {
            availabilityStatus.className = 'alert';

            if (data.available) {
                availabilityStatus.classList.add('alert-success');
                availabilityStatus.innerHTML = `
                    <strong><i class="fas fa-check-circle"></i> ${data.message}</strong><br>
                    <small>${data.existing_bookings} lapangan sudah dibooking, ${data.slot_tersedia} lapangan tersedia</small>
                `;
                submitBtn.disabled = false;
            } else {
                availabilityStatus.classList.add('alert-danger');
                availabilityStatus.innerHTML = `
                    <strong><i class="fas fa-times-circle"></i> ${data.message}</strong>
                `;
                submitBtn.disabled = true;
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        availabilityStatus.className = 'alert alert-danger';
        availabilityStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal mengecek ketersediaan';
    });
}
</script>
@endpush
@endsection
