Anda adalah seorang **Senior Business Analyst, System Analyst, Software Architect, dan Backend Engineer** yang berpengalaman merancang aturan bisnis untuk sistem pemesanan, antrean, restoran/kafe, dan aplikasi berbasis scheduling.

Tugas Anda adalah membuat dokumen:

`BUSINESS_RULES.md`

untuk sistem:

# SISTEM PEMESANAN DAN ANTREAN BERBASIS WEB MENGGUNAKAN METODE PRIORITY SCHEDULING PADA ATRIUM COFFEE

Dokumen ini harus menjadi **sumber aturan bisnis utama** yang digunakan oleh developer saat mengimplementasikan sistem dengan Laravel.

Jika file `PRD.md` tersedia di project, baca dan jadikan sebagai referensi utama. Pastikan seluruh aturan dalam `BUSINESS_RULES.md` konsisten dengan `PRD.md`.

Jangan mengubah tujuan sistem yang sudah ditetapkan dalam PRD.

---

# 1. TUJUAN DOKUMEN

Jelaskan bahwa `BUSINESS_RULES.md` digunakan untuk:

* mendefinisikan aturan operasional sistem,
* menentukan kondisi dan validasi setiap proses,
* menjadi acuan implementasi backend,
* menghindari inkonsistensi logika,
* memastikan Priority Scheduling berjalan sesuai kebutuhan,
* menjadi referensi untuk testing dan UAT.

---

# 2. AKTOR SISTEM

Gunakan aktor berikut:

1. Pelanggan
2. Kasir
3. Dapur
4. Admin
5. Pemilik

Jelaskan secara ringkas tanggung jawab bisnis masing-masing aktor.

---

# 3. FORMAT BUSINESS RULE

Setiap aturan harus menggunakan kode unik.

Gunakan format:

`BR-XXX`

Contoh:

`BR-001`

`BR-002`

`BR-003`

Untuk setiap business rule gunakan struktur:

| Field         | Isi    |
| ------------- | ------ |
| Rule ID       | BR-001 |
| Nama Aturan   | ...    |
| Deskripsi     | ...    |
| Aktor Terkait | ...    |
| Kondisi       | ...    |
| Aksi Sistem   | ...    |
| Validasi      | ...    |
| Exception     | ...    |

Jika suatu aturan sederhana, boleh menggunakan tabel ringkas.

---

# 4. ATURAN UMUM SISTEM

Buat aturan mengenai:

* setiap transaksi harus memiliki nomor order unik,
* setiap order aktif memiliki nomor antrean,
* semua nilai harga dihitung server-side,
* pelanggan tidak dapat mengubah total transaksi,
* status pesanan harus mengikuti state transition yang valid,
* semua perubahan penting dicatat,
* semua operasi berdasarkan role,
* waktu transaksi menggunakan waktu server,
* pesanan selesai tidak boleh diedit sembarangan.

---

# 5. BUSINESS RULE PEMESANAN

Definisikan aturan pemesanan secara detail.

Minimal mencakup:

* pelanggan dapat memesan tanpa akun jika guest checkout digunakan,
* pelanggan hanya dapat memilih menu yang tersedia,
* quantity minimal 1,
* quantity harus bilangan bulat,
* menu habis tidak dapat dipesan,
* harga diambil dari database,
* perubahan harga setelah item masuk keranjang harus divalidasi saat checkout,
* catatan pesanan bersifat opsional,
* satu order dapat memiliki banyak item,
* total dihitung dari seluruh item,
* order belum dianggap valid sebelum checkout selesai,
* duplicate submission harus dicegah,
* pelanggan tidak boleh membuat transaksi dengan keranjang kosong.

Beri ID masing-masing aturan.

---

# 6. BUSINESS RULE QR CODE

Jika pemesanan menggunakan QR Code, buat aturan:

* QR dapat mengarahkan pelanggan ke halaman menu,
* jika QR mewakili meja, sistem dapat mengenali nomor meja,
* QR tidak boleh berisi informasi sensitif,
* QR yang tidak valid harus diarahkan ke halaman aman,
* pelanggan tetap dapat mengakses menu tanpa QR jika kebijakan Atrium Coffee mengizinkan.

Jika penggunaan QR per meja belum dipastikan, tandai sebagai:

`Needs Confirmation`.

---

# 7. BUSINESS RULE CHECKOUT

Definisikan aturan checkout.

Minimal:

* keranjang tidak boleh kosong,
* item harus divalidasi ulang,
* harga harus divalidasi ulang,
* ketersediaan menu harus dicek ulang,
* total harus dihitung ulang server-side,
* order number dibuat setelah checkout berhasil,
* order tidak boleh dibuat dua kali akibat refresh,
* sistem harus menggunakan transaksi database agar order dan order_items konsisten.

---

# 8. BUSINESS RULE PEMBAYARAN

Definisikan aturan pembayaran.

Gunakan status pembayaran seperti:

* `UNPAID`
* `PENDING`
* `PAID`
* `FAILED`
* `REFUNDED`
* `CANCELLED`

Jelaskan aturan:

* pesanan tidak masuk antrean produksi sebelum memenuhi kondisi pembayaran yang ditetapkan,
* pembayaran tunai dapat diverifikasi kasir,
* pembayaran digital hanya dianggap berhasil setelah status tervalidasi,
* total pembayaran harus sama dengan total order,
* pembayaran tidak boleh diproses dua kali,
* pembayaran gagal dapat dicoba ulang,
* pembayaran sukses harus mencatat waktu pembayaran,
* perubahan status pembayaran harus dicatat dalam history atau audit log.

Jika metode pembayaran aktual belum diketahui, tandai:

`Needs Confirmation`.

---

# 9. KAPAN PESANAN MASUK ANTREAN

Ini adalah aturan kritis.

Definisikan bahwa order hanya dapat masuk antrean jika:

1. order valid,
2. memiliki item,
3. pembayaran telah memenuhi syarat,
4. order tidak dibatalkan,
5. menu masih dapat diproses.

Tentukan status awal antrean.

Contoh:

`WAITING`

Jelaskan bahwa nomor antrean dibuat satu kali dan tidak boleh berubah kecuali terdapat alasan bisnis khusus.

---

# 10. PRIORITY SCHEDULING

Ini merupakan bagian paling penting.

Sistem menggunakan:

# Priority Scheduling + FCFS Tie-Breaker + Aging

Definisikan secara eksplisit:

* setiap order memiliki nilai priority,
* semakin kecil angka priority berarti semakin tinggi prioritas,
* sistem mengurutkan antrean berdasarkan priority terlebih dahulu,
* apabila priority sama, gunakan `queued_at` paling awal,
* apabila queued_at sama, gunakan `queue_id` atau `order_id` terkecil sebagai deterministic tie-breaker.

Contoh:

```text
ORDER BY effective_priority ASC, queued_at ASC, id ASC
```

---

# 11. LEVEL PRIORITY

Gunakan level awal berikut sebagai rekomendasi:

| Priority | Level         | Arti                            |
| -------- | ------------- | ------------------------------- |
| 1        | Sangat Tinggi | Kondisi khusus                  |
| 2        | Tinggi        | Pesanan prioritas               |
| 3        | Normal        | Pesanan standar                 |
| 4        | Rendah        | Pesanan dengan prioritas rendah |

Namun Priority 1 tidak boleh diberikan sembarangan.

Definisikan siapa yang dapat menetapkan atau mengubah priority.

Rekomendasi:

* pelanggan tidak dapat mengubah priority,
* kasir hanya dapat memilih dari opsi tertentu,
* admin dapat mengatur rule,
* dapur tidak dapat menaikkan priority tanpa alasan yang valid,
* semua perubahan priority manual harus dicatat.

---

# 12. PENENTUAN PRIORITY

Jelaskan bahwa priority tidak boleh ditentukan secara arbitrer.

Buat aturan berdasarkan faktor yang realistis.

Contoh:

## Priority 1

Hanya untuk kasus khusus yang telah ditentukan Atrium Coffee.

Contoh:

* pesanan yang sebelumnya gagal diproses karena kesalahan sistem,
* remake akibat kesalahan internal,
* keputusan supervisor/admin.

## Priority 2

Contoh:

* pesanan tertentu yang memiliki layanan prioritas resmi,
* pesanan yang sudah mengalami keterlambatan signifikan.

## Priority 3

Default seluruh pesanan normal.

## Priority 4

Gunakan hanya jika Atrium Coffee memang memiliki kategori layanan yang lebih rendah.

Jika tidak diperlukan, rekomendasikan agar sistem cukup menggunakan Priority 1–3.

Jangan membuat aturan diskriminatif terhadap pelanggan.

---

# 13. DEFAULT PRIORITY

Tetapkan:

`default_priority = 3`

Semua pesanan baru mendapatkan Priority Normal kecuali memenuhi rule tertentu.

Priority tidak boleh ditentukan berdasarkan:

* status sosial,
* penampilan pelanggan,
* kedekatan dengan staff,
* alasan subjektif lainnya.

---

# 14. FCFS TIE-BREAKER

Definisikan:

Jika dua atau lebih pesanan memiliki priority yang sama:

* pesanan dengan `queued_at` lebih awal diproses terlebih dahulu.

Contoh:

Order A:

* Priority 3
* 10:01

Order B:

* Priority 3
* 10:04

Maka:

A diproses sebelum B.

---

# 15. AGING

Priority Scheduling memiliki risiko starvation.

Gunakan Aging.

Definisikan aturan awal yang realistis.

Contoh:

* setiap 10 menit menunggu, effective priority dapat naik satu tingkat,
* priority tidak boleh lebih tinggi dari batas tertentu,
* perubahan karena aging bersifat otomatis.

Contoh:

Order:

* base_priority = 3
* menunggu 10 menit → effective_priority = 2
* menunggu 20 menit → effective_priority = 1

Namun base priority tidak berubah.

Gunakan konsep:

`base_priority`

dan

`effective_priority`

Jelaskan perbedaannya.

Formula sederhana yang direkomendasikan:

```text
aging_steps = floor(waiting_minutes / aging_interval)

effective_priority = max(
    minimum_priority,
    base_priority - aging_steps
)
```

Contoh:

```text
aging_interval = 10 menit
minimum_priority = 1
```

Tandai angka interval sebagai configurable.

---

# 16. ATURAN STARVATION PREVENTION

Buat aturan bahwa:

* tidak boleh ada order aktif yang menunggu tanpa batas,
* aging harus dievaluasi saat sistem menentukan next queue,
* admin dapat mengubah interval aging,
* perubahan konfigurasi tidak boleh mengubah order yang sudah selesai,
* order dengan waktu tunggu ekstrem harus ditandai sebagai overdue.

---

# 17. PREEMPTIVE VS NON-PREEMPTIVE

Gunakan:

# Non-Preemptive Priority Scheduling

Jelaskan:

Jika dapur sudah mulai mengerjakan suatu order, order tersebut tidak dihentikan hanya karena ada pesanan dengan priority lebih tinggi yang baru masuk.

Pesanan baru akan menjadi kandidat berikutnya setelah proses aktif selesai.

Ini penting untuk operasional dapur.

---

# 18. PEMILIHAN NEXT ORDER

Buat aturan:

Ketika dapur siap mengambil order berikutnya:

1. ambil semua queue dengan status `WAITING`,
2. hitung effective priority,
3. urutkan berdasarkan effective priority,
4. gunakan FCFS,
5. ambil satu order teratas,
6. lakukan locking,
7. ubah status menjadi `PROCESSING`.

Gunakan database locking atau atomic operation untuk mencegah dua staff mengambil order yang sama.

---

# 19. CONCURRENCY RULES

Definisikan aturan:

* satu order tidak boleh diproses oleh dua worker tanpa mekanisme pembagian item,
* sistem harus mencegah race condition,
* perubahan status harus menggunakan validasi status saat ini,
* operasi claim order sebaiknya atomic,
* gunakan database transaction jika diperlukan.

---

# 20. NOMOR ANTREAN

Definisikan aturan:

* nomor antrean dibuat otomatis,
* mudah dibaca pelanggan,
* unik minimal dalam periode yang ditentukan,
* nomor antrean tidak sama dengan primary key database,
* nomor antrean tidak boleh berubah setelah diberikan.

Contoh format:

`A001`

atau:

`ORD-20260830-001`

Beri rekomendasi format yang paling sederhana.

---

# 21. STATUS ANTREAN

Gunakan status:

* `WAITING`
* `PROCESSING`
* `READY`
* `COMPLETED`
* `CANCELLED`

Definisikan transisi legal:

```text
WAITING → PROCESSING
PROCESSING → READY
READY → COMPLETED

WAITING → CANCELLED
```

Untuk pembatalan setelah PROCESSING, buat aturan khusus.

---

# 22. STATUS ORDER

Gunakan status yang konsisten dengan PRD.

Contoh:

* `PENDING_PAYMENT`
* `WAITING_VERIFICATION`
* `QUEUED`
* `PROCESSING`
* `READY`
* `COMPLETED`
* `CANCELLED`

Jelaskan hubungan order status dan queue status.

Hindari duplikasi status yang membingungkan.

---

# 23. STATE TRANSITION RULES

Buat tabel:

| Current Status | Allowed Next Status | Actor | Condition |
| -------------- | ------------------- | ----- | --------- |

Untuk seluruh status utama.

Jangan izinkan perubahan seperti:

`COMPLETED → PROCESSING`

kecuali terdapat mekanisme reopen yang resmi.

---

# 24. BUSINESS RULE DAPUR

Definisikan:

* dapur hanya melihat order yang siap diproses,
* order harus mengikuti scheduling,
* dapur tidak boleh memilih order secara bebas tanpa alasan,
* dapur dapat melihat item dan catatan,
* dapur dapat mulai proses,
* dapur dapat menandai ready,
* setiap perubahan status memiliki timestamp,
* order processing tidak boleh diklaim ulang.

---

# 25. OVERRIDE ANTREAN

Jika kasir/admin perlu mengubah priority secara manual:

wajib ada:

* alasan,
* user yang melakukan,
* waktu perubahan,
* priority lama,
* priority baru.

Tidak boleh ada fitur "skip queue" tanpa audit trail.

---

# 26. PEMBATALAN ORDER

Definisikan aturan berdasarkan status.

Contoh:

## Sebelum Dibayar

Pelanggan dapat membatalkan.

## Setelah Dibayar tetapi Belum Diproses

Pembatalan memerlukan verifikasi kasir.

## Setelah PROCESSING

Tidak dapat dibatalkan otomatis.

Harus melalui kasir/admin dan menggunakan alasan.

## Setelah READY

Secara normal tidak dapat dibatalkan.

Sesuaikan dengan kebijakan Atrium Coffee.

Tandai kebijakan refund sebagai `Needs Confirmation` apabila belum diketahui.

---

# 27. REFUND

Definisikan aturan dasar:

* refund hanya untuk pembayaran yang berhasil,
* refund memerlukan role yang berwenang,
* alasan refund wajib,
* refund dicatat,
* nilai refund tidak boleh melebihi nilai pembayaran,
* partial refund hanya jika fitur tersebut memang diimplementasikan.

Jika tidak diperlukan untuk MVP, tandai partial refund sebagai future enhancement.

---

# 28. MENU AVAILABILITY

Definisikan:

* menu memiliki status tersedia/habis,
* menu habis tidak dapat ditambahkan ke keranjang,
* perubahan availability harus berlaku pada order baru,
* order yang sudah dibayar tetap menjadi tanggung jawab staff,
* jika menu ternyata tidak dapat diproses setelah pembayaran, harus ada mekanisme substitusi/refund sesuai kebijakan.

---

# 29. HARGA

Definisikan:

* harga disimpan di server,
* harga dari browser tidak dipercaya,
* snapshot harga harus disimpan pada `order_items`,
* perubahan harga menu tidak mengubah order lama,
* subtotal dihitung dari quantity × unit_price,
* total dihitung ulang server-side.

---

# 30. ESTIMASI WAKTU

Buat business rule untuk estimasi waktu tunggu.

Estimasi dapat mempertimbangkan:

* estimated preparation time order,
* jumlah antrean sebelum order,
* jumlah kitchen station,
* order aktif.

Namun untuk MVP gunakan algoritma sederhana dan explainable.

Contoh:

```text
estimated_waiting_time =
sum(estimated_processing_time dari order di depan)
```

Jika terdapat lebih dari satu station, boleh dibagi secara proporsional.

Tandai estimasi sebagai perkiraan, bukan jaminan.

---

# 31. ORDER TIME TRACKING

Simpan timestamp minimal:

* ordered_at
* paid_at
* queued_at
* processing_started_at
* ready_at
* completed_at
* cancelled_at

Gunakan timestamp ini untuk:

* laporan,
* evaluasi antrean,
* perhitungan waiting time,
* pengujian Priority Scheduling.

---

# 32. WAITING TIME

Definisikan:

```text
waiting_time =
processing_started_at - queued_at
```

Processing time:

```text
processing_time =
ready_at - processing_started_at
```

Total service time:

```text
total_service_time =
completed_at - queued_at
```

Jelaskan fungsi masing-masing metrik.

---

# 33. BUSINESS RULE KASIR

Kasir dapat:

* membuat order,
* memverifikasi pembayaran,
* melihat antrean,
* membatalkan sesuai rule,
* memperbaiki order sebelum diproses jika diizinkan,
* melihat transaksi.

Kasir tidak dapat:

* mengubah konfigurasi scheduling global,
* menghapus audit log,
* menghapus transaksi selesai.

---

# 34. BUSINESS RULE ADMIN

Admin dapat:

* mengelola menu,
* mengelola kategori,
* mengelola user,
* mengelola role,
* mengatur konfigurasi scheduling,
* melihat audit log.

Untuk perubahan konfigurasi penting, simpan audit trail.

---

# 35. BUSINESS RULE PEMILIK

Pemilik:

* memiliki akses laporan,
* monitoring,
* statistik,
* tidak harus melakukan operasi transaksi harian.

Tentukan bahwa akses pemilik secara default adalah read-oriented.

---

# 36. BUSINESS RULE LAPORAN

Laporan harus berasal dari transaksi yang valid.

Definisikan:

* transaksi cancelled tidak dihitung sebagai penjualan,
* refunded harus diperhitungkan,
* omzet berdasarkan pembayaran valid,
* laporan antrean menggunakan timestamp aktual,
* filter tanggal menggunakan timezone yang sama.

---

# 37. AUDIT LOG

Wajib dicatat untuk aktivitas penting:

* login tertentu jika diperlukan,
* perubahan status order,
* perubahan priority,
* cancellation,
* refund,
* perubahan harga,
* perubahan konfigurasi scheduling,
* perubahan role.

Minimal audit log menyimpan:

* user_id
* action
* entity_type
* entity_id
* old_value
* new_value
* reason
* timestamp

---

# 38. ERROR HANDLING

Definisikan aturan untuk:

* duplicate order,
* duplicate payment,
* menu unavailable,
* invalid status transition,
* expired session,
* database failure,
* payment timeout,
* queue claim conflict,
* invalid priority,
* missing item.

Untuk setiap kondisi jelaskan bagaimana sistem harus merespons.

---

# 39. BUSINESS VALIDATION

Buat tabel validasi penting.

Contoh:

| Validasi     | Rule                       |
| ------------ | -------------------------- |
| Quantity     | >= 1                       |
| Menu         | harus tersedia             |
| Price        | server-side                |
| Priority     | sesuai range               |
| Order Status | harus mengikuti transition |
| Payment      | harus tervalidasi          |
| Queue        | tidak boleh duplicate      |

---

# 40. CONFIGURATION

Tentukan business rule yang sebaiknya configurable.

Contoh:

* default_priority
* minimum_priority
* maximum_priority
* aging_interval_minutes
* order_timeout_minutes
* estimated_default_processing_minutes
* queue_number_prefix

Jangan hard-code jika parameter kemungkinan berubah.

---

# 41. DEFAULT CONFIGURATION

Berikan rekomendasi nilai awal:

```text
default_priority = 3
minimum_priority = 1
maximum_priority = 3
aging_interval_minutes = 10
```

Jika Priority 4 tidak benar-benar diperlukan, rekomendasikan range 1–3 agar lebih sederhana.

---

# 42. EDGE CASES

Buat minimal 20 edge cases beserta expected behavior.

Minimal mencakup:

* dua order masuk bersamaan,
* priority sama,
* queued_at sama,
* order baru berpriority tinggi ketika dapur sedang memproses order lain,
* order menunggu lama,
* payment berhasil tetapi callback terlambat,
* duplicate payment callback,
* kasir mengubah priority,
* dua staff dapur menekan "Proses" bersamaan,
* menu habis setelah checkout,
* harga berubah setelah masuk keranjang,
* koneksi terputus,
* refresh checkout,
* order dibatalkan,
* server restart,
* antrean kosong,
* priority invalid,
* aging melewati minimum priority,
* order ready tidak diambil,
* pelanggan kehilangan halaman tracking.

---

# 43. SIMULASI PRIORITY SCHEDULING

Buat simulasi minimal 8 order.

Gunakan kolom:

| Order | Queued At | Base Priority | Waiting Time | Effective Priority | Status | Urutan |
| ----- | --------- | ------------: | -----------: | -----------------: | ------ | -----: |

Berikan contoh:

* priority berbeda,
* priority sama,
* FCFS,
* aging,
* non-preemptive behavior.

Jelaskan hasilnya.

---

# 44. PSEUDOCODE

Buat pseudocode proses `getNextOrder()`.

Gunakan logika yang jelas.

Contoh konsep:

```text
BEGIN TRANSACTION

get WAITING queues

calculate effective priority

sort by:
1. effective_priority ASC
2. queued_at ASC
3. id ASC

lock first row

update queue status to PROCESSING

set processing_started_at

COMMIT
```

Jangan hanya menyalin contoh. Buat versi yang lengkap dan sesuai Laravel/MySQL.

---

# 45. DATABASE IMPLICATIONS

Tanpa membuat full database design, jelaskan field yang dibutuhkan agar business rules dapat diimplementasikan.

Minimal:

## orders

* status
* payment_status
* base_priority
* estimated_processing_time
* ordered_at

## queues

* order_id
* queue_number
* base_priority
* queued_at
* processing_started_at
* ready_at
* completed_at
* status

## priority_change_logs

atau audit log equivalent.

Jangan over-engineer.

---

# 46. AUTHORIZATION MATRIX

Buat tabel aksi kritis:

| Action               | Pelanggan | Kasir | Dapur | Admin | Pemilik |
| -------------------- | --------- | ----- | ----- | ----- | ------- |
| Create Order         | ...       | ...   | ...   | ...   | ...     |
| Change Priority      | ...       | ...   | ...   | ...   | ...     |
| Verify Payment       | ...       | ...   | ...   | ...   | ...     |
| Start Processing     | ...       | ...   | ...   | ...   | ...     |
| Cancel Order         | ...       | ...   | ...   | ...   | ...     |
| Configure Scheduling | ...       | ...   | ...   | ...   | ...     |

Gunakan aturan yang paling masuk akal.

---

# 47. BUSINESS RULE PRIORITY

Buat bagian ringkas yang menjadi single source of truth.

Contoh:

```text
1. Semua order normal dimulai dengan priority 3.
2. Priority lebih kecil berarti lebih tinggi.
3. Next order dipilih berdasarkan effective priority.
4. Priority sama diselesaikan menggunakan FCFS.
5. Scheduling bersifat non-preemptive.
6. Aging digunakan untuk mencegah starvation.
7. Manual priority override wajib memiliki alasan.
8. Semua perubahan priority diaudit.
```

Buat versi final yang lebih lengkap.

---

# 48. ASSUMPTIONS

Tuliskan asumsi yang digunakan.

Contoh:

* pembayaran harus valid sebelum antrean produksi,
* satu order memiliki satu antrean utama,
* Atrium Coffee menggunakan satu workflow dapur utama,
* sistem scheduling bersifat non-preemptive,
* pelanggan normal memiliki default priority.

---

# 49. NEEDS CONFIRMATION

Buat bagian khusus untuk aturan yang masih perlu dikonfirmasi.

Minimal:

* metode pembayaran,
* dine-in/takeaway,
* QR per meja atau QR umum,
* jumlah kitchen station,
* kategori order prioritas,
* kebijakan refund,
* apakah pelanggan dapat membatalkan sendiri,
* apakah order makanan dan minuman menggunakan queue yang sama,
* interval aging final.

Jangan menghentikan penyusunan dokumen karena informasi tersebut belum tersedia.

---

# 50. OUTPUT REQUIREMENTS

Output akhir harus:

1. berupa isi `BUSINESS_RULES.md`,
2. menggunakan Markdown,
3. konsisten dengan `PRD.md`,
4. menggunakan ID `BR-XXX`,
5. memiliki Table of Contents,
6. menggunakan tabel untuk aturan yang kompleks,
7. menggunakan code block untuk pseudocode/formula,
8. tidak mengandung penjelasan yang terlalu abstrak,
9. setiap rule harus dapat diterjemahkan menjadi validasi atau logika program,
10. prioritaskan simplicity untuk proyek Laravel skala coffee shop.

Gunakan struktur:

```markdown
# Business Rules

## Sistem Pemesanan dan Antrean Berbasis Web Menggunakan Metode Priority Scheduling pada Atrium Coffee

## 1. Document Purpose
## 2. Business Context
## 3. Actors
## 4. General Rules
## 5. Ordering Rules
## 6. Checkout Rules
## 7. Payment Rules
## 8. Queue Rules
## 9. Priority Scheduling Rules
## 10. Aging Rules
## 11. Order Status Rules
## 12. Kitchen Rules
## 13. Cashier Rules
## 14. Cancellation & Refund
## 15. Menu & Pricing
## 16. Authorization Rules
## 17. Reporting Rules
## 18. Audit Rules
## 19. Error & Edge Cases
## 20. Scheduling Simulation
## 21. Configuration
## 22. Database Implications
## 23. Assumptions
## 24. Needs Confirmation
```

---

# INSTRUKSI KUALITAS

Sebelum memberikan hasil akhir, lakukan pemeriksaan internal.

Pastikan:

* tidak ada business rule yang bertentangan,
* Priority Scheduling digunakan secara nyata,
* FCFS digunakan hanya sebagai tie-breaker,
* scheduling bersifat non-preemptive,
* Aging mencegah starvation,
* base priority dan effective priority dibedakan,
* pelanggan tidak dapat memanipulasi priority,
* manual override memiliki audit trail,
* proses claim queue aman terhadap race condition,
* status order dan queue konsisten,
* aturan dapat diimplementasikan di Laravel + MySQL,
* tidak ada logika yang terlalu kompleks untuk skala Atrium Coffee.

Jika terdapat konflik antara kesederhanaan dan kompleksitas teknis, pilih pendekatan yang:

1. sederhana,
2. deterministic,
3. mudah diuji,
4. mudah dipahami,
5. cocok untuk penelitian akademik,
6. aman untuk implementasi nyata.

Jangan tampilkan proses berpikir internal.

Tampilkan hanya isi final `BUSINESS_RULES.md`.
