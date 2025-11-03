<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data profil desa dengan error handling
try {
    $profil = $pdo->query("SELECT * FROM profil WHERE id = 1")->fetch();
} catch (PDOException $e) {
    $profil = null;
}

// Ambil data kepala desa / pejabat dengan error handling
try {
    $pejabat = $pdo->query("SELECT * FROM pejabat ORDER BY periode_mulai DESC")->fetchAll();
} catch (PDOException $e) {
    $pejabat = [];
}

// Data fallback - HANYA JIKA data tidak ada di database
$profil_data = $profil ?: [
    'nama_desa' => 'Desa Pinabetengan Selatan',
    'tentang' => 'Desa Pinabetengan Selatan adalah sebuah desa yang terletak di Kecamatan Tompaso Baru, Kabupaten Minahasa Selatan, Provinsi Sulawesi Utara. Desa ini memiliki luas wilayah sekitar 450 hektar dengan topografi yang beragam, mulai dari dataran rendah hingga perbukitan.',
    'jumlah_penduduk' => 2500,
    'visi' => 'Terwujudnya Desa Pinabetengan Selatan yang Maju, Mandiri, Sejahtera, dan Berbudaya yang berlandaskan pada nilai-nilai keagamaan dan kearifan lokal.',
    'misi' => 'Meningkatkan kualitas SDM melalui pendidikan dan pelatihan, mengembangkan potensi ekonomi lokal, melestarikan budaya dan tradisi, meningkatkan infrastruktur desa, dan mewujudkan pemerintahan yang bersih dan transparan.',
    'gambar_desa' => '',
    'sejarah' => 'Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan".'
];

// Data fallback untuk pejabat - HANYA JIKA tidak ada data di database
if (empty($pejabat)) {
    $pejabat = [
        [
            'nama' => 'Johanis Tumimomor, S.Sos',
            'jabatan' => 'Kepala Desa',
            'periode_mulai' => '2021-01-01',
            'periode_selesai' => null,
            'foto' => ''
        ],
        [
            'nama' => 'Drs. Markus Kansil',
            'jabatan' => 'Kepala Desa',
            'periode_mulai' => '2015-01-01',
            'periode_selesai' => '2020-12-31',
            'foto' => ''
        ],
        [
            'nama' => 'Alexander Rondonuwu',
            'jabatan' => 'Kepala Desa',
            'periode_mulai' => '2009-01-01',
            'periode_selesai' => '2014-12-31',
            'foto' => ''
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Profil Desa - <?= htmlspecialchars($profil_data['nama_desa']) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">

<style>
/* === VARIABLES === */
:root {
  /* Light Theme */
  --bg-primary: #F5F3EF;
  --bg-secondary: #FFFFFF;
  --bg-tertiary: #E8E4DC;
  --text-primary: #2C2C2C;
  --text-secondary: #757575;
  --text-muted: #9E9E9E;
  --accent-red: #C62828;
  --accent-yellow: #FFD54F;
  --accent-dark: #1A1A1A;
  --shadow: 0 4px 16px rgba(0,0,0,0.08);
  --shadow-hover: 0 8px 32px rgba(0,0,0,0.12);
  --radius: 16px;
  --transition: 0.3s ease;
}

[data-theme="dark"] {
  /* Dark Theme - Harmonious Soft Dark */
  --bg-primary: #1A1A1A;
  --bg-secondary: #2D2D2D;
  --bg-tertiary: #252525;
  --text-primary: #FFFFFF;
  --text-secondary: #E0E0E0;
  --text-muted: #B0B0B0;
  --accent-red: #EF5350;
  --accent-yellow: #FFCA28;
  --accent-dark: #121212;
  --shadow: 0 4px 16px rgba(0,0,0,0.3);
  --shadow-hover: 0 8px 32px rgba(0,0,0,0.4);
}

/* === BASE STYLES === */
* { 
  box-sizing: border-box;
  transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--bg-primary);
  color: var(--text-primary);
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

h1, h2, h3, h4, h5, h6 {
  font-family: 'Poppins', sans-serif;
  color: var(--text-primary);
  font-weight: 600;
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

/* === HERO === */
.page-hero {
  min-height: 50vh;
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
  background: linear-gradient(135deg, var(--text-primary) 0%, var(--accent-red) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.page-subtitle {
  font-size: 1.2rem;
  color: var(--text-secondary);
  max-width: 500px;
}

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

/* === SECTIONS === */
.section { 
  padding: 4rem 0;
  background: var(--bg-primary);
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
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.section-subtitle {
  color: var(--text-secondary);
  font-size: 1.1rem;
  max-width: 600px;
  margin: 0 auto;
}

/* === CARDS === */
.profile-card,
.info-card,
.official-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  transition: var(--transition);
  border: 1px solid transparent;
}

.profile-card:hover,
.info-card:hover,
.official-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

/* Profile Card */
.profile-image {
  height: 300px;
  background: linear-gradient(135deg, var(--accent-red), var(--accent-yellow));
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--bg-secondary);
  font-size: 4rem;
  overflow: hidden;
}

.profile-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-content { 
  padding: 2.5rem; 
}

.profile-content h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: var(--text-primary);
}

.profile-content p {
  color: var(--text-secondary);
  line-height: 1.7;
  margin-bottom: 1rem;
}

/* Info Card */
.info-card {
  padding: 2rem;
  height: 100%;
  border-top: 4px solid var(--accent-red);
  position: relative;
}

.info-card-icon {
  font-size: 2.5rem;
  color: var(--accent-red);
  margin-bottom: 1rem;
}

.info-card h4 {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--text-primary);
}

.info-card p {
  color: var(--text-secondary);
  line-height: 1.7;
  margin: 0;
}

.info-card ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.info-card ul li {
  padding: 0.5rem 0;
  display: flex;
  align-items: start;
  gap: 0.8rem;
  color: var(--text-secondary);
}

.info-card ul li i {
  color: var(--accent-red);
  margin-top: 0.3rem;
}

/* Button Styles for Info Card */
.btn-read-more {
  background: var(--accent-red);
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 25px;
  font-weight: 500;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
  margin-top: 1rem;
  font-size: 0.9rem;
}

.btn-read-more:hover {
  background: var(--accent-dark);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
}

/* Official Card */
.official-card {
  text-align: center;
  overflow: hidden;
}

.official-photo {
  height: 250px;
  background: linear-gradient(135deg, var(--accent-red), var(--accent-yellow));
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--bg-secondary);
  font-size: 4rem;
  overflow: hidden;
}

.official-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.official-info { 
  padding: 2rem; 
}

.official-name {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--text-primary);
}

.official-position {
  color: var(--accent-red);
  font-weight: 500;
  margin-bottom: 1rem;
}

.official-period {
  color: var(--text-secondary);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

/* === GOOGLE MAPS === */
.map-container {
  width: 100%;
  height: 450px;
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow-hover);
  border: 2px solid var(--accent-yellow);
}

.map-iframe {
  width: 100%;
  height: 100%;
  border: none;
  filter: grayscale(20%) contrast(90%);
}

[data-theme="dark"] .map-iframe {
  filter: grayscale(50%) invert(90%) hue-rotate(180deg);
}

/* === FOOTER === */
.footer {
  background: var(--accent-dark);
  color: var(--text-primary);
  padding: 4rem 0 2rem;
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
  color: var(--text-secondary);
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
  color: var(--text-primary);
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
  color: var(--text-muted);
}

/* === UTILITIES === */
.fade-in { 
  opacity: 0; 
  transform: translateY(20px); 
  transition: all 0.6s ease; 
}

.fade-in.show { 
  opacity: 1; 
  transform: translateY(0); 
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .section { padding: 3rem 0; }
  .page-hero { padding: 90px 0 30px; min-height: 40vh; }
  .profile-image, .official-photo { height: 200px; }
  .map-container { height: 350px; }
  .profile-content { padding: 2rem; }
  .info-card { padding: 1.5rem; }
}

@media (max-width: 480px) {
  .map-container { height: 300px; }
}
</style>
</head>
<body data-theme="light">

<!-- NAVBAR - UPDATED TO MATCH LAYANAN.PHP -->
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
          <a class="nav-link-custom dropdown-toggle active" href="#" id="profilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Profil
          </a>
          <ul class="dropdown-menu" aria-labelledby="profilDropdown">
            <li><a class="dropdown-item" href="detail_sejarah.php">Sejarah Desa</a></li>
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
          <a class="nav-link-custom dropdown-toggle" href="#" id="layananDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Layanan
          </a>
          <ul class="dropdown-menu" aria-labelledby="layananDropdown">
            <li><a class="dropdown-item" href="layanan.php?page=layanan">Layanan Publik Desa</a></li>
            <li><a class="dropdown-item" href="layanan.php?page=apbdes">APBDes</a></li>
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
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
          <li class="breadcrumb-item active">Profil Desa</li>
        </ol>
      </nav>
      <h1 class="page-title">Profil Desa</h1>
      <p class="page-subtitle">
        Mengenal lebih dekat tentang <?= htmlspecialchars($profil_data['nama_desa']) ?>, sejarah, visi misi, dan pemerintahan desa.
      </p>
    </div>
  </div>
</section>

<!-- TENTANG DESA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Tentang Kami</h2>
      <p class="section-subtitle">Kantor <?= htmlspecialchars($profil_data['nama_desa']) ?></p>
    </div>
    
    <div class="row g-4 align-items-center mb-5">
      <div class="col-lg-6">
        <div class="profile-card fade-in">
          <div class="profile-image">
            <?php if(!empty($profil_data['gambar_desa'])): ?>
              <img src="<?= htmlspecialchars($profil_data['gambar_desa']) ?>" alt="Gambar <?= htmlspecialchars($profil_data['nama_desa']) ?>">
            <?php else: ?>
              <i class="fas fa-building"></i>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="profile-content fade-in">
          <h3>Selamat Datang di <?= htmlspecialchars($profil_data['nama_desa']) ?></h3>
          <p><?= nl2br(htmlspecialchars($profil_data['tentang'])) ?></p>
          <p>Nama Pinabetengan berasal dari bahasa Minahasa yang memiliki makna historis dan budaya yang mendalam bagi masyarakat Minahasa. Desa ini dikenal dengan keramahan penduduknya dan kekayaan alamnya yang masih terjaga dengan baik.</p>
          <p>Dengan jumlah penduduk sekitar <?= number_format($profil_data['jumlah_penduduk']) ?> jiwa, masyarakat <?= htmlspecialchars($profil_data['nama_desa']) ?> sebagian besar bermata pencaharian sebagai petani, pedagang, dan pegawai.</p>
          
          <!-- BUTTON BACA SELENGKAPNYA - DITAMBAHKAN DI SINI -->
          <a href="detail_sejarah.php" class="btn-read-more">
            <i class="fas fa-book me-1"></i>
            Baca Selengkapnya
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VISI MISI & SEJARAH -->
<section class="section section-alt">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="info-card fade-in">
          <div class="info-card-icon">
            <i class="fas fa-eye"></i>
          </div>
          <h4>Visi Desa</h4>
          <p><?= nl2br(htmlspecialchars($profil_data['visi'])) ?></p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="info-card fade-in">
          <div class="info-card-icon">
            <i class="fas fa-bullseye"></i>
          </div>
          <h4>Misi Desa</h4>
          <p><?= nl2br(htmlspecialchars($profil_data['misi'])) ?></p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="info-card fade-in">
          <div class="info-card-icon">
            <i class="fas fa-history"></i>
          </div>
          <h4>Sejarah Singkat</h4>
          <?php if (!empty($profil_data['sejarah'])): ?>
            <p><?= nl2br(htmlspecialchars($profil_data['sejarah'])) ?></p>
          <?php else: ?>
            <p>Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan". Desa ini telah melalui berbagai fase perkembangan dari masa ke masa.</p>
          <?php endif; ?>
          
          <!-- BUTTON BACA SELENGKAPNYA -->
          <a href="detail_sejarah.php" class="btn-read-more">
            <i class="fas fa-book me-1"></i>
            Baca Selengkapnya
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- KEPALA DESA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Kepala Desa</h2>
      <p class="section-subtitle">Masa periode jabatan kepala pemerintahan desa</p>
    </div>
    
    <div class="row g-4">
      <?php foreach($pejabat as $p): ?>
        <div class="col-lg-4 col-md-6">
          <div class="official-card fade-in">
            <div class="official-photo">
              <?php if(!empty($p['foto'])): ?>
                <img src="<?= htmlspecialchars($p['foto']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
              <?php else: ?>
                <i class="fas fa-user-tie"></i>
              <?php endif; ?>
            </div>
            <div class="official-info">
              <h3 class="official-name"><?= htmlspecialchars($p['nama']) ?></h3>
              <p class="official-position"><?= htmlspecialchars($p['jabatan']) ?></p>
              <div class="official-period">
                <i class="fas fa-calendar"></i>
                <span><?= date('Y', strtotime($p['periode_mulai'])) ?> - <?= $p['periode_selesai'] ? date('Y', strtotime($p['periode_selesai'])) : 'Sekarang' ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PETA GOOGLE MAPS -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Peta Lokasi</h2>
      <p class="section-subtitle">Lokasi <?= htmlspecialchars($profil_data['nama_desa']) ?> di Kecamatan Tompaso Baru</p>
    </div>
    
    <div class="map-container fade-in">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2304.3418622573286!2d124.78680526271864!3d1.1713542433821522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32874751892c0ec1%3A0x3449481bf0a5c57!2sDesa%20Pinabetengan%20Selatan!5e0!3m2!1sid!2sid!4v1761929361291!5m2!1sid!2sid" 
        class="map-iframe"
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade"
        title="Lokasi <?= htmlspecialchars($profil_data['nama_desa']) ?>">
      </iframe>
    </div>
    
    <div class="row mt-4">
      <div class="col-12 text-center">
        <div class="info-card" style="max-width: 500px; margin: 0 auto;">
          <h4><i class="fas fa-info-circle me-2"></i>Informasi Lokasi</h4>
          <p class="mb-2"><strong>Alamat:</strong> <?= htmlspecialchars($profil_data['nama_desa']) ?>, Kec. Tompaso Baru</p>
          <p class="mb-2"><strong>Kabupaten:</strong> Minahasa Selatan</p>
          <p class="mb-0"><strong>Provinsi:</strong> Sulawesi Utara</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <h3 class="footer-brand"><?= htmlspecialchars($profil_data['nama_desa']) ?></h3>
    <p class="footer-info">
      Jl. <?= htmlspecialchars($profil_data['nama_desa']) ?>, Kec. Tompaso Baru<br>
      Kab. Minahasa Selatan, Sulawesi Utara<br>
      <i class="fas fa-phone"></i> (0431) 123-456 | 
      <i class="fas fa-envelope"></i> info@pinabetenganselatan.desa.id
    </p>
    <div class="social-links">
      <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
      <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
      <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($profil_data['nama_desa']) ?>. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
// UNIFIED THEME MANAGEMENT - SAME AS INDEX.PHP
function initTheme() {
  const savedTheme = localStorage.getItem('theme') || 'light';
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = savedTheme === 'system' ? (systemPrefersDark ? 'dark' : 'light') : savedTheme;
  
  document.body.setAttribute('data-theme', theme);
  updateThemeIcon(theme);
  updateMapTheme(theme);
}

function updateThemeIcon(theme) {
  const icon = document.querySelector('#themeToggle i');
  if (theme === 'dark') {
    icon.classList.replace('fa-moon', 'fa-sun');
  } else {
    icon.classList.replace('fa-sun', 'fa-moon');
  }
}

function updateMapTheme(theme) {
  const mapIframe = document.querySelector('.map-iframe');
  if (mapIframe) {
    if (theme === 'dark') {
      mapIframe.style.filter = 'grayscale(50%) invert(90%) hue-rotate(180deg)';
    } else {
      mapIframe.style.filter = 'grayscale(20%) contrast(90%)';
    }
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
    updateMapTheme(newTheme);
    
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
      updateMapTheme(newTheme);
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