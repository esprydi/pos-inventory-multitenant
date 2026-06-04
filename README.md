# POS & Manajemen Inventaris Multi-Tenant (SaaS)

Sistem Point of Sale (POS) dan Manajemen Inventaris berskala Enterprise yang dibangun dengan arsitektur **Software as a Service (SaaS)** dan **Multi-Tenancy**. Proyek ini secara khusus dirancang untuk mendigitalisasi operasional berbagai jenis toko ritel maupun grosir, memungkinkan banyak entitas bisnis untuk beroperasi secara mandiri dan aman di bawah satu ekosistem aplikasi pusat.

## 🚀 Latar Belakang & Tujuan Proyek

Aplikasi ini diciptakan untuk memecahkan masalah pencatatan inventaris dan penjualan yang masih manual pada bisnis ritel dan pertokoan. Dengan pendekatan multi-tenant, penyedia layanan (Super Admin) dapat menyewakan aplikasi ini kepada banyak pemilik toko (Tenant). Setiap Tenant akan memiliki ruang kerja, data, dan konfigurasi yang terisolasi sepenuhnya.

## ✨ Fitur Unggulan

### 1. Arsitektur Multi-Tenant yang Aman
- **Isolasi Data Penuh**: Data setiap toko/tenant dipisahkan dengan aman menggunakan paket `stancl/tenancy`.
- **Subdomain Routing**: Setiap tenant mendapatkan URL/subdomain unik (contoh: `toko-a.pos-saas.test`, `toko-b.pos-saas.test`).
- **Central & Tenant Domains**: Pemisahan yang jelas antara logika aplikasi pusat (Landing Page / Area Manajemen Super Admin) dan aplikasi tenant (Dashboard POS & Inventaris).

### 2. Manajemen Inventaris Toko
- **Katalog Produk & Kategori**: Manajemen barang dagangan, pengkategorian produk, dan varian secara spesifik.
- **Pelacakan Stok Terpusat**: Pemantauan akurat untuk stok masuk (restock) dan stok keluar (penjualan).
- **Fleksibilitas Penambahan Data**: Form input yang dinamis dan aman dengan proteksi validasi.

### 3. Modul Point of Sale (POS)
- **Pemrosesan Transaksi**: Antarmuka kasir yang dioptimalkan untuk kecepatan.
- **Dukungan Transaksi Fleksibel**: Dirancang untuk menangani transaksi ritel (eceran) maupun grosir dengan efisien.

### 4. Keamanan & Autentikasi
- **Role-Based Access Control (RBAC)**: Pembatasan hak akses yang tegas.
- **Login Terisolasi**: Pengguna (karyawan/pemilik toko) login di pintu masuk (URL) yang spesifik untuk tenant mereka masing-masing.

## 🛠️ Teknologi & Stack Pengembangan

- **Backend Framework**: [Laravel 11](https://laravel.com/)
- **Multi-Tenancy Engine**: [Stancl/Tenancy for Laravel](https://tenancyforlaravel.com/)
- **Frontend / UI**: Laravel Blade, [Tailwind CSS](https://tailwindcss.com/) (atau Bootstrap), dan [Alpine.js](https://alpinejs.dev/)
- **Database**: Relational Database Management System (MySQL / MariaDB / PostgreSQL)
- **Routing**: Pendekatan Domain-based routing untuk membedakan arus pengguna secara otomatis.

## 📂 Struktur Arsitektur & Routing

Aplikasi ini memisahkan alur logika dengan sangat rapi:

- `routes/web.php`: Menangani rute aplikasi sentral. Mengatur pendaftaran tenant baru, landing page publik, dan operasi pusat.
- `routes/tenant.php`: Dieksekusi secara eksklusif dalam konteks tenant. Segala aktivitas POS, manajemen produk, kasir, dan transaksi ada di sini dan langsung berinteraksi dengan database/schema dari tenant yang aktif saat itu.

## 💻 Panduan Instalasi (Development)

Berikut adalah panduan teknis langkah demi langkah untuk melakukan *setup* aplikasi di environment lokal Anda.

### Prasyarat
Pastikan sistem Anda memenuhi spesifikasi berikut:
- PHP >= 8.2
- Composer 2.x
- Node.js (LTS version) & NPM
- Git
- MySQL / MariaDB (Disarankan)

### Langkah Instalasi

1. **Clone Repositori**
   ```bash
   git clone https://github.com/esprydi/pos-inventory-multitenant.git
   cd pos-inventory-multitenant/pos-saas
   ```

2. **Instal Dependensi Backend & Frontend**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment Variables**
   Salin file konfigurasi bawaan dan atur parameter database.
   ```bash
   cp .env.example .env
   ```
   **PENTING**: Sesuaikan blok database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_central_anda
   DB_USERNAME=root
   DB_PASSWORD=
   
   # Konfigurasi Domain Utama (Central Domain)
   CENTRAL_DOMAINS=localhost
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi Database Pusat**
   Perintah ini akan menjalankan migrasi untuk tabel *central* (seperti tabel `tenants` dan `domains`).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Kompilasi Aset Frontend**
   Bangun file CSS dan JS menggunakan Vite.
   ```bash
   npm run build
   # Atau gunakan `npm run dev` jika ingin mengaktifkan hot-reloading.
   ```

7. **Konfigurasi Domain / Hosts (Opsional untuk Testing Subdomain)**
   Agar simulasi subdomain berjalan mulus di lokal, Anda disarankan menggunakan domain statis lokal (contoh: `pos-saas.test`).
   Tambahkan baris berikut di file `hosts` OS Anda (Misal di Windows: `C:\Windows\System32\drivers\etc\hosts`):
   ```text
   127.0.0.1 pos-saas.test
   127.0.0.1 toko-a.pos-saas.test
   127.0.0.1 toko-b.pos-saas.test
   ```
   *Jika mengubah host, ubah `APP_URL` di `.env` menjadi `http://pos-saas.test` dan `CENTRAL_DOMAINS=pos-saas.test`.*

8. **Jalankan Aplikasi Lokal**
   ```bash
   php artisan serve
   ```
   Jika menggunakan domain kustom dari langkah 7:
   ```bash
   php artisan serve --host=pos-saas.test --port=8000
   ```

## 👥 Penggunaan Dasar: Membuat Tenant Pertama

Untuk melihat kehebatan Multi-Tenancy secara langsung, Anda perlu mendaftarkan sebuah tenant (toko) terlebih dahulu:

1. Buka terminal baru dan jalankan Laravel Tinker:
   ```bash
   php artisan tinker
   ```
2. Eksekusi kode pembentukan tenant berikut:
   ```php
   // Membuat tenant baru
   $tenant1 = App\Models\Tenant::create(['id' => 'toko-a']);
   
   // Mendaftarkan domain/subdomain untuk tenant tersebut
   $tenant1->domains()->create(['domain' => 'toko-a.localhost']); 
   // Gunakan 'toko-a.pos-saas.test' jika mengikuti langkah 7 di atas
   ```
3. Keluar dari tinker, lalu buka browser dan akses `http://toko-a.localhost:8000` (sesuaikan port jika berbeda). Anda akan melihat aplikasi memuat dalam konteks tenant 'toko-a'.

## 📄 Lisensi

Proyek aplikasi perangkat lunak ini didistribusikan secara *Open Source* di bawah pelindungan [Lisensi MIT](https://opensource.org/licenses/MIT).
