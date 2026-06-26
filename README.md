# Sistem Inventaris Gudang

Aplikasi web manajemen stok barang gudang berbasis **CodeIgniter 4** dan **MySQL** menggunakan arsitektur **MVC** dan paradigma **OOP**.

> Proyek UTS - Mata Kuliah Pemrograman Web 2

---

## Anggota Kelompok

| No | Nama Lengkap | NIM | Peran |
|----|--------------|-----|-------|
| 1 | Yoseph Martua Leonard Sianipar | 312410437 | Backend Developer |
| 2 | Noval Suprayoga | 312410305 | Backend Developer & Dokumentasi |
| 3 | Amanda Ramadani | 312410352 | Frontend Developer & Database |
| 4 | Ardito Sayudha | 312410627 | - |

---

## Pembagian Tugas

### Yoseph Martua Leonard Sianipar - Backend Developer

- Setup project CodeIgniter 4 (struktur folder, konfigurasi awal).
- Konfigurasi database MySQL (`Database.php`, `.env`).
- Membuat sistem autentikasi: login, logout, session.
- Implementasi `AuthFilter` untuk proteksi halaman.
- Integrasi dan testing keseluruhan aplikasi.

### Noval Suprayoga - Backend Developer & Dokumentasi

- Membuat `BarangModel` (CRUD, validasi kode unik).
- Membuat `BarangController` (index, create, store, edit, update, delete).
- Implementasi logika validasi form barang.
- Konfigurasi routes untuk semua endpoint CRUD Barang.
- Membuat README, laporan, dan dokumentasi proyek.

### Amanda Ramadani - Frontend Developer & Database

- Membuat layout reusable (`header.php`, `footer.php`, sidebar navigasi).
- Membuat semua tampilan view (login, barang, transaksi, dashboard).
- Desain UI/UX: CSS styling, badge status stok, tabel responsif.
- Implementasi info stok real-time saat pilih barang (JavaScript).
- Perancangan skema database (ERD, tabel admin/barang/transaksi).
- Membuat `TransaksiModel` (JOIN query, generate kode otomatis).
- Membuat `TransaksiController` (logika update dan rollback stok otomatis).

### Ardito Sayudha

- -

---

## Fitur Aplikasi

- **Login & Logout** - Autentikasi admin/user dengan password terenkripsi bcrypt.
- **Manajemen Barang** - CRUD data barang dengan validasi kode unik.
- **Transaksi Barang** - Pencatatan barang masuk/keluar dan update stok otomatis.
- **Rollback Stok** - Stok otomatis dikembalikan saat transaksi diedit/dihapus.
- **Dashboard** - Statistik stok, monitoring barang kritis, transaksi terbaru.
- **Auth Guard** - Halaman utama terlindungi `AuthFilter`.

---

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | CodeIgniter 4 (PHP 8.x) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| Server Lokal | XAMPP + `php spark serve` |
| Editor | Visual Studio Code |
| Paradigma | OOP + MVC |

---

## Struktur Folder

```text
app/
├── Config/
│   ├── Database.php
│   ├── Filters.php
│   └── Routes.php
├── Controllers/
│   ├── Auth.php
│   ├── Dashboard.php
│   ├── Barang.php
│   └── Transaksi.php
├── Filters/
│   └── AuthFilter.php
├── Models/
│   ├── AdminModel.php
│   ├── BarangModel.php
│   ├── TransaksiModel.php
│   └── TransaksiDetailModel.php
├── Services/
│   └── TransaksiService.php
└── Views/
    ├── auth/
    ├── layout/
    ├── barang/
    ├── transaksi/
    └── dashboard/
```

---

## Cara Instalasi & Menjalankan

### Prasyarat

- PHP 8.0+
- MySQL/MariaDB via XAMPP
- Composer

### Langkah-langkah

1. Clone atau ekstrak proyek ke folder XAMPP.

```bash
cd C:/xampp/htdocs
```

2. Install dependency.

```bash
composer install
```

3. Salin atau sesuaikan file `.env`.

```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = inventaris_gudang
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

4. Pastikan MySQL di XAMPP aktif.

5. Jalankan migration.

```bash
php spark migrate
```

6. Jalankan server.

```bash
php spark serve
```

7. Buka aplikasi.

```text
http://localhost:8080
```

---

## Alur Aplikasi

| Method | Route | Halaman | Deskripsi |
|--------|-------|---------|-----------|
| GET | `/login` | Login | Form autentikasi admin/user |
| POST | `/login` | - | Proses verifikasi login |
| GET | `/dashboard` | Dashboard | Statistik stok dan transaksi terbaru |
| GET | `/barang` | Data Barang | Daftar semua barang dan status stok |
| GET | `/barang/create` | Tambah Barang | Form input barang baru |
| POST | `/barang/store` | - | Proses simpan barang |
| GET | `/barang/edit/{id}` | Edit Barang | Form edit data barang |
| POST | `/barang/update/{id}` | - | Proses update barang |
| POST | `/barang/delete/{id}` | - | Proses hapus barang |
| GET | `/transaksi` | Transaksi | Riwayat semua transaksi |
| GET | `/transaksi/create` | Tambah Transaksi | Form transaksi masuk/keluar |
| POST | `/transaksi/store` | - | Proses simpan transaksi |
| GET | `/transaksi/edit/{id}` | Edit Transaksi | Edit dan recalculate stok otomatis |
| POST | `/transaksi/update/{id}` | - | Proses update transaksi |
| POST | `/transaksi/delete/{id}` | - | Proses hapus transaksi |
| GET | `/logout` | - | Destroy session dan redirect ke login |

---

## Skema Database Lama

```sql
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_admin VARCHAR(100),
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) UNIQUE NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    satuan VARCHAR(20),
    stok INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(20) UNIQUE NOT NULL,
    barang_id INT NOT NULL,
    jenis ENUM('masuk','keluar') NOT NULL,
    jumlah INT NOT NULL,
    keterangan TEXT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barang_id) REFERENCES barang(id)
);
```

---

## Update Implementasi Terbaru

Bagian ini adalah tambahan dari refactor terbaru, tanpa menghapus dokumentasi lama di atas.

### Penyesuaian Schema Database Baru

Database terbaru memakai tabel login `user`, bukan `admin`.

```text
user
├── id
├── nama
├── username
├── password
├── role
└── is_active
```

Data barang sekarang terhubung ke tabel referensi.

```text
barang
├── kategori_id -> kategori.id
├── satuan_id   -> satuan.id
├── supplier_id -> supplier.id
└── gudang_id   -> gudang.id
```

Jika tabel `kategori`, `satuan`, `supplier`, atau `gudang` masih kosong, migration akan mengisi data default supaya form barang bisa langsung digunakan.

### Transaksi One-to-Many

Transaksi sekarang memakai pola header-detail.

```text
transaksi (header)
├── id
├── kode_transaksi
├── user_id
├── jenis
├── tanggal
└── keterangan

transaksi_detail (detail)
├── id
├── transaksi_id
├── barang_id
├── jumlah
├── harga_satuan
└── total_harga
```

Relasi:

- Satu `transaksi` dapat memiliki banyak `transaksi_detail`.
- Satu baris `transaksi_detail` mengarah ke satu `barang`.
- Stok barang otomatis disesuaikan saat transaksi dibuat, diedit, atau dihapus.
- Operasi transaksi, detail transaksi, dan stok dibungkus dalam database transaction lewat `TransaksiService`.

### File Tambahan/Penting

```text
app/Models/TransaksiDetailModel.php
app/Models/KategoriModel.php
app/Models/SatuanModel.php
app/Models/SupplierModel.php
app/Models/GudangModel.php
app/Services/TransaksiService.php
app/Database/Migrations/2026-05-25-000001_CreateInventorySchema.php
```

### Perbaikan CodeIgniter 4

- Controller memakai `BaseController`.
- Route tidak lagi bertumpuk.
- Auto-routing tetap nonaktif.
- Aksi hapus memakai POST.
- CSRF diaktifkan.
- Query relasi barang dan detail transaksi dipindahkan ke model/service agar controller lebih rapi.

### Role Akses

| Role | Akses |
|---|---|
| admin | Semua fitur, termasuk Master Data |
| operator | Dashboard, Data Barang, Transaksi; tidak bisa mengakses Master Data |
| viewer | Hanya melihat Dashboard, Data Barang, dan Transaksi; tidak bisa tambah/edit/hapus |

Akun tambahan yang dibuat lewat migration:

| Role | Username | Password |
|---|---|---|
| operator | `operator` | `Operator123` |
| viewer | `viewer` | `Viewer123` |

---

## Catatan

- Stok barang otomatis terupdate setiap ada transaksi masuk/keluar.
- Saat transaksi dihapus atau diedit, stok akan otomatis dikembalikan atau dihitung ulang.
- Barang dengan stok di bawah `stok_minimum` tampil sebagai stok kritis di dashboard.
- Semua halaman utama memerlukan login terlebih dahulu.
