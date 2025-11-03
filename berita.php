<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil parameter kategori dari URL
$kategori = $_GET['kategori'] ?? 'kegiatan';
$page_title = $kategori === 'pengumuman' ? 'Pengumuman Resmi' : 'Kegiatan & Program';

// Ambil data berita dari database berdasarkan kategori
try {
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE kategori = ? ORDER BY tanggal DESC, id DESC");
    $stmt->execute([$kategori]);
    $berita = $stmt->fetchAll();
} catch (PDOException $e) {
    $berita = [];
}

// Data fallback jika tidak ada berita di database
if (empty($berita)) {
    if ($kategori === 'kegiatan') {
        $berita = [
            [
                'id' => 1,
                'judul' => 'Festival Budaya Pinabetengan 2024 Sukses Digelar dengan Meriah',
                'isi' => 'Festival Budaya Pinabetengan 2024 berhasil diselenggarakan dengan meriah di lapangan desa. Acara yang dihadiri oleh ribuan pengunjung dari berbagai daerah ini menampilkan berbagai kesenian tradisional Minahasa, mulai dari tarian Maengket, musik Bambu, hingga pameran kuliner khas desa. Festival ini menjadi ajang pelestarian budaya sekaligus promosi wisata desa.',
                'tanggal' => '2024-03-15',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Admin Desa',
                'dibaca' => 1250
            ],
            [
                'id' => 2,
                'judul' => 'Pelatihan UMKM Tingkatkan Kualitas Produk Pangsit Jagung',
                'isi' => 'Pemerintah desa menyelenggarakan pelatihan peningkatan kualitas produk pangsit jagung bagi para pelaku UMKM. Pelatihan yang diikuti oleh 25 peserta dari berbagai dusun ini fokus pada teknik pengemasan, pemasaran digital, dan standar higienitas. Diharapkan produk pangsit jagung khas desa dapat bersaing di pasar yang lebih luas.',
                'tanggal' => '2024-03-10',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Bidang Ekonomi',
                'dibaca' => 890
            ],
            [
                'id' => 3,
                'judul' => 'Perbaikan Jalan Desa Selesai, Akses Transportasi Lebih Lancar',
                'isi' => 'Pekerjaan perbaikan jalan sepanjang 2 km di Desa Pinabetengan Selatan telah selesai dilaksanakan tepat waktu. Pembangunan menggunakan material berkualitas tinggi dengan sistem drainase yang baik. Hal ini akan memudahkan akses transportasi warga dan pengunjung menuju destinasi wisata desa.',
                'tanggal' => '2024-03-05',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Bidang PU',
                'dibaca' => 756
            ],
            [
                'id' => 4,
                'judul' => 'Bendang Stable Jadi Destinasi Wisata Edukasi Favorit',
                'isi' => 'Bendang Stable semakin populer sebagai destinasi wisata edukasi keluarga. Rata-rata dikunjungi 100 pengunjung per minggu yang ingin belajar tentang perawatan kuda tradisional Minahasa. Fasilitas terus ditingkatkan dengan penambahan area bermain anak dan kafe tradisional.',
                'tanggal' => '2024-02-28',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Bidang Pariwisata',
                'dibaca' => 1120
            ]
        ];
    } else {
        $berita = [
            [
                'id' => 5,
                'judul' => 'Pengumuman Jadwal Pemadaman Listrik Bergilir',
                'isi' => 'Diberitahukan kepada seluruh warga Desa Pinabetengan Selatan bahwa akan dilakukan pemadaman listrik bergilir pada tanggal 20-22 Maret 2024 untuk perawatan jaringan listrik. Pemadaman akan dilakukan pukul 09.00-15.00 WITA secara bergilir sesuai zona.',
                'tanggal' => '2024-03-18',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'PLN Wilayah',
                'dibaca' => 1567
            ],
            [
                'id' => 6,
                'judul' => 'Pendaftaran Bantuan Sosial Tahap II Dibuka',
                'isi' => 'Pemerintah Desa membuka pendaftaran bantuan sosial tahap II untuk masyarakat kurang mampu. Pendaftaran dibuka mulai tanggal 25 Maret - 5 April 2024 di Kantor Desa. Syarat: Fotokopi KTP, KK, dan surat keterangan tidak mampu dari RT/RW.',
                'tanggal' => '2024-03-20',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'Bidang Sosial',
                'dibaca' => 934
            ],
            [
                'id' => 7,
                'judul' => 'Pengumuman Libur Nasional Hari Raya Nyepi',
                'isi' => 'Berdasarkan kalender nasional, Hari Raya Nyepi 2024 jatuh pada tanggal 11 Maret 2024. Seluruh aktivitas perkantoran desa akan diliburkan. Pelayanan darurat tetap dapat diakses melalui nomor hotline 24 jam.',
                'tanggal' => '2024-03-08',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'Sekretaris Desa',
                'dibaca' => 756
            ],
            [
                'id' => 8,
                'judul' => 'Pemberitahuan Pelaksanaan Kerja Bakti Bersama',
                'isi' => 'Dalam rangka menyambut Hari Kebersihan Nasional, akan diadakan kerja bakti bersama pada hari Minggu, 24 Maret 2024 pukul 07.00 WITA. Tempat kumpul: Lapangan Desa. Diharapkan partisipasi seluruh warga.',
                'tanggal' => '2024-03-22',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'Ketua RT',
                'dibaca' => 890
            ]
        ];
    }
}

// Kategori berita untuk filter
$kategori_list = ['Semua', 'Kegiatan', 'Pengumuman'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $page_title ?> - Desa Pinabetengan Selatan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
  --bg-primary: #F5F3EF;
  --bg-secondary: #FFFFFF;
  --bg-tertiary: #E8E4DC;
  --text-primary: #2C2C2C;
  --text-secondary: #757575;
  --accent-red: #C62828;
  --accent-yellow: #FFD54F;
  --accent-green: #7CB342;
  --accent-blue: #2196F3;
  --accent-purple: #9C27B0;
  --accent-orange: #FF9800;
  --accent-dark: #1A1A1A;
  --shadow: 0 4px 16px rgba(0,0,0,0.08);
  --shadow-hover: 0 8px 32px rgba(0,0,0,0.12);
  --radius: 16px;
  --transition: 0.3s ease;
}

[data-theme="dark"] {
  --bg-primary: #1A1A1A;
  --bg-secondary: #2D2D2D;
  --bg-tertiary: #252525;
  --text-primary: #FFFFFF;
  --text-secondary: #E0E0E0;
  --accent-red: #EF5350;
  --accent-yellow: #FFCA28;
  --accent-green: #8BC34A;
  --accent-blue: #42A5F5;
  --accent-purple: #BA68C8;
  --accent-orange: #FFB74D;
  --shadow: 0 4px 16px rgba(0,0,0,0.3);
  --shadow-hover: 0 8px 32px rgba(0,0,0,0.4);
}

* { 
  box-sizing: border-box;
  transition: background-color 0.3s ease, color 0.3s ease;
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--bg-primary);
  color: var(--text-primary);
  line-height: 1.6;
  margin: 0;
  padding-top: 0;
}

/* === IMPROVED NAVBAR - SAME AS INDEX.PHP === */
.navbar-custom {
  background: rgba(245, 243, 239, 0.95);
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  box-shadow: 0 2px 15px rgba(44, 44, 44, 0.08);
  padding: 0.8rem 0;
  transition: var(--transition);
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;
}

.navbar-custom.scrolled {
  background: rgba(245, 243, 239, 0.98);
  box-shadow: 0 4px 25px rgba(44, 44, 44, 0.12);
  padding: 0.5rem 0;
}

.navbar-brand-custom {
  font-family: 'Poppins', sans-serif;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-primary) !important;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
}

.navbar-brand-custom .icon-leaf {
  color: var(--accent-red);
  font-size: 1.5rem;
  transition: var(--transition);
}

.navbar-brand-custom:hover .icon-leaf {
  transform: rotate(20deg) scale(1.1);
}

.navbar-nav {
  gap: 0.2rem;
}

.nav-link-custom {
  color: var(--text-primary) !important;
  font-weight: 500;
  padding: 0.5rem 1rem !important;
  margin: 0 0.1rem;
  border-radius: 25px;
  position: relative;
  transition: var(--transition);
  font-size: 0.95rem;
}

.nav-link-custom::before {
  content: '';
  position: absolute;
  bottom: 3px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--accent-red), var(--accent-yellow));
  border-radius: 2px;
  transition: var(--transition);
}

.nav-link-custom:hover::before,
.nav-link-custom.active::before {
  width: 50%;
}

.nav-link-custom:hover {
  color: var(--accent-red) !important;
  background: rgba(198, 40, 40, 0.1);
}

.nav-link-custom.active {
  color: var(--accent-red) !important;
}

/* Dropdown Styles - Same as index.php */
.nav-item.dropdown {
  position: relative;
}

.nav-link-custom.dropdown-toggle {
  position: relative;
  padding-right: 2rem !important;
}

.nav-link-custom.dropdown-toggle::after {
  content: '\f078';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  border: none;
  position: absolute;
  right: 0.8rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.7rem;
  transition: var(--transition);
}

.nav-link-custom.dropdown-toggle.show::after {
  transform: translateY(-50%) rotate(180deg);
}

.dropdown-menu {
  background: white;
  border: none;
  border-radius: 15px;
  padding: 0.5rem 0;
  box-shadow: var(--shadow-hover);
  margin-top: 0.5rem !important;
  animation: dropdownFadeIn 0.3s ease-out;
  min-width: 200px;
  border: 2px solid var(--bg-tertiary);
}

@keyframes dropdownFadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-item {
  padding: 0.6rem 1.2rem;
  color: var(--text-primary);
  font-weight: 500;
  transition: var(--transition);
  position: relative;
  border-left: 2px solid transparent;
  font-size: 0.9rem;
}

.dropdown-item:hover,
.dropdown-item:focus {
  background: linear-gradient(135deg, var(--accent-yellow), white);
  color: var(--accent-red);
  border-left-color: var(--accent-red);
  transform: translateX(5px);
}

.btn-login-custom {
  background: var(--accent-red);
  color: var(--bg-secondary) !important;
  padding: 0.5rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  border: 2px solid var(--accent-red);
  transition: var(--transition);
  box-shadow: 0 4px 15px rgba(198, 40, 40, 0.2);
  text-decoration: none;
  margin-left: 0.5rem;
}

.btn-login-custom:hover {
  background: transparent;
  color: var(--accent-red) !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(198, 40, 40, 0.3);
}

.btn-theme-toggle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--accent-yellow);
  border: none;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
  box-shadow: var(--shadow);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.btn-theme-toggle:hover {
  background: var(--accent-yellow);
  transform: scale(1.15) rotate(180deg);
  box-shadow: 0 6px 25px rgba(255, 213, 79, 0.4);
}

.btn-theme-toggle i {
  font-size: 1.1rem;
  transition: var(--transition);
}

/* Dark mode navbar */
[data-theme="dark"] .navbar-custom {
  background: rgba(20, 20, 20, 0.95);
  box-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
  border-bottom: 1px solid rgba(255, 213, 79, 0.1);
}

[data-theme="dark"] .navbar-custom.scrolled {
  background: rgba(20, 20, 20, 0.98);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
}

[data-theme="dark"] .navbar-brand-custom {
  color: #FFFFFF !important;
}

[data-theme="dark"] .navbar-brand-custom .icon-leaf {
  color: var(--accent-yellow);
}

[data-theme="dark"] .nav-link-custom {
  color: #E0E0E0 !important;
}

[data-theme="dark"] .nav-link-custom:hover {
  color: var(--accent-yellow) !important;
  background: rgba(255, 213, 79, 0.12);
}

[data-theme="dark"] .nav-link-custom.active {
  color: var(--accent-yellow) !important;
}

[data-theme="dark"] .dropdown-menu {
  background: #222222;
  border-color: rgba(255, 213, 79, 0.15);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
}

[data-theme="dark"] .dropdown-item {
  color: #E0E0E0;
}

[data-theme="dark"] .dropdown-item:hover,
[data-theme="dark"] .dropdown-item:focus {
  background: rgba(255, 213, 79, 0.12);
  color: var(--accent-yellow);
}

[data-theme="dark"] .btn-login-custom {
  background: var(--accent-red);
  color: #FFFFFF !important;
  border-color: var(--accent-red);
}

[data-theme="dark"] .btn-login-custom:hover {
  background: rgba(198, 40, 40, 0.2);
  color: var(--accent-red) !important;
  border-color: var(--accent-red);
}

/* Mobile responsive adjustments */
@media (max-width: 991px) {
  .dropdown-menu {
    background: transparent;
    box-shadow: none;
    border: none;
    margin-top: 0 !important;
    padding: 0;
  }
  
  .dropdown-item {
    padding-left: 2rem;
    border-left: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }
  
  .dropdown-item:hover {
    transform: none;
    border-left: none;
  }
  
  [data-theme="dark"] .dropdown-item {
    border-bottom-color: rgba(255, 255, 255, 0.05);
  }
}

/* === HEADER BERITA YANG MENARIK === */
.berita-header {
  background: linear-gradient(135deg, var(--accent-red) 0%, var(--accent-orange) 100%);
  color: white;
  padding: 120px 0 80px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.berita-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="news" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="2" fill="white" opacity="0.1"/><circle cx="50" cy="50" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23news)"/></svg>');
}

.berita-header-content {
  position: relative;
  z-index: 2;
}

.berita-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(3rem, 6vw, 5rem);
  font-weight: 800;
  margin-bottom: 1rem;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.berita-subtitle {
  font-size: 1.3rem;
  opacity: 0.9;
  margin-bottom: 2rem;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.berita-stats {
  display: flex;
  justify-content: center;
  gap: 3rem;
  flex-wrap: wrap;
  margin-top: 3rem;
}

.stat-item {
  text-align: center;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  font-family: 'Poppins', sans-serif;
}

.stat-label {
  font-size: 0.9rem;
  opacity: 0.8;
}

/* === FILTER KATEGORI === */
.kategori-filter {
  background: var(--bg-secondary);
  padding: 1.5rem 0;
  box-shadow: var(--shadow);
  position: sticky;
  top: 80px;
  z-index: 100;
}

.filter-container {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  justify-content: center;
}

.filter-btn {
  background: var(--bg-primary);
  border: 2px solid var(--bg-tertiary);
  color: var(--text-primary);
  padding: 0.7rem 1.5rem;
  border-radius: 25px;
  font-weight: 500;
  transition: var(--transition);
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-btn.active,
.filter-btn:hover {
  background: var(--accent-red);
  color: white;
  border-color: var(--accent-red);
  transform: translateY(-2px);
}

/* === BERITA GRID YANG LEBIH PRESISI === */
.section { 
  padding: 3rem 0;
}

.berita-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.berita-card {
  background: var(--bg-secondary);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: var(--transition);
  height: 100%;
  position: relative;
  border: 1px solid var(--bg-tertiary);
  display: flex;
  flex-direction: column;
}

.berita-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.berita-image {
  height: 180px;
  background: linear-gradient(135deg, var(--accent-red), var(--accent-orange));
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.berita-image i {
  font-size: 2.5rem;
  color: var(--bg-secondary);
  opacity: 0.9;
}

.berita-badge {
  position: absolute;
  top: 0.8rem;
  left: 0.8rem;
  background: var(--accent-red);
  color: white;
  padding: 0.4rem 1rem;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.berita-content {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.berita-meta {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  margin-bottom: 0.8rem;
  font-size: 0.8rem;
  color: var(--text-secondary);
}

.berita-meta i {
  color: var(--accent-red);
  font-size: 0.7rem;
}

.berita-title {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 0.8rem;
  line-height: 1.4;
  color: var(--text-primary);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 3em;
}

.berita-excerpt {
  color: var(--text-secondary);
  margin-bottom: 1.2rem;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  flex: 1;
  font-size: 0.9rem;
}

.berita-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid var(--bg-tertiary);
  padding-top: 1rem;
  margin-top: auto;
}

.btn-read-more {
  background: var(--accent-red);
  color: white;
  border: none;
  padding: 0.5rem 1.2rem;
  border-radius: 20px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  transition: var(--transition);
  font-size: 0.85rem;
}

.btn-read-more:hover {
  background: var(--accent-dark);
  color: white;
  transform: translateX(3px);
}

.berita-views {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  color: var(--text-secondary);
  font-size: 0.8rem;
}

.berita-views i {
  color: var(--accent-red);
  font-size: 0.7rem;
}

/* === HIGHLIGHT BERITA UTAMA === */
.berita-utama {
  background: var(--bg-tertiary);
  padding: 3rem 0;
}

.utama-card {
  background: var(--bg-secondary);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-hover);
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0;
  border: 1px solid transparent;
  max-width: 1000px;
  margin: 0 auto;
}

.utama-card:hover {
  border-color: var(--accent-yellow);
}

.utama-image {
  background: linear-gradient(135deg, var(--accent-red), var(--accent-orange));
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  min-height: 300px;
}

.utama-image i {
  font-size: 3.5rem;
  color: var(--bg-secondary);
}

.utama-content {
  padding: 2.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.utama-badge {
  background: var(--accent-red);
  color: white;
  padding: 0.5rem 1.2rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.utama-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 1.2rem;
  line-height: 1.3;
  color: var(--text-primary);
}

.utama-excerpt {
  font-size: 1rem;
  color: var(--text-secondary);
  margin-bottom: 1.5rem;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.utama-meta {
  display: flex;
  align-items: center;
  gap: 1.2rem;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.utama-meta i {
  color: var(--accent-red);
}

/* === PAGINATION === */
.pagination {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 2rem;
}

.page-item {
  margin: 0;
}

.page-link {
  background: var(--bg-secondary);
  border: 2px solid var(--bg-tertiary);
  color: var(--text-primary);
  padding: 0.6rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  transition: var(--transition);
  font-size: 0.9rem;
}

.page-link:hover,
.page-item.active .page-link {
  background: var(--accent-red);
  color: white;
  border-color: var(--accent-red);
  transform: translateY(-2px);
}

/* === FOOTER === */
.footer {
  background: var(--accent-dark);
  color: white;
  padding: 3rem 0 1.5rem;
  text-align: center;
}

.footer-brand {
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--accent-yellow);
  margin-bottom: 1rem;
}

.footer-info {
  color: rgba(255,255,255,0.8);
  margin-bottom: 1.5rem;
  line-height: 1.6;
  font-size: 0.9rem;
}

.social-links {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.social-link {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-decoration: none;
  transition: var(--transition);
  font-size: 0.9rem;
}

.social-link:hover {
  background: var(--accent-yellow);
  color: var(--accent-dark);
  transform: translateY(-2px);
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 1.5rem;
  color: rgba(255,255,255,0.6);
  font-size: 0.85rem;
}

.fade-in { 
  opacity: 0; 
  transform: translateY(30px); 
  transition: all 0.8s ease; 
}

.fade-in.show { 
  opacity: 1; 
  transform: translateY(0); 
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .berita-header {
    padding: 100px 0 60px;
  }
  
  .berita-stats {
    gap: 2rem;
  }
  
  .stat-number {
    font-size: 2rem;
  }
  
  .berita-grid {
    grid-template-columns: 1fr;
    gap: 1.2rem;
  }
  
  .utama-card {
    grid-template-columns: 1fr;
  }
  
  .utama-content {
    padding: 2rem;
  }
  
  .utama-title {
    font-size: 1.5rem;
  }
  
  .filter-container {
    justify-content: flex-start;
    overflow-x: auto;
    padding-bottom: 0.5rem;
  }
  
  .berita-image {
    height: 160px;
  }
  
  .berita-content {
    padding: 1.2rem;
  }
}

@media (max-width: 480px) {
  .berita-content {
    padding: 1rem;
  }
  
  .berita-footer {
    flex-direction: column;
    gap: 0.8rem;
    align-items: flex-start;
  }
  
  .btn-read-more {
    width: 100%;
    justify-content: center;
  }
  
  .utama-image {
    min-height: 200px;
  }
  
  .utama-content {
    padding: 1.5rem;
  }
}

.empty-state {
  text-align: center;
  padding: 3rem 2rem;
  color: var(--text-secondary);
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state h4 {
  font-size: 1.3rem;
  margin-bottom: 0.8rem;
  color: var(--text-primary);
}

.empty-state p {
  font-size: 0.9rem;
  opacity: 0.8;
  max-width: 400px;
  margin: 0 auto;
}
</style>
</head>
<body data-theme="light">

<!-- NAVBAR - SAME AS INDEX.PHP -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand-custom" href="index.php">
      <i class="fas fa-leaf icon-leaf"></i>
      <span>Pinabetengan Selatan</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link-custom" href="index.php">Beranda</a>
        </li>
        
        <!-- Profil Desa Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link-custom dropdown-toggle" href="#" id="profilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Profil
          </a>
          <ul class="dropdown-menu" aria-labelledby="profilDropdown">
            <li><a class="dropdown-item" href="profil.php?page=sejarah">Sejarah Desa</a></li>
            <li><a class="dropdown-item" href="profil.php?page=visi-misi">Visi & Misi</a></li>
            <li><a class="dropdown-item" href="profil.php?page=struktur">Struktur Pemerintahan</a></li>
            <li><a class="dropdown-item" href="profil.php?page=wilayah">Wilayah & Peta</a></li>
          </ul>
        </li>
        
        <!-- Data Desa Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link-custom dropdown-toggle" href="#" id="dataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Data
          </a>
          <ul class="dropdown-menu" aria-labelledby="dataDropdown">
            <li><a class="dropdown-item" href="data.php?page=penduduk">Jumlah Data Penduduk</a></li>
            <li><a class="dropdown-item" href="data.php?page=pendidikan">Data Pendidikan</a></li>
            <li><a class="dropdown-item" href="data.php?page=pekerjaan">Data Pekerjaan</a></li>
            <li><a class="dropdown-item" href="data.php?page=layanan">Layanan Publik Desa</a></li>
            <li><a class="dropdown-item" href="data.php?page=apbdes">APBDes</a></li>
            <li><a class="dropdown-item" href="data.php?page=rencana-kerja">Rencana Kerja Pemerintah</a></li>
          </ul>
        </li>
        
        <!-- Potensi Desa Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link-custom dropdown-toggle" href="#" id="potensiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Potensi
          </a>
          <ul class="dropdown-menu" aria-labelledby="potensiDropdown">
            <li><a class="dropdown-item" href="potensi.php?page=watu-pinawetengan">Desa Watu Pinawetengan</a></li>
            <li><a class="dropdown-item" href="potensi.php?page=pangsit-jagung">Produk Pangsit Jagung UMKM</a></li>
            <li><a class="dropdown-item" href="potensi.php?page=bendang-stable">Bendang Stable</a></li>
          </ul>
        </li>
        
        <!-- Berita Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link-custom dropdown-toggle active" href="#" id="beritaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Berita
          </a>
          <ul class="dropdown-menu" aria-labelledby="beritaDropdown">
            <li><a class="dropdown-item <?= $kategori === 'kegiatan' ? 'active' : '' ?>" href="berita.php?kategori=kegiatan">Kegiatan & Program</a></li>
            <li><a class="dropdown-item <?= $kategori === 'pengumuman' ? 'active' : '' ?>" href="berita.php?kategori=pengumuman">Pengumuman Resmi</a></li>
          </ul>
        </li>
        
        <li class="nav-item">
          <a class="nav-link-custom" href="kontak.php">Kontak</a>
        </li>
      </ul>
      <div class="d-flex gap-2 align-items-center">
        <a class="btn-login-custom" href="login.php">Login</a>
        <button class="btn-theme-toggle" id="themeToggle">
          <i class="fas fa-moon"></i>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- HEADER BERITA YANG MENARIK -->
<header class="berita-header">
  <div class="container">
    <div class="berita-header-content fade-in">
      <h1 class="berita-title"><?= $page_title ?></h1>
      <p class="berita-subtitle">
        <?= $kategori === 'pengumuman' 
            ? 'Informasi resmi dan pengumuman penting dari Pemerintah Desa Pinabetengan Selatan' 
            : 'Update terbaru seputar kegiatan, program, dan perkembangan Desa Pinabetengan Selatan' ?>
      </p>
      
      <div class="berita-stats">
        <div class="stat-item">
          <div class="stat-number"><?= count($berita) ?></div>
          <div class="stat-label">Total <?= $page_title ?></div>
        </div>
        <div class="stat-item">
          <div class="stat-number">
            <?= $kategori === 'pengumuman' ? '2.1K' : '4.3K' ?>
          </div>
          <div class="stat-label">Pembaca Bulanan</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">
            <?= $kategori === 'pengumuman' ? date('M Y') : date('Y') ?>
          </div>
          <div class="stat-label">
            <?= $kategori === 'pengumuman' ? 'Update Terbaru' : 'Tahun Aktif' ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- FILTER KATEGORI -->
<section class="kategori-filter">
  <div class="container">
    <div class="filter-container">
      <a href="berita.php?kategori=kegiatan" class="filter-btn <?= $kategori === 'kegiatan' ? 'active' : '' ?>">
        <i class="fas fa-calendar-alt me-2"></i>Kegiatan & Program
      </a>
      <a href="berita.php?kategori=pengumuman" class="filter-btn <?= $kategori === 'pengumuman' ? 'active' : '' ?>">
        <i class="fas fa-bullhorn me-2"></i>Pengumuman Resmi
      </a>
    </div>
  </div>
</section>

<!-- BERITA UTAMA -->
<?php if (!empty($berita)): ?>
<section class="berita-utama">
  <div class="container">
    <div class="utama-card fade-in">
      <div class="utama-image">
        <i class="<?= $kategori === 'pengumuman' ? 'fas fa-bullhorn' : 'fas fa-calendar-check' ?>"></i>
      </div>
      <div class="utama-content">
        <span class="utama-badge"><?= $kategori === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?></span>
        <h2 class="utama-title"><?= htmlspecialchars($berita[0]['judul']) ?></h2>
        <p class="utama-excerpt"><?= substr(strip_tags($berita[0]['isi']), 0, 200) ?>...</p>
        <div class="utama-meta">
          <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($berita[0]['tanggal'])) ?></span>
          <?php if (isset($berita[0]['penulis'])): ?>
          <span><i class="fas fa-user"></i> <?= $berita[0]['penulis'] ?></span>
          <?php endif; ?>
          <?php if (isset($berita[0]['dibaca'])): ?>
          <span><i class="fas fa-eye"></i> <?= $berita[0]['dibaca'] ?> dibaca</span>
          <?php endif; ?>
        </div>
        <a href="berita-detail.php?id=<?= $berita[0]['id'] ?>&kategori=<?= $kategori ?>" class="btn-read-more align-self-start">
          Baca Selengkapnya <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- DAFTAR BERITA -->
<section class="section">
  <div class="container">
    <?php if (empty($berita)): ?>
      <div class="empty-state fade-in">
        <i class="<?= $kategori === 'pengumuman' ? 'fas fa-bullhorn' : 'fas fa-calendar-alt' ?>"></i>
        <h4>Belum Ada <?= $page_title ?></h4>
        <p>
          <?= $kategori === 'pengumuman' 
              ? 'Saat ini belum ada pengumuman resmi yang diterbitkan. Silakan kembali lagi nanti.' 
              : 'Belum ada kegiatan atau program yang dijadwalkan saat ini. Pantau terus untuk update terbaru.' ?>
        </p>
      </div>
    <?php else: ?>
      <div class="berita-grid">
        <?php foreach(array_slice($berita, 1) as $b): ?>
          <article class="berita-card fade-in">
            <div class="berita-image">
              <span class="berita-badge"><?= $kategori === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?></span>
              <i class="<?= $kategori === 'pengumuman' ? 'fas fa-bullhorn' : 'fas fa-newspaper' ?>"></i>
            </div>
            <div class="berita-content">
              <div class="berita-meta">
                <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?></span>
                <?php if (isset($b['penulis'])): ?>
                <span><i class="fas fa-user"></i> <?= $b['penulis'] ?></span>
                <?php endif; ?>
              </div>
              <h3 class="berita-title"><?= htmlspecialchars($b['judul']) ?></h3>
              <p class="berita-excerpt"><?= substr(strip_tags($b['isi']), 0, 120) ?>...</p>
              <div class="berita-footer">
                <a href="berita-detail.php?id=<?= $b['id'] ?>&kategori=<?= $kategori ?>" class="btn-read-more">
                  Baca <i class="fas fa-arrow-right"></i>
                </a>
                <?php if (isset($b['dibaca'])): ?>
                <span class="berita-views">
                  <i class="fas fa-eye"></i> <?= $b['dibaca'] ?>
                </span>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      
      <!-- PAGINATION -->
      <nav>
        <ul class="pagination">
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <h3 class="footer-brand">Desa Pinabetengan Selatan</h3>
    <p class="footer-info">
      Jl. Desa Pinabetengan Selatan, Kec. Tompaso Baru<br>
      Kab. Minahasa Selatan, Sulawesi Utara<br>
      <i class="fas fa-phone me-2"></i>(0431) 123-456 | 
      <i class="fas fa-envelope mx-2"></i>info@pinabetenganselatan.desa.id
    </p>
    <div class="social-links">
      <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
      <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
      <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> Desa Pinabetengan Selatan</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// UNIFIED THEME MANAGEMENT - SAME AS INDEX.PHP
function initTheme() {
  const savedTheme = localStorage.getItem('theme') || 'light';
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = savedTheme === 'system' ? (systemPrefersDark ? 'dark' : 'light') : savedTheme;
  
  document.body.setAttribute('data-theme', theme);
  updateThemeIcon(theme);
}

function updateThemeIcon(theme) {
  const icon = document.querySelector('#themeToggle i');
  if (theme === 'dark') {
    icon.classList.replace('fa-moon', 'fa-sun');
  } else {
    icon.classList.replace('fa-sun', 'fa-moon');
  }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  initTheme();
  
  // Theme toggle - SAME LOGIC AS INDEX.PHP
  document.getElementById('themeToggle').addEventListener('click', function() {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
    
    // Add click animation
    this.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
      this.style.transform = '';
    }, 300);
  });

  // Fade in animations
  const fadeElements = document.querySelectorAll('.fade-in');
  fadeElements.forEach(el => {
    setTimeout(() => el.classList.add('show'), 100);
  });

  // Navbar scroll effect - SAME AS INDEX.PHP
  const navbar = document.querySelector('.navbar-custom');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Listen to system theme changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      const newTheme = e.matches ? 'dark' : 'light';
      document.body.setAttribute('data-theme', newTheme);
      updateThemeIcon(newTheme);
    }
  });

  // Dropdown smooth animations
  const dropdownItems = document.querySelectorAll('.dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
      this.style.transform = 'translateX(8px)';
    });
    
    item.addEventListener('mouseleave', function() {
      this.style.transform = 'translateX(0)';
    });
  });
});
</script>
</body>
</html>