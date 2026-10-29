# Catatan Perbaikan – Sistem Inventaris & Penjualan

Dokumen ini merangkum perbaikan bug dan peningkatan tampilan dibanding versi awal.

## 1. Bug yang diperbaiki

| No | Masalah di versi awal | Perbaikan |
|----|------------------------|-----------|
| 1 | Tambah produk gagal (`Field 'code' doesn't have a default value`) | Kode produk dibuat otomatis (contoh: `PRD-7KQ2XD`) dan selalu unik |
| 2 | Harga jual kosong di transaksi (kode memakai kolom `selling_price`, sedangkan database punya kolom `price`) | Semua kode memakai kolom `price` secara konsisten |
| 3 | Role admin/staf tidak tersimpan (kolom `role` tidak ada di `$fillable` model User) | `role` ditambahkan ke `$fillable` |
| 4 | Halaman produk bisa diakses tanpa login | Route produk dan transaksi berada di dalam middleware `auth` |
| 5 | Halaman Data Produk terpotong (tabel tidak tertutup) | View ditulis ulang lengkap |
| 6 | Rincian barang tidak tersimpan saat transaksi (`sale_details` kosong) | Setiap transaksi menyimpan rincian barang, harga, dan subtotal |
| 7 | Dashboard menampilkan SKU kosong (kolom `sku` tidak ada) | Diganti kode produk |
| 8 | Nomor invoice bisa bentrok jika dua transaksi terjadi dalam detik yang sama | Format `INV-YYYYMMDD-XXXXX` dengan pengecekan unik |
| 9 | Menu "Data Produk" muncul dua kali dan HTML navbar rusak | Navbar ditata ulang dan responsif di layar kecil |
| 10 | Admin bisa menghapus akunnya sendiri atau akun yang punya riwayat transaksi | Dicegah di server dan di tampilan |
| 11 | Produk yang sudah pernah terjual bisa dihapus (riwayat hilang) | Produk dengan riwayat transaksi tidak bisa dihapus |
| 12 | Redirect setelah login ke `/home` (route tidak ada) | Diarahkan ke `/dashboard` |
| 13 | Logika bisnis bercampur di file route | Dipindah ke controller: `AuthController`, `DashboardController`, `ProductController`, `TransactionController`, `UserController` |
| 14 | `AuthController` ganda dan tidak terpakai | Sekarang dipakai untuk login dan logout |

## 2. Peningkatan tampilan

- Navbar berwarna biru dengan ikon, menu aktif ter-highlight, dan tombol menu di layar kecil.
- Dashboard dengan 4 kartu ringkasan (produk, transaksi, total pendapatan, stok menipis), daftar stok rendah, dan transaksi terbaru.
- Badge stok: hijau (aman), kuning (menipis), merah (habis).
- Kasir menampilkan pratinjau total bayar secara langsung saat memilih produk dan jumlah.
- Produk yang stoknya habis otomatis tidak bisa dipilih di kasir.
- Halaman detail transaksi siap dicetak.
- Notifikasi sukses atau gagal muncul di pojok kanan dan hilang otomatis.
- Konfirmasi sebelum menghapus data.
- Tanggal ditampilkan dalam Bahasa Indonesia.

## 3. Lain-lain

- Seeder menyediakan 5 produk contoh dan 2 akun (admin dan staf) agar langsung bisa didemokan.
- Ditambahkan 9 tes fitur (`tests/Feature/InventorySalesTest.php`) untuk login, hak akses, pembuatan produk, transaksi, dan validasi stok.
- `phpunit.xml` sekarang memakai SQLite di memori saat testing, sehingga tes tidak menghapus database utama.
- File tes default Laravel (`ExampleTest.php`) dihapus karena tidak relevan.

## 4. Cara menjalankan

```bash
composer install            # jika folder vendor belum ada
php artisan migrate:fresh --seed
php artisan serve
php artisan test            # menjalankan 9 tes fitur
```

Akun demo:
- Admin: `admin@gmail.com` / `password123`
- Staf: `staff@gmail.com` / `password123`
