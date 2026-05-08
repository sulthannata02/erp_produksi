DOKUMENTASI FITUR DAN HALAMAN SISTEM INFORMASI PRODUKSI & PACKING
Dokumen ini menjelaskan pembagian fitur, halaman, serta alur sistem berdasarkan role pengguna
agar sistem yang dibangun tetap sederhana namun terstruktur dan sesuai dengan kebutuhan di
lapangan.
GAMBARAN UMUM SISTEM
Sistem ini merupakan aplikasi berbasis web yang digunakan untuk mengintegrasikan proses
produksi dari gudang hingga packing. Sistem dibagi menjadi dua jenis pengguna, yaitu Admin dan
Operator. Masing-masing memiliki akses ke halaman yang berbeda sesuai dengan tugasnya.
ROLE DAN HALAMAN SISTEM
ADMIN (Gudang dan Produksi)
Setelah login, admin akan masuk ke dashboard utama yang menampilkan ringkasan data seperti
jumlah material dan produksi.
Halaman yang dapat diakses oleh admin meliputi:- Dashboard Admin
Halaman ini menampilkan informasi umum terkait jumlah material dan status produksi sebagai
gambaran awal kondisi sistem.- Halaman Material
Digunakan untuk menginput data material yang masuk dari gudang. Admin dapat menambahkan
data seperti nama material, jumlah, dan tanggal masuk. Data ini akan menjadi dasar proses
produksi.- Halaman Produksi
Digunakan untuk mencatat material yang sedang diproses. Admin memilih material dari data yang
sudah ada, kemudian menginput jumlah yang diproses dan memperbarui status produksi.- Halaman Monitoring
Digunakan untuk melihat keseluruhan data mulai dari material hingga produksi. Halaman ini
membantu mengurangi komunikasi manual karena semua data dapat dipantau secara langsung.
OPERATOR (QC dan Packing)
Operator akan masuk ke dashboard operator yang menampilkan data produksi yang perlu
diperiksa atau diproses lebih lanjut.
Halaman yang dapat diakses oleh operator meliputi:
Dashboard Operator
Menampilkan daftar pekerjaan seperti data produksi yang belum di-QC atau belum dilakukan
packing.- Halaman QC
Digunakan untuk melakukan pengecekan kualitas produk setelah proses produksi. Operator
memilih data produksi lalu menginput hasil apakah produk memenuhi standar atau tidak.- Halaman Packing
Digunakan untuk proses pengemasan. Operator akan menginput jumlah produk yang termasuk
Finished Good (FG) dan Not Good (NG).- Halaman Tracking
Digunakan untuk melihat status seluruh proses mulai dari material, produksi, QC, hingga packing.
Halaman ini memastikan informasi tersedia secara real-time.
ALUR SISTEM
Proses dimulai dari admin yang menginput material pada halaman material. Data tersebut
kemudian digunakan pada halaman produksi untuk mencatat proses produksi. Setelah itu, operator
melakukan pengecekan pada halaman QC dan melanjutkan ke halaman packing untuk mencatat
hasil akhir berupa FG dan NG.
Seluruh data dari setiap tahap akan terhubung dan dapat dipantau melalui halaman tracking,
sehingga tidak diperlukan lagi komunikasi manual antar bagian.
KESIMPULAN
Dengan pembagian halaman berdasarkan role ini, sistem menjadi lebih terstruktur, mudah
digunakan, dan tetap sederhana. Setiap pengguna hanya mengakses fitur yang dibutuhkan sesuai
tugasnya, sehingga sistem tetap efisien namun mampu menjawab permasalahan utama seperti
kurangnya integrasi dan tidak adanya data real-time