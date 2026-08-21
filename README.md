# KAMI Kompeten

Aplikasi Laravel untuk mengelola informasi pelatihan, sertifikasi, layanan, artikel, portofolio, pesan kontak, dan pendaftaran. Konten publik hanya tampil bila berstatus terbit, waktu terbit telah lewat, dan status verifikasinya `verified`.

## Persyaratan

- PHP 8.3+ beserta ekstensi umum Laravel (`ctype`, `curl`, `dom`, `fileinfo`, `filter`, `mbstring`, `openssl`, `pdo`, `session`, `tokenizer`, dan `xml`)
- Composer 2
- Node.js LTS dan npm
- MySQL 8.0+ atau MariaDB 10.6+ untuk produksi
- Worker antrean untuk notifikasi email

## Instalasi lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buat basis data terlebih dahulu, lalu sesuaikan `DB_*` di `.env`. SQLite dapat digunakan untuk pengembangan cepat; produksi disarankan memakai MySQL/MariaDB:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_basis_data
DB_USERNAME=pengguna_basis_data
DB_PASSWORD=kata_sandi_yang_kuat
```

Pastikan `APP_URL` berisi URL HTTPS kanonis tanpa garis miring penutup dan `APP_TIMEZONE=Asia/Jakarta` bila sesuai operasi organisasi. Jangan simpan `.env` di Git.

## Administrator

Tidak ada akun atau kata sandi bawaan. Setelah migrasi, jalankan perintah interaktif berikut di lingkungan tujuan:

```bash
php artisan app:create-admin
```

Kata sandi diminta melalui masukan tersembunyi dan tidak diteruskan sebagai argumen shell. Perintah menolak email yang sudah ada agar tidak menaikkan hak akun secara tidak sengaja.

## Penyimpanan, email, dan antrean

Jalankan `php artisan storage:link` agar gambar pada disk `public` dapat diakses. Dokumen pendaftaran dan dokumen legal disimpan di disk privat `local` dan hanya diunduh melalui rute admin terotorisasi.

Konfigurasikan `MAIL_*`, alamat pengirim, serta penerima notifikasi melalui panel admin. Notifikasi memakai antrean:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Kelola worker dengan systemd, Supervisor, atau layanan proses setara. Jalankan ulang worker setiap rilis dengan `php artisan queue:restart`.

## Pengembangan dan pengujian

```bash
npm run dev
composer test
```

Suite fitur menggunakan basis data pengujian yang ditentukan di `phpunit.xml`. Sebelum rilis, jalankan:

```bash
php artisan test
./vendor/bin/pint --test
npm run build
```

## Deployment produksi

1. Arahkan document root ke direktori `public/`.
2. Pasang dependensi dengan `composer install --no-dev --prefer-dist --optimize-autoloader`.
3. Atur `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, basis data, cache, sesi, antrean, dan SMTP.
4. Jalankan `php artisan migrate --force` dan `php artisan storage:link`.
5. Bangun aset dengan `npm ci && npm run build` (atau unggah aset hasil build tepercaya).
6. Jalankan `php artisan optimize` dan mulai ulang worker antrean.
7. Beri hak tulis hanya pada `storage/` dan `bootstrap/cache/`.
8. Jadwalkan `php artisan schedule:run` setiap menit bila tugas terjadwal ditambahkan:

```cron
* * * * * cd /path/aplikasi && php artisan schedule:run >> /dev/null 2>&1
```

Saat deployment, gunakan maintenance mode bila diperlukan (`php artisan down --secret=...` lalu `php artisan up`), cadangkan basis data, dan siapkan prosedur rollback.

## Verifikasi konten dan operasional

- Isi nama badan hukum, alamat, kontak, dan identitas organisasi hanya dari sumber resmi.
- Jangan menerbitkan klaim akreditasi, kemitraan LSP, testimoni, profil, atau portofolio sebelum diverifikasi.
- Schema.org organisasi hanya dihasilkan dari nilai pengaturan yang terisi; analitik tidak dimuat otomatis sebelum integrasi persetujuan pengguna diterapkan.
- Uji pengiriman email, worker antrean, tautan WhatsApp, URL kanonis, sitemap, dan halaman kesalahan pada domain produksi.
- Ganti favicon/logo bawaan melalui pengaturan dan pastikan semua gambar mempunyai izin penggunaan.
- PDF legal, materi program, serta aset merek tidak disertakan otomatis. Unggah dokumen resmi dan periksa semua tautan unduhan sebelum peluncuran.

## Keamanan

Gunakan HTTPS, kata sandi unik, akun basis data dengan hak minimum, pencadangan terenkripsi, rotasi rahasia, dan pembatasan akses panel admin. Konten teks kaya saat ini ditampilkan sebagai teks biasa yang di-escape; jangan mengubahnya menjadi HTML mentah tanpa sanitasi tepercaya.
