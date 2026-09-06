# Design System & UI/UX Specification

## Sistem Pemesanan dan Antrean Berbasis Web Menggunakan Metode Priority Scheduling pada Atrium Coffee

Dokumen ini menjadi acuan utama desain antarmuka dan implementasi frontend. Spesifikasi diselaraskan dengan `PRD.md`, `BUSINESS_RULES.md`, `desain_database.md`, dan `SYSTEM_DESIGN.md` tanpa mengubah aturan bisnis yang telah ditetapkan.

## Daftar Isi

- [1. Design Purpose](#1-design-purpose)
- [2. Design Principles](#2-design-principles)
- [3. Technology](#3-technology)
- [4. Brand Direction](#4-brand-direction)
- [5. Design Tokens](#5-design-tokens)
- [6. Typography](#6-typography)
- [7. Colors](#7-colors)
- [8. Spacing](#8-spacing)
- [9. Layout](#9-layout)
- [10. Navigation](#10-navigation)
- [11. Components](#11-components)
- [12. Customer Experience](#12-customer-experience)
- [13. Cashier UI](#13-cashier-ui)
- [14. Kitchen UI](#14-kitchen-ui)
- [15. Admin UI](#15-admin-ui)
- [16. Owner UI](#16-owner-ui)
- [17. Priority & Queue Visualization](#17-priority--queue-visualization)
- [18. Responsive Design](#18-responsive-design)
- [19. Accessibility](#19-accessibility)
- [20. Screen Specifications](#20-screen-specifications)
- [21. Wireframes](#21-wireframes)
- [22. Blade Components](#22-blade-components)
- [23. Tailwind Configuration](#23-tailwind-configuration)
- [24. Implementation Rules](#24-implementation-rules)
- [25. Design Acceptance Checklist](#25-design-acceptance-checklist)

## 1. Design Purpose

Dokumen ini digunakan untuk:

- mendefinisikan visual language Atrium Coffee;
- menjaga konsistensi lintas halaman dan role;
- menjadi referensi implementasi Laravel Blade + Tailwind CSS;
- menentukan layout, navigation, responsivitas, dan interaction pattern;
- menyediakan reusable component dengan state yang lengkap;
- membuat proses pelanggan cepat dan mudah pada smartphone;
- membuat dashboard kasir dan dapur efisien untuk operasional;
- menyajikan monitoring admin dan pemilik secara jelas;
- memenuhi aksesibilitas dasar dan touch usability.

### 1.1 Prioritas per role

| Role | Prioritas pengalaman |
|---|---|
| Pelanggan | Menemukan menu, checkout, dan tracking dengan sedikit langkah |
| Kasir | Memindai status dan menyelesaikan transaksi dengan cepat |
| Dapur | Mengetahui order yang harus dikerjakan dan melakukan aksi tanpa ambigu |
| Admin | Mengelola data dan konfigurasi dengan aman |
| Pemilik | Memahami kondisi usaha melalui ringkasan dan laporan |

## 2. Design Principles

1. **Clear hierarchy** — satu primary action per konteks; informasi terpenting tampil paling dominan.
2. **Warm minimalism** — nuansa kopi hadir melalui warna dan gambar, bukan dekorasi berlebihan.
3. **Operational clarity** — status, waktu, nomor antrean, dan tindakan tidak boleh ambigu.
4. **Mobile first for customers** — alur utama dapat dilakukan satu tangan pada layar kecil.
5. **Fast at a glance** — dashboard staf dapat dipindai dalam beberapa detik.
6. **Consistent feedback** — loading, success, warning, error, disabled, dan empty state selalu tersedia.
7. **Accessible by default** — semantic HTML, focus jelas, kontras memadai, dan status tidak bergantung pada warna.
8. **Material-inspired, not copied** — memakai surface, elevation, state, dialog, chip, dan snackbar dengan identitas Atrium Coffee.
9. **Progressive disclosure** — detail lanjutan muncul saat diperlukan; halaman utama tetap ringkas.
10. **Implementation realism** — pola harus mudah dibuat dengan Blade, Tailwind CSS, dan Alpine.js.

Material Design diterapkan sebagai prinsip visual dan interaksi. Project tidak menggunakan Material UI React.

## 3. Technology

### 3.1 Core

- Laravel Blade untuk rendering halaman.
- Tailwind CSS untuk styling utility-first.
- Alpine.js untuk drawer, modal, dropdown, tabs, quantity control, dan snackbar.
- Vite untuk asset pipeline.
- Heroicons outline sebagai icon set utama; solid hanya untuk state terpilih.

### 3.2 Optional

- Livewire jika filter, tabel, atau form kompleks membutuhkan state server yang dinamis.
- Laravel Echo/WebSocket pada fase lanjutan; MVP menggunakan polling.
- Chart.js atau ApexCharts untuk 2–4 chart laporan, dimuat hanya pada halaman terkait.

### 3.3 Batasan frontend

- Tidak menggunakan React, Vue, atau Material UI React.
- Harga, total, priority, status, dan urutan antrean tetap server-authoritative.
- JavaScript tidak menentukan kandidat order dapur; tombol claim meminta kandidat terbaik dari server.

## 4. Brand Direction

### 4.1 Karakter

Atrium Coffee tampil **hangat, modern, premium, nyaman, dan elegan tanpa terasa formal**. Surface utama tetap terang agar operasional mudah dibaca. Warna espresso digunakan sebagai anchor, caramel sebagai aksen terbatas, dan cream sebagai background hangat.

### 4.2 Visual cues

- Fotografi menu dengan pencahayaan hangat dan latar bersih.
- Bentuk membulat sedang (`rounded-xl`/`rounded-2xl`).
- Border tipis dan shadow lembut.
- Ruang putih cukup; tidak semua section harus menjadi floating card.
- Ilustrasi opsional memakai line art sederhana bertema kopi.

### 4.3 Voice and tone

| Konteks | Gaya copy | Contoh |
|---|---|---|
| Pelanggan | Ramah dan langsung | “Pesananmu sedang disiapkan.” |
| Operasional | Ringkas dan spesifik | “3 pembayaran menunggu verifikasi.” |
| Error | Tenang dan dapat ditindaklanjuti | “Pembayaran belum terkonfirmasi. Coba periksa kembali.” |
| Destruktif | Tegas tentang konsekuensi | “Pesanan yang dibatalkan mungkin memerlukan refund.” |

## 5. Design Tokens

### 5.1 Ringkasan token

| Token | Nilai rekomendasi | Penggunaan |
|---|---|---|
| Primary | `coffee-600` | CTA, active navigation, link penting |
| Primary hover | `coffee-700` | Hover CTA |
| Secondary | `caramel-500` | Aksen terbatas, highlight brand |
| Background | `coffee-50` / `stone-50` | Latar halaman |
| Surface | `white` | Card, table, modal |
| Surface muted | `stone-100` | Section sekunder, disabled subtle |
| Border | `stone-200` | Border standar |
| Text primary | `stone-900` | Heading dan body utama |
| Text secondary | `stone-600` | Metadata dan helper |
| Success | `emerald-600` | Ready/success |
| Warning | `amber-600` | Pending/overdue ringan |
| Error | `red-600` | Error/cancelled/destructive |
| Info | `sky-600` | Processing/info |
| Focus | `coffee-500` | Ring focus |

### 5.2 Radius

| Token | Tailwind | Penggunaan |
|---|---|---|
| Small | `rounded-lg` | Chip, small control |
| Medium | `rounded-xl` | Button, input, table container |
| Large | `rounded-2xl` | Card utama, modal, menu card |
| Full | `rounded-full` | Avatar, badge, icon circle |

Hindari radius besar pada setiap nested element. Parent card `rounded-2xl` dapat berisi input/button `rounded-xl`.

### 5.3 Elevation

| Level | Tailwind | Penggunaan |
|---|---|---|
| 0 | tanpa shadow + border | Table/section datar |
| 1 | `shadow-sm` | Card standar |
| 2 | `shadow` | Sticky summary, dropdown |
| 3 | `shadow-md` | Modal, drawer, floating overlay |

Hover tidak perlu selalu menaikkan elevation. Menu card interaktif boleh berubah dari `shadow-sm` ke `shadow`.

### 5.4 Motion

```text
Standard: transition duration-150 ease-out
Overlay:  transition duration-200 ease-out
Exit:     transition duration-150 ease-in
```

Animasi hanya untuk memberi konteks. Hormati `prefers-reduced-motion` dan hindari animation loop dekoratif.

## 6. Typography

Gunakan **Plus Jakarta Sans** untuk identitas hangat-modern, dengan fallback `Inter, ui-sans-serif, system-ui`. Jika performa/font hosting menjadi kendala, gunakan Inter atau system font secara konsisten.

| Style | Tailwind recommendation | Penggunaan |
|---|---|---|
| Display | `text-3xl sm:text-4xl font-bold tracking-tight` | Hero pelanggan terbatas |
| H1/Page title | `text-2xl font-semibold tracking-tight text-stone-900` | Judul halaman |
| H2/Section | `text-lg font-semibold text-stone-900` | Judul section |
| H3/Card | `text-base font-semibold text-stone-900` | Judul card |
| Body large | `text-base leading-7` | Copy pelanggan |
| Body | `text-sm leading-6` | UI dan dashboard |
| Small | `text-sm text-stone-600` | Helper/metadata |
| Caption | `text-xs leading-5 text-stone-500` | Timestamp/label kecil |
| Numeric KPI | `text-2xl font-semibold tabular-nums` | Nilai statistik |
| Queue number | `text-5xl sm:text-6xl font-bold tabular-nums tracking-tight` | Nomor antrean utama |

Gunakan `tabular-nums` untuk timer, uang, antrean, dan KPI agar angka tidak bergeser saat berubah.

## 7. Colors

### 7.1 Brand palette

```js
coffee: {
  50:  '#FAF7F2',
  100: '#F3EADF',
  200: '#E7D3BD',
  300: '#D6B392',
  400: '#BE8E66',
  500: '#A66F4F',
  600: '#8B563E',
  700: '#704333',
  800: '#5C372D',
  900: '#4B2F27',
  950: '#2A1915'
},
caramel: {
  50:  '#FFF9EB',
  100: '#FDF0C8',
  200: '#F9DC8C',
  300: '#F3C24F',
  400: '#E9A925',
  500: '#CD8618',
  600: '#A96413',
  700: '#884B15',
  800: '#703D18',
  900: '#5F3419'
}
```

### 7.2 Semantic application

| State | Background | Text/icon | Border |
|---|---|---|---|
| Success | `bg-emerald-50` | `text-emerald-700` | `border-emerald-200` |
| Warning | `bg-amber-50` | `text-amber-800` | `border-amber-200` |
| Error | `bg-red-50` | `text-red-700` | `border-red-200` |
| Info | `bg-sky-50` | `text-sky-700` | `border-sky-200` |
| Neutral | `bg-stone-100` | `text-stone-700` | `border-stone-200` |

### 7.3 Color usage rules

- `coffee-600` adalah primary interactive color pada light background.
- `coffee-50` dapat menjadi selected/active surface.
- Caramel tidak digunakan untuk body text kecil karena kontrasnya lebih rendah.
- Merah hanya untuk error/destructive, bukan priority tinggi.
- Priority memakai label `P1/P2/P3` selain warna.
- Dark mode adalah future enhancement; jangan menambahkan class dark secara parsial yang belum diuji.

## 8. Spacing

Sistem spacing menggunakan kelipatan 4 px sesuai skala Tailwind.

| Konteks | Nilai |
|---|---|
| Icon-label gap | `gap-2` (8 px) |
| Form field stack | `space-y-1.5` atau `space-y-2` |
| Form group | `space-y-5` atau `gap-5` |
| Card padding compact | `p-4` |
| Card padding default | `p-5 sm:p-6` |
| Card internal gap | `gap-4` |
| Section gap mobile | `space-y-6` |
| Section gap desktop | `space-y-8` |
| Dashboard grid | `gap-4 lg:gap-6` |
| Page horizontal padding | `px-4 sm:px-6 lg:px-8` |
| Page vertical padding | `py-6 lg:py-8` |

Jangan menambahkan margin acak seperti 13 px. Pilih nilai skala Tailwind terdekat.

## 9. Layout

### 9.1 Grid dan container

```html
<main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4 lg:gap-6">
    <!-- content -->
  </div>
</main>
```

- Customer content: `max-w-6xl`; checkout/tracking: `max-w-3xl`.
- Dashboard: `max-w-7xl` atau full width untuk kitchen display.
- Form edit: `max-w-2xl` agar line length terkendali.
- Report table: full width di dalam main container.

### 9.2 Internal application shell

Desktop:

```text
┌────────────────┬────────────────────────────────────────┐
│ Sidebar 256px  │ Topbar                                 │
│                ├────────────────────────────────────────┤
│ Navigation     │ Main content                           │
│                │                                        │
│ Account/logout │                                        │
└────────────────┴────────────────────────────────────────┘
```

Implementation:

```text
min-h-screen bg-stone-50
sidebar: fixed inset-y-0 w-64 border-r bg-white lg:flex
content: lg:pl-64
topbar: sticky top-0 z-30 border-b bg-white/95 backdrop-blur
```

Mobile/tablet:

- Topbar setinggi sekitar 64 px.
- Sidebar menjadi slide-over drawer.
- Bottom navigation hanya dipakai bila role mempunyai maksimum 4 tujuan utama.
- Main content tidak tertutup sticky CTA; tambahkan safe bottom padding.

### 9.3 Customer shell

- Header ringkas: logo, location/table context, cart.
- Desktop dapat memakai horizontal navigation.
- Mobile memakai sticky header dan optional bottom navigation.
- Checkout memakai sticky bottom total pada mobile.

### 9.4 Sidebar

- Lebar 256 px (`w-64`).
- Logo di atas, menu dikelompokkan, akun/logout di bawah.
- Item: `flex min-h-11 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium`.
- Active: `bg-coffee-50 text-coffee-700` dengan icon coffee-600.
- Hover: `hover:bg-stone-100 hover:text-stone-900`.
- Label group memakai `text-xs font-semibold uppercase tracking-wider text-stone-400`.

### 9.5 Topbar

Berisi page title/breadcrumb, satu action kontekstual, notification, dan profile dropdown. Search hanya muncul pada halaman yang membutuhkannya. Hindari menaruh semua filter laporan di topbar.

## 10. Navigation

### 10.1 Pelanggan

- Beranda
- Menu
- Pesanan Saya (jika login)
- Antrean/Tracking aktif
- Profil (jika akun pelanggan digunakan)

Mobile bottom navigation maksimum empat item: `Beranda`, `Menu`, `Pesanan`, `Keranjang`.

### 10.2 Kasir

- Dashboard
- Pesanan
- Pembayaran
- Antrean
- Transaksi

### 10.3 Dapur

- Antrean
- Sedang Diproses
- Siap Diambil

Kitchen display lebih baik memakai tabs di bagian atas daripada sidebar permanen pada tablet.

### 10.4 Admin

- Dashboard
- Menu
- Kategori
- Pengguna
- Priority Rules
- Laporan
- Pengaturan
- Audit Log

### 10.5 Pemilik

- Dashboard
- Penjualan
- Antrean
- Laporan

Navigation aktif memakai kombinasi background, text weight, dan `aria-current="page"`.

## 11. Components

### 11.1 Component matrix

| Component | Purpose | Variants | Sizes/states | Tailwind baseline |
|---|---|---|---|---|
| Button | Menjalankan aksi | primary, secondary, outline, text, danger | sm/md/lg; hover, focus, loading, disabled | `inline-flex min-h-11 items-center justify-center rounded-xl font-medium` |
| Icon button | Aksi berikon | neutral, primary, danger | 40/44/48 px; tooltip wajib bila ambigu | `inline-grid size-11 place-items-center rounded-xl` |
| Input | Input satu baris | default, with icon | sm/md; invalid, disabled, readonly | `w-full rounded-xl border-stone-300 bg-white` |
| Select | Memilih satu nilai | native/custom terbatas | invalid, disabled | sama dengan input |
| Textarea | Catatan panjang | default | resize vertical; invalid/disabled | `min-h-28 w-full rounded-xl` |
| Checkbox | Pilihan independen | default | checked, indeterminate, disabled | `size-5 rounded border-stone-300 text-coffee-600` |
| Radio | Pilihan tunggal | card/default | checked, disabled | `size-5 text-coffee-600` |
| Switch | Boolean cepat | default | on/off, disabled | track + thumb, label tetap |
| Card | Kelompok konten | flat, elevated, interactive | default/loading/disabled | `rounded-2xl border border-stone-200 bg-white shadow-sm` |
| Stat card | KPI | neutral, semantic accent | loading/trend | card + numeric tabular |
| Data table | Data padat | standard, selectable | loading/empty/error | border container + sticky header |
| Badge | Status | success, warning, error, info, neutral | sm/md | `inline-flex rounded-full px-2.5 py-1 text-xs font-medium` |
| Chip | Filter/priority | selectable, priority | selected/disabled | border + concise label |
| Alert | Pesan inline | success, warning, error, info | dismissible optional | semantic surface + icon/title/body |
| Snackbar | Feedback singkat | semantic | entering, visible, exiting | fixed, elevation 3 |
| Modal | Konfirmasi/fokus | standard, destructive | loading, error | centered desktop, bottom sheet mobile optional |
| Drawer | Nav/filter/edit | left/right/bottom | open/closed | fixed overlay + `shadow-md` |
| Tabs | Pergantian view | underline, pill | selected, focus, disabled | semantic tablist |
| Dropdown | Aksi sekunder | menu | open/closed/disabled | `rounded-xl border bg-white shadow` |
| Pagination | Data bertahap | numbered/simple | current/disabled | target minimum 40–44 px |
| Breadcrumb | Lokasi halaman | default | current item | concise; hide intermediate on mobile |
| Skeleton | Loading content | text/card/table | animated/reduced motion | `animate-pulse bg-stone-200` |
| Empty state | Tanpa data | compact/full | optional CTA | icon + title + description |
| Tooltip | Penjelas aksi | top/bottom | hover/focus | tidak berisi informasi kritis |

### 11.2 Buttons

Base:

```html
<button class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition duration-150 ease-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-coffee-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
  Label
</button>
```

| Variant | Class tambahan |
|---|---|
| Primary | `bg-coffee-600 text-white shadow-sm hover:bg-coffee-700 active:bg-coffee-800` |
| Secondary | `bg-coffee-100 text-coffee-800 hover:bg-coffee-200` |
| Outlined | `border border-stone-300 bg-white text-stone-800 hover:bg-stone-50` |
| Text | `text-coffee-700 hover:bg-coffee-50` |
| Danger | `bg-red-600 text-white shadow-sm hover:bg-red-700` |

Sizes:

- `sm`: `min-h-9 px-3 py-2 text-xs`; hanya desktop/table compact.
- `md`: `min-h-11 px-4 py-2.5 text-sm`; default.
- `lg`: `min-h-12 px-5 py-3 text-base`; checkout/kitchen CTA.

Loading mempertahankan lebar button, menampilkan spinner, dan mengganti label seperti `Menyimpan…`. Button disabled bukan pengganti validasi; berikan alasan/helper bila pengguna perlu memahami penyebabnya.

### 11.3 Form fields

```html
<div class="space-y-1.5">
  <label for="customer_name" class="block text-sm font-medium text-stone-800">
    Nama pelanggan <span class="text-red-600" aria-hidden="true">*</span>
  </label>
  <input id="customer_name" name="customer_name"
    class="block min-h-11 w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-coffee-500 focus:outline-none focus:ring-2 focus:ring-coffee-500/20"
    aria-describedby="customer_name_help">
  <p id="customer_name_help" class="text-xs text-stone-500">Nama untuk pemanggilan pesanan.</p>
</div>
```

Invalid state: `border-red-500 focus:border-red-500 focus:ring-red-500/20`, `aria-invalid="true"`, dan pesan spesifik. Placeholder tidak menggantikan label.

### 11.4 Cards

```html
<section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
  <!-- content -->
</section>
```

- Interactive card harus memiliki affordance dan focus state.
- Jangan membuat card di dalam card lebih dari satu tingkat.
- Section besar yang sudah mempunyai background tidak selalu membutuhkan card.

### 11.5 Status badge

| Order status | Label UI | Style | Icon/cue |
|---|---|---|---|
| `PENDING_PAYMENT` | Menunggu Pembayaran | warning | Clock |
| `WAITING_VERIFICATION` | Menunggu Verifikasi | warning | Document check |
| `QUEUED` | Dalam Antrean | neutral/coffee | Queue list |
| `PROCESSING` | Diproses | info | Cog/bolt |
| `READY` | Siap Diambil | success | Check circle |
| `COMPLETED` | Selesai | neutral/success | Check badge |
| `CANCELLED` | Dibatalkan | error | X circle |

Badge selalu menampilkan teks, bukan titik warna saja.

### 11.6 Table

Anatomi: header halaman → toolbar search/filter → table → pagination. Gunakan `overflow-x-auto` hanya sebagai fallback desktop/tablet; pada mobile data penting berubah menjadi card list.

- Header: `sticky top-0 bg-stone-50 text-xs font-semibold uppercase tracking-wide text-stone-600`.
- Cell: `px-4 py-3.5 text-sm`.
- Row hover: `hover:bg-stone-50`.
- Action utama berlabel; overflow menu hanya untuk aksi sekunder.
- Sorting memakai icon dan `aria-sort`.

### 11.7 Modal, drawer, snackbar

- Modal default maksimal `max-w-lg`; destructive confirmation fokus pada konsekuensi.
- Mobile boleh memakai bottom sheet untuk detail menu/filter, bukan untuk form panjang kritis.
- Drawer navigation berasal dari kiri; filter/edit panel dari kanan.
- Snackbar desktop `bottom-6 right-6`; mobile `bottom-4 inset-x-4`.
- Snackbar tidak menggantikan inline error pada form.
- Fokus dipindah ke dialog saat buka dan dikembalikan ke trigger saat tutup.

### 11.8 Empty, loading, error

Empty state terdiri dari icon, judul, deskripsi, dan CTA bila ada. Loading memakai skeleton yang menyerupai layout final. Error menyediakan penjelasan dan tindakan retry jika aman.

Contoh empty cart:

```text
[Basket icon]
Keranjang masih kosong
Pilih menu favoritmu untuk mulai memesan.
[Lihat Menu]
```

## 12. Customer Experience

### 12.1 Customer journey

```text
Scan QR → Menu → Detail/Add → Cart → Checkout
→ Payment → Order Success → Queue Tracking → Ready → Completed
```

Target desain:

- Menu dapat mulai dijelajahi tanpa login.
- Cart selalu mudah dijangkau dan menampilkan jumlah item.
- Checkout meminta data minimum.
- Setelah order dibuat, tracking token tersimpan pada browser dan link dapat disalin.
- Nomor antrean hanya tampil setelah order benar-benar masuk queue.

### 12.2 Home dan menu

- Header logo, konteks meja jika ada, dan cart count.
- Search berada dekat atas.
- Category chip horizontal yang dapat di-scroll pada mobile.
- Menu populer maksimal satu section sebelum katalog lengkap.
- Grid: satu kolom untuk card horizontal pada layar sangat kecil atau dua kolom compact; desktop 3–4 kolom.

### 12.3 Menu card

Anatomi:

```text
[Gambar rasio 4:3]
[Kategori] [Habis jika relevan]
Nama Menu
Deskripsi maksimum 2 baris
Rp25.000                 [Tambah]
```

- Gunakan `line-clamp-2` untuk deskripsi.
- Harga memakai `font-semibold tabular-nums`.
- Menu habis memiliki overlay/badge yang jelas dan button disabled.
- Image menggunakan `loading="lazy"`, dimensi eksplisit, serta alt text bermakna.

### 12.4 Menu detail

- Gambar besar, nama, kategori, harga, deskripsi.
- Quantity stepper dengan min 1.
- Textarea catatan, bukan daftar opsi yang tidak diperlukan.
- Sticky CTA `Tambah • Rp...` pada mobile.
- Jika availability berubah, tampilkan inline alert dan nonaktifkan CTA.

### 12.5 Cart dan checkout

- Setiap item menampilkan thumbnail, nama, notes, quantity, subtotal, dan remove.
- Remove dapat langsung dilakukan dengan undo snackbar untuk mengurangi modal.
- Mobile memiliki sticky summary: total dan CTA Checkout.
- Checkout mengelompokkan informasi pelanggan, tipe order/meja, pembayaran, dan ringkasan.
- Validasi muncul dekat input dan fokus menuju error pertama.

### 12.6 Payment dan success

Payment menampilkan total, metode, status, order number, instruksi, serta timer hanya jika backend mempunyai expiry nyata. Tunai memakai teks `Bayar di Kasir`.

Order success:

- Icon check/success.
- Nomor order.
- Nomor antrean jika sudah tersedia; jika belum, tampilkan `Menunggu verifikasi pembayaran`.
- Total dan status pembayaran.
- Primary CTA `Lihat Status Pesanan`.
- Secondary action `Salin Tautan`.

### 12.7 Queue tracking

Urutan informasi:

1. Status dan nomor antrean besar.
2. Estimasi dan jumlah order di depan.
3. Stepper proses.
4. Ringkasan item/order.
5. Bantuan atau tindakan yang masih diizinkan.

Jangan menampilkan priority pelanggan karena pelanggan tidak dapat memanipulasinya dan informasi tersebut tidak diperlukan untuk tindakan pelanggan.

## 13. Cashier UI

### 13.1 Dashboard

KPI maksimum empat:

- Pesanan Hari Ini
- Menunggu Pembayaran
- Dalam Antrean
- Selesai

Area berikutnya: payment butuh tindakan, order terbaru, dan snapshot antrean. Alert operasional ditempatkan di atas KPI hanya bila membutuhkan tindakan segera.

### 13.2 Order table

Kolom desktop:

```text
Order | Pelanggan | Waktu | Total | Pembayaran | Priority | Status | Aksi
```

- `Order` dan aksi tetap mudah ditemukan.
- Filter: tanggal, payment status, order status, channel.
- Priority chip tampil, tetapi tidak menjadi kontrol inline bebas.
- Change priority membuka dialog yang meminta nilai baru dan alasan.
- Mobile/tablet kecil berubah ke card list dengan order, status, total, dan satu primary action.

### 13.3 Fast transaction

Order kasir memakai layout dua panel pada desktop: katalog di kiri, cart di kanan. Tablet dapat memakai cart drawer. Keyboard shortcut dapat menjadi enhancement, tetapi tidak menggantikan button yang terlihat.

## 14. Kitchen UI

Kitchen dashboard adalah layar operasional paling kritis. Gunakan ukuran teks dan target aksi lebih besar, kontras kuat, serta informasi non-esensial lebih sedikit.

### 14.1 Layout

Desktop lebar:

```text
Current Order (2/3 width) | Next Queue (1/3 width)
Waiting Orders (full width/list)
```

Tablet:

```text
Current Order
Next Queue
Waiting Orders horizontal/vertical
```

### 14.2 Current order

Menampilkan:

- Nomor antrean dominan.
- Nomor order dan P1/P2/P3.
- Timer sejak mulai dengan `tabular-nums`.
- Daftar item: quantity tebal, nama, catatan tepat di bawah item.
- CTA besar `Siap Diambil`.
- Jika belum ada current order, primary CTA `Mulai Order Berikutnya`.

Catatan pelanggan memakai warning surface lembut tetapi tidak disamakan dengan error.

### 14.3 Waiting queue

Card antrean:

```text
A017     [P2 Tinggi]
2× Cappuccino · 1× Croissant
Menunggu 08:32 · Est. 6 menit
[Overdue jika relevan]
```

Waiting list bersifat informatif. Dapur tidak memilih card secara bebas; primary action tetap `Mulai Order Berikutnya` dan server menentukan kandidat.

### 14.4 Aging dan overdue

- `P1/P2/P3` menggambarkan base/effective priority sesuai label yang dipilih produk; jika effective berbeda, gunakan teks `Naik otomatis karena waktu tunggu` pada detail/tooltip.
- Overdue: badge amber/red `Overdue` + durasi eksplisit.
- Jangan memakai kedipan, animasi terus-menerus, atau seluruh card merah.
- Timer tidak boleh menjadi satu-satunya indikator overdue.

### 14.5 Kitchen interaction safety

- Button action minimal `min-h-12` dan cukup lebar.
- Setelah klik, button loading mencegah submit berulang.
- Konflik claim menampilkan snackbar: `Order sudah diambil petugas lain. Antrean diperbarui.`
- Mark ready memerlukan konfirmasi ringan hanya jika risiko salah tekan tinggi; dapat berupa hold/second action, bukan modal berulang.

## 15. Admin UI

### 15.1 Dashboard

KPI: Total Menu, Menu Tersedia, Pengguna Aktif, dan Order Hari Ini. Section berikutnya menampilkan aktivitas terbaru, menu habis, serta ringkasan konfigurasi scheduling.

### 15.2 Menu management

Table desktop:

```text
Image | Name | Category | Price | Availability | Active | Actions
```

- Availability menggunakan switch dengan label visible dan optimistic UI hanya jika rollback error jelas.
- Create/edit menggunakan halaman atau drawer pada desktop; mobile menggunakan halaman penuh.
- Delete sebenarnya soft delete dan memakai confirmation dialog.
- Preview gambar dan validasi file tampil sebelum submit.

### 15.3 Scheduling settings

Field:

- Default Priority
- Minimum Priority
- Maximum Priority
- Aging Interval (menit)
- Overdue Threshold (menit)
- Queue Number Prefix

Setiap field memiliki helper text dan contoh dampak. Tampilkan summary sebelum simpan:

```text
Dengan interval 10 menit, order P3 menjadi efektif P2 setelah 10 menit
dan P1 setelah 20 menit. Base priority tetap P3.
```

Save memerlukan alasan perubahan jika kebijakan audit mengharuskannya. Success snackbar tidak menghilangkan summary nilai aktif.

## 16. Owner UI

### 16.1 Dashboard hierarchy

1. Filter periode dan last updated.
2. Empat KPI: Omzet, Total Order, Average Order Value, Average Waiting Time.
3. Sales trend dan orders trend.
4. Peak hour dan top menu.
5. Link ke laporan rinci.

Gunakan 2–4 chart maksimum. Setiap chart memiliki title, periode, unit, legend ringkas, tooltip keyboard-accessible bila library mendukung, serta table fallback/export.

### 16.2 Chart recommendation

| Data | Chart | Catatan |
|---|---|---|
| Sales trend | Line/area ringan | Rupiah per hari/jam |
| Orders trend | Bar | Jumlah order per periode |
| Peak hour | Bar horizontal | Mudah membandingkan jam |
| Top menu | Bar horizontal | Maksimum 5–10 item |

Pie/donut dihindari untuk banyak kategori. Warna chart menggunakan coffee scale dan satu semantic accent, bukan rainbow palette.

## 17. Priority & Queue Visualization

### 17.1 Priority chips

| Priority | Label | Style |
|---|---|---|
| 1 | `P1 Khusus` | `border-red-200 bg-red-50 text-red-800` |
| 2 | `P2 Tinggi` | `border-amber-200 bg-amber-50 text-amber-800` |
| 3 | `P3 Normal` | `border-stone-200 bg-stone-100 text-stone-700` |

P1 bukan label “darurat” medis; gunakan `Khusus` agar sesuai aturan operasional. Icon opsional selalu disertai teks.

### 17.2 Queue number

- Customer tracking: sangat dominan, centered, `text-5xl` atau lebih.
- Kitchen current: dominan di kiri atas.
- Table/list: `font-semibold tabular-nums`.
- Queue number tidak sama dengan order number; tampilkan label untuk keduanya.

### 17.3 Progress stepper

Tahap pelanggan:

```text
Pesanan Diterima → Dalam Antrean → Diproses → Siap Diambil → Selesai
```

`Menunggu Pembayaran` dan `Menunggu Verifikasi` ditampilkan sebagai status sebelum step produksi. Cancelled mengganti stepper dengan error-neutral terminal panel, bukan menandai semua step gagal.

## 18. Responsive Design

### 18.1 Breakpoints

Gunakan default Tailwind:

| Prefix | Minimum width | Peran utama |
|---|---:|---|
| base | 0 | Smartphone |
| `sm` | 640 px | Smartphone besar |
| `md` | 768 px | Tablet |
| `lg` | 1024 px | Laptop/sidebar |
| `xl` | 1280 px | Desktop dashboard |
| `2xl` | 1536 px | Display lebar |

### 18.2 Screen responsive matrix

| Screen | Mobile | Tablet | Desktop |
|---|---|---|---|
| Customer Home/Menu | 1–2 column, category scroll, sticky cart | 2–3 columns | 3–4 columns, full header |
| Menu Detail | Single column, sticky CTA/bottom sheet | 2 columns | Image dan detail berdampingan |
| Cart | Item list + sticky bottom total | Wider list | Content + sticky summary side panel |
| Checkout | Single column | Single/wide | Form 2/3 + summary 1/3 |
| Queue Tracking | Centered single column | Wider progress | Detail and summary side-by-side optional |
| Cashier Dashboard | KPI 2 columns, card lists | KPI 4 columns, mixed | Sidebar + table panels |
| Cashier Orders | Filter drawer + cards | Compact table/cards | Full data table |
| Kitchen | Stacked, large actions | Stacked/two area | Current 2/3 + next 1/3 + waiting |
| Admin Menu | Cards/filter drawer | Compact table | Full table and toolbar |
| Owner Dashboard | KPI 2 columns, charts stacked | KPI 4, charts 2 columns | 12-column dashboard grid |
| Reports | Filters drawer, summary cards | Horizontal filter bar | Filter + table + chart |

### 18.3 Mobile rules

- Touch target minimum sekitar 44×44 px.
- Body text utama minimal 14–16 px.
- Tidak ada horizontal scroll halaman; tabel saja boleh scroll sebagai fallback.
- Sticky bottom CTA tidak menutup content atau snackbar.
- Modal memakai `max-h-[90dvh]` dan scroll internal terkontrol.
- Gunakan `100dvh` bila viewport height diperlukan.

## 19. Accessibility

### 19.1 Requirements

- Gunakan landmark `header`, `nav`, `main`, `aside`, dan `footer`.
- Hanya satu `h1` utama per halaman dan hierarchy heading tidak dilompati.
- Semua input memiliki label programatik.
- Semua button icon-only mempunyai `aria-label` dan tooltip bila perlu.
- Focus state memakai `focus-visible:ring-2` dan tidak dihapus tanpa pengganti.
- Modal menggunakan focus trap, Escape untuk menutup jika aman, dan mengembalikan fokus.
- Snackbar penting memakai live region yang sesuai, tanpa membacakan timer setiap detik.
- Status dan priority memakai teks/icon selain warna.
- Kontras teks mengikuti WCAG AA; teks normal minimum 4.5:1.
- Error summary dapat fokus dan menghubungkan pesan ke field dengan `aria-describedby`.
- Table menggunakan `caption` tersembunyi/terlihat dan header `scope="col"`.
- Link memiliki nama yang menjelaskan tujuan, bukan “klik di sini”.
- UI tetap dapat digunakan pada zoom 200%.

### 19.2 Reduced motion

Skeleton pulse dan transition dinonaktifkan/dikurangi melalui `motion-reduce:animate-none motion-reduce:transition-none`.

## 20. Screen Specifications

Setiap screen mengikuti state minimum: default, loading, empty, error, dan success/feedback bila ada.

### 20.1 Customer Home

- **Purpose:** memperkenalkan layanan dan mempercepat pencarian menu.
- **Primary User:** pelanggan.
- **Layout:** customer shell, hero ringkas, search, category chips, popular menu, CTA.
- **Main Components:** header, search input, chips, menu cards, cart button.
- **Primary Actions:** lihat menu/tambah menu.
- **Secondary Actions:** buka tracking order aktif.
- **Important States:** loading menu, tidak ada menu, QR meja invalid, offline/error.
- **Responsive Behavior:** stacked mobile; grid 3–4 kolom desktop.

### 20.2 Menu Listing

- **Purpose:** menemukan dan memilih produk.
- **Primary User:** pelanggan.
- **Layout:** sticky search/category mobile; filter + responsive grid.
- **Main Components:** search, category chips, menu card, cart summary.
- **Primary Actions:** tambah ke cart.
- **Secondary Actions:** buka detail.
- **Important States:** hasil kosong, menu habis, loading skeleton, gagal memuat.
- **Responsive Behavior:** 1–2/2–3/3–4 columns dari mobile ke desktop.

### 20.3 Menu Detail

- **Purpose:** melihat detail, mengatur quantity dan catatan.
- **Primary User:** pelanggan.
- **Layout:** image dan information; sticky CTA mobile.
- **Main Components:** image, badge, stepper, textarea, price summary.
- **Primary Actions:** tambah ke cart.
- **Secondary Actions:** kembali ke menu.
- **Important States:** unavailable, price updated, invalid quantity, success snackbar.
- **Responsive Behavior:** single column mobile, two columns desktop.

### 20.4 Cart

- **Purpose:** meninjau item dan total sebelum checkout.
- **Primary User:** pelanggan.
- **Layout:** item list dan order summary.
- **Main Components:** cart item, quantity control, remove, notes, totals.
- **Primary Actions:** checkout.
- **Secondary Actions:** tambah menu, hapus item.
- **Important States:** empty cart, quantity updating, item unavailable, price changed.
- **Responsive Behavior:** sticky bottom summary mobile; sticky right summary desktop.

### 20.5 Checkout

- **Purpose:** mengumpulkan informasi minimum dan membuat order.
- **Primary User:** pelanggan/kasir pada flow berbeda.
- **Layout:** grouped form dan summary card.
- **Main Components:** input, radio order type, table selector, payment selector, totals.
- **Primary Actions:** buat pesanan/bayar.
- **Secondary Actions:** kembali ke cart.
- **Important States:** submitting, validation error, changed price, unavailable item, duplicate submit.
- **Responsive Behavior:** one column mobile; 2/3 form + 1/3 summary desktop.

### 20.6 Order Success

- **Purpose:** mengonfirmasi order dan mengarahkan tracking.
- **Primary User:** pelanggan.
- **Layout:** centered success panel.
- **Main Components:** success icon, order number, optional queue number, total, status.
- **Primary Actions:** lihat status pesanan.
- **Secondary Actions:** salin link/nomor order.
- **Important States:** payment verified + queued; payment awaiting verification; copy success.
- **Responsive Behavior:** `max-w-xl`, full-width actions mobile.

### 20.7 Queue Tracking

- **Purpose:** memberi transparansi status dan estimasi.
- **Primary User:** pelanggan.
- **Layout:** status hero, metrics, stepper, order details.
- **Main Components:** queue number, status badge, ETA, queue ahead, progress stepper.
- **Primary Actions:** refresh otomatis; lihat bantuan bila bermasalah.
- **Secondary Actions:** salin order number, cancel jika masih legal.
- **Important States:** pending payment, waiting, processing, ready, completed, cancelled, polling error.
- **Responsive Behavior:** single focused column; detail may split on desktop.

### 20.8 Cashier Dashboard

- **Purpose:** ringkasan operasional dan tindakan mendesak.
- **Primary User:** kasir.
- **Layout:** application shell, KPI, pending payments, recent orders, queue snapshot.
- **Main Components:** stat cards, alerts, tables/card lists.
- **Primary Actions:** buat order, verifikasi payment.
- **Secondary Actions:** buka order/queue.
- **Important States:** no pending payments, loading cards, stale update, error.
- **Responsive Behavior:** 2-to-4 KPI grid; tables become cards.

### 20.9 Cashier Orders

- **Purpose:** mencari dan menangani order.
- **Primary User:** kasir.
- **Layout:** title/actions, filter toolbar, order table, pagination.
- **Main Components:** search, filters, table, status/priority chips, action menu.
- **Primary Actions:** view/continue transaction.
- **Secondary Actions:** change priority/cancel sesuai policy.
- **Important States:** no results, loading, conflict, unauthorized action.
- **Responsive Behavior:** filter drawer + order cards on mobile.

### 20.10 Kitchen Queue

- **Purpose:** memperlihatkan urutan produksi.
- **Primary User:** dapur.
- **Layout:** current, next, waiting orders.
- **Main Components:** current card, next card, queue cards, timers, priority chips.
- **Primary Actions:** mulai order berikutnya.
- **Secondary Actions:** lihat detail.
- **Important States:** empty queue, claim loading/conflict, overdue, connectivity warning.
- **Responsive Behavior:** stacked tablet; split wide desktop; large controls throughout.

### 20.11 Kitchen Current Order

- **Purpose:** memandu pengerjaan order aktif.
- **Primary User:** dapur.
- **Layout:** large queue header + item checklist/read list + action footer.
- **Main Components:** queue number, timer, item rows, notes alert, ready CTA.
- **Primary Actions:** tandai siap diambil.
- **Secondary Actions:** report issue (future/controlled).
- **Important States:** no active order, submit loading, invalid transition, connection error.
- **Responsive Behavior:** content remains large; CTA sticky on tablet.

### 20.12 Admin Dashboard

- **Purpose:** memantau konfigurasi dan data master.
- **Primary User:** admin.
- **Layout:** KPI + quick links + recent activity.
- **Main Components:** stat cards, activity list, availability summary.
- **Primary Actions:** kelola menu/user/settings.
- **Secondary Actions:** lihat audit/report.
- **Important States:** empty activity, loading, permission issue.
- **Responsive Behavior:** 2/4 KPI grid and stacked/two-column sections.

### 20.13 Menu Management

- **Purpose:** CRUD menu dan availability.
- **Primary User:** admin.
- **Layout:** toolbar, filters, table/cards, create/edit view.
- **Main Components:** data table, image thumbnail, switch, modal confirmation.
- **Primary Actions:** tambah/edit menu.
- **Secondary Actions:** toggle availability, soft delete.
- **Important States:** empty menu, upload error, duplicate SKU, save success.
- **Responsive Behavior:** table desktop; cards and full-page forms mobile.

### 20.14 Scheduling Settings

- **Purpose:** mengatur parameter priority scheduling secara aman.
- **Primary User:** admin.
- **Layout:** `max-w-3xl`, current summary, grouped fields, impact example.
- **Main Components:** numeric inputs/select, helper alerts, save bar.
- **Primary Actions:** simpan konfigurasi.
- **Secondary Actions:** reset form ke nilai aktif, lihat audit.
- **Important States:** validation error, unsaved changes, save success, conflict/stale data.
- **Responsive Behavior:** single column; sticky save bar mobile optional.

### 20.15 Owner Dashboard

- **Purpose:** melihat performa bisnis dan layanan.
- **Primary User:** pemilik.
- **Layout:** period filter, KPI, 2–4 charts, top menu.
- **Main Components:** date filter, stat cards, charts, summary tables.
- **Primary Actions:** ubah periode, buka laporan.
- **Secondary Actions:** export.
- **Important States:** no data period, loading skeleton, export processing/error.
- **Responsive Behavior:** charts stacked mobile and paired desktop.

### 20.16 Reports

- **Purpose:** analisis penjualan, antrean, dan menu.
- **Primary User:** admin/pemilik.
- **Layout:** report tabs, filters, KPI summary, chart/table, pagination/export.
- **Main Components:** tabs, date range, filter drawer, table, export button.
- **Primary Actions:** apply filter/view report.
- **Secondary Actions:** export PDF/Excel jika tersedia.
- **Important States:** no data, invalid period, loading, export queued/ready/failed.
- **Responsive Behavior:** filter drawer mobile; summary cards before table.

## 21. Wireframes

Wireframe bersifat low fidelity dan menunjukkan hierarchy, bukan ukuran pixel final.

### 21.1 Customer Menu — Mobile

```text
┌──────────────────────────────────┐
│ Atrium Coffee        [Cart 2]    │
│ Meja T03                         │
├──────────────────────────────────┤
│ [ Cari menu...                 ] │
│ [Semua] [Kopi] [Non Kopi] [Food]│
├──────────────────────────────────┤
│ Menu Populer                     │
│ ┌──────────────┐ ┌─────────────┐ │
│ │    Image     │ │    Image    │ │
│ │ Cappuccino   │ │ Croissant   │ │
│ │ Rp25.000 [+] │ │ Rp18.000[+] │ │
│ └──────────────┘ └─────────────┘ │
│                                  │
│ Semua Menu                       │
│ ┌──────────────────────────────┐ │
│ │ [Image] Americano            │ │
│ │         Rp20.000    [Tambah] │ │
│ └──────────────────────────────┘ │
├──────────────────────────────────┤
│ 2 item · Rp43.000   [Lihat Cart]│
└──────────────────────────────────┘
```

### 21.2 Queue Tracking — Mobile

```text
┌──────────────────────────────────┐
│ ← Status Pesanan                 │
├──────────────────────────────────┤
│       Antrean Anda               │
│                                  │
│             A017                 │
│        [Dalam Antrean]           │
│                                  │
│  3 pesanan di depan · ±12 menit │
├──────────────────────────────────┤
│ ● Pesanan Diterima               │
│ │                                │
│ ● Dalam Antrean                  │
│ │                                │
│ ○ Diproses                       │
│ │                                │
│ ○ Siap Diambil                   │
│ │                                │
│ ○ Selesai                        │
├──────────────────────────────────┤
│ Order ORD-...                    │
│ 2× Cappuccino                    │
│ 1× Croissant                     │
│ Total                 Rp68.000   │
└──────────────────────────────────┘
```

### 21.3 Cashier Dashboard — Desktop

```text
┌──────────────┬───────────────────────────────────────────┐
│ ATRIUM       │ Dashboard Kasir      [Buat Order] [User] │
│ Dashboard ●  ├───────────────────────────────────────────┤
│ Pesanan      │ [Hari Ini] [Menunggu] [Antrean] [Selesai]│
│ Pembayaran   │                                           │
│ Antrean      │ Pembayaran Perlu Verifikasi               │
│ Transaksi    │ ┌───────────────────────────────────────┐ │
│              │ │ Order | Customer | Total | Status |… │ │
│              │ └───────────────────────────────────────┘ │
│              │                                           │
│              │ Order Terbaru        Snapshot Antrean     │
│              │ ┌─────────────────┐  ┌─────────────────┐  │
│              │ │ table/list      │  │ A017 P2 Waiting│  │
│ Logout       │ └─────────────────┘  └─────────────────┘  │
└──────────────┴───────────────────────────────────────────┘
```

### 21.4 Kitchen Dashboard — Desktop

```text
┌──────────────────────────────────────────────────────────┐
│ ATRIUM KITCHEN       Antrean diperbarui 10:32:05         │
├────────────────────────────────────┬─────────────────────┤
│ CURRENT ORDER                      │ NEXT QUEUE          │
│ A016          [P3 Normal]  06:42   │ A017 [P2 Tinggi]   │
│ Order ORD-...                      │ Menunggu 08:32     │
│                                    │ Est. 6 menit       │
│ 2× Cappuccino                      │                     │
│    Tanpa gula                      │ [Mulai Berikutnya] │
│ 1× Croissant                       │                     │
│                                    │                     │
│ [          SIAP DIAMBIL          ] │                     │
├────────────────────────────────────┴─────────────────────┤
│ WAITING ORDERS                                           │
│ [A017 P2 08:32] [A018 P3 05:12] [A019 P3 02:41]         │
└──────────────────────────────────────────────────────────┘
```

### 21.5 Owner Dashboard — Desktop

```text
┌──────────────┬───────────────────────────────────────────┐
│ ATRIUM       │ Dashboard Pemilik   [30 Agu] [Hari Ini] │
│ Dashboard ●  ├───────────────────────────────────────────┤
│ Penjualan    │ [Omzet] [Orders] [AOV] [Avg Waiting]     │
│ Antrean      │                                           │
│ Laporan      │ ┌──────────────────┐ ┌─────────────────┐ │
│              │ │ Sales Trend     │ │ Orders Trend    │ │
│              │ │ ~ line chart ~  │ │ | bar chart |   │ │
│              │ └──────────────────┘ └─────────────────┘ │
│              │ ┌──────────────────┐ ┌─────────────────┐ │
│              │ │ Peak Hour       │ │ Top Menu        │ │
│              │ └──────────────────┘ └─────────────────┘ │
└──────────────┴───────────────────────────────────────────┘
```

## 22. Blade Components

### 22.1 Struktur frontend

```text
resources/
├── css/
│   └── app.css
├── js/
│   └── app.js
└── views/
    ├── layouts/
    │   ├── app.blade.php
    │   ├── auth.blade.php
    │   ├── customer.blade.php
    │   └── dashboard.blade.php
    ├── components/
    │   ├── alert.blade.php
    │   ├── badge.blade.php
    │   ├── button.blade.php
    │   ├── card.blade.php
    │   ├── empty-state.blade.php
    │   ├── form/
    │   │   ├── checkbox.blade.php
    │   │   ├── input.blade.php
    │   │   ├── select.blade.php
    │   │   └── textarea.blade.php
    │   ├── modal.blade.php
    │   ├── priority-chip.blade.php
    │   ├── skeleton.blade.php
    │   ├── stat-card.blade.php
    │   ├── status-badge.blade.php
    │   └── table.blade.php
    ├── customer/
    ├── cashier/
    ├── kitchen/
    ├── admin/
    └── owner/
```

### 22.2 Component API example

```blade
<x-button variant="primary" size="lg" type="submit">
    Buat Pesanan
</x-button>

<x-status-badge :status="$order->status" />
<x-priority-chip :priority="$queue->base_priority" />

<x-form.input
    name="customer_name"
    label="Nama pelanggan"
    :value="old('customer_name')"
    :error="$errors->first('customer_name')"
    required
/>
```

Component harus menerima data/variant yang terbatas, bukan string class bebas sebagai API utama. `class` masih dapat digabung untuk layout context melalui `$attributes->class()`.

### 22.3 Alpine interaction

Gunakan Alpine untuk state presentasional:

```text
drawerOpen, modalOpen, selectedTab, menuOpen,
quantity, snackbarVisible, filterExpanded
```

State bisnis tetap berasal dari server. Gunakan `x-cloak`, focus plugin bila tersedia, dan cleanup timer polling saat component dihancurkan.

## 23. Tailwind Configuration

Contoh untuk Tailwind 3.x; sesuaikan sintaks jika project memakai versi berbeda.

```js
// tailwind.config.js
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/View/Components/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        coffee: {
          50: '#FAF7F2',
          100: '#F3EADF',
          200: '#E7D3BD',
          300: '#D6B392',
          400: '#BE8E66',
          500: '#A66F4F',
          600: '#8B563E',
          700: '#704333',
          800: '#5C372D',
          900: '#4B2F27',
          950: '#2A1915',
        },
        caramel: {
          50: '#FFF9EB',
          100: '#FDF0C8',
          200: '#F9DC8C',
          300: '#F3C24F',
          400: '#E9A925',
          500: '#CD8618',
          600: '#A96413',
          700: '#884B15',
          800: '#703D18',
          900: '#5F3419',
        },
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
      },
      boxShadow: {
        soft: '0 8px 24px rgba(42, 25, 21, 0.08)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

`shadow-soft` hanya dipakai untuk overlay/hero tertentu. Card standar tetap menggunakan `shadow-sm`.

### 23.1 Base CSS

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  html { @apply scroll-smooth antialiased; }
  body { @apply bg-stone-50 font-sans text-stone-900; }
  [x-cloak] { display: none !important; }
}

@layer components {
  .focus-ring {
    @apply focus-visible:outline-none focus-visible:ring-2
           focus-visible:ring-coffee-500 focus-visible:ring-offset-2;
  }
}
```

Jangan memindahkan seluruh utility ke `@apply`. Ekstraksi dilakukan untuk primitive yang benar-benar berulang atau lewat Blade component.

## 24. Implementation Rules

### 24.1 Wajib

- Gunakan design token dan semantic color yang ditetapkan.
- Gunakan Blade component untuk primitive yang berulang.
- Sediakan hover, focus, active, loading, disabled, error, dan success sesuai konteks.
- Gunakan server-provided label/status mapping yang konsisten.
- Pastikan layout mobile tidak memiliki overflow horizontal halaman.
- Format uang menggunakan locale Indonesia dan angka tabular pada dashboard.
- Waktu tampil dalam timezone aplikasi yang disepakati (`Asia/Makassar`).
- Tombol destructive memakai label eksplisit dan confirmation bila dampaknya material.
- Dashboard kitchen meminta `claim-next`; jangan mengirim pilihan urutan dari client.
- Uji keyboard, zoom, kontras, dan touch target.

### 24.2 Dilarang

- Membuat warna acak di luar token untuk status/role.
- Menambah variant button tanpa use case berulang.
- Menggunakan placeholder sebagai label.
- Mengandalkan warna untuk membedakan priority/status.
- Memaksa tabel desktop penuh pada smartphone.
- Menumpuk card lebih dari dua level.
- Menggunakan modal untuk setiap aksi sederhana.
- Menampilkan spinner satu halaman tanpa struktur/skeleton.
- Menaruh business rule harga, status, atau scheduling di JavaScript.
- Menampilkan primary key sebagai tracking credential.

### 24.3 Do / Don't

#### Do

- Gunakan spacing dan typography hierarchy secara konsisten.
- Letakkan primary action di posisi yang dapat diprediksi.
- Tampilkan status dengan label yang mudah dipahami.
- Berikan recovery action pada error.
- Tampilkan detail priority/aging secukupnya untuk staf.
- Pertahankan kitchen UI besar dan minim distraksi.

#### Don't

- Menggunakan terlalu banyak warna, shadow, radius, atau chart.
- Membuat copy teknis seperti nama enum terlihat kepada pelanggan.
- Membuat timer berkedip atau mengganggu.
- Menyembunyikan aksi penting dalam overflow menu.
- Menggunakan teks lebih kecil demi memasukkan lebih banyak kolom.

### 24.4 Design system summary

| Element | Style |
|---|---|
| Primary Button | `rounded-xl bg-coffee-600 text-white shadow-sm`, min-height 44 px |
| Secondary Button | `rounded-xl bg-coffee-100 text-coffee-800` |
| Outlined Button | Border stone, white surface, coffee focus ring |
| Card | `rounded-2xl border-stone-200 bg-white p-5 shadow-sm` |
| Input | `rounded-xl border-stone-300`, label dan helper/error terlihat |
| Badge | Rounded-full semantic surface + text + optional icon |
| Priority Chip | P1/P2/P3 tertulis; semantic border/background |
| Sidebar | 256 px, white, active coffee-50/coffee-700 |
| Topbar | Sticky, border bottom, background white/95 |
| Table | White surface, sticky muted header, 14 px body |
| Modal | Rounded-2xl, max-w-lg, shadow-md, focus trapped |
| Snackbar | Semantic icon/text, bottom-right desktop/bottom-center mobile |

### 24.5 Future enhancement

- Dark mode yang dirancang dan diuji menyeluruh.
- WebSocket untuk update status langsung.
- PWA/offline shell terbatas.
- Multi-station kitchen UI.
- Customer account dan personalized history.
- WhatsApp/SMS notification dengan consent.

## 25. Design Acceptance Checklist

### Brand dan konsistensi

- [ ] Palet coffee, neutral, dan semantic digunakan konsisten.
- [ ] Typography memakai keluarga dan hierarchy yang disetujui.
- [ ] Spacing mengikuti skala 4 px.
- [ ] Shadow dan radius tidak berlebihan.
- [ ] Icon berasal dari satu keluarga yang konsisten.

### Components dan states

- [ ] Button memiliki hover, focus, active, loading, dan disabled state.
- [ ] Semua input memiliki label, helper/error, dan focus state.
- [ ] Status menampilkan label/icon, bukan warna saja.
- [ ] Priority P1/P2/P3 terlihat jelas bagi staf.
- [ ] Empty, loading, error, dan success state tersedia pada screen penting.
- [ ] Modal destructive menjelaskan konsekuensi dan memerlukan konfirmasi.
- [ ] Reusable pattern telah menjadi Blade component.

### Customer experience

- [ ] Menu mudah dicari dan difilter pada smartphone.
- [ ] Menu habis tidak dapat ditambahkan.
- [ ] Cart dan sticky checkout CTA tidak menutup content.
- [ ] Checkout hanya meminta data yang diperlukan.
- [ ] Order success membedakan order number dan queue number.
- [ ] Tracking menampilkan status, estimasi, antrean di depan, dan stepper.
- [ ] Pelanggan tidak dapat memilih atau mengubah priority.

### Operational dashboards

- [ ] Cashier table/card memperlihatkan payment, priority, dan order status.
- [ ] Kitchen current order terbaca dari jarak operasional.
- [ ] Kitchen CTA memiliki target besar dan label tidak ambigu.
- [ ] Waiting queue hanya informatif; claim terbaik ditentukan server.
- [ ] Overdue menunjukkan teks dan durasi, bukan warna saja.
- [ ] Scheduling settings menjelaskan dampak aging dengan bahasa sederhana.
- [ ] Owner dashboard dibatasi pada chart yang benar-benar berguna.

### Responsive dan accessibility

- [ ] Tidak ada overflow horizontal halaman pada 320 px.
- [ ] Tabel kompleks berubah menjadi card/fallback yang layak pada mobile.
- [ ] Touch target utama minimum sekitar 44 px.
- [ ] Semua alur utama dapat digunakan dengan keyboard.
- [ ] Focus indicator terlihat jelas.
- [ ] Kontras teks memenuhi target WCAG AA.
- [ ] Heading dan landmark semantic tersusun benar.
- [ ] Modal mengelola fokus dengan benar.
- [ ] UI tetap dapat digunakan pada zoom 200%.
- [ ] Reduced-motion preference dihormati.

### Implementation readiness

- [ ] Blade components memiliki API variant yang terbatas dan terdokumentasi.
- [ ] Tailwind content paths mencakup seluruh Blade/component.
- [ ] Tidak ada inline style tanpa kebutuhan khusus.
- [ ] Business rules tetap berada di server.
- [ ] Polling memiliki interval, cleanup, retry, dan terminal stop yang aman.
- [ ] Loading/error tidak membocorkan detail internal.
- [ ] Semua screen specification telah diterjemahkan ke acceptance test UI.
