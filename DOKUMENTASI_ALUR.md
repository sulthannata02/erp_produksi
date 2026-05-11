# Dokumentasi Alur Sistem ERP Produksi & Packing
**PT. Actmetal Indonesia**

Dokumen ini menjelaskan alur kerja operasional sistem ERP yang mencakup modul Material, Produksi, QC, hingga Packing.

---

## 1. Peran Pengguna (User Roles)

Sistem ini membagi akses menjadi dua peran utama:
*   **Admin**: Bertanggung jawab atas pengelolaan data master (Material & User), pemantauan real-time (Monitoring), dan penarikan laporan produksi.
*   **Operator**: Bertanggung jawab atas seluruh input operasional di lapangan, mulai dari penerimaan barang, proses produksi, pengecekan kualitas (QC), hingga pengemasan (Packing).

---

## 2. Alur Kerja Operasional (Workflow)

### Tahap 1: Pengelolaan Data Master (Admin)
Sebelum operasional dimulai, Admin mendaftarkan data **Material** ke dalam sistem.
*   Input mencakup: Kode Part, Nama Material, Customer, serta standar Qty per Hanger dan Qty per Box.
*   Data ini menjadi acuan otomatis untuk kalkulasi di tahap produksi dan packing.

### Tahap 2: Barang Datang / Material Masuk (Operator)
Saat material dikirim oleh supplier/customer:
*   Operator mencatat kedatangan barang di menu **Barang Datang**.
*   Operator menginput Qty yang datang dan tanggal masuk.
*   Sistem secara otomatis menambah **Stok Aktual** material tersebut.

### Tahap 3: Perencanaan & Eksekusi Produksi (Operator)
Di sistem ini, Operator memiliki kendali penuh atas SPK agar fleksibel dengan kondisi lapangan:
*   **Buat SPK**: Operator membuat rencana produksi di menu **Produksi**. Cukup pilih material, masukkan target Hanger, dan sistem akan menghitung estimasi Qty Pcs & Box secara otomatis.
*   **Mulai Kerja**: Operator mengubah status SPK menjadi **"Proses"** saat mulai mengerjakan batch tersebut. Pada tahap ini, stok material awal akan terpotong secara otomatis.
*   **Selesai**: Setelah pekerjaan di mesin selesai, Operator menginput jumlah hanger aktual yang dihasilkan dan mengubah status menjadi **"Selesai"**.

### Tahap 4: Quality Control / QC (Operator)
Setiap batch produksi yang selesai harus melewati tahap QC:
*   Operator QC memilih data dari hasil produksi yang sudah selesai.
*   Operator menginput jumlah **FG (Finished Goods)** yang bagus dan **NG (Not Good)** yang reject.
*   Hanya jumlah FG yang akan tersedia untuk tahap Packing selanjutnya.

### Tahap 5: Packing / Pengemasan (Operator)
Tahap akhir sebelum barang siap dikirim:
*   Operator memilih data dari hasil QC (FG).
*   Sistem menghitung otomatis jumlah Box yang dibutuhkan berdasarkan standar material.
*   Setelah disimpan, Operator dapat mencetak **Label Packing / Kanban** untuk ditempelkan pada box.
*   Stok akhir barang jadi (Finished Goods) akan tercatat di sistem.

---

## 3. Monitoring & Pelaporan (Admin)

Admin memiliki akses khusus untuk mengawasi jalannya seluruh tahap di atas:
*   **Dashboard**: Melihat statistik ringkas total material, produksi, QC, dan packing secara global.
*   **Monitoring**: Pantau status setiap Kode SPK secara real-time (apakah masih di tahap Produksi, QC, atau sudah Packing).
*   **Laporan**: Menarik data rekapitulasi produksi berdasarkan range tanggal atau customer tertentu dan mengunduhnya dalam format PDF untuk keperluan arsip atau evaluasi.

---

**Catatan Keamanan Data**: Sistem dilengkapi dengan validasi otomatis. Operator tidak dapat melompati tahapan (misal: Packing sebelum QC) untuk memastikan data yang masuk ke laporan 100% akurat.
