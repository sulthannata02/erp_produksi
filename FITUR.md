# Dokumentasi Fitur ERP Produksi & Packing
**PT. ACTMETAL INDONESIA**

Sistem ini membagi tanggung jawab antara manajemen strategi (Admin) dan eksekusi lapangan (Operator).

---

## 🔑 Hak Akses: Administrator (Admin)
Fokus pada perencanaan, pengawasan, dan pengelolaan aset.

| Fitur | Deskripsi | Kegunaan |
| :--- | :--- | :--- |
| **Master Material** | Pengelolaan Blueprint Material. | Mengatur Target Stok, gambar, dan standar konversi (Pcs/Box, Pcs/Hanger). |
| **Rencana Produksi (SPK)** | Membuat Blueprint/Perintah Kerja. | Menentukan material, target hanger, dan menugaskan operator (Belum potong stok). |
| **Monitoring Pipeline** | Pantau status SPK secara real-time. | Melihat batch mana yang masih rencana, sedang diproses, atau sudah QC/Packing. |
| **Kelola Pengguna** | Manajemen akun. | Mengatur hak akses Admin dan Operator. |
| **Laporan Terpadu** | Export PDF Laporan. | Analisis hasil produksi, jumlah barang reject (NG), dan produktivitas. |

---

## 🛠️ Hak Akses: Operator (Team Produksi)
Fokus pada validasi data aktual dan pengerjaan fisik.

| Fitur | Deskripsi | Kegunaan |
| :--- | :--- | :--- |
| **Dashboard Tugas** | Daftar SPK & Notifikasi. | Melihat perintah kerja dari Admin yang harus segera dikerjakan. |
| **Validasi Produksi** | Tombol "Mulai Kerja" pada SPK. | Menginput jumlah hanger aktual dan **memotong stok fisik** di gudang. |
| **Barang Datang** | Input kedatangan material. | Menambah **Stok Aktual** gudang sebagai modal awal produksi. |
| **Quality Control (QC)** | Input FG & NG. | Memisahkan barang layak kirim (Fine Good) dan barang cacat (Not Good). |
| **Packing & Kanban** | Pengemasan & Cetak Label. | Menghasilkan kode box dan label fisik untuk pengiriman. |

---

## 🔄 Integrasi Blueprint vs Aktual
1. **Admin** membuat rencana di sistem (**Blueprint**).
2. **Operator** menerima perintah tersebut di dashboard.
3. Saat barang beneran dikerjakan, **Operator** melakukan validasi jumlah (**Aktual**).
4. Sistem otomatis memotong stok fisik hanya ketika **Operator** sudah mulai bekerja.
