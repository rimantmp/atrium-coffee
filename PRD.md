Anda adalah seorang **Senior Product Manager, System Analyst, Software Architect, dan Business Analyst** yang berpengalaman dalam merancang **Product Requirements Document (PRD)** untuk aplikasi web operasional, khususnya sistem pemesanan, antrean, restoran, kafe, dan workflow berbasis scheduling.

Tugas Anda adalah membuat sebuah dokumen bernama:

`PRD.md`

untuk sistem dengan judul:

# SISTEM PEMESANAN DAN ANTREAN BERBASIS WEB MENGGUNAKAN METODE PRIORITY SCHEDULING PADA ATRIUM COFFEE

Dokumen harus ditulis dalam format **Markdown yang rapi, profesional, terstruktur, detail, dan siap digunakan sebagai acuan pengembangan sistem**.

Gunakan bahasa Indonesia yang formal, jelas, natural, dan mudah dipahami oleh mahasiswa, developer, dosen pembimbing, maupun stakeholder nonteknis.

---

# 1. KONTEKS SISTEM

Atrium Coffee membutuhkan sistem berbasis web untuk mengelola proses:

* pemesanan pelanggan,
* pembayaran,
* pembentukan nomor antrean,
* pengaturan prioritas antrean,
* proses pengerjaan pesanan,
* pemantauan status pesanan,
* pengelolaan menu,
* pengelolaan transaksi,
* serta laporan operasional.

Sistem harus mampu mengurangi antrean manual, mengurangi kesalahan pencatatan pesanan, meningkatkan efisiensi kerja kasir dan dapur, serta memberikan transparansi status pesanan kepada pelanggan.

Metode yang digunakan dalam pengelolaan antrean adalah:

# Priority Scheduling

Priority Scheduling digunakan untuk menentukan urutan pengerjaan pesanan berdasarkan tingkat prioritas tertentu.

Sistem harus tetap memperhatikan urutan kedatangan apabila terdapat dua atau lebih pesanan dengan tingkat prioritas yang sama.

Gunakan prinsip:

`Priority Scheduling + FCFS sebagai tie-breaker`

Artinya:

1. Pesanan dengan prioritas lebih tinggi diproses terlebih dahulu.
2. Jika terdapat beberapa pesanan dengan prioritas sama, pesanan yang masuk lebih dahulu diproses terlebih dahulu.

Jelaskan konsep tersebut secara teknis dan implementatif di dalam PRD.

---

# 2. AKTOR SISTEM

Sistem memiliki lima aktor utama:

## 2.1 Pelanggan

Pelanggan dapat:

* mengakses website melalui smartphone atau perangkat lain,
* membuka sistem melalui QR Code,
* melihat daftar menu,
* melihat detail produk,
* memilih makanan/minuman,
* menambahkan pesanan ke keranjang,
* menentukan jumlah pesanan,
* menambahkan catatan pesanan,
* membuat pesanan,
* mendapatkan kode atau nomor antrean,
* melakukan pembayaran,
* melihat status pembayaran,
* melihat status pesanan,
* melihat estimasi waktu tunggu,
* melihat nomor antrean aktif,
* melihat apakah pesanan sudah siap,
* melihat riwayat pesanan apabila menggunakan akun.

---

## 2.2 Kasir

Kasir dapat:

* login ke sistem,
* melihat pesanan masuk,
* membuat pesanan untuk pelanggan yang memesan langsung di kasir,
* memverifikasi pesanan,
* menerima pembayaran,
* mengubah status pembayaran,
* mencetak atau menampilkan bukti transaksi,
* melihat antrean,
* melihat tingkat prioritas pesanan,
* membantu mengubah atau membatalkan pesanan apabila memenuhi ketentuan,
* melihat status proses pesanan,
* menandai transaksi sebagai selesai.

---

## 2.3 Dapur

Dapur bertanggung jawab terhadap proses pengerjaan pesanan makanan/minuman.

Dapur dapat:

* login ke dashboard dapur,
* melihat daftar antrean pesanan,
* melihat urutan antrean berdasarkan Priority Scheduling,
* melihat detail pesanan,
* melihat catatan pelanggan,
* melihat waktu pesanan masuk,
* melihat estimasi pengerjaan,
* melihat tingkat prioritas,
* mengambil pesanan untuk diproses,
* mengubah status pesanan menjadi `Diproses`,
* mengubah status menjadi `Siap Diambil`,
* mengubah status item apabila pengerjaan dilakukan per item,
* melihat antrean berikutnya.

---

## 2.4 Admin

Admin dapat:

* mengelola akun pengguna internal,
* mengelola role dan hak akses,
* mengelola menu,
* mengelola kategori menu,
* mengelola harga,
* mengelola foto menu,
* mengatur status menu tersedia atau habis,
* mengatur parameter Priority Scheduling,
* mengatur bobot prioritas,
* melihat seluruh transaksi,
* melihat laporan antrean,
* melihat laporan penjualan,
* mengelola konfigurasi sistem,
* melihat log aktivitas.

---

## 2.5 Pemilik

Pemilik memiliki akses monitoring dan laporan.

Pemilik dapat:

* login ke sistem,
* melihat dashboard ringkasan,
* melihat total transaksi,
* melihat omzet,
* melihat jumlah pesanan,
* melihat menu terlaris,
* melihat rata-rata waktu tunggu,
* melihat performa pelayanan,
* melihat jumlah antrean,
* melihat laporan penjualan,
* melihat laporan antrean,
* melakukan filter berdasarkan tanggal,
* melihat grafik,
* mengunduh laporan.

Pemilik tidak harus memiliki hak untuk mengubah transaksi operasional secara langsung.

---

# 3. TUJUAN UTAMA SISTEM

Tuliskan secara rinci tujuan sistem, minimal mencakup:

1. digitalisasi proses pemesanan,
2. otomatisasi antrean,
3. penerapan Priority Scheduling,
4. peningkatan efisiensi pelayanan,
5. pengurangan kesalahan pencatatan,
6. transparansi status pesanan,
7. monitoring operasional,
8. penyediaan laporan bagi pemilik,
9. peningkatan pengalaman pelanggan.

---

# 4. RUANG LINGKUP SISTEM

Buat bagian:

## In Scope

Minimal mencakup:

* autentikasi pengguna internal,
* pemesanan pelanggan,
* menu digital,
* QR Code,
* keranjang belanja,
* checkout,
* nomor antrean,
* Priority Scheduling,
* pembayaran,
* dashboard kasir,
* dashboard dapur,
* dashboard admin,
* dashboard pemilik,
* tracking status pesanan,
* estimasi waktu tunggu,
* manajemen menu,
* laporan transaksi,
* laporan antrean,
* laporan penjualan,
* role-based access control,
* responsive web design.

## Out of Scope

Berikan batasan yang realistis, misalnya:

* delivery menggunakan kurir internal,
* marketplace pihak ketiga,
* sistem payroll,
* akuntansi penuh,
* manajemen HR,
* inventory tingkat lanjut,
* loyalty program apabila belum menjadi kebutuhan utama.

---

# 5. ALUR BISNIS UTAMA

Jelaskan workflow secara step-by-step.

## 5.1 Pemesanan Melalui QR Code

Contoh alur:

Pelanggan scan QR Code
→ membuka halaman menu
→ memilih menu
→ memasukkan ke keranjang
→ checkout
→ pesanan dibuat
→ pembayaran dilakukan
→ pembayaran diverifikasi
→ sistem menentukan prioritas
→ nomor antrean dibuat
→ pesanan masuk ke dashboard dapur
→ dapur memproses
→ pesanan siap
→ pelanggan melihat status
→ pesanan diambil
→ transaksi selesai.

Jelaskan lebih detail.

---

# 6. STATUS PESANAN

Gunakan status utama seperti:

* `Menunggu Pembayaran`
* `Menunggu Verifikasi`
* `Masuk Antrean`
* `Menunggu Diproses`
* `Diproses`
* `Siap Diambil`
* `Selesai`
* `Dibatalkan`

Jelaskan:

* siapa yang dapat mengubah status,
* kondisi perubahan status,
* validasi perpindahan status.

Buat juga state transition sederhana dalam format Mermaid jika sesuai.

---

# 7. PRIORITY SCHEDULING

Ini merupakan bagian paling penting.

Jelaskan implementasi metode Priority Scheduling pada sistem.

Priority Scheduling menentukan urutan pemrosesan pesanan berdasarkan nilai prioritas.

Contoh tingkat prioritas:

* Priority 1 = Sangat Tinggi
* Priority 2 = Tinggi
* Priority 3 = Normal
* Priority 4 = Rendah

Namun jangan langsung mengasumsikan skema tersebut sebagai satu-satunya pilihan.

Berikan rekomendasi skema prioritas yang paling realistis untuk operasional Atrium Coffee.

Contoh faktor yang dapat digunakan:

* jenis pesanan,
* tingkat urgensi,
* jumlah item,
* estimasi waktu pengerjaan,
* jenis pelayanan,
* pelanggan dine-in/takeaway,
* pesanan yang sudah terlalu lama menunggu,
* prioritas khusus yang ditentukan kasir/admin.

Jelaskan juga risiko starvation.

Tambahkan mekanisme:

# Aging

Aging digunakan agar pesanan berprioritas rendah tidak terus-menerus tertunda.

Contoh:

apabila pesanan sudah menunggu melewati batas waktu tertentu, nilai prioritasnya dapat dinaikkan otomatis.

Buat contoh aturan yang realistis.

---

# 8. ALGORITMA SCHEDULING

Tuliskan pseudocode sederhana untuk proses:

1. mengambil pesanan yang statusnya siap masuk antrean,
2. menghitung atau membaca nilai priority,
3. mengurutkan berdasarkan priority,
4. menggunakan waktu masuk sebagai FCFS tie-breaker,
5. menerapkan aging jika diperlukan,
6. mengambil antrean berikutnya.

Contoh logika:

`ORDER BY priority ASC, queued_at ASC`

Apabila angka priority yang lebih kecil berarti prioritas lebih tinggi.

Jelaskan dengan bahasa yang mudah dipahami.

---

# 9. CONTOH SIMULASI PRIORITY SCHEDULING

Buat tabel simulasi minimal 6 pesanan.

Kolom:

* ID Pesanan
* Waktu Masuk
* Jenis Pesanan
* Jumlah Item
* Priority
* Estimasi Waktu
* Urutan Proses

Kemudian jelaskan mengapa urutannya demikian.

Tambahkan contoh apabila terdapat priority sama sehingga FCFS digunakan.

Tambahkan juga contoh aging.

---

# 10. ESTIMASI WAKTU TUNGGU

Sistem harus menampilkan estimasi waktu tunggu.

Jelaskan metode estimasinya.

Misalnya berdasarkan:

* antrean sebelum pelanggan,
* estimasi durasi pengerjaan,
* jumlah station dapur,
* jumlah item,
* kompleksitas menu.

Jangan memberikan formula terlalu rumit apabila tidak dibutuhkan.

Berikan formula yang realistis dan dapat diimplementasikan.

---

# 11. FUNCTIONAL REQUIREMENTS

Buat functional requirement dengan kode seperti:

`FR-001`

`FR-002`

dan seterusnya.

Kelompokkan berdasarkan modul.

Minimal:

## Authentication

## Pelanggan

## Pemesanan

## Keranjang

## Checkout

## Pembayaran

## Antrean

## Priority Scheduling

## Kasir

## Dapur

## Admin

## Pemilik

## Menu

## Laporan

## Notifikasi

## Audit Log

Tuliskan minimal 30 functional requirements.

Setiap requirement harus jelas, spesifik, dan dapat diuji.

---

# 12. NON-FUNCTIONAL REQUIREMENTS

Buat kode seperti:

`NFR-001`

Minimal membahas:

* performance,
* security,
* usability,
* reliability,
* availability,
* scalability,
* maintainability,
* responsive design,
* backup,
* auditability,
* compatibility,
* accessibility.

Berikan target yang realistis.

---

# 13. USER STORIES

Buat user stories untuk semua role.

Gunakan format:

`Sebagai [aktor], saya ingin [tujuan], sehingga [manfaat].`

Minimal:

* Pelanggan: 8 user stories
* Kasir: 6 user stories
* Dapur: 6 user stories
* Admin: 6 user stories
* Pemilik: 5 user stories

---

# 14. ACCEPTANCE CRITERIA

Untuk user story penting, buat acceptance criteria dengan format:

Given
When
Then

Prioritaskan acceptance criteria untuk:

* membuat pesanan,
* checkout,
* pembayaran,
* nomor antrean,
* Priority Scheduling,
* perubahan status dapur,
* tracking pesanan,
* pembatalan,
* laporan.

---

# 15. MODUL SISTEM

Definisikan modul utama:

1. Authentication
2. Customer Ordering
3. Menu Management
4. Cart
5. Checkout
6. Payment
7. Queue Management
8. Priority Scheduling Engine
9. Cashier Dashboard
10. Kitchen Dashboard
11. Admin Dashboard
12. Owner Dashboard
13. Reporting
14. Notifications
15. Audit Log

Jelaskan fungsi masing-masing modul.

---

# 16. DATABASE / ENTITY REQUIREMENTS

Identifikasi entity utama.

Minimal:

* users
* roles
* customers
* categories
* menus
* orders
* order_items
* payments
* queues
* queue_histories
* priority_rules
* order_status_histories
* notifications
* audit_logs

Untuk setiap entity jelaskan:

* fungsi,
* atribut penting,
* relasi dengan tabel lain.

Jangan membuat struktur database berlebihan.

---

# 17. REKOMENDASI DATABASE

Buat rancangan tabel inti yang mencakup contoh field.

Contoh:

## orders

* id
* order_number
* customer_id
* order_type
* subtotal
* total
* payment_status
* order_status
* priority
* estimated_processing_time
* ordered_at
* created_at
* updated_at

## queues

* id
* order_id
* queue_number
* priority
* queued_at
* started_at
* completed_at
* status

Sesuaikan dengan kebutuhan sebenarnya.

---

# 18. ROLE-BASED ACCESS CONTROL

Buat matriks hak akses dalam tabel.

Kolom:

* Fitur
* Pelanggan
* Kasir
* Dapur
* Admin
* Pemilik

Gunakan indikator:

* View
* Create
* Update
* Delete
* Tidak memiliki akses

Buat sesederhana tetapi informatif.

---

# 19. DASHBOARD PELANGGAN

Definisikan tampilan:

* halaman utama,
* daftar menu,
* detail menu,
* keranjang,
* checkout,
* pembayaran,
* nomor antrean,
* tracking status,
* estimasi waktu tunggu.

---

# 20. DASHBOARD KASIR

Minimal menampilkan:

* pesanan terbaru,
* pembayaran,
* antrean,
* nomor order,
* customer,
* total,
* payment status,
* order status,
* priority,
* waktu masuk.

---

# 21. DASHBOARD DAPUR

Dashboard dapur harus fokus pada efisiensi.

Tampilkan:

* current order,
* next queue,
* daftar antrean,
* prioritas,
* timer,
* waktu tunggu,
* detail menu,
* catatan pelanggan,
* tombol mulai proses,
* tombol siap diambil.

Pesanan prioritas lebih tinggi harus ditampilkan secara jelas.

---

# 22. DASHBOARD ADMIN

Minimal:

* statistik ringkas,
* manajemen menu,
* kategori,
* user,
* role,
* parameter scheduling,
* laporan,
* konfigurasi.

---

# 23. DASHBOARD PEMILIK

Minimal:

* omzet hari ini,
* omzet periode,
* jumlah transaksi,
* rata-rata transaksi,
* rata-rata waktu tunggu,
* jumlah pesanan,
* menu terlaris,
* peak hour,
* performa antrean,
* chart penjualan,
* chart jumlah pesanan.

---

# 24. LAPORAN

Sistem harus mendukung:

## Laporan Penjualan

Filter:

* hari,
* minggu,
* bulan,
* rentang tanggal.

## Laporan Antrean

Minimal:

* jumlah antrean,
* rata-rata waktu tunggu,
* waktu tunggu maksimum,
* jumlah pesanan selesai,
* jumlah dibatalkan,
* distribusi priority.

## Laporan Menu

* menu terlaris,
* jumlah penjualan per menu.

Sediakan opsi export:

* PDF
* Excel

Jika export dianggap tahap selanjutnya, tandai sebagai optional/future enhancement.

---

# 25. NOTIFIKASI

Definisikan notifikasi seperti:

* pesanan berhasil dibuat,
* pembayaran berhasil,
* pesanan mulai diproses,
* pesanan siap diambil,
* pesanan dibatalkan.

Notifikasi pada versi awal dapat menggunakan:

* alert web,
* toast,
* status realtime.

WhatsApp atau SMS dapat dimasukkan sebagai future enhancement.

---

# 26. REALTIME UPDATE

Rekomendasikan mekanisme update status menggunakan:

* polling,
* WebSocket,
* Server Sent Events,

dan jelaskan opsi terbaik untuk tahap awal.

Jangan membuat arsitektur terlalu kompleks jika sistem masih skala kedai kopi.

---

# 27. ERROR & EDGE CASES

Identifikasi minimal 15 edge cases.

Contoh:

* pelanggan membuat order ketika menu habis,
* pembayaran gagal,
* pesanan ganda,
* koneksi internet terputus,
* kasir membatalkan order,
* priority sama,
* pesanan terlalu lama menunggu,
* dapur tidak menerima update,
* pesanan sudah dibayar tetapi dibatalkan,
* menu berubah setelah masuk keranjang,
* antrean kosong,
* dua pengguna mengedit pesanan bersamaan.

Berikan penanganannya.

---

# 28. VALIDASI SISTEM

Jelaskan validasi utama:

* quantity tidak boleh nol,
* menu harus tersedia,
* total harus dihitung server-side,
* status tidak boleh dilewati sembarangan,
* pembayaran harus tervalidasi,
* order selesai tidak dapat diedit,
* priority hanya dapat diubah oleh role tertentu.

---

# 29. KEAMANAN

Berikan requirement keamanan:

* password hashing,
* authentication,
* authorization,
* CSRF protection,
* XSS protection,
* SQL Injection protection,
* server-side validation,
* session security,
* rate limiting login,
* activity logging.

---

# 30. REKOMENDASI TEKNOLOGI

Berikan rekomendasi stack yang cocok untuk proyek mahasiswa tetapi tetap profesional.

Prioritaskan:

Backend:

* Laravel

Frontend:

* Blade
* Tailwind CSS
* Alpine.js

Database:

* MySQL

Tambahkan bila diperlukan:

* Livewire
* Laravel Echo
* WebSocket

Namun jangan memaksakan teknologi kompleks jika tidak diperlukan.

Berikan rekomendasi stack:

## MVP

dan

## Advanced Version

---

# 31. ARSITEKTUR SISTEM

Rekomendasikan arsitektur:

`Client → Web Application → Business Logic → Scheduling Engine → Database`

Jelaskan hubungan antar lapisan.

Buat diagram menggunakan Mermaid.

---

# 32. FLOWCHART

Buat Mermaid flowchart untuk:

## Pemesanan Pelanggan

## Proses Antrean

## Priority Scheduling

## Dapur Memproses Pesanan

---

# 33. SEQUENCE DIAGRAM

Buat minimal 2 sequence diagram Mermaid:

1. Pelanggan melakukan pemesanan.
2. Dapur memproses antrean Priority Scheduling.

---

# 34. USE CASE

Definisikan daftar use case berdasarkan aktor:

* Pelanggan
* Kasir
* Dapur
* Admin
* Pemilik

Jika memungkinkan buat diagram Mermaid.

Jika Mermaid tidak mendukung use case secara native, gunakan flowchart yang menyerupai use case atau cukup berikan daftar use case secara sistematis.

---

# 35. MVP

Pisahkan fitur menjadi:

## Must Have

## Should Have

## Could Have

## Future Enhancement

Gunakan pendekatan MoSCoW.

---

# 36. PRIORITAS PENGEMBANGAN

Rekomendasikan tahapan:

### Phase 1 — Foundation

### Phase 2 — Ordering

### Phase 3 — Queue & Scheduling

### Phase 4 — Kitchen & Cashier

### Phase 5 — Admin & Owner

### Phase 6 — Reporting & Optimization

Jelaskan deliverable setiap fase.

---

# 37. SUCCESS METRICS

Definisikan indikator keberhasilan.

Contoh:

* pengurangan waktu tunggu,
* jumlah transaksi digital,
* persentase pesanan yang selesai tepat waktu,
* rata-rata processing time,
* error rate transaksi,
* jumlah pembatalan,
* tingkat keberhasilan pembayaran.

---

# 38. ASUMSI

Tuliskan asumsi yang digunakan dalam PRD.

Contoh:

* Atrium Coffee memiliki koneksi internet stabil,
* setiap staff memiliki akun sendiri,
* satu order memiliki satu nomor antrean,
* dapur memiliki satu workflow utama,
* pembayaran dilakukan sebelum pesanan diproses, kecuali ditentukan berbeda.

---

# 39. OPEN QUESTIONS

Buat bagian pertanyaan yang perlu dikonfirmasi sebelum development.

Misalnya:

* apakah pelanggan wajib login?
* apakah pemesanan hanya dine-in atau juga takeaway?
* apakah satu meja memiliki QR sendiri?
* metode pembayaran apa saja?
* bagaimana penentuan priority?
* apakah ada lebih dari satu kitchen station?
* apakah antrean makanan dan minuman dipisahkan?

Jangan berhenti membuat PRD hanya karena informasi tersebut belum tersedia.

Gunakan asumsi yang paling rasional dan tandai dengan jelas sebagai:

`Assumption`

atau

`Needs Confirmation`.

---

# 40. FORMAT OUTPUT

Output akhir WAJIB:

1. langsung berupa isi `PRD.md`,
2. menggunakan Markdown,
3. memiliki Table of Contents,
4. menggunakan heading yang konsisten,
5. menggunakan tabel jika lebih mudah dibaca,
6. menggunakan code block untuk pseudocode,
7. menggunakan Mermaid untuk diagram,
8. tidak menggunakan bahasa promosi,
9. tidak membuat requirement yang abstrak,
10. setiap requirement harus dapat diterjemahkan menjadi fitur development.

Gunakan struktur dokumen kurang lebih:

```markdown
# Product Requirements Document

## Sistem Pemesanan dan Antrean Berbasis Web Menggunakan Metode Priority Scheduling pada Atrium Coffee

## 1. Document Information
## 2. Executive Summary
## 3. Background
## 4. Problem Statement
## 5. Objectives
## 6. Stakeholders
## 7. User Roles
## 8. Scope
## 9. Business Process
## 10. Priority Scheduling
## 11. Functional Requirements
## 12. Non-Functional Requirements
## 13. User Stories
## 14. Acceptance Criteria
## 15. System Modules
## 16. Data Model
## 17. RBAC
## 18. UI / Dashboard Requirements
## 19. Reports
## 20. Error & Edge Cases
## 21. Security
## 22. Architecture
## 23. System Diagrams
## 24. MVP & Prioritization
## 25. Development Roadmap
## 26. Success Metrics
## 27. Assumptions
## 28. Open Questions
```

---

# INSTRUKSI KUALITAS

Sebelum memberikan hasil akhir, lakukan pemeriksaan internal terhadap PRD.

Pastikan:

* kebutuhan bisnis dan kebutuhan teknis tidak bertentangan,
* alur status pesanan konsisten,
* Priority Scheduling benar-benar digunakan dalam workflow, bukan hanya dijelaskan secara teori,
* tidak ada role yang memiliki hak akses tidak wajar,
* requirement dapat diimplementasikan menggunakan Laravel,
* desain sistem tidak terlalu kompleks untuk skala Atrium Coffee,
* struktur database tidak over-engineered,
* setiap fitur utama memiliki tujuan bisnis yang jelas,
* sistem dapat dikembangkan secara bertahap dari MVP,
* Priority Scheduling memiliki mekanisme untuk mencegah starvation.

Apabila terdapat beberapa pilihan desain sistem, pilih solusi yang:

1. paling sederhana,
2. paling realistis,
3. mudah diimplementasikan,
4. mudah diuji,
5. sesuai untuk proyek akademik,
6. tetapi tetap mengikuti praktik pengembangan perangkat lunak yang baik.

Jangan menampilkan proses berpikir internal.

Tampilkan hanya hasil akhir berupa dokumen `PRD.md`.
