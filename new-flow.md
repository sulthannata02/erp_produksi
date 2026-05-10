# Alur ERP Produksi & Packing (Optimasi Baru)

Setelah melakukan cross-check terhadap kebutuhan sistem dan praktik terbaik (best practice) ERP manufaktur, berikut adalah alur yang paling logis dan efisien untuk diterapkan:

## 1. Master Data (Admin)
*   **Kelola Material**: Admin menginput data teknis material (**Nama, Kode Part, Qty/Hanger, Qty/Box**).
    *   *Koreksi*: Tidak perlu memilih operator di sini. Material adalah benda statis, sedangkan operator adalah pelaku dinamis.
*   **Kelola User**: Admin mengelola akun (Admin & Operator).

## 2. Inbound / Gudang (Operator/Admin)
*   **Material Masuk**: Mencatat kedatangan barang mentah dari supplier.
    *   Input: `Jumlah Datang`.
    *   Efek: Menambah **Stok Material** di Master Data.

## 3. Proses Produksi (Operator/Admin)
*   **Input Produksi**: Mencatat hasil kerja di lapangan.
    *   **Operator**: Otomatis (jika Operator login sendiri) atau dipilih manual (jika Admin yang menginputkan data lapangan).
    *   Input: `Jumlah Hanger`.
    *   Sistem: Otomatis menghitung `Qty Total` (Hanger × Qty/Hanger).
    *   Efek: Mengurangi **Stok Material** di Master Data.

## 4. Quality Control / QC (Operator)
*   **Pemeriksaan**: Mengecek hasil dari Produksi tertentu.
    *   Input: `Jumlah FG` (Finish Good) dan `Jumlah NG` (Not Good).
    *   Status: Jika sudah di-QC, data siap untuk di-Packing.

## 5. Packaging / Packing (Operator)
*   **Pengemasan**: Membungkus barang yang sudah lolos QC (FG).
    *   Input: `Jumlah FG` yang akan dimasukkan ke box.
    *   Sistem: Otomatis menghitung `Jumlah Box` (Qty FG ÷ Qty/Box, pembulatan ke atas).
    *   Status: Mencetak **Kode Packing** unik.

## 6. Output (Final)
*   **Print Kanban (pdf)**: Mencetak label identitas untuk ditempel di Box.
    *   Isi Label: Customer, Nama Barang, Qty/Box, Jumlah Box, dan No. Packing.

---

### Kesimpulan Perubahan (Koreksi Janggal):
1.  **Pilih Operator di Material (HAPUS)**: Karena satu material bisa dikerjakan oleh siapa saja tergantung shift.
2.  **Pilih Operator di Produksi (PERTAHANKAN)**: Karena di sinilah transaksi kerja terjadi.
3.  **Otomatisasi**: Sistem harus lebih banyak menghitung (Hanger -> Pcs -> Box) daripada input manual untuk mengurangi kesalahan manusia.
