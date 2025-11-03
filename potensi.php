<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data potensi dengan error handling
try {
    $potensi = $pdo->query("SELECT * FROM potensi WHERE status = 'active' ORDER BY urutan")->fetchAll();
} catch (PDOException $e) {
    $potensi = [];
}

// Data fallback jika tidak ada data dari database
if (empty($potensi)) {
    $potensi = [
        [
            'id' => 1,
            'nama' => 'Watu Pinawetengan',
            'jenis' => 'Wisata Budaya & Sejarah',
            'deskripsi' => 'Situs megalitikum bersejarah yang menjadi cikal bakal peradaban Minahasa.',
            'icon' => 'monument',
            'fasilitas' => ['Area Parkir', 'Mushola', 'Warung Makan', 'Guide Lokal'],
            'aktivitas' => ['Wisata Sejarah', 'Foto Budaya', 'Studi Arkeologi'],
            'lokasi' => 'Desa Pinabetengan Selatan',
            'jam_operasional' => '08:00 - 17:00 WITA',
            'tiket' => 'Rp 10.000',
            'kontak' => '6281234567890',
            'rating' => 4.8,
            'highlight' => true
        ],
        [
            'id' => 2,
            'nama' => 'Produk Pangsit Jagung UMKM',
            'jenis' => 'Kuliner & Kerajinan',
            'deskripsi' => 'Pangsit jagung khas desa yang dibuat dari jagung lokal dengan resep turun-temurun.',
            'icon' => 'cookie-bite',
            'fasilitas' => ['Showroom Produk', 'Area Produksi', 'Packaging Higienis'],
            'aktivitas' => ['Beli Produk', 'Workshop Pembuatan', 'Foto Produk'],
            'lokasi' => 'Sentra UMKM Desa',
            'jam_operasional' => '07:00 - 20:00 WITA',
            'harga' => 'Rp 25.000 - Rp 50.000',
            'kontak' => '6281345678901',
            'rating' => 4.9,
            'highlight' => true
        ],
        [
            'id' => 3,
            'nama' => 'Bendang Stable',
            'jenis' => 'Wisata Edukasi & Peternakan',
            'deskripsi' => 'Peternakan kuda tradisional yang menawarkan pengalaman wisata edukasi.',
            'icon' => 'horse',
            'fasilitas' => ['Area Berkuda', 'Kandang Kuda', 'Pemandu Wisata', 'Kafe'],
            'aktivitas' => ['Berkuda', 'Edukasi Peternakan', 'Foto dengan Kuda'],
            'lokasi' => 'Dusun Bendang',
            'jam_operasional' => '06:00 - 18:00 WITA',
            'tiket' => 'Rp 75.000',
            'kontak' => '6281456789012',
            'rating' => 4.7,
            'highlight' => true
        ]
    ];
} else {
    // Format data dari database untuk match dengan struktur yang diharapkan frontend
    $formatted_potensi = [];
    foreach ($potensi as $item) {
        $formatted_potensi[] = [
            'id' => $item['id'],
            'nama' => $item['nama_potensi'],
            'jenis' => 'Wisata Desa', // Default value
            'deskripsi' => $item['deskripsi'],
            'icon' => str_replace('fas fa-', '', $item['icon']), // Remove fas fa- prefix
            'fasilitas' => ['Fasilitas Tersedia'], // Default value
            'aktivitas' => ['Aktivitas Tersedia'], // Default value
            'lokasi' => 'Desa Pinabetengan Selatan', // Default value
            'jam_operasional' => '08:00 - 17:00 WITA', // Default value
            'tiket' => 'Hubungi untuk info', // Default value
            'kontak' => '6281234567890', // Default value
            'rating' => 4.5, // Default value
            'highlight' => true // Default value
        ];
    }
    $potensi = $formatted_potensi;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Potensi Desa - Desa Pinabetengan Selatan</title>

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

/* === BREADCRUMB STYLES === */
.breadcrumb {
  background: transparent;
  padding: 0;
  margin-bottom: 1rem;
}

.breadcrumb-item {
  color: var(--text-secondary);
}

.breadcrumb-item.active {
  color: var(--accent-red);
  font-weight: 500;
}

.breadcrumb-item a {
  color: var(--text-secondary);
  text-decoration: none;
  transition: var(--transition);
}

.breadcrumb-item a:hover {
  color: var(--accent-red);
}

/* Dark mode breadcrumb */
[data-theme="dark"] .breadcrumb-item {
  color: #B0B0B0;
}

[data-theme="dark"] .breadcrumb-item.active {
  color: var(--accent-yellow);
}

[data-theme="dark"] .breadcrumb-item a {
  color: #B0B0B0;
}

[data-theme="dark"] .breadcrumb-item a:hover {
  color: var(--accent-yellow);
}

.page-hero {
  min-height: 60vh;
  background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-tertiary) 100%);
  padding: 100px 0 50px;
  display: flex;
  align-items: center;
}

.page-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2.5rem, 5vw, 4rem);
  font-weight: 700;
  margin-bottom: 1rem;
}

.page-subtitle {
  font-size: 1.2rem;
  color: var(--text-secondary);
  max-width: 600px;
}

.section { 
  padding: 4rem 0;
}

.section-alt {
  background: var(--bg-tertiary);
}

.section-header { 
  text-align: center; 
  margin-bottom: 3rem; 
}

.section-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 700;
  margin-bottom: 1rem;
}

.section-subtitle {
  color: var(--text-secondary);
  font-size: 1.1rem;
  max-width: 600px;
  margin: 0 auto;
}

.potensi-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: var(--transition);
  height: 100%;
  border: 1px solid transparent;
}

.potensi-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.potensi-image {
  height: 250px;
  background: linear-gradient(135deg, var(--accent-red), var(--accent-yellow));
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.potensi-image i {
  font-size: 4rem;
  color: var(--bg-secondary);
}

.potensi-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: var(--accent-yellow);
  color: var(--accent-red);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.8rem;
}

.potensi-content {
  padding: 2rem;
}

.potensi-title {
  font-size: 1.4rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.potensi-type {
  background: var(--accent-blue);
  color: white;
  padding: 0.3rem 1rem;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 1rem;
}

.potensi-desc {
  color: var(--text-secondary);
  margin-bottom: 1.5rem;
}

.potensi-rating {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.rating-stars {
  color: var(--accent-yellow);
}

.detail-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.detail-item {
  background: var(--bg-tertiary);
  padding: 1rem;
  border-radius: 10px;
}

.detail-label {
  font-size: 0.8rem;
  color: var(--text-secondary);
  margin-bottom: 0.3rem;
}

.detail-value {
  font-size: 1rem;
  font-weight: 600;
}

.btn-primary-custom {
  background: var(--accent-red);
  color: white;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
}

.btn-primary-custom:hover {
  background: var(--accent-red);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
}

.btn-whatsapp {
  background: #25D366;
  color: white;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
}

.btn-whatsapp:hover {
  background: #128C7E;
  color: white;
  transform: translateY(-2px);
}

.stats-section {
  background: linear-gradient(135deg, var(--accent-dark) 0%, #2D2D2D 100%);
  color: white;
  padding: 3rem 0;
  border-radius: var(--radius);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 2rem;
  text-align: center;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--accent-yellow);
  margin-bottom: 0.5rem;
}

.stat-label {
  color: rgba(255,255,255,0.8);
}

.footer {
  background: var(--accent-dark);
  color: white;
  padding: 3rem 0 1rem;
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
  margin-bottom: 2rem;
  line-height: 1.6;
}

.social-links {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 2rem;
}

.social-link {
  width: 45px;
  height: 45px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-decoration: none;
  transition: var(--transition);
}

.social-link:hover {
  background: var(--accent-yellow);
  color: var(--accent-dark);
  transform: translateY(-2px);
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 2rem;
  color: rgba(255,255,255,0.6);
}

.fade-in { 
  opacity: 0; 
  transform: translateY(20px); 
  transition: all 0.6s ease; 
}

.fade-in.show { 
  opacity: 1; 
  transform: translateY(0); 
}

@media (max-width: 768px) {
  .section { padding: 3rem 0; }
  .page-hero { padding: 90px 0 30px; }
  .potensi-content { padding: 1.5rem; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
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
            <li><a class="dropdown-item" href="data.php?page=rencana-kerja">Rencana Kerja Pemerintah</a></li>
          </ul>
        </li>
         <!-- Layanan Desa Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link-custom dropdown-toggle" href="#" id="dataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Layanan
          </a>
          <ul class="dropdown-menu" aria-labelledby="dataDropdown">
            <li><a class="dropdown-item" href="layanan.php?page=layanan">Layanan Publik Desa</a></li>
            <li><a class="dropdown-item" href="layanan.php?page=apbdes">APBDes</a></li>
          </ul>
        </li>
        
        <!-- Potensi Desa Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link-custom dropdown-toggle active" href="#" id="potensiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
          <a class="nav-link-custom dropdown-toggle" href="#" id="beritaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Berita
          </a>
          <ul class="dropdown-menu" aria-labelledby="beritaDropdown">
            <li><a class="dropdown-item" href="berita.php?kategori=kegiatan">Kegiatan & Program</a></li>
            <li><a class="dropdown-item" href="berita.php?kategori=pengumuman">Pengumuman Resmi</a></li>
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

<!-- HERO -->
<section class="page-hero">
  <div class="container">
    <div class="page-hero-content fade-in">
      <!-- BREADCRUMB -->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
          <li class="breadcrumb-item active">Potensi</li>
        </ol>
      </nav>
      <!-- END BREADCRUMB -->
      
      <h1 class="page-title">Potensi Desa</h1>
      <p class="page-subtitle">
        Menjelajahi kekayaan alam, budaya, dan ekonomi Desa Pinabetengan Selatan.
      </p>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    
    <div class="section-header">
      <h2 class="section-title">Potensi Unggulan</h2>
      <p class="section-subtitle">Tiga destinasi utama yang menjadi ikon desa kami</p>
    </div>
    
    <div class="row g-4">
      <?php foreach($potensi as $p): ?>
        <div class="col-lg-4 col-md-6">
          <div class="potensi-card fade-in">
            <div class="potensi-image">
              <?php if($p['highlight']): ?>
                <div class="potensi-badge">
                  <i class="fas fa-star me-1"></i> Unggulan
                </div>
              <?php endif; ?>
              <i class="fas fa-<?= $p['icon'] ?>"></i>
            </div>
            <div class="potensi-content">
              <h3 class="potensi-title"><?= htmlspecialchars($p['nama']) ?></h3>
              <span class="potensi-type"><?= htmlspecialchars($p['jenis']) ?></span>
              
              <p class="potensi-desc"><?= htmlspecialchars($p['deskripsi']) ?></p>
              
              <div class="potensi-rating">
                <div class="rating-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?= $i <= $p['rating'] ? '' : '-o' ?>"></i>
                  <?php endfor; ?>
                </div>
                <span><?= $p['rating'] ?></span>
              </div>
              
              <div class="detail-grid">
                <div class="detail-item">
                  <div class="detail-label">Lokasi</div>
                  <div class="detail-value"><?= htmlspecialchars($p['lokasi']) ?></div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">Jam Operasional</div>
                  <div class="detail-value"><?= htmlspecialchars($p['jam_operasional']) ?></div>
                </div>
                <div class="detail-item">
                  <div class="detail-label"><?= isset($p['tiket']) ? 'Tiket' : 'Harga' ?></div>
                  <div class="detail-value"><?= htmlspecialchars($p['tiket'] ?? $p['harga']) ?></div>
                </div>
              </div>
              
              <div class="d-flex gap-2 flex-wrap">
                <a href="potensi-detail.php?id=<?= $p['id'] ?>" class="btn-primary-custom">
                  <i class="fas fa-info-circle"></i> Detail
                </a>
                <a href="https://wa.me/<?= $p['kontak'] ?>?text=Halo, saya tertarik dengan <?= urlencode($p['nama']) ?>" 
                   class="btn-whatsapp" target="_blank">
                  <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="stats-section fade-in">
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number">4.8</div>
          <div class="stat-label">Rating Wisatawan</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">15K+</div>
          <div class="stat-label">Pengunjung/Tahun</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">50+</div>
          <div class="stat-label">UMKM Terdampak</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">3</div>
          <div class="stat-label">Destinasi Unggulan</div>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <h3 class="footer-brand">Desa Pinabetengan Selatan</h3>
    <p class="footer-info">
      Jl. Desa Pinabetengan Selatan, Kec. Tompaso Baru<br>
      Kab. Minahasa Selatan, Sulawesi Utara
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