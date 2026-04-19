# 🏭 Sistem Inventaris Gudang

Aplikasi web manajemen stok barang gudang berbasis **CodeIgniter 4** + **MySQL** menggunakan arsitektur **MVC** dan paradigma **OOP**.

> Proyek UTS — Mata Kuliah Pemrograman Web 2

---

## 👥 Anggota Kelompok

| No | Nama Lengkap | NIM | Peran |
|----|--------------|-----|-------|
| 1 | Yoseph Martua Leonard Sianipar | 312410437 | Backend Developer|
| 2 | Noval Suprayoga | 312410305 | Backend Developer & Dokumentasi|
| 3 | Amanda Ramadani | 312410352 | Frontend Developer & Database |
| 4 | Ardito Sayudha | 312410627 | 

---

## 📋 Pembagian Tugas

### 👤 Yoseph Martua Leonard Sianipar — Backend Developer
- Setup project CodeIgniter 4 (struktur folder, konfigurasi awal)
- Konfigurasi database MySQL (`Database.php`, `.env`)
- Membuat sistem autentikasi: Login, Logout, Session
- Implementasi `AuthFilter` untuk proteksi halaman
- Integrasi dan testing keseluruhan aplikasi

### 👤 Noval Suprayoga — Backend Developer & Dokumentasi
- Membuat `BarangModel` (CRUD, validasi kode unik)
- Membuat `BarangController` (index, create, store, edit, update, delete)
- Implementasi logika validasi form barang
- Konfigurasi Routes untuk semua endpoint CRUD Barang
- Membuat README, laporan, dan dokumentasi proyek

### 👤 Amanda Ramadani  — Frontend Developer
- Membuat layout reusable (`header.php`, `footer.php`, sidebar navigasi)
- Membuat semua tampilan View (login, barang, transaksi, dashboard)
- Desain UI/UX: CSS styling, badge status stok, tabel responsif
- Implementasi info stok real-time saat pilih barang (JavaScript)
- Perancangan skema database (ERD, tabel admin/barang/transaksi)
- Membuat `TransaksiModel` (JOIN query, generate kode otomatis)
- Membuat `TransaksiController` (logika update & rollback stok otomatis)

### 👤 Ardito Sayudha

---

## ✨ Fitur Aplikasi

- 🔐 **Login & Logout** — Autentikasi admin dengan password terenkripsi bcrypt
- 📦 **Manajemen Barang** — CRUD data barang dengan validasi kode unik
- 🔄 **Transaksi Barang** — Pencatatan barang masuk/keluar + update stok otomatis
- ↩️ **Rollback Stok** — Stok otomatis dikembalikan saat transaksi diedit/dihapus
- 📊 **Dashboard** — Statistik stok, monitoring barang kritis, transaksi terbaru
- 🛡️ **Auth Guard** — Semua halaman terlindungi `AuthFilter`

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | CodeIgniter 4 (PHP 8.x) |
| Database | MySQL 5.7+ |
| Frontend | HTML5, CSS3, JavaScript |
| Server Lokal | XAMPP + `php spark serve` |
| Editor | Visual Studio Code |
| Paradigma | OOP + MVC |

---

## 📁 Struktur Folder

```
app/
├── Config/
│   ├── Database.php          ← konfigurasi koneksi MySQL
│   ├── Filters.php           ← registrasi AuthFilter
│   └── Routes.php            ← definisi semua route
├── Controllers/
│   ├── Auth.php              ← Login & Logout
│   ├── Dashboard.php         ← halaman dashboard statistik
│   ├── Barang.php            ← CRUD data barang
│   └── Transaksi.php         ← CRUD transaksi masuk/keluar
├── Filters/
│   └── AuthFilter.php        ← proteksi halaman dari akses tanpa login
├── Models/
│   ├── AdminModel.php        ← model autentikasi
│   ├── BarangModel.php       ← model data barang
│   └── TransaksiModel.php    ← model transaksi + JOIN query
└── Views/
    ├── auth/
    │   └── login.php
    ├── layout/
    │   ├── header.php        ← sidebar + CSS global
    │   └── footer.php
    ├── barang/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    ├── transaksi/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    └── dashboard/
        └── index.php
```

---

## ⚙️ Cara Instalasi & Menjalankan

### Prasyarat
- PHP 8.0+
- MySQL 5.7+ (via XAMPP)
- Composer

### Langkah-langkah

**1. Clone / ekstrak proyek**
```bash
cd C:/xampp/htdocs
# ekstrak folder proyek di sini
```

**2. Install dependency**
```bash
composer install
```

**3. Salin file environment**
```bash
cp env .env
```

**4. Edit file `.env` — sesuaikan konfigurasi database**
```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = inventaris_gudang
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

**5. Import database**
- Buka `http://localhost/phpmyadmin`
- Buat database baru: `inventaris_gudang`
- Import file `inventaris_gudang.sql`

**6. Jalankan server**
```bash
php spark serve
```

**7. Buka browser**
```
http://localhost:8080
```

---

## 🗺️ Alur Aplikasi

| Route | Halaman | Deskripsi |
|-------|---------|-----------|
| `GET /login` | Login | Form autentikasi admin |
| `POST /login` | - | Proses verifikasi login |
| `GET /dashboard` | Dashboard | Statistik stok & transaksi terbaru |
| `GET /barang` | Data Barang | Daftar semua barang + status stok |
| `GET /barang/create` | Tambah Barang | Form input barang baru |
| `GET /barang/edit/:id` | Edit Barang | Form edit data barang |
| `GET /transaksi` | Transaksi | Riwayat semua transaksi |
| `GET /transaksi/create` | Tambah Transaksi | Form transaksi masuk/keluar |
| `GET /transaksi/edit/:id` | Edit Transaksi | Edit + recalculate stok otomatis |
| `GET /logout` | - | Destroy session, redirect ke login |

---

## 🗄️ Skema Database

```sql
-- Tabel admin (autentikasi)
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_admin VARCHAR(100),
    username   VARCHAR(50) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL
);

-- Tabel barang (master data)
CREATE TABLE barang (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang  VARCHAR(20) UNIQUE NOT NULL,
    nama_barang  VARCHAR(100) NOT NULL,
    satuan       VARCHAR(20),
    stok         INT DEFAULT 0,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabel transaksi
CREATE TABLE transaksi (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi   VARCHAR(20) UNIQUE NOT NULL,
    barang_id        INT NOT NULL,
    jenis            ENUM('masuk','keluar') NOT NULL,
    jumlah           INT NOT NULL,
    keterangan       TEXT,
    tanggal          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barang_id) REFERENCES barang(id)
);
```

---

## 📌 Catatan

- Stok barang **otomatis terupdate** setiap ada transaksi masuk/keluar
- Saat transaksi **dihapus atau diedit**, stok akan **otomatis dikembalikan** (rollback)
- Barang dengan stok `≤ 10` akan ditampilkan sebagai **stok kritis** di dashboard
- Semua halaman memerlukan **login terlebih dahulu** sebelum bisa diakses
