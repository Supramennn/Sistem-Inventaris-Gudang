# Sistem Inventaris Gudang

Aplikasi manajemen stok gudang berbasis CodeIgniter 4 dan MySQL.

## Fitur Utama

- Login admin dengan session.
- CRUD data barang.
- Transaksi barang masuk dan keluar.
- Transaksi one-to-many: satu kode transaksi dapat berisi banyak barang.
- Perubahan stok otomatis saat transaksi dibuat, diubah, atau dihapus.
- Validasi stok untuk transaksi keluar.
- Dashboard ringkas untuk stok, transaksi, dan barang kritis.

## Struktur Penting

```text
app/
├── Controllers/
│   ├── Auth.php
│   ├── Barang.php
│   ├── Dashboard.php
│   └── Transaksi.php
├── Database/Migrations/
│   └── 2026-05-25-000001_CreateInventorySchema.php
├── Models/
│   ├── AdminModel.php
│   ├── BarangModel.php
│   ├── TransaksiModel.php
│   └── TransaksiDetailModel.php
├── Services/
│   └── TransaksiService.php
└── Views/
    ├── barang/
    ├── dashboard/
    ├── transaksi/
    └── layout/
```

## Skema Transaksi One-to-Many

```text
transaksi (header)
├── id
├── kode_transaksi
├── jenis
├── tanggal
└── keterangan

transaksi_detail (detail)
├── id
├── transaksi_id
├── barang_id
└── jumlah
```

Relasi:

- `transaksi.id` punya banyak `transaksi_detail.transaksi_id`.
- `barang.id` dipakai oleh banyak `transaksi_detail.barang_id`.

## Instalasi

1. Pastikan PHP 8.2+, MySQL, dan Composer tersedia.
2. Sesuaikan koneksi database di `.env` atau `app/Config/Database.php`.
3. Jalankan dependency jika folder `vendor` belum ada:

```bash
composer install
```

4. Jalankan migration:

```bash
php spark migrate
```

5. Jalankan aplikasi:

```bash
php spark serve
```

6. Buka:

```text
http://localhost:8080
```

## Catatan Migration

Migration akan membuat tabel baru jika belum ada. Jika proyek lama masih memakai kolom `barang_id` dan `jumlah` di tabel `transaksi`, data lama akan disalin ke tabel `transaksi_detail` agar bisa ikut format one-to-many.

## Route Utama

| Method | Route | Keterangan |
|---|---|---|
| GET | `/login` | Form login |
| POST | `/login` | Proses login |
| GET | `/dashboard` | Dashboard |
| GET | `/barang` | Daftar barang |
| GET | `/barang/create` | Form tambah barang |
| POST | `/barang/store` | Simpan barang |
| GET | `/barang/edit/{id}` | Form edit barang |
| POST | `/barang/update/{id}` | Update barang |
| POST | `/barang/delete/{id}` | Hapus barang |
| GET | `/transaksi` | Daftar transaksi |
| GET | `/transaksi/create` | Form transaksi |
| POST | `/transaksi/store` | Simpan transaksi |
| GET | `/transaksi/edit/{id}` | Form edit transaksi |
| POST | `/transaksi/update/{id}` | Update transaksi |
| POST | `/transaksi/delete/{id}` | Hapus transaksi |

## Implementasi

- Controller memakai `BaseController`.
- Route tidak lagi bertumpuk dan auto-routing tetap nonaktif.
- Aksi hapus memakai POST dan CSRF aktif.
- Logika stok dipusatkan di `TransaksiService`.
- Operasi header transaksi, detail transaksi, dan stok dibungkus database transaction.
