# 📝 Laporan Migrasi: EQ-Math (Native PHP ke Laravel MVC)

Dokumen ini mencatat detail teknis migrasi yang telah dilakukan untuk memastikan konsistensi arsitektur dan memudahkan pengembangan di masa mendatang.

## 🚀 Ringkasan Migrasi
Proyek ini memindahkan sistem pendaftaran kelas matematika dari PHP Native ke Laravel 11 dengan standar industri (Eloquent ORM, Blade Templating, dan Middleware).

---

## 🗄️ 1. Basis Data & Model (Eloquent)
Struktur tabel tetap dipertahankan sesuai skema asli agar kompatibel dengan data yang sudah ada, namun dikelola melalui Laravel Migration.

- **User Model (`User.php`)**:
    - Relasi: `hasMany(TransaksiPembayaran)`.
    - Atribut khusus: `nama_lengkap`, `role` (admin/siswa), `no_wa`.
- **Master Data**:
    - `MasterKelas`: Memiliki banyak `JadwalKelas`. Menggunakan `hasManyThrough` untuk menghitung jumlah siswa terdaftar secara efisien.
    - `MasterPengajar`: Terhubung ke `JadwalKelas`.
- **Transaksi**:
    - `TransaksiPembayaran`: Mencatat `order_id` (Midtrans) dan status (`pending`, `settlement`, `cancel`).

---

## 🔐 2. Autentikasi & Otoritas (Role-Based)
Sistem login menggunakan Laravel built-in Guard dengan tambahan logika Role.

- **AuthController**:
    - `login`: Menggunakan `Auth::attempt` dengan fitur "Remember Me".
    - `register`: Menggunakan `Hash::make` untuk keamanan password. Default role: `siswa`.
- **Middleware (`RoleMiddleware`)**:
    - Membatasi akses berdasarkan kolom `role` di tabel `users`.
    - Dialiaskan sebagai `'role'` di `bootstrap/app.php`.

---

## 💳 3. Integrasi Midtrans (Payment Gateway)
Migrasi logika pembayaran dari cURL manual ke Laravel HTTP Client.

- **Proses Checkout**: 
    - Snap Token didapatkan di `PaymentController@checkout`.
    - Popup Snap JS dipicu di `siswa/checkout.blade.php`.
- **Webhook / Notification Handler**:
    - Endpoint: `POST /payment/webhook`.
    - Logika: Validasi `signature_key` menggunakan SHA512 sebelum memperbarui status transaksi di database.
- **🛡️ Keamanan CSRF**: 
    - Rute webhook **dikecualikan** dari proteksi CSRF di `bootstrap/app.php` agar Midtrans bisa mengirim notifikasi.

---

## 🛣️ 4. Struktur Rute (Routing)
Rute dikelompokkan menggunakan Prefix dan Middleware untuk kerapihan.

- **Auth**: `/login`, `/register`, `/logout`.
- **Siswa (`/siswa`)**: Dashboard, Pilih Kelas, Checkout.
- **Admin (`/admin`)**: Dashboard (Statistik total siswa, pendapatan bulan ini, transaksi terbaru).
- **API/Webhook**: `/payment/webhook`.

---

## 🎨 5. Tampilan (Blade Layouting)
Frontend menggunakan **Tailwind CSS** dan **FontAwesome** yang dibungkus dalam sistem layouting Blade.

- **Layout Utama**: `layouts/app.blade.php`.
- **Direktori View**:
    - `auth/`: Login & Register.
    - `siswa/`: Dashboard & Alur pendaftaran.
    - `admin/`: Dashboard & CRUD (mendatang).

---

## ⚠️ Catatan untuk AI/Developer Berikutnya
1. **Environment**: Pastikan `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` sudah terisi di file `.env`.
2. **Role Check**: Jika ingin menambahkan halaman baru, gunakan middleware `->middleware(['auth', 'role:admin'])` atau `role:siswa`.
3. **Database Spasi**: Saat migrasi data native ke Laravel, pastikan kolom `nama_lengkap` dan `no_wa` tidak berisi spasi berlebih agar fungsi query tidak terganggu.
4. **Fonnte OTP**: Logika pengiriman OTP (Fonnte) sudah disiapkan di `auth.php` versi native, bisa diimplementasikan ke `AuthController` Laravel menggunakan `Http::post`.

---
*Dibuat oleh: Gemini CLI (Senior Laravel Developer Mode)*
*Tanggal: 21 Mei 2026*
