@extends('layout')

@section('title', 'Booking ' . $lapangan->nama)

@section('content')
<section class="page-header">
    <div class="container">
        <h1>Booking {{ $lapangan->nama }}</h1>
        <p>Isi form di bawah untuk melakukan booking</p>
    </div>
</section>

<section class="booking-form-section">
    <div class="container">
        <div class="booking-layout">
            <div class="booking-form-container">
                <h2>Form Booking</h2>

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                    <div class="form-group">
                        <label for="nama_pemesan">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama_pemesan" name="nama_pemesan" class="form-control" value="{{ old('nama_pemesan') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="telepon">Nomor Telepon <span class="required">*</span></label>
                        <input type="tel" id="telepon" name="telepon" class="form-control"
                               value="{{ old('telepon') }}" required
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]*"
                               inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               minlength="10"
                               maxlength="15">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_booking">Tanggal Booking <span class="required">*</span></label>
                        <input type="date" id="tanggal_booking" name="tanggal_booking" class="form-control" value="{{ old('tanggal_booking') }}" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai <span class="required">*</span></label>
                        <select id="jam_mulai" name="jam_mulai" class="form-control" required>
                            <option value="">Pilih Jam</option>
                            @php
                                $jamBuka = \Carbon\Carbon::parse($lapangan->jam_buka ?? '06:00:00')->format('H');
                                $jamTutup = \Carbon\Carbon::parse($lapangan->jam_tutup ?? '23:00:00')->format('H');
                            @endphp
                            @for($i = $jamBuka; $i < $jamTutup; $i++)
                            <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                            @endfor
                        </select>
                        <small class="form-text text-muted">
                            Jam Operasional: {{ \Carbon\Carbon::parse($lapangan->jam_buka ?? '06:00:00')->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($lapangan->jam_tutup ?? '23:00:00')->format('H:i') }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="durasi_jam">Durasi (Jam) <span class="required">*</span></label>
                        <select id="durasi_jam" name="durasi_jam" class="form-control" required>
                            <option value="">Pilih Durasi</option>
                            @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">{{ $i }} Jam</option>
                            @endfor
                        </select>
                        <small class="form-text text-muted" id="durasiInfo">Durasi akan disesuaikan dengan jam tutup</small>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan (Opsional)</label>
                        <textarea id="catatan" name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Availability Status -->
                    <div id="availabilityStatus" class="alert" style="display: none; margin-bottom: 1.5rem;"></div>

                    <div class="booking-summary" id="bookingSummary" style="display: none;">
                        <h4>Ringkasan Booking</h4>
                        <div class="summary-item">
                            <span>Lapangan:</span>
                            <span>{{ $lapangan->nama }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Harga per Jam:</span>
                            <span>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Durasi:</span>
                            <span id="summaryDurasi">-</span>
                        </div>
                        <div class="summary-item total">
                            <span>Total Harga:</span>
                            <span id="summaryTotal">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Konfirmasi Booking</button>
                    <a href="{{ route('lapangan.show', $lapangan->id) }}" class="btn btn-outline btn-block">Batal</a>
                </form>
            </div>

            <div class="booking-info-sidebar">
                <div class="info-card">
                    <h3>{{ $lapangan->nama }}</h3>
                    @if($lapangan->foto)
                    <img src="{{ asset($lapangan->foto) }}" alt="{{ $lapangan->nama }}" style="width: 100%; border-radius: 8px; margin: 1rem 0;">
                    @else
                    <img src="https://placehold.co/300x200/2ecc71/ffffff?text={{ urlencode($lapangan->nama) }}" alt="{{ $lapangan->nama }}" style="width: 100%; border-radius: 8px; margin: 1rem 0;">
                    @endif
                    <div class="info-item">
                        <span>Tipe:</span>
                        <span>{{ ucfirst($lapangan->tipe) }}</span>
                    </div>
                    <div class="info-item">
                        <span>Harga:</span>
                        <span class="price">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</span>
                    </div>
                </div>

                <div class="info-card">
                    <h4>Ketentuan Booking</h4>
                    <ul class="rules-list">
                        <li>✓ Booking minimal 1 jam sebelum jadwal main</li>
                        <li>✓ Maksimal durasi booking 8 jam</li>
                        <li>✓ Pembayaran dilakukan saat kedatangan</li>
                        <li>✓ Harap datang 15 menit sebelum jadwal</li>
                        <li>✓ Bawa sepatu futsal dan perlengkapan sendiri</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const hargaPerJam = {{ $lapangan->harga_per_jam }};
    const lapanganId = {{ $lapangan->id }};
    const jamTutup = {{ \Carbon\Carbon::parse($lapangan->jam_tutup ?? '23:00:00')->format('H') }};
    const durasiSelect = document.getElementById('durasi_jam');
    const summaryDiv = document.getElementById('bookingSummary');
    const summaryDurasi = document.getElementById('summaryDurasi');
    const summaryTotal = document.getElementById('summaryTotal');
    const submitBtn = document.querySelector('button[type="submit"]');
    const availabilityStatus = document.getElementById('availabilityStatus');
    const jamMulaiSelect = document.getElementById('jam_mulai');
    const durasiInfo = document.getElementById('durasiInfo');

    // Update available duration options based on jam_mulai
    function updateDurasiOptions() {
        const jamMulai = jamMulaiSelect.value;
        if (!jamMulai) return;

        const jamMulaiHour = parseInt(jamMulai.split(':')[0]);
        const maxDurasi = jamTutup - jamMulaiHour;

        // Clear and rebuild duration options
        durasiSelect.innerHTML = '<option value="">Pilih Durasi</option>';
        for (let i = 1; i <= Math.min(maxDurasi, 8); i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i + ' Jam';
            durasiSelect.appendChild(option);
        }

        // Update info text
        durasiInfo.textContent = `Maksimal ${maxDurasi} jam (tutup jam ${jamTutup}:00)`;

        // Reset selected duration if it exceeds max
        const currentDurasi = parseInt(durasiSelect.value);
        if (currentDurasi > maxDurasi) {
            durasiSelect.value = '';
            summaryDiv.style.display = 'none';
        }
    }

    // Calculate total price
    durasiSelect.addEventListener('change', function() {
        const durasi = parseInt(this.value);
        if (durasi > 0) {
            const total = durasi * hargaPerJam;
            summaryDiv.style.display = 'block';
            summaryDurasi.textContent = durasi + ' Jam';
            summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            checkAvailability();
        } else {
            summaryDiv.style.display = 'none';
        }
    });

    // Update duration when jam_mulai changes
    jamMulaiSelect.addEventListener('change', function() {
        updateDurasiOptions();
        checkAvailability();
    });

    // Real-time availability check
    function checkAvailability() {
        const tanggal = document.getElementById('tanggal_booking').value;
        const jamMulai = document.getElementById('jam_mulai').value;
        const durasi = document.getElementById('durasi_jam').value;

        if (!tanggal || !jamMulai || !durasi) {
            availabilityStatus.style.display = 'none';
            return;
        }

        // Show loading
        availabilityStatus.style.display = 'block';
        availabilityStatus.className = 'alert alert-info';
        availabilityStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengecek ketersediaan...';

        const requestData = {
            lapangan_id: lapanganId,
            tanggal_booking: tanggal,
            jam_mulai: jamMulai,
            durasi_jam: durasi
        };

        console.log('Checking availability with data:', requestData);
        console.log('URL:', '{{ route("booking.check") }}');

        fetch('{{ route("booking.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text);
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            availabilityStatus.className = 'alert';

            if (data.available) {
                availabilityStatus.classList.add('alert-success');
                availabilityStatus.innerHTML = `
                    <strong><i class="fas fa-check-circle"></i> ${data.message}</strong><br>
                    <small>${data.existing_bookings} lapangan sudah dibooking, ${data.slot_tersedia} lapangan masih tersedia</small>
                `;
                submitBtn.disabled = false;
            } else {
                availabilityStatus.classList.add('alert-danger');
                availabilityStatus.innerHTML = `
                    <strong><i class="fas fa-times-circle"></i> ${data.message}</strong><br>
                    ${data.total_lapangan > 0 ? `<small>Semua ${data.total_lapangan} lapangan sudah dibooking. Silakan pilih waktu lain.</small>` : ''}
                `;
                submitBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error checking availability:', error);
            availabilityStatus.className = 'alert alert-warning';
            availabilityStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal mengecek ketersediaan. Silakan coba lagi.';
            submitBtn.disabled = false;
        });
    }

    // Trigger check on input change
    document.getElementById('tanggal_booking').addEventListener('change', checkAvailability);
    // jam_mulai change already handled above
    // durasi_jam change already handled above
</script>
@endpush
@endsection
