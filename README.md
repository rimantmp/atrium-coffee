# Atrium Coffee — Sistem Pemesanan dan Antrean

MVP aplikasi web Laravel untuk pemesanan, pembayaran, tracking, dan antrean dapur menggunakan **Non-Preemptive Priority Scheduling + FCFS + Aging**.

## Fitur yang tersedia

- Menu digital responsif dan checkout pelanggan.
- Snapshot harga/item yang dihitung server-side.
- Pembayaran tunai/QRIS simulasi dan nomor antrean harian.
- Tracking order melalui token acak dengan polling status.
- Login dan pembatasan role kasir, dapur, admin, dan pemilik.
- Dashboard dapur untuk claim order berikutnya secara atomik.
- FCFS untuk priority sama dan aging setiap 10 menit.
- Workflow `WAITING → PROCESSING → READY → COMPLETED`.
- Dashboard kasir, manajemen menu/settings admin, dan laporan ringkas pemilik.
- Status history dan log perubahan priority.

## Persyaratan

- PHP 8.3+
- Composer
- Node.js dan npm
- SQLite untuk development atau MySQL 8 untuk production

## Menjalankan aplikasi

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Untuk development frontend, jalankan `npm run dev` pada terminal terpisah.

## Akun demo

Password default seed development: `password`.

| Role | Email |
|---|---|
| Admin | `admin@atrium.test` |
| Kasir | `kasir@atrium.test` |
| Dapur | `dapur@atrium.test` |
| Pemilik | `pemilik@atrium.test` |

Ganti seluruh password sebelum penggunaan nyata. Nilai seed dapat diubah melalui `DEMO_PASSWORD`.

## Pengujian

```bash
php artisan test
vendor/bin/pint --test
npm run build
```

Test mencakup checkout dan perhitungan total, enqueue tunggal, FCFS, aging, sinkronisasi status dapur, serta pembatasan role.

## Production

Ubah koneksi database pada `.env` ke MySQL, atur `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, dan `APP_TIMEZONE=Asia/Makassar`. Gunakan HTTPS, backup database, secure cookie, process supervisor untuk queue worker, dan password unik untuk setiap staf.

Payment QRIS pada MVP adalah simulasi. Integrasi gateway nyata harus menambahkan verifikasi signature dan idempotency callback sebelum dipakai untuk transaksi produksi.

## Dokumen acuan

- `PRD.md`
- `BUSINESS_RULES.md`
- `desain_database.md`
- `SYSTEM_DESIGN.md`
- `DESIGN.md`
- `DEVELOPMENT_PLAN.md`
