# Blueprint Pengembangan E-Commerce (Studi Kasus: Toko Bunga)

Dokumen ini adalah rangkuman komprehensif dari seluruh proses, fitur, sistem keamanan, dan pengujian yang diperlukan untuk membangun website *e-commerce* seperti proyek Fleure Flower ini dari awal hingga akhir. Anda dapat menjadikan dokumen ini sebagai cetak biru (blueprint) jika ingin membuat proyek serupa di masa depan.

---

## 1. Arsitektur & Teknologi (Tech Stack)
*   **Backend Framework:** Laravel (PHP)
*   **Frontend UI:** Laravel Blade, Tailwind CSS v4 (untuk styling), JavaScript Native / Alpine.js (untuk interaktivitas)
*   **Database:** MySQL atau SQLite
*   **Asset Bundler:** Vite (untuk kompilasi dan optimasi CSS/JS)
*   **Pihak Ketiga:** Midtrans API (Payment Gateway), Google OAuth (Social Login), Phosphor Icons (Ikon), Chart.js (Grafik Admin).

---

## 2. Fitur-Fitur Utama (Features)

### A. Sisi Pelanggan (Kustomer)
*   **Autentikasi & Profil:**
    *   Registrasi dan Login menggunakan Email/Password.
    *   Login menggunakan akun Google (OAuth).
    *   Halaman profil: Manajemen Avatar, Bio, Nomor Telepon, dan Histori Pesanan.
    *   *Limitasi Bisnis:* Nama kustomer hanya dapat diedit maksimal 1 kali dalam 24 jam.
*   **Katalog & Belanja:**
    *   Halaman beranda (*Hero section*, Desain Terbaru, Koleksi Pilihan).
    *   Halaman koleksi produk dengan Filter (Kategori) dan Pencarian (*Search*).
    *   Sistem Keranjang Belanja (*Cart*) yang dinamis dan terhubung dengan _session_ atau database.
*   **Pemesanan & Pembayaran (Checkout):**
    *   Pengisian form pengiriman (alamat, kota, catatan).
    *   Integrasi *Payment Gateway* (Midtrans) sehingga pengguna bisa membayar via Transfer Bank, e-Wallet (GoPay, OVO), atau Kartu Kredit.
    *   Tombol *shortcut* WhatsApp otomatis (membawa data produk) untuk opsi pemesanan manual.

### B. Sisi Pengelola (Admin Panel)
*   **Dashboard Statistik:** 
    *   Ringkasan jumlah produk, pesanan tertunda, dan pesanan selesai.
    *   Grafik analitik pendapatan (Harian, Mingguan, Bulanan) menggunakan Chart.js.
*   **Manajemen Produk (CRUD):** Tambah, edit, hapus produk (Nama, Kategori, Harga, Deskripsi, Upload Gambar, Status: Tersedia / Sold Out).
*   **Manajemen Pesanan:** Melihat detail pelanggan, bukti transaksi, dan merubah status pesanan secara *real-time* (*Pending -> Paid -> Processing -> Shipped -> Completed*).
*   **Pengaturan Toko:** Mengubah nama toko dan nomor WhatsApp pusat.

---

## 3. Standar Keamanan (Security Measures)

Sistem *e-commerce* menangani uang dan data pribadi, sehingga keamanan adalah prioritas mutlak. Berikut daftar keamanannya:

1.  **Proteksi Formulir (CSRF & XSS):**
    *   Laravel otomatis menggunakan token `@csrf` di setiap form untuk mencegah *Cross-Site Request Forgery*.
    *   Blade templating `{{ $data }}` otomatis menetralkan tag HTML asing (*escaping*) untuk mencegah serangan *Cross-Site Scripting (XSS)*.
2.  **Pencegahan SQL Injection:** 
    *   Selalu gunakan perlindungan ORM bawaan (Laravel Eloquent) alih-alih melakukan kueri SQL mentah.
3.  **Rate Limiting (Anti-Spam & Bruteforce):**
    *   Membatasi batas percobaan login gagal (misal: blokir 1 menit jika 5x salah password).
    *   Membatasi frekuensi *update* profil (limitasi fitur).
4.  **Validasi Server-Side yang Ketat:**
    *   Semua input (teks, gambar, angka) divalidasi di backend (misalnya: file harus berformat `jpg/png/svg` dan tidak lebih dari 2MB, nama tidak boleh mengandung karakter aneh).
5.  **Pessimistic Locking (Keamanan Transaksi Uang):**
    *   **Penting:** Saat menerima *Webhook* dari Payment Gateway, kueri database harus dikunci (`lockForUpdate()`) agar jika ada dua sinyal pembayaran datang di detik (milidetik) yang bersamaan, sistem tidak membaca data yang salah atau menggandakan status pembayaran (*Race Condition*).

---

## 4. Standar Pengujian (Testing & QA)

Sebelum aplikasi diluncurkan ke publik, pengujian berikut wajib dilakukan:

1.  **Unit Testing:**
    *   Menguji fungsi-fungsi kecil secara otomatis (Misal: Memastikan logika potongan diskon berjalan benar, memastikan kueri pendapatan harian Chart tidak menghitung pesanan yang dibatalkan).
2.  **Feature / End-to-End (E2E) Testing:**
    *   Menguji alur manusia. Mulai dari: Login -> Masukkan barang ke keranjang -> Masuk halaman checkout -> Klik bayar -> Lakukan simulasi pembayaran Midtrans Sandbox -> Pastikan status di database berubah menjadi "Paid".
3.  **UI/UX & Responsiveness Testing:**
    *   Memastikan tidak ada *overflow* (elemen keluar batas) di berbagai ukuran layar: *Mobile* (HP 320px), Tablet (768px), dan Desktop (1080px+).
    *   Pengujian *Navbar Sticky* dan *Sidebar Admin* di perangkat *touchscreen*.
4.  **Performance Auditing (Lighthouse):**
    *   Mengoptimalkan ukuran gambar (kompresi/WebP) dan *lazy-loading*.
    *   Menggunakan *bundler* seperti Vite untuk meminifikasi CSS & JavaScript agar *loading* website cepat (*First Contentful Paint* di bawah 2 detik).
