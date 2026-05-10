Sul, ini revisi dan tambahan fitur yang gua mau yaa 🙏

1. Role Operator

* Operator ga bisa tambah/edit material
* Yang bisa tambah material admin aja

2. Master Material
   Di material tambahin:

* Nama customer
* Kode material
* Qty per hanger
* Qty per box

Contoh:

* 1 hanger = 2 pcs
* 1 box = 250 pcs

3. Dashboard

* Sidebar dihapus aja
* Semua fitur tampil di dashboard dalam bentuk card/menu
* Tambahin prioritas produksi / delivery note
* Ada info material prioritas + qty

4. Material

* Tambahin filter/search berdasarkan customer
* Kalau pilih customer, material yang tampil cuma milik customer itu
* Tambahin tabel qty per hanger sama qty perbox juga kaya gambar yang gua kasih


5. Material Masuk / Barang Datang(kalo admin kan ada tambah material, nah operator ada material masuk)

* Tambahin fitur material masuk/barang datang untuk gudang
* Gudang tidak menambah  material baru
* Gudang hanya pilih material yang sudah ada di material
* Input:

  * Material
  * Customer
  * Qty barang datang
  * Delivery Note (DN)
  * Tanggal datang
* Stok otomatis bertambah sesuai qty barang datang

6. Produksi

* Produksi juga ada customer
* Operator pilih material dari  material
* Input jumlah hanger
* Qty total otomatis keitung
* Total box otomatis keitung dari qty per box

7. QC

* Tambahin kolom customer
* Data QC ngambil dari hasil produksi
* Ada FG dan NG

8. Packing

* Packing ngambil data dari QC/produksi
* Tampilan lebih simple aja
* Ada:

  * Customer
  * Material
  * Qty total
  * Qty per box
  * Total box otomatis

9. Print Kanban

* Di tabel packing tambahin tombol “Print Kanban”
* Jadi boxer tinggal print label dari packing
* Ga usah bikin menu boxer terpisah

10. Kanban
    Isi kanban:

* Customer
* Material
* Kode material
* Qty per box
* Total box
* Tanggal
* Nama team/operator otomatis mengikuti user yang login

11. UI

* Dibikin clean dan profesional
* Tetep sederhana aja biar cocok buat ERP sederhana KP

Flow sistem:
Material Masuk → Produksi → QC → Packing → Print Kanban

# catatan tambahan
buat packing ng sama fg nya tetep ada ya, gua lupa nambahin di list revisi.
