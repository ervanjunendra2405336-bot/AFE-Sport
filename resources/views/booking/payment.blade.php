@extends('layout')

@section('title', 'Pembayaran Booking')

@section('content')
<section class="page-header">
    <div class="container">
        <h1>Pembayaran Booking</h1>
        <p>Pilih metode pembayaran dan selesaikan transaksi Anda</p>
    </div>
</section>

<section class="payment-section">
    <div class="container">
        <div class="payment-layout">
            <!-- Informasi Booking -->
            <div class="booking-summary">
                <h2>Detail Booking</h2>
                <div class="summary-card">
                    <div class="summary-item">
                        <span class="label">Kode Booking:</span>
                        <span class="value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Lapangan:</span>
                        <span class="value">{{ $booking->lapangan->nama }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Tanggal:</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Jam:</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Durasi:</span>
                        <span class="value">{{ $booking->durasi_jam }} Jam</span>
                    </div>
                    <hr>
                    <div class="summary-item total">
                        <span class="label">Total Pembayaran:</span>
                        <span class="value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="payment-form-container">
                <h2>Metode Pembayaran</h2>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('booking.payment.process', $booking->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf

                    <div class="payment-methods">
                        <div class="payment-method-card">
                            <input type="radio" id="transfer" name="metode_pembayaran" value="transfer" required>
                            <label for="transfer" class="method-label">
                                <div class="method-icon">🏦</div>
                                <div class="method-info">
                                    <h3>Transfer Bank</h3>
                                    <p>Transfer ke rekening kami</p>
                                </div>
                            </label>
                        </div>

                        <div class="payment-method-card">
                            <input type="radio" id="cash" name="metode_pembayaran" value="cash" required>
                            <label for="cash" class="method-label">
                                <div class="method-icon">💵</div>
                                <div class="method-info">
                                    <h3>Bayar di Tempat</h3>
                                    <p>Bayar saat datang ke venue</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Info Transfer Bank -->
                    <div id="transferInfo" class="transfer-info" style="display: none;">
                        <div class="bank-info-card">
                            <h3>📋 Informasi Rekening</h3>
                            <div class="bank-details">
                                <div class="bank-item">
                                    <span class="bank-label">Bank:</span>
                                    <span class="bank-value">Mandiri</span>
                                </div>
                                <div class="bank-item">
                                    <span class="bank-label">Nomor Rekening:</span>
                                    <span class="bank-value account-number">8111010111055507</span>
                                    <button type="button" class="btn-copy" onclick="copyAccountNumber()">📋 Salin</button>
                                </div>
                                <div class="bank-item">
                                    <span class="bank-label">Atas Nama:</span>
                                    <span class="bank-value">Ervan Junendra Dwi Hermawan</span>
                                </div>
                                <div class="bank-item">
                                    <span class="bank-label">Jumlah Transfer:</span>
                                    <span class="bank-value total-amount">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="upload-section">
                                <label for="bukti_pembayaran" class="upload-label">
                                    Upload Bukti Transfer <span class="required">*</span>
                                </label>
                                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="form-control" accept="image/*">
                                <small class="help-text">Format: JPG, PNG. Maksimal 2MB</small>
                                <div id="imagePreview" class="image-preview"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Cash Payment -->
                    <div id="cashInfo" class="cash-info" style="display: none;">
                        <div class="info-card">
                            <h3>ℹ️ Informasi Pembayaran Tunai</h3>
                            <ul>
                                <li>Bayar langsung di kasir venue saat Anda datang</li>
                                <li>Tunjukkan kode booking Anda</li>
                                <li>Siapkan uang pas untuk mempercepat transaksi</li>
                                <li>Pembayaran harus dilakukan sebelum main</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('lapangan.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const transferRadio = document.getElementById('transfer');
    const cashRadio = document.getElementById('cash');
    const transferInfo = document.getElementById('transferInfo');
    const cashInfo = document.getElementById('cashInfo');
    const buktiInput = document.getElementById('bukti_pembayaran');

    transferRadio.addEventListener('change', function() {
        if (this.checked) {
            transferInfo.style.display = 'block';
            cashInfo.style.display = 'none';
            buktiInput.required = true;
        }
    });

    cashRadio.addEventListener('change', function() {
        if (this.checked) {
            transferInfo.style.display = 'none';
            cashInfo.style.display = 'block';
            buktiInput.required = false;
            buktiInput.value = '';
        }
    });

    // Preview image
    buktiInput.addEventListener('change', function() {
        const preview = document.getElementById('imagePreview');
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(file);
        }
    });
});

function copyAccountNumber() {
    const accountNumber = '8111010111055507';
    navigator.clipboard.writeText(accountNumber).then(function() {
        alert('Nomor rekening berhasil disalin!');
    });
}
</script>

<style>
.payment-section {
    padding: 3rem 0;
    background: #f8f9fa;
}

.payment-layout {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
}

.booking-summary {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.summary-card {
    margin-top: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-item.total {
    border-bottom: none;
    margin-top: 1rem;
    font-size: 1.2rem;
    font-weight: 700;
    color: #2ecc71;
}

.payment-form-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.payment-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin: 2rem 0;
}

.payment-method-card {
    position: relative;
}

.payment-method-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.method-label {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px solid #ddd;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-method-card input[type="radio"]:checked + .method-label {
    border-color: #2ecc71;
    background: #f0fdf4;
}

.method-icon {
    font-size: 2.5rem;
}

.method-info h3 {
    margin: 0;
    font-size: 1.1rem;
}

.method-info p {
    margin: 0.25rem 0 0;
    color: #7f8c8d;
    font-size: 0.9rem;
}

.transfer-info, .cash-info {
    margin-top: 2rem;
}

.bank-info-card, .info-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #2ecc71;
}

.bank-details {
    margin: 1rem 0;
}

.bank-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.bank-label {
    font-weight: 600;
    color: #2c3e50;
}

.bank-value {
    font-weight: 700;
    color: #2ecc71;
}

.account-number {
    font-size: 1.2rem;
}

.btn-copy {
    padding: 0.4rem 0.8rem;
    background: #2ecc71;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    margin-left: 0.5rem;
}

.btn-copy:hover {
    background: #27ae60;
}

.total-amount {
    font-size: 1.3rem;
}

.upload-section {
    margin-top: 1.5rem;
}

.upload-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.help-text {
    display: block;
    margin-top: 0.5rem;
    color: #7f8c8d;
    font-size: 0.85rem;
}

.image-preview {
    margin-top: 1rem;
}

.image-preview img {
    max-width: 300px;
    border-radius: 8px;
    border: 2px solid #ddd;
}

.info-card ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.info-card li {
    margin: 0.5rem 0;
    color: #2c3e50;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.form-actions .btn {
    flex: 1;
}

@media (max-width: 768px) {
    .payment-layout {
        grid-template-columns: 1fr;
    }

    .payment-methods {
        grid-template-columns: 1fr;
    }

    .booking-summary {
        position: static;
    }
}
</style>
@endsection
