# Task dan Progres Pengembangan

## Ringkasan Status

Dokumen ini mencatat pekerjaan yang telah diimplementasikan dan diverifikasi pada proyek Sistem Pemesanan dan Antrean Atrium Coffee. Status di bawah didasarkan pada kode, route, migration, tampilan, dan pengujian yang tersedia pada aplikasi.

Status saat ini:

- MVP fungsional: sekitar 95%.
- Kesiapan produksi: sekitar 50%.
- Aplikasi sudah dapat digunakan sebagai demo atau prototipe MVP.
- Aplikasi belum dinyatakan siap digunakan pada lingkungan produksi.

## Pekerjaan yang Telah Diselesaikan

### 1. Foundation dan Konfigurasi Aplikasi

- [x] Membuat proyek Laravel.
- [x] Menyiapkan konfigurasi environment development.
- [x] Menyiapkan Composer dan autoload aplikasi.
- [x] Menyiapkan Vite sebagai frontend build tool.
- [x] Menyiapkan Tailwind CSS.
- [x] Menyiapkan struktur asset JavaScript dan CSS.
- [x] Membuat production frontend build.
- [x] Menyiapkan PHPUnit untuk automated test.
- [x] Menyiapkan Laravel Pint untuk formatting kode PHP.
- [x] Memasang dan mengonfigurasi Laravel Boost.
- [x] Membuat dokumentasi instalasi dan penggunaan dasar pada `README.md`.

### 2. Database dan Data Awal

- [x] Membuat migration pengguna, cache, dan job queue Laravel.
- [x] Membuat migration tabel inti Atrium Coffee.
- [x] Membuat tabel kategori dan menu.
- [x] Membuat tabel order dan order item.
- [x] Membuat tabel pembayaran.
- [x] Membuat tabel antrean.
- [x] Membuat daily counter untuk nomor antrean.
- [x] Membuat order status history dan queue history.
- [x] Membuat priority change log.
- [x] Membuat system settings.
- [x] Membuat audit log.
- [x] Menambahkan foreign key, unique constraint, dan index utama.
- [x] Membuat model Eloquent untuk data inti.
- [x] Membuat enum status order, pembayaran, dan antrean.
- [x] Membuat factory pengguna.
- [x] Membuat seeder akun dan data demo.
- [x] Menjalankan seluruh migration pada database development.

### 3. Authentication dan Role Access

- [x] Membuat halaman login pengguna internal.
- [x] Membuat proses login dan logout.
- [x] Melakukan regenerasi session setelah login.
- [x] Memblokir login untuk akun tidak aktif.
- [x] Menyimpan waktu login terakhir pengguna.
- [x] Membuat middleware pemeriksaan role.
- [x] Membatasi halaman berdasarkan role Admin, Kasir, Dapur, dan Pemilik.
- [x] Membuat redirect dashboard sesuai role pengguna.
- [x] Menambahkan rate limit pada proses login.
- [x] Membuat administrasi pengguna internal.
- [x] Membuat tambah, edit, reset password, aktivasi/nonaktivasi, dan soft delete pengguna.
- [x] Membatasi role pengguna ke Admin, Kasir, Dapur, dan Pemilik.
- [x] Melindungi Admin agar tidak dapat menonaktifkan, menurunkan role, atau menghapus akun sendiri.
- [x] Mencatat perubahan pengguna pada audit log tanpa menyimpan password.

### 4. Menu dan Pemesanan Pelanggan

- [x] Membuat halaman katalog menu publik.
- [x] Menampilkan menu berdasarkan kategori aktif.
- [x] Menampilkan status ketersediaan menu.
- [x] Membuat form pemilihan item dan jumlah pesanan.
- [x] Mendukung catatan per item dan catatan pesanan.
- [x] Mendukung tipe pesanan dine-in dan takeaway.
- [x] Mendukung nama, nomor telepon, dan nomor meja pelanggan.
- [x] Membuat validasi checkout pada server.
- [x] Memastikan menu aktif dan tersedia sebelum order dibuat.
- [x] Menghitung ulang harga dan total pesanan pada server.
- [x] Menyimpan snapshot nama, harga, jumlah, dan estimasi item.
- [x] Membuat nomor order unik.
- [x] Membuat tracking token acak untuk pelanggan.
- [x] Menyimpan status history awal order.
- [x] Menambahkan token idempotensi unik pada checkout publik.
- [x] Memastikan pengiriman checkout berulang mengembalikan order yang sama tanpa menduplikasi item atau history.
- [x] Menambahkan rate limit pada checkout.

### 5. Pembayaran dan Pembuatan Antrean

- [x] Membuat pilihan pembayaran tunai dan QRIS simulasi.
- [x] Membuat payment record ketika pembayaran berhasil.
- [x] Menyinkronkan payment status dan order status.
- [x] Membuat daily counter secara transaksional.
- [x] Membuat nomor antrean harian dengan format `A001`, `A002`, dan seterusnya.
- [x] Memastikan satu order hanya menghasilkan satu antrean.
- [x] Memblokir pembayaran order yang telah dibatalkan.
- [x] Menyimpan history dan audit ketika order dibayar dan masuk antrean.
- [x] Memisahkan pengajuan pembayaran pelanggan dari verifikasi pembayaran kasir.
- [x] Menahan pembuatan antrean sampai pembayaran diverifikasi.
- [x] Membuat verifikasi pembayaran kasir yang idempoten.
- [x] Menampilkan pembayaran pending pada dashboard kasir.
- [x] Membuat refund penuh untuk pembayaran yang sudah lunas.
- [x] Membatasi refund pada order yang masih menunggu dalam antrean.
- [x] Membatalkan order dan antrean secara atomik saat refund.
- [x] Membuat refund idempoten dan menyimpan alasan, petugas, nominal, serta audit log.

### 6. Priority Scheduling dan Workflow Dapur

- [x] Membuat `SchedulingService`.
- [x] Menerapkan base priority dengan rentang 1 sampai 3.
- [x] Menerapkan FCFS untuk antrean dengan prioritas yang sama.
- [x] Menerapkan aging berdasarkan interval konfigurasi.
- [x] Menetapkan minimum effective priority.
- [x] Mengurutkan antrean berdasarkan effective priority, waktu antre, dan ID.
- [x] Membuat claim antrean berikutnya di dalam database transaction.
- [x] Mengunci kandidat antrean ketika proses claim berlangsung.
- [x] Membuat alur status `WAITING -> PROCESSING -> READY -> COMPLETED`.
- [x] Menyinkronkan status order dengan status antrean.
- [x] Menyimpan waktu mulai proses, siap, dan selesai.
- [x] Menyimpan order history dan queue history pada perubahan status.
- [x] Membuat dashboard antrean dapur.
- [x] Membuat aksi claim order berikutnya.
- [x] Membuat aksi tandai pesanan siap.

### 7. Tracking Pelanggan

- [x] Membuat halaman tracking berdasarkan token pesanan.
- [x] Menampilkan nomor order dan nomor antrean.
- [x] Menampilkan status terbaru pesanan.
- [x] Membuat endpoint JSON status pesanan.
- [x] Membuat pembaruan status menggunakan polling.
- [x] Menambahkan rate limit pada endpoint status.

### 8. Operasional Kasir

- [x] Membuat dashboard kasir.
- [x] Menampilkan statistik order hari ini.
- [x] Menampilkan jumlah antrean menunggu, siap, dan selesai.
- [x] Membuat daftar order dengan pagination.
- [x] Membuat aksi penyelesaian order yang sudah siap.
- [x] Membuat pembatalan order dengan alasan wajib.
- [x] Membatasi pembatalan berdasarkan status order dan antrean.
- [x] Membuat perubahan priority manual dengan alasan wajib.
- [x] Menyimpan perubahan priority pada log.
- [x] Membuat form pesanan walk-in pada dashboard kasir.
- [x] Menghitung harga pesanan walk-in pada server.
- [x] Membuat pembayaran dan antrean walk-in secara transaksional.

### 9. Dashboard Admin dan Pemilik

- [x] Membuat dashboard Admin.
- [x] Menampilkan ringkasan jumlah menu, menu tersedia, order, dan omzet.
- [x] Membuat CRUD kategori lengkap dengan proteksi kategori yang masih digunakan.
- [x] Membuat tambah, edit, dan soft delete menu.
- [x] Membuat upload dan validasi foto menu JPG, PNG, dan WebP maksimal 2 MB.
- [x] Membuat toggle ketersediaan menu.
- [x] Membuat pengaturan default priority.
- [x] Membuat pengaturan minimum priority.
- [x] Membuat pengaturan interval aging dan batas overdue.
- [x] Membuat dashboard Pemilik.
- [x] Menampilkan omzet, jumlah order, rata-rata order, dan rata-rata waktu tunggu.
- [x] Menampilkan daftar menu terlaris.
- [x] Menampilkan transaksi terbaru.
- [x] Membuat filter laporan berdasarkan rentang tanggal dan timezone Asia/Makassar.
- [x] Membuat ringkasan omzet, transaksi, rata-rata transaksi, waktu tunggu, antrean menunggu, dan antrean selesai.
- [x] Memastikan laporan omzet hanya menghitung transaksi berstatus lunas.
- [x] Membuat laporan menu terlaris beserta jumlah item dan nilai penjualan.
- [x] Membuat export laporan transaksi CSV yang dapat dibuka di Excel.

### 10. Audit dan Keamanan Dasar

- [x] Membuat `AuditService`.
- [x] Mencatat pembayaran dan pembuatan antrean.
- [x] Mencatat perubahan status ready dan completed.
- [x] Mencatat pembatalan beserta alasan.
- [x] Mencatat perubahan priority beserta alasan dan pengguna.
- [x] Menggunakan CSRF protection pada form Laravel.
- [x] Menggunakan transaksi database pada operasi kritis utama.
- [x] Menggunakan tracking token yang tidak menampilkan ID order langsung.
- [x] Menambahkan rate limiting pada endpoint publik utama.
- [x] Membuat request ID UUID untuk setiap request dan mengirimkannya pada header `X-Request-ID`.
- [x] Menambahkan structured log untuk method, path, status, durasi, pengguna, dan request ID.
- [x] Membuat halaman audit log khusus Admin.
- [x] Membuat filter audit berdasarkan aksi, jenis entitas, dan periode.
- [x] Memastikan nilai audit yang ditampilkan tetap di-escape.

### 11. Notification Outbox

- [x] Membuat tabel dan model notification outbox.
- [x] Membuat event notifikasi untuk status order `READY` dan `COMPLETED`.
- [x] Menjamin satu event bisnis hanya membuat satu record outbox.
- [x] Mengirim job setelah database transaction berhasil commit.
- [x] Membuat queued worker yang unik dan idempoten.
- [x] Menambahkan retry, backoff, timeout, attempt counter, status gagal, dan pesan error.
- [x] Menyimpan waktu pemrosesan event notifikasi.

### 12. Monitoring Operasional

- [x] Membuat endpoint health check publik dengan rate limit.
- [x] Membatasi respons health check publik agar tidak membocorkan detail internal.
- [x] Memeriksa koneksi database dan fungsi cache aplikasi.
- [x] Membuat dashboard monitoring khusus Admin.
- [x] Menampilkan jumlah failed jobs dan queued jobs.
- [x] Mendeteksi notification outbox gagal atau pending terlalu lama.
- [x] Mendeteksi pembayaran pending lebih dari 15 menit.
- [x] Mendeteksi antrean overdue berdasarkan konfigurasi sistem.
- [x] Menampilkan status sistem `HEALTHY`, `DEGRADED`, atau `UNHEALTHY`.

### 13. Continuous Integration

- [x] Membuat workflow GitHub Actions untuk push dan pull request ke branch `main`.
- [x] Membatasi permission workflow ke akses baca repository.
- [x] Menambahkan concurrency control agar workflow lama dibatalkan ketika commit baru masuk.
- [x] Menambahkan validasi Composer dan audit advisory keamanan dependency.
- [x] Menambahkan pemeriksaan format PHP menggunakan Laravel Pint.
- [x] Menjalankan seluruh automated test Laravel dalam pipeline.
- [x] Menginstal dependency frontend secara deterministik menggunakan `npm ci`.
- [x] Membuat production frontend build dalam pipeline.

### 14. Redesign UI/UX

- [x] Melakukan audit struktur Blade, layout, route navigasi, CSS, JavaScript, dan dependency frontend.
- [x] Membuat design system Tailwind CSS v4 dengan palet indigo, tipografi, card, form, button, badge, tabel, dan alert yang konsisten.
- [x] Membuat sidebar internal reusable dengan ikon, profil pengguna, active state berdasarkan route, dan drawer mobile.
- [x] Membuat top navbar reusable dengan judul halaman, breadcrumb, profil, dan logout.
- [x] Membuat alert validasi/session serta footer reusable.
- [x] Merapikan statistik dashboard Admin dan Kasir menggunakan card responsif.
- [x] Mendesain ulang halaman audit log dengan filter, badge, responsive table, pagination, dan empty state.
- [x] Menyelaraskan halaman monitoring operasional dengan design system baru.
- [x] Mendesain ulang halaman login menggunakan layout autentikasi responsive.
- [x] Menyelaraskan layout pelanggan dengan identitas visual baru.
- [x] Mempertahankan route, controller, model, database, business logic, form field, dan selector JavaScript yang ada.

## Hasil Verifikasi Terakhir

- [x] PHP 8.4 tersedia.
- [x] Composer 2.9 tersedia.
- [x] Seluruh migration berstatus `Ran`.
- [x] Terdapat 36 route aplikasi non-vendor.
- [x] Automated test berhasil: 36 test dan 146 assertion.
- [x] Frontend production build berhasil menggunakan `npm run build`.
- [x] Laravel package discovery berhasil.
- [x] Composer configuration valid dan tidak memiliki advisory keamanan yang diketahui.

## Pekerjaan yang Selesai Sebagian

- [x] Manajemen kategori dan menu telah mencakup tambah, edit, soft delete, ketersediaan, status aktif, dan upload foto.
- [~] RBAC dan pengujian endpoint Admin sudah berjalan, tetapi Policy terpisah dan matriks authorization seluruh aksi belum lengkap.
- [~] Pembayaran tunai dan QRIS tersedia sebagai simulasi, tetapi belum menggunakan payment gateway nyata.
- [~] Dashboard role utama tersedia, tetapi beberapa fungsi operasional lanjutan belum tersedia.
- [x] Laporan pemilik mencakup filter periode, KPI penjualan/antrean, menu terlaris, dan export CSV.
- [~] Audit telah mencakup beberapa aksi kritis, tetapi belum seluruh perubahan admin dan konfigurasi.
- [~] Test mencakup alur utama, FCFS, aging, sinkronisasi status, dan pembatasan role, tetapi coverage belum mencukupi untuk production.
- [~] Tampilan responsif dan mobile sidebar telah dibuat, tetapi belum menjalani UAT perangkat nyata dan accessibility review lengkap.

## Pekerjaan yang Belum Diselesaikan

- [ ] Integrasi payment gateway QRIS nyata dan validasi callback.
- [ ] Backup dan restore rehearsal.
- [ ] CD/deployment pipeline setelah target hosting ditentukan.
- [ ] Staging dan deployment production.
- [ ] Concurrency test pada database MySQL.
- [ ] Test idempotency payment callback setelah gateway nyata dipilih.
- [ ] Security review lengkap.
- [ ] Responsive, accessibility, dan browser UAT.
- [ ] UAT bersama stakeholder dan perbaikan defect.
- [ ] Training staf, runbook, rollback plan, dan handover.

## Catatan Kesiapan

Aplikasi sudah mampu mendemonstrasikan alur utama berikut:

```text
Menu -> Checkout -> Pembayaran Simulasi -> Nomor Antrean
-> Claim Dapur -> Ready -> Completed -> Tracking Pelanggan
```

Sebelum digunakan untuk transaksi nyata, payment gateway, pengujian concurrency MySQL, keamanan, backup, monitoring, UAT, dan prosedur deployment harus diselesaikan terlebih dahulu.
