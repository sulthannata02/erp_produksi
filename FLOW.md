# 📦 Sistem Informasi Produksi & Packing — Alur Aplikasi

> Dokumen ini menjelaskan alur fitur, halaman, dan akses berdasarkan role pengguna pada sistem ERP Produksi & Packing berbasis web (Laravel).

---

## 🗂️ Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Role Pengguna](#role-pengguna)
3. [Alur Utama Sistem](#alur-utama-sistem)
4. [Fitur Per Role](#fitur-per-role)
   - [Admin](#admin)
   - [Operator](#operator)
5. [Skema Database](#skema-database)
6. [Routing Aplikasi](#routing-aplikasi)

---

## Gambaran Umum

Sistem ini adalah aplikasi web berbasis **Laravel** yang mengintegrasikan proses produksi dari penerimaan material di gudang hingga proses packing akhir. Sistem dibagi menjadi dua role utama:

| Role | Tanggung Jawab |
|------|---------------|
| **Admin** | Manajemen material, produksi, monitoring, dan laporan |
| **Operator** | QC (Quality Control), packing, dan tracking status |

---

## Role Pengguna

```
[Login Page]
     │
     ├──► role = admin    ──► Dashboard Admin
     │
     └──► role = operator ──► Dashboard Operator
```

---

## Alur Utama Sistem

Berikut adalah alur data dari awal hingga akhir proses produksi:

```
[1] Admin: Input Material
        │
        ▼
[2] Admin: Buat Data Produksi  (pilih material, input jumlah & kode produksi)
        │
        ▼
[3] Operator: QC  (cek kualitas → hasil: good / not good)
        │
        ├── hasil = good
        │       ▼
        │  [4] Operator: Packing  (input FG & NG, kode packing)
        │       │
        │       ▼
        │  [5] Selesai ✅
        │
        └── hasil = not good
                ▼
           [Ditolak / Dikembalikan ❌]
```

> Seluruh tahapan dapat dipantau secara real-time oleh **Admin** melalui halaman Monitoring, dan oleh **Operator** melalui halaman Tracking.

---

## Fitur Per Role

### Admin

#### 1. 🔐 Login
- Akses melalui `/login`
- Jika sudah login langsung redirect ke `/dashboard`

#### 2. 📊 Dashboard Admin
**URL:** `/dashboard`

Menampilkan ringkasan kondisi sistem:
- Total material yang terdaftar
- Total data produksi
- Total QC yang sudah dilakukan
- Total packing selesai
- Daftar prioritas produksi (10 terbaru, urut tanggal)
- Grafik produksi 30 hari terakhir

#### 3. 🏗️ Manajemen Material
**URL:** `/materials` (CRUD)

| Aksi | Method | Deskripsi |
|------|--------|-----------|
| Lihat semua | `GET /materials` | Daftar semua material |
| Tambah form | `GET /materials/create` | Form input material baru |
| Simpan | `POST /materials` | Menyimpan data material |
| Detail | `GET /materials/{id}` | Detail satu material |
| Edit form | `GET /materials/{id}/edit` | Form edit material |
| Update | `PUT /materials/{id}` | Memperbarui data |
| Hapus | `DELETE /materials/{id}` | Menghapus data |

**Field Material:**
- `nama_material` — Nama material
- `jumlah` — Jumlah stok
- `satuan` — Satuan (default: Pcs)
- `gambar` — Foto material (opsional)
- `tanggal_masuk` — Tanggal masuk ke gudang
- `customer` / `no_po` — Data customer & PO

#### 4. ⚙️ Manajemen Produksi
**URL:** `/productions` (CRUD)

| Aksi | Method | Deskripsi |
|------|--------|-----------|
| Lihat semua | `GET /productions` | Daftar semua produksi |
| Tambah form | `GET /productions/create` | Form input produksi baru |
| Simpan | `POST /productions` | Menyimpan data produksi |
| Edit form | `GET /productions/{id}/edit` | Form edit produksi |
| Update | `PUT /productions/{id}` | Memperbarui data |
| Hapus | `DELETE /productions/{id}` | Menghapus data |

**Field Produksi:**
- `kode_produksi` — Kode unik produksi (auto/manual)
- `material_id` — Referensi ke material
- `jumlah_produksi` — Jumlah yang diproduksi
- `tanggal_produksi` — Tanggal produksi
- `operator` — Nama operator yang mengerjakan
- `customer` / `no_po` — Data customer & PO

#### 5. 📡 Monitoring
**URL:** `/monitoring`

Halaman khusus admin untuk memantau **seluruh status produksi secara real-time**, mencakup:
- Data material → produksi → QC → packing
- Filter dan tampilan status per batch
- Mengurangi kebutuhan komunikasi manual antar bagian

#### 6. 📄 Laporan
**URL:** `/laporan`

- Menampilkan rekap data keseluruhan
- Export laporan: `GET /laporan/export`

---

### Operator

#### 1. 🔐 Login
- Sama dengan admin, redirect ke dashboard sesuai role

#### 2. 📋 Dashboard Operator
**URL:** `/dashboard`

Menampilkan antrian pekerjaan:
- Daftar produksi yang **belum di-QC**
- Daftar produksi yang **sudah QC (good) tapi belum di-packing**
- Statistik: total belum QC, belum packing, QC selesai, packing selesai

#### 3. 🔍 QC (Quality Control)
**URL:** `/qcs` (CRUD)

| Aksi | Method | Deskripsi |
|------|--------|-----------|
| Lihat semua | `GET /qcs` | Daftar hasil QC |
| Tambah form | `GET /qcs/create` | Form input QC baru |
| Simpan | `POST /qcs` | Menyimpan hasil QC |
| Edit form | `GET /qcs/{id}/edit` | Form edit QC |
| Update | `PUT /qcs/{id}` | Memperbarui hasil QC |
| Hapus | `DELETE /qcs/{id}` | Menghapus data QC |

**Field QC:**
- `production_id` — Referensi ke data produksi
- `qty_qc` — Jumlah item yang di-QC
- `hasil` — Hasil QC: `good` / `not_good`
- `keterangan` — Catatan tambahan
- `status` — Status: `proses` / `selesai`
- `tanggal_qc` — Tanggal pelaksanaan QC

#### 4. 📦 Packing
**URL:** `/packings` (CRUD)

| Aksi | Method | Deskripsi |
|------|--------|-----------|
| Lihat semua | `GET /packings` | Daftar packing |
| Tambah form | `GET /packings/create` | Form input packing |
| Simpan | `POST /packings` | Menyimpan data packing |
| Edit form | `GET /packings/{id}/edit` | Form edit packing |
| Update | `PUT /packings/{id}` | Memperbarui data |
| Hapus | `DELETE /packings/{id}` | Menghapus data |

**Field Packing:**
- `kode_packing` — Kode unik packing
- `qc_id` — Referensi ke data QC
- `jumlah` — Total item dipacking
- `jumlah_fg` — Finished Good (produk lolos)
- `jumlah_ng` — Not Good (produk gagal)
- `operator` — Nama operator packing
- `status` — Status: `proses` / `selesai`
- `keterangan` — Catatan tambahan
- `tanggal_packing` — Tanggal packing

#### 5. 🗺️ Tracking
**URL:** `/tracking`

Operator dapat melihat status lengkap setiap batch:
- Material → Produksi → QC → Packing
- Data real-time tanpa perlu tanya ke bagian lain

---

## Skema Database

```
┌─────────────┐       ┌──────────────────┐       ┌───────────────┐       ┌──────────────┐
│  materials  │──1:N──│   productions    │──1:1──│     qcs       │──1:1──│   packings   │
├─────────────┤       ├──────────────────┤       ├───────────────┤       ├──────────────┤
│ id          │       │ id               │       │ id            │       │ id           │
│ nama_material│      │ kode_produksi    │       │ production_id │       │ kode_packing │
│ jumlah      │       │ material_id (FK) │       │ qty_qc        │       │ qc_id (FK)   │
│ satuan      │       │ jumlah_produksi  │       │ hasil         │       │ jumlah       │
│ gambar      │       │ tanggal_produksi │       │ keterangan    │       │ jumlah_fg    │
│ tanggal_masuk│      │ operator         │       │ status        │       │ jumlah_ng    │
│ customer    │       │ customer         │       │ tanggal_qc    │       │ operator     │
│ no_po       │       │ no_po            │       └───────────────┘       │ status       │
└─────────────┘       └──────────────────┘                               │ keterangan   │
                                                                         │ tanggal_packing│
                                                                         └──────────────┘

┌─────────────┐
│    users    │
├─────────────┤
│ id          │
│ name        │
│ email       │
│ password    │
│ role        │  ← 'admin' | 'operator'
└─────────────┘
```

---

## Routing Aplikasi

```
/login              → AuthController (guest only)
/logout             → AuthController (auth)

/dashboard          → DashboardController (auth) — tampilan sesuai role

── Admin only ─────────────────────────────────────────────────
/materials/**       → MaterialController   (CRUD)
/productions/**     → ProductionController (CRUD)
/monitoring         → MonitoringController (index)
/laporan            → LaporanController    (index, export)

── Operator only ──────────────────────────────────────────────
/qcs/**             → QcController         (CRUD)
/packings/**        → PackingController    (CRUD)
/tracking           → TrackingController   (index)
```

---
*Terakhir diperbarui: Mei 2026*