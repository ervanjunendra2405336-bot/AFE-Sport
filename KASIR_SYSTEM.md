# 💰 Sistem Kasir Walk-in Booking

## Deskripsi

Sistem kasir walk-in booking memungkinkan admin/kasir untuk membuat booking langsung untuk customer yang datang ke lapangan (walk-in customer). Berbeda dengan booking online yang melalui proses pending → upload payment → confirmation, sistem kasir ini langsung membuat booking dengan status **confirmed** karena admin dapat langsung menerima pembayaran.

## Fitur Utama

### 1. **Daftar Booking Walk-in** (`/admin/kasir`)

-   Menampilkan semua booking walk-in untuk hari ini
-   Filter berdasarkan:
    -   Tanggal booking
    -   Status (pending, confirmed, completed, cancelled)
    -   Pencarian berdasarkan kode booking
-   Menampilkan ringkasan statistik:
    -   Total booking confirmed
    -   Total booking pending
    -   Total pendapatan hari ini
    -   Total pembayaran cash

### 2. **Buat Booking Baru** (`/admin/kasir/create`)

-   Form untuk membuat booking walk-in:
    -   **Data Customer**: Nama, No HP, Email (opsional)
    -   **Detail Booking**: Lapangan, Tanggal, Jam Mulai, Durasi
    -   **Metode Pembayaran**: Cash atau Transfer
-   Fitur otomatis:
    -   Real-time availability check (sama seperti customer booking)
    -   Validasi jam operasional lapangan
    -   Pencegahan overbooking
    -   Kalkulasi harga otomatis
    -   Generate kode booking format: `AFE-YYYYMMDD-XXXX`
-   Status booking: **Auto-confirmed** (tidak perlu approval)
-   Email default: `walk-in@afesport.com` jika customer tidak punya email

### 3. **Lihat Detail/Receipt** (`/admin/kasir/{id}`)

-   Menampilkan detail lengkap booking dalam format receipt
-   Informasi yang ditampilkan:
    -   Kode booking (besar dan jelas)
    -   Data customer (nama, HP, email)
    -   Detail lapangan dan jadwal
    -   Rincian pembayaran dengan total
    -   Status booking
-   Fitur cetak (print-friendly)
-   Tombol konfirmasi pembayaran untuk booking pending

### 4. **Konfirmasi Pembayaran** (`/admin/kasir/{id}/confirm`)

-   Untuk booking dengan status pending
-   Mengubah status dari pending → confirmed
-   Digunakan jika customer booking walk-in dengan transfer

## Alur Kerja

### Skenario 1: Customer Walk-in dengan Cash

1. Customer datang ke lapangan
2. Kasir/admin membuka `/admin/kasir/create`
3. Input data customer dan pilih lapangan/waktu
4. Sistem check availability real-time
5. Pilih metode pembayaran: **Cash**
6. Submit form
7. Booking langsung **confirmed**
8. Kasir terima pembayaran cash
9. Customer mendapat receipt dengan kode booking

### Skenario 2: Customer Walk-in dengan Transfer

1. Customer datang ke lapangan
2. Kasir/admin membuka `/admin/kasir/create`
3. Input data customer dan pilih lapangan/waktu
4. Sistem check availability real-time
5. Pilih metode pembayaran: **Transfer**
6. Submit form
7. Booking dibuat dengan status **pending**
8. Customer melakukan transfer
9. Kasir/admin konfirmasi pembayaran via `/admin/kasir/{id}/confirm`
10. Status berubah menjadi **confirmed**

## Validasi & Keamanan

### Validasi yang Diterapkan:

1. **Anti-Overbooking**: Cek jumlah booking existing vs `jumlah_lapangan`
2. **Jam Operasional**: Validasi `jam_mulai >= jam_buka` dan `jam_selesai <= jam_tutup`
3. **Durasi**: Minimum 1 jam, maksimum 8 jam
4. **Tanggal**: Tidak bisa booking untuk hari yang sudah lewat
5. **Data Required**: Nama pemesan, no HP, lapangan, tanggal, jam, durasi wajib diisi

### Keamanan:

-   Route dilindungi middleware `auth` dan `admin`
-   CSRF token protection untuk semua form
-   Try-catch error handling
-   Logging untuk debugging

## Routes

```php
Route::prefix('kasir')->middleware(['auth', 'admin'])->name('admin.kasir.')->group(function () {
    Route::get('/', 'KasirController@index')->name('index');                    // Daftar booking
    Route::get('/create', 'KasirController@create')->name('create');            // Form buat booking
    Route::post('/', 'KasirController@store')->name('store');                   // Simpan booking
    Route::get('/{booking}', 'KasirController@show')->name('show');             // Detail/receipt
    Route::post('/{booking}/confirm', 'KasirController@confirm')->name('confirm'); // Konfirmasi payment
    Route::post('/check-slots', 'KasirController@getAvailableSlots')->name('check-slots'); // API availability
});
```

## Database

### Booking Table Columns (Walk-in Specific):

-   `email`: Default `walk-in@afesport.com` jika tidak ada email
-   `catatan`: Otomatis `"Walk-in booking by admin/kasir"`
-   `status`: Auto `"confirmed"` untuk cash, `"pending"` untuk transfer
-   `metode_pembayaran`: `"cash"` atau `"transfer"`
-   `booking_code`: Format `AFE-YYYYMMDD-XXXX`

## Controller Methods

### KasirController.php

```php
1. index()                 // List bookings filtered by today, cash payments
2. create()                // Load lapangan for booking form
3. store()                 // Create booking with validations
4. show($booking)          // Display booking receipt
5. confirm($booking)       // Confirm pending payment
6. getAvailableSlots()     // AJAX API for slot checking
```

## Views

```
resources/views/admin/kasir/
├── index.blade.php        // Daftar booking dengan filter dan statistik
├── create.blade.php       // Form buat booking dengan real-time check
└── show.blade.php         // Receipt printable dengan detail lengkap
```

## Perbedaan dengan Booking Online

| Aspek          | Booking Online             | Walk-in Kasir                             |
| -------------- | -------------------------- | ----------------------------------------- |
| Status Awal    | `pending`                  | `confirmed` (cash) / `pending` (transfer) |
| Email          | Wajib (user login)         | Opsional (default: walk-in@afesport.com)  |
| Payment Upload | Ya (gambar bukti transfer) | Tidak (admin langsung terima)             |
| Approval Admin | Wajib                      | Tidak (auto-confirmed)                    |
| User Account   | Wajib login                | Tidak perlu                               |
| Catatan        | Isi sendiri                | Otomatis "Walk-in booking by admin/kasir" |

## Testing

### Test Case 1: Buat Booking Cash

1. Login sebagai admin: `admin@tapem.com` / `admin123`
2. Buka menu "Kasir (Walk-in)"
3. Klik "Buat Booking Baru"
4. Isi form:
    - Nama: "John Doe"
    - No HP: "081234567890"
    - Email: (kosongkan)
    - Lapangan: Pilih lapangan available
    - Tanggal: Hari ini
    - Jam Mulai: 14:00
    - Durasi: 2 Jam
    - Metode: Cash
5. Submit
6. Verifikasi status langsung **confirmed**
7. Check total harga = `harga_per_jam * durasi`

### Test Case 2: Check Overbooking Prevention

1. Buat booking untuk lapangan X jam 15:00 durasi 2 jam
2. Coba buat booking lagi untuk lapangan X jam 16:00 durasi 2 jam
3. Jika `jumlah_lapangan = 1`, sistem reject dengan pesan "Lapangan penuh"
4. Availability alert harus merah

### Test Case 3: Validasi Jam Operasional

1. Pilih lapangan dengan `jam_tutup = 23:00`
2. Pilih jam_mulai = 22:00
3. Pilih durasi = 2 jam
4. Sistem reject karena `jam_selesai (24:00) > jam_tutup (23:00)`
5. Durasi dropdown harus auto-limit maksimal 1 jam

### Test Case 4: Konfirmasi Payment Transfer

1. Buat booking dengan metode Transfer
2. Status awal = pending
3. Buka detail booking via "Kasir" menu
4. Klik tombol "Konfirmasi Pembayaran"
5. Status berubah menjadi confirmed
6. Redirect ke index dengan pesan sukses

## Troubleshooting

### Error: "Gagal mengecek ketersediaan"

**Solusi**:

-   Check console browser untuk error AJAX
-   Verify route `/check-availability` exists
-   Check `booking.check` route in web.php
-   Verify CSRF token
-   Check Laravel log: `storage/logs/laravel.log`

### Error: "Route [admin.kasir.confirm] not defined"

**Solusi**:

-   Run `php artisan route:cache`
-   Verify routes di web.php sudah benar
-   Check typo di nama route

### Booking tidak muncul di index

**Solusi**:

-   Check filter tanggal (default = hari ini)
-   Check filter status
-   Verify `metode_pembayaran` (default filter = cash & transfer)
-   Check pagination

### Email default tidak jalan

**Solusi**:

-   Form email adalah opsional
-   Jika kosong, controller auto-set ke `walk-in@afesport.com`
-   Tidak perlu validasi email required

## Future Improvements

1. 🖨️ Integrasi printer thermal untuk cetak receipt otomatis
2. 📱 QR Code di receipt untuk check-in
3. 📊 Laporan kasir per shift/periode
4. 💵 Cash drawer management
5. 🔔 Notifikasi WhatsApp untuk customer
6. 📈 Dashboard kasir dengan target harian
7. 🎫 Voucher/discount system
8. 👥 Customer loyalty points

## Catatan Penting

-   ⚠️ Route kasir dilindungi middleware `auth` dan `admin`
-   ⚠️ Semua validasi sama dengan booking customer (overbooking, jam operasional)
-   ⚠️ Email `walk-in@afesport.com` hanya identifier, tidak kirim email
-   ⚠️ Booking dengan status confirmed langsung masuk ke laporan pendapatan
-   ⚠️ Print receipt hanya menampilkan konten receipt, semua navigation hidden

---

**Developed by**: AFE Sport Development Team  
**Last Updated**: {{ date('Y-m-d') }}
