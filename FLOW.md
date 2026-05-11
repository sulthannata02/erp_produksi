# Alur Kerja (Workflow) ERP Produksi
**PT. ACTMETAL INDONESIA**

Sistem ini dirancang dengan pemisahan antara **Perencanaan (Blueprint)** dan **Eksekusi (Aktual)** untuk akurasi data stok yang maksimal.

---

## 🏗️ 1. Tahap Perencanaan / Blueprint (Admin)
*   **Input Master Material**: Admin mendaftarkan identitas material.
*   **Rencana Produksi (SPK)**: Admin membuat blueprint produksi, menentukan target (Target Hanger), dan menugaskan Operator.
*   **Status: Rencana**: Pada tahap ini, stok di gudang **belum berkurang**. Ini baru sebatas perintah kerja.

## 📦 2. Tahap Penerimaan Material (Operator)
*   **Barang Datang**: Operator menginput kedatangan material fisik.
*   **Aktual Stok**: Input ini menambah stok fisik yang ada di gudang. Stok inilah yang nantinya akan divalidasi saat produksi dimulai.

## 🏭 3. Tahap Validasi & Eksekusi (Operator)
*   **Validasi SPK**: Operator melihat daftar SPK yang ditugaskan kepada mereka di Dashboard.
*   **Mulai Kerja**: Operator menginput jumlah hanger aktual yang beneran dikerjakan (Validasi).
*   **Konsumsi Stok**: Saat Operator menekan "Mulai", sistem akan mengecek ketersediaan stok fisik. Jika cukup, **Stok Aktual dikurangi** dan status berubah menjadi **Proses**.

## 🛡️ 4. Tahap Quality Control (Operator)
*   **Input QC**: Setelah pengerjaan fisik selesai, Operator QC memeriksa hasilnya.
*   **Pemisahan**: Memasukkan jumlah **FG (Good)** dan **NG (Reject)**.

## 📦 5. Tahap Packing & Kanban (Operator)
*   **Packing**: Barang yang lolos QC (FG) dikemas ke dalam box.
*   **Hitung Box**: Sistem menghitung otomatis jumlah box berdasarkan standar konversi.
*   **Cetak Kanban**: Mencetak label identitas untuk setiap box.

## 📊 6. Tahap Monitoring & Laporan (Admin)
*   **Pipeline Monitoring**: Admin memantau progres tiap batch: **SPK -> Produksi -> QC -> Packing**.
*   **Laporan Tahunan/Bulanan**: Menarik data total FG, NG, dan penggunaan material secara akurat.

---

### 💡 Perbedaan Kunci
*   **Blueprint (Admin)**: Rencana kerja / SPK. Belum memotong stok.
*   **Aktual (Operator)**: Validasi pengerjaan di lapangan. Di sini stok fisik beneran dipotong.