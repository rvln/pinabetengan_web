CREATE DATABASE desa_pinabetengan;
USE desa_pinabetengan;

-- Tabel berita
CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    tanggal DATE DEFAULT (CURRENT_DATE),
    gambar VARCHAR(255)
);

-- Tabel statistik desa (bisa diupdate manual via admin)
CREATE TABLE statistik (
    id INT PRIMARY KEY DEFAULT 1,
    penduduk INT DEFAULT 0,
    rt INT DEFAULT 0,
    rw INT DEFAULT 0,
    umkm INT DEFAULT 0,
    wisata_pertanian INT DEFAULT 0
);

-- Tabel APBDes
CREATE TABLE apbdes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uraian VARCHAR(255) NOT NULL,
    anggaran DECIMAL(15,2) NOT NULL,
    realisasi DECIMAL(15,2) DEFAULT 0.00
);

-- Tabel admin (untuk login)
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL  -- simpan hash
);

-- Isi data awal
INSERT INTO statistik (penduduk, rt, rw, umkm, wisata_pertanian) 
VALUES (2500, 5, 3, 12, 4);

INSERT INTO admin (username, password) 
VALUES ('admin', '$$2y$12$MqfCfNe7n5ZWYI8QMdhxVOiin4.NLg2Y4/3pM9HAl5MKIKrZK3LV.'); 
-- password: password (gunakan password_hash() di PHP untuk ganti)
-- Tabel statistik
CREATE TABLE IF NOT EXISTS statistik (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penduduk INT DEFAULT 0,
    rt INT DEFAULT 0,
    umkm INT DEFAULT 0,
    wisata_pertanian INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert data default
INSERT INTO statistik (id, penduduk, rt, umkm, wisata_pertanian) 
VALUES (1, 2500, 8, 15, 5) 
ON DUPLICATE KEY UPDATE penduduk=2500, rt=8, umkm=15, wisata_pertanian=5;

-- Tabel berita (jika belum ada)
CREATE TABLE IF NOT EXISTS berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    isi TEXT,
    tanggal DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel umkm (jika belum ada)  
CREATE TABLE IF NOT EXISTS umkm (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS umkm (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_umkm VARCHAR(255) NOT NULL,
    pemilik VARCHAR(255),
    deskripsi TEXT,
    alamat TEXT,
    telepon VARCHAR(20),
    produk TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- grant untuk user nonroot
-- 1. Buat database
CREATE DATABASE IF NOT EXISTS desa_pinabetengan;

-- 2. Pastikan user 'andro' sudah ada (jika belum, buat)
CREATE USER IF NOT EXISTS 'andro'@'localhost' IDENTIFIED BY 'kakidikepala';

-- 3. Beri semua hak akses ke database desa_pinabetengan untuk user 'andro'
GRANT ALL PRIVILEGES ON desa_pinabetengan.* TO 'andro'@'localhost';

-- 4. Muat ulang hak akses
FLUSH PRIVILEGES;

-- 5. (Opsional) Cek apakah user andro punya akses
SHOW GRANTS FOR 'andro'@'localhost';


-- Tabel layanan
CREATE TABLE layanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255),
    deskripsi TEXT,
    icon VARCHAR(50),
    persyaratan JSON,
    waktu_proses VARCHAR(100),
    biaya VARCHAR(100),
    status ENUM('active','inactive'),
    urutan INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel apbdes  
CREATE TABLE apbdes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun VARCHAR(4),
    anggaran_pendapatan BIGINT,
    anggaran_belanja BIGINT,
    saldo_awal BIGINT,
    file_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel rencana_kerja
CREATE TABLE rencana_kerja (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255),
    tahun VARCHAR(4),
    deskripsi TEXT,
    lokasi VARCHAR(255),
    anggaran BIGINT,
    status ENUM('Perencanaan','Dalam Pengerjaan','Selesai'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE potensi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255),
    jenis VARCHAR(100),
    deskripsi TEXT,
    deskripsi_lengkap TEXT,
    gambar VARCHAR(500),
    icon VARCHAR(50),
    fasilitas JSON,
    aktivitas JSON,
    lokasi VARCHAR(255),
    jam_operasional VARCHAR(100),
    tiket VARCHAR(100),
    harga VARCHAR(100),
    kontak VARCHAR(20),
    rating DECIMAL(2,1),
    highlight BOOLEAN DEFAULT FALSE,
    status ENUM('active','inactive'),
    urutan INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Tabel admin/users
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    nama_lengkap VARCHAR(100),
    email VARCHAR(100),
    level ENUM('superadmin', 'admin', 'editor'),
    status ENUM('active', 'inactive'),
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel berita
CREATE TABLE berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    isi TEXT,
    excerpt TEXT,
    gambar VARCHAR(255),
    kategori VARCHAR(50),
    penulis VARCHAR(100),
    tanggal DATE,
    status ENUM('draft', 'published'),
    dibaca INT DEFAULT 0,
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel potensi
CREATE TABLE potensi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255),
    jenis VARCHAR(100),
    deskripsi TEXT,
    deskripsi_lengkap TEXT,
    gambar VARCHAR(255),
    icon VARCHAR(50),
    fasilitas JSON,
    aktivitas JSON,
    lokasi VARCHAR(255),
    jam_operasional VARCHAR(100),
    tiket VARCHAR(100),
    harga VARCHAR(100),
    kontak VARCHAR(20),
    rating DECIMAL(2,1),
    highlight BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive'),
    urutan INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel layanan
CREATE TABLE layanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255),
    deskripsi TEXT,
    icon VARCHAR(50),
    persyaratan JSON,
    waktu_proses VARCHAR(100),
    biaya VARCHAR(100),
    status ENUM('active', 'inactive'),
    urutan INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel APBDes
CREATE TABLE apbdes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun VARCHAR(4),
    anggaran_pendapatan BIGINT,
    anggaran_belanja BIGINT,
    saldo_awal BIGINT,
    file_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel rencana_kerja
CREATE TABLE rencana_kerja (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255),
    tahun VARCHAR(4),
    deskripsi TEXT,
    lokasi VARCHAR(255),
    anggaran BIGINT,
    status ENUM('Perencanaan', 'Dalam Pengerjaan', 'Selesai'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel kontak
CREATE TABLE kontak (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    email VARCHAR(100),
    telepon VARCHAR(20),
    subjek VARCHAR(255),
    pesan TEXT,
    status ENUM('baru', 'dibalas', 'selesai'),
    catatan_admin TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel profil
CREATE TABLE profil (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tentang TEXT,
    jumlah_penduduk INT,
    visi TEXT,
    misi TEXT,
    sejarah TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel pejabat
CREATE TABLE pejabat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    jabatan VARCHAR(100),
    periode_mulai DATE,
    periode_selesai DATE,
    foto VARCHAR(255),
    urutan INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);