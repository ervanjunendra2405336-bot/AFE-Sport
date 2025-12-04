# 🏟️ AFE Sport - Multi-Sport Booking System

Sistem booking lapangan olahraga berbasis web dengan fitur lengkap untuk admin dan customer. Mendukung berbagai jenis olahraga (Futsal, Basket, Badminton, Tenis, Voli, Mini Soccer, Padel).

---

## 📋 Daftar Isi

1. [Fitur Utama](#fitur-utama)
2. [Teknologi](#teknologi)
3. [Struktur Database](#struktur-database)
4. [Instalasi](#instalasi)
5. [Konfigurasi](#konfigurasi)
6. [Fitur Detail](#fitur-detail)
7. [Panduan Penggunaan](#panduan-penggunaan)
8. [API Endpoints](#api-endpoints)
9. [Troubleshooting](#troubleshooting)

---

## 🎯 Fitur Utama

### **Customer Features (Tanpa Login)**

-   ✅ Browse lapangan berdasarkan kategori olahraga
-   ✅ Filter lapangan (kategori, kota, harga)
-   ✅ Detail lapangan dengan foto dan fasilitas
-   ✅ **Real-time availability check** (cek slot tersedia)
-   ✅ **Anti-overbooking system** (validasi jumlah lapangan)
-   ✅ **Validasi jam operasional** (otomatis limit durasi)
-   ✅ Booking tanpa registrasi (hanya perlu nama, email, telepon)
-   ✅ Upload bukti pembayaran
-   ✅ Konfirmasi booking via email

### **Admin Features**

-   ✅ **Dashboard Analytics** dengan Chart.js:
    -   Total pendapatan
    -   Total lapangan
    -   Total booking
    -   Lapangan terpopuler
-   ✅ **Manajemen Lapangan:**
    -   CRUD lapangan (Create, Read, Update, Delete)
    -   Upload gambar lapangan
    -   Set jam operasional (jam buka - jam tutup)
    -   Set jumlah lapangan (multiple courts)
    -   Kategori olahraga
-   ✅ **Manajemen Booking:**
    -   Lihat semua booking
    -   Konfirmasi/tolak booking
    -   Update status booking
    -   Filter by status, date, lapangan
-   ✅ **Transaksi Management:**
    -   Periode filtering (hari ini, minggu ini, bulan ini, custom)
    -   Statistics: total transaksi, pendapatan, status breakdown
    -   Export data
-   ✅ **Analytics Dashboard:**
    -   Grafik pendapatan harian (7 hari terakhir)
    -   Lapangan terpopuler (Top 5)
    -   Kategori olahraga terpopuler
    -   Jam tersibuk
    -   Metode pembayaran (Cash vs Transfer)
-   ✅ **Verifikasi Payment:**
    -   Review bukti transfer
    -   Approve/reject payment

---

## 🛠️ Teknologi

### **Backend**

-   **Framework:** Laravel 11
-   **Database:** SQLite (production-ready, dapat diganti MySQL/PostgreSQL)
-   **Authentication:** Laravel Breeze (admin only)
-   **Image Processing:** Laravel File Upload

### **Frontend**

-   **Template Engine:** Blade
-   **CSS:** Custom CSS with Orange Theme (#e67e22)
-   **JavaScript:** Vanilla JS + AJAX
-   **Charts:** Chart.js 3.x
-   **Icons:** Font Awesome 6

### **Development**

-   **PHP:** 8.2+
-   **Composer:** 2.x
-   **Node.js:** 18+ (untuk Vite)
-   **Server:** XAMPP / Laravel Valet / Sail

---

## 💾 Struktur Database

### **Tabel Utama**

#### **1. sport_categories** (Kategori Olahraga)

```sql
- id: INTEGER PRIMARY KEY
- nama: VARCHAR (Futsal, Basket, Tenis, etc)
- slug: VARCHAR (futsal, basket, tenis)
- deskripsi: TEXT
- icon: VARCHAR (emoji/icon class)
- warna: VARCHAR (hex color untuk badge)
- created_at, updated_at: TIMESTAMP
```

#### **2. lapangan** (Venue/Courts)

```sql
- id: INTEGER PRIMARY KEY
- sport_category_id: INTEGER (FK to sport_categories)
- nama: VARCHAR (nama lapangan)
- kode_lapangan: VARCHAR (unique code)
- deskripsi: TEXT
- tipe: VARCHAR (indoor/outdoor)
- lokasi: VARCHAR
- kota: VARCHAR
- alamat: VARCHAR
- harga_per_jam: NUMERIC (weekday price)
- harga_weekend: NUMERIC (weekend price)
- foto: VARCHAR (path to image)
- galeri: TEXT (JSON array of images)
- fasilitas: TEXT (JSON array: WiFi, Parkir, etc)
- kapasitas: INTEGER (max players)
- ukuran: VARCHAR (dimensi lapangan)
- aturan: TEXT
- jumlah_lapangan: INTEGER (jumlah court tersedia)
- jam_buka: TIME (06:00:00)
- jam_tutup: TIME (23:00:00)
- tersedia: BOOLEAN (1=tersedia, 0=tidak)
- rating: INTEGER (1-5)
- jumlah_review: INTEGER
- created_at, updated_at: TIMESTAMP
```

#### **3. bookings** (Booking Transactions)

```sql
- id: INTEGER PRIMARY KEY
- lapangan_id: INTEGER (FK to lapangan)
- user_id: INTEGER NULLABLE (FK to users, null untuk guest)
- booking_code: VARCHAR (AFE-20250127-A1B2)
- nama_pemesan: VARCHAR
- email: VARCHAR
- no_hp: VARCHAR
- telepon: VARCHAR
- tanggal_booking: DATE
- jam_mulai: TIME
- jam_selesai: TIME
- durasi_jam: INTEGER
- total_harga: NUMERIC
- status: VARCHAR (pending, confirmed, cancelled, completed)
- metode_pembayaran: VARCHAR (cash, transfer)
- bukti_pembayaran: VARCHAR (path to image)
- catatan: TEXT
- created_at, updated_at: TIMESTAMP
```

#### **4. users** (Admin Users)

```sql
- id: INTEGER PRIMARY KEY
- name: VARCHAR
- email: VARCHAR UNIQUE
- password: VARCHAR (hashed)
- role: VARCHAR (admin, customer)
- phone: VARCHAR
- email_verified_at: TIMESTAMP
- remember_token: VARCHAR
- created_at, updated_at: TIMESTAMP
```

**Default Admin:**

-   Email: `admin@tapem.com`
-   Password: `admin123`
-   Role: `admin`

---

## 📦 Instalasi

### **1. Clone Repository**

```bash
cd d:\xampp\htdocs
git clone [repository-url] TAPEMWEB
cd TAPEMWEB
```

### **2. Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### **3. Environment Setup**

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### **4. Database Setup**

```bash
# Create SQLite database
type nul > database\database.sqlite

# Run migrations
php artisan migrate

# Seed data (admin user + sample data)
php artisan db:seed
```

### **5. Build Assets**

```bash
# Development
npm run dev

# Production
npm run build
```

### **6. Start Server**

```bash
# Laravel development server
php artisan serve

# Akses aplikasi
# Website: http://127.0.0.1:8000
# Admin Panel: http://127.0.0.1:8000/admin/login
```

---

## ⚙️ Konfigurasi

### **1. Database Configuration (.env)**

```env
DB_CONNECTION=sqlite
DB_DATABASE=D:\xampp\htdocs\TAPEMWEB\database\database.sqlite
```

Alternatif MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afe_sport
DB_USERNAME=root
DB_PASSWORD=
```

### **2. Application Settings**

```env
APP_NAME="AFE Sport"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

### **3. Session Configuration**

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### **4. File Upload Settings**

```env
FILESYSTEM_DISK=public
```

Link storage:

```bash
php artisan storage:link
```

---

## 🎨 Fitur Detail

### **1. Anti-Overbooking System**

**Problem:** Jika lapangan punya 3 court, bisa 4 orang booking di jam yang sama?

**Solution:**

```php
// Count existing bookings at same time
$existingBookings = Booking::where('lapangan_id', $id)
    ->where('tanggal_booking', $tanggal)
    ->whereIn('status', ['pending', 'confirmed'])
    ->where(function($query) use ($jamMulai, $jamSelesai) {
        $query->where('jam_mulai', '<', $jamSelesai)
              ->where('jam_selesai', '>', $jamMulai);
    })
    ->count();

// Reject if full
if ($existingBookings >= $lapangan->jumlah_lapangan) {
    return error("Semua lapangan penuh");
}
```

**Real-time Check:**

-   AJAX check saat user mengisi form
-   Tampilkan: "✅ Tersedia 2 dari 3 lapangan"
-   Disable submit button jika penuh

---

### **2. Validasi Jam Operasional**

**Problem:** User booking jam 21:00 selama 3 jam, tapi lapangan tutup jam 23:00?

**Solution:**

```php
// Validate closing time
if ($jamSelesai->format('H:i') > $jamTutup->format('H:i')) {
    $maxDurasi = $jamTutup->diffInHours($jamMulai);
    return error("Lapangan tutup jam {$jamTutup}. Maksimal durasi: {$maxDurasi} jam");
}
```

**Frontend Smart Control:**

-   Jam mulai dropdown: hanya 06:00 - 22:00 (tidak 23:00)
-   Durasi auto-adjust:
    -   Jam 21:00 → durasi max 2 jam
    -   Jam 22:00 → durasi max 1 jam
    -   Jam 20:00 → durasi max 3 jam

---

### **3. Real-time Availability Check**

**AJAX Endpoint:**

```javascript
POST /check-availability
{
    "lapangan_id": 1,
    "tanggal_booking": "2025-11-28",
    "jam_mulai": "14:00",
    "durasi_jam": 2
}

Response:
{
    "available": true,
    "slot_tersedia": 2,
    "total_lapangan": 3,
    "existing_bookings": 1,
    "message": "✅ Tersedia 2 dari 3 lapangan"
}
```

**Triggers:**

-   Change tanggal → check
-   Change jam → check + update durasi options
-   Change durasi → check

---

### **4. Dynamic Pricing**

```php
// Weekend detection
$isWeekend = Carbon::parse($tanggal)->isWeekend();

// Apply pricing
$hargaPerJam = $isWeekend
    ? $lapangan->harga_weekend
    : $lapangan->harga_per_jam;

$totalHarga = $hargaPerJam * $durasi;
```

---

### **5. Image Management System**

**Category-based Naming:**

```
futsal1.jpg, futsal2.jpg, futsal3.jpg
basket1.jpg, basket2.jpg
badminton1.jpg, badminton2.jpg
tenis1.jpg, tenis2.jpg
voli1.jpg, voli2.jpg
minisoccer1.jpg, minisoccer2.jpg, minisoccer3.jpg
padel1.jpg
```

**Mapping Script:**

```php
// update-images-by-category.php
$categoryMapping = [
    'Futsal' => 'futsal',
    'Basket' => 'basket',
    'Badminton' => 'badminton',
    'Tenis' => 'tenis',
    'Voli' => 'voli',
    'Mini Soccer' => 'minisoccer',
    'Padel' => 'padel'
];
```

Lihat: [README_GAMBAR_KATEGORI.md](README_GAMBAR_KATEGORI.md)

---

### **6. Analytics Dashboard**

**5 Chart.js Graphs:**

1. **Pendapatan Harian** (Line Chart)
    - 7 hari terakhir
    - Total pendapatan per hari
2. **Lapangan Terpopuler** (Bar Chart)
    - Top 5 lapangan by total booking
3. **Kategori Terpopuler** (Doughnut Chart)
    - Breakdown by sport category
4. **Jam Tersibuk** (Bar Chart)
    - Peak hours analysis
    - SQLite: `strftime('%H', jam_mulai)`
5. **Metode Pembayaran** (Pie Chart)
    - Cash vs Transfer ratio

---

## 📖 Panduan Penggunaan

### **A. Customer Flow**

#### **1. Browse Lapangan**

```
Homepage → Kategori Olahraga → List Lapangan → Detail Lapangan
```

**Filter Options:**

-   Kategori olahraga
-   Kota
-   Range harga

#### **2. Booking Process**

```
Detail Lapangan → Klik "Booking" → Isi Form → Check Availability → Konfirmasi → Payment → Success
```

**Form Fields:**

-   Nama Lengkap \*
-   Email \*
-   Nomor Telepon \*
-   Tanggal Booking \* (min: hari ini)
-   Jam Mulai \* (auto-limit by jam operasional)
-   Durasi \* (auto-adjust by jam tutup)
-   Catatan (optional)

**Real-time Validation:**

-   ✅ Slot tersedia
-   ❌ Slot penuh
-   ❌ Melewati jam tutup

#### **3. Payment**

```
Booking Success → Upload Bukti Transfer → Submit → Pending Confirmation
```

**Payment Methods:**

-   Cash (bayar di tempat)
-   Transfer (upload bukti)

#### **4. Booking Code**

```
Format: AFE-YYYYMMDD-XXXX
Contoh: AFE-20250127-A1B2
```

---

### **B. Admin Flow**

#### **1. Login**

```
URL: http://127.0.0.1:8000/admin/login
Email: admin@tapem.com
Password: admin123
```

#### **2. Dashboard**

```
Overview Cards:
- 💰 Total Pendapatan
- 🏟️ Total Lapangan
- 📋 Total Booking

Recent Bookings Table
Lapangan Terpopuler
```

#### **3. Manajemen Lapangan**

**Create Lapangan:**

```
Lapangan → Tambah Lapangan

Required Fields:
- Nama Lapangan *
- Kategori Olahraga *
- Jumlah Lapangan * (1-10)
- Kota *
- Alamat *
- Deskripsi *
- Harga per Jam *
- Harga Weekend
- Jam Buka * (default: 06:00)
- Jam Tutup * (default: 23:00)
- Fasilitas (comma-separated)
- Status * (Tersedia/Tidak Tersedia)
- Upload Gambar
```

**Edit/Delete:**

-   Edit: Update any field
-   Delete: Soft delete (recommended) atau hard delete

#### **4. Manajemen Booking**

**Filter Options:**

```
- Status: All, Pending, Confirmed, Cancelled, Completed
- Lapangan: All, Specific Court
- Tanggal: Date range
- Search: Booking code, Nama pemesan
```

**Actions:**

```
- View Detail
- Confirm Booking (pending → confirmed)
- Cancel Booking (any → cancelled)
- Complete Booking (confirmed → completed)
```

**Bulk Actions:**

-   Select multiple → Confirm All
-   Select multiple → Export CSV

#### **5. Verifikasi Payment**

```
Booking (Pending) → View → Lihat Bukti Transfer → Approve/Reject

Approve:
- Status: pending → confirmed
- Send notification email

Reject:
- Status: pending → cancelled
- Reason: Invalid payment proof
```

#### **6. Transaksi Management**

**Periode Filter:**

```
- Hari Ini
- Minggu Ini
- Bulan Ini
- Semua
- Custom (pilih range)
```

**Statistics Display:**

```
- Total Transaksi: 150
- Total Pendapatan: Rp 15.000.000
- Pending: 12
- Confirmed: 120
- Cancelled: 18
- Transfer: 80
- Cash: 70
```

#### **7. Analytics**

**Access:** Admin Panel → Analitik

**Refresh Data:**

-   Auto-refresh setiap 5 menit
-   Manual refresh button

**Export:**

-   Download chart as PNG
-   Export data as Excel

---

## 🔌 API Endpoints

### **Public Endpoints (Customer)**

#### **Homepage**

```
GET /
Response: View homepage with categories
```

#### **Lapangan List**

```
GET /lapangan
Query Params:
- category: sport_category_id
- kota: string
- harga_min: integer
- harga_max: integer
```

#### **Lapangan Detail**

```
GET /lapangan/{id}
Response: View lapangan detail with booking button
```

#### **Create Booking**

```
GET /booking/create/{lapangan_id}
Response: Booking form view
```

```
POST /booking/store
Body:
- lapangan_id
- nama_pemesan
- email
- telepon
- tanggal_booking
- jam_mulai
- durasi_jam
- catatan

Response: Redirect to payment page
```

#### **Check Availability (AJAX)**

```
POST /check-availability
Headers:
- X-CSRF-TOKEN: token

Body:
{
    "lapangan_id": 1,
    "tanggal_booking": "2025-11-28",
    "jam_mulai": "14:00",
    "durasi_jam": 2
}

Response:
{
    "available": boolean,
    "slot_tersedia": integer,
    "total_lapangan": integer,
    "existing_bookings": integer,
    "message": string
}
```

#### **Payment**

```
GET /booking/payment/{booking_id}
Response: Payment form view

POST /booking/payment/{booking_id}
Body:
- metode_pembayaran: cash|transfer
- bukti_pembayaran: file (required if transfer)

Response: Redirect to success page
```

#### **Booking Success**

```
GET /booking/success/{booking_id}
Response: Success page with booking code
```

---

### **Protected Endpoints (Admin)**

**Auth Middleware:** `auth`, `admin`

#### **Admin Dashboard**

```
GET /admin
Response: Dashboard with statistics and charts
```

#### **Lapangan Management**

```
GET /admin/lapangan
Response: List all lapangan with filter

GET /admin/lapangan/create
Response: Create form

POST /admin/lapangan
Body: Form data with image upload

GET /admin/lapangan/{id}/edit
Response: Edit form

PUT /admin/lapangan/{id}
Body: Updated form data

DELETE /admin/lapangan/{id}
Response: Redirect with success message
```

#### **Booking Management**

```
GET /admin/booking
Query: status, lapangan_id, date_from, date_to

GET /admin/booking/{id}
Response: Booking detail

POST /admin/booking/{id}/confirm
Response: Update status to confirmed

POST /admin/booking/{id}/cancel
Response: Update status to cancelled
```

#### **Transaksi**

```
GET /admin/transaksi
Query: periode (hari-ini|minggu-ini|bulan-ini|semua|custom)
       date_from, date_to

Response: Statistics + transaction list
```

#### **Analytics**

```
GET /admin/analitik
Response: Charts dashboard

GET /admin/analitik/pendapatan-harian
Response: JSON data for line chart

GET /admin/analitik/lapangan-terpopuler
Response: JSON data for bar chart

GET /admin/analitik/kategori-terpopuler
Response: JSON data for doughnut chart

GET /admin/analitik/jam-tersibuk
Response: JSON data for bar chart

GET /admin/analitik/metode-pembayaran
Response: JSON data for pie chart
```

---

## 🎨 Customization

### **1. Ganti Logo**

```bash
# Copy logo baru
copy logo-baru.png public\images\logo.png

# Backup logo lama (optional)
copy public\images\logo.png public\images\logo-backup.png
```

Specs:

-   Format: PNG with transparency
-   Size: 200x50 - 400x100px (landscape)
-   Used in: Navbar, Footer, Admin Sidebar, Login Header

Lihat: [README_LOGO.md](README_LOGO.md)

---

### **2. Ganti Gambar Lapangan**

```bash
# Copy gambar baru sesuai kategori
copy gambar-futsal-1.jpg public\images\futsal1.jpg
copy gambar-futsal-2.jpg public\images\futsal2.jpg
copy gambar-basket-1.jpg public\images\basket1.jpg
```

Specs:

-   Format: JPEG
-   Size: 800x600px
-   Ratio: 4:3
-   File size: 200-500 KB

Lihat: [README_GAMBAR_KATEGORI.md](README_GAMBAR_KATEGORI.md)

---

### **3. Ubah Theme Color**

File: `public/css/style.css`

```css
/* Current: Orange Theme */
--primary-color: #e67e22;
--primary-dark: #d35400;

/* Gradient backgrounds */
background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);

/* To change: Find & Replace all occurrences */
```

Popular alternatives:

-   Blue: `#3498db` → `#2980b9`
-   Green: `#2ecc71` → `#27ae60`
-   Purple: `#9b59b6` → `#8e44ad`
-   Red: `#e74c3c` → `#c0392b`

---

### **4. Tambah Kategori Olahraga**

```bash
php artisan tinker
```

```php
DB::table('sport_categories')->insert([
    'nama' => 'Golf',
    'slug' => 'golf',
    'deskripsi' => 'Lapangan golf mini',
    'icon' => '⛳',
    'warna' => '#27ae60',
    'created_at' => now(),
    'updated_at' => now()
]);
```

---

### **5. Update Jam Operasional Default**

File: `database/migrations/2025_01_15_000000_add_jam_operasional_to_lapangan_table.php`

```php
// Change default values
$table->time('jam_buka')->default('07:00:00'); // Was 06:00:00
$table->time('jam_tutup')->default('22:00:00'); // Was 23:00:00
```

Apply:

```bash
php artisan migrate:refresh --path=database/migrations/2025_01_15_000000_add_jam_operasional_to_lapangan_table.php
```

---

## 🐛 Troubleshooting

### **1. Error 419 PAGE EXPIRED**

**Problem:** CSRF token expired setelah idle

**Solution:**

```bash
# Clear sessions
php artisan session:table
php -r "DB::table('sessions')->truncate();"

# Or via SQL
DELETE FROM sessions;

# Restart browser
```

---

### **2. Gambar Tidak Muncul**

**Problem:** Image path incorrect

**Check:**

1. File exists: `public/images/futsal1.jpg`
2. Database path: `images/futsal1.jpg` (tanpa `/` di depan)
3. Asset helper: `asset($lapangan->foto)`

**Fix:**

```bash
# Re-run image update script
php update-images-by-category.php

# Check file permissions
icacls public\images /grant Everyone:F /T
```

---

### **3. Real-time Check Tidak Bekerja**

**Problem:** AJAX request fail

**Check Console:**

```javascript
// Open DevTools (F12) → Console
// Look for errors

// Common issues:
- CSRF token missing
- Route not found
- JSON parse error
```

**Fix:**

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Check route
php artisan route:list | Select-String "check-availability"
```

---

### **4. Database Lock Error (SQLite)**

**Problem:** `database is locked`

**Solution:**

```bash
# Close all DB connections
# Stop all php artisan serve

# Use DB browser to close connections
# Or switch to MySQL
```

**Switch to MySQL:**

```env
# .env
DB_CONNECTION=mysql
DB_DATABASE=afe_sport
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate:fresh --seed
```

---

### **5. Chart Tidak Muncul**

**Problem:** Chart.js not loaded

**Fix:**

```blade
<!-- Add to layout head -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Check console for errors -->
```

---

### **6. Upload Gambar Gagal**

**Problem:** File size too large atau format tidak didukung

**Fix:**

```php
// config/filesystems.php
'max_file_size' => 2048, // 2MB in KB

// Validation
'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
```

**Increase PHP limits:**

```ini
; php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

---

### **7. Email Tidak Terkirim**

**Problem:** Mail configuration not set

**Setup Mail:**

```env
# .env - Using Mailtrap (development)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@afesport.com
MAIL_FROM_NAME="AFE Sport"
```

**Test:**

```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

---

## 📊 Performance Tips

### **1. Cache Configuration**

```bash
# Cache config for production
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all cache
php artisan optimize:clear
```

---

### **2. Database Optimization**

**Add Indexes:**

```sql
-- bookings table
CREATE INDEX idx_bookings_lapangan_tanggal ON bookings(lapangan_id, tanggal_booking);
CREATE INDEX idx_bookings_status ON bookings(status);

-- lapangan table
CREATE INDEX idx_lapangan_category ON lapangan(sport_category_id);
CREATE INDEX idx_lapangan_kota ON lapangan(kota);
```

---

### **3. Image Optimization**

```bash
# Compress images
magick convert futsal1.jpg -quality 75 futsal1-compressed.jpg

# Batch compress
Get-ChildItem public\images\*.jpg | ForEach-Object {
    magick convert $_.FullName -quality 75 -resize 800x600^ $_.FullName
}
```

---

### **4. Enable Query Caching**

```php
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\Cache;

public function boot()
{
    // Cache lapangan list for 10 minutes
    if ($this->app->environment('production')) {
        View::composer('lapangan.index', function($view) {
            $lapangan = Cache::remember('lapangan.all', 600, function() {
                return Lapangan::with('sportCategory')->get();
            });
            $view->with('lapangan', $lapangan);
        });
    }
}
```

---

## 🚀 Deployment

### **Production Checklist**

```bash
# 1. Update .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# 2. Optimize
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Build assets
npm run build

# 4. Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app/public

# 5. Migrate database
php artisan migrate --force

# 6. Seed production data
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=SportCategorySeeder
```

---

### **Shared Hosting (cPanel)**

1. **Upload files:**

    - Upload all files to `public_html/`
    - Move `public` folder contents to root

2. **Database:**

    - Create MySQL database via cPanel
    - Update `.env` with credentials
    - Import database via phpMyAdmin

3. **.htaccess redirect:**

```apache
# public_html/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### **VPS (Ubuntu)**

```bash
# Install dependencies
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-sqlite3 php8.2-mbstring php8.2-xml nginx

# Clone project
cd /var/www
git clone [repo] afe-sport

# Setup
cd afe-sport
composer install --no-dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Nginx config
sudo nano /etc/nginx/sites-available/afe-sport

# Restart services
sudo systemctl restart php8.2-fpm nginx
```

---

## 📞 Support & Documentation

### **Quick Links**

-   📘 [Logo Replacement Guide](README_LOGO.md)
-   🖼️ [Image Management Guide](README_GAMBAR_KATEGORI.md)
-   🏟️ [Futsal Category Guide](README_FUTSAL.md)
-   🎯 [Multi-Sport System](README_MULTISPORT.md)
-   📋 [All Documentation](README_INDEX.md)

### **Contact**

-   **Email:** info@afesport.com
-   **Admin Panel:** http://127.0.0.1:8000/admin
-   **Website:** http://127.0.0.1:8000

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 🙏 Credits

**Developed by:** AFE Sport Development Team  
**Framework:** Laravel 11  
**Charts:** Chart.js  
**Icons:** Font Awesome  
**Last Updated:** 2025-01-27

---

**Happy Coding! 🚀**
