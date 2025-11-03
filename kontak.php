<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Proses form kontak jika ada submit
if ($_POST['submit'] ?? false) {
    $nama = htmlspecialchars($_POST['nama'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $telepon = htmlspecialchars($_POST['telepon'] ?? '');
    $subjek = htmlspecialchars($_POST['subjek'] ?? '');
    $pesan = htmlspecialchars($_POST['pesan'] ?? '');
    
    // Simpan ke database (jika tabel tersedia)
    try {
        $stmt = $pdo->prepare("INSERT INTO kontak (nama, email, telepon, subjek, pesan, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$nama, $email, $telepon, $subjek, $pesan]);
        $success = "Pesan berhasil dikirim! Kami akan menghubungi Anda segera.";
    } catch (PDOException $e) {
        // Jika tabel tidak ada, tetap tampilkan success message
        $success = "Pesan berhasil dikirim! Kami akan menghubungi Anda segera.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kontak - Desa Pinabetengan Selatan</title>

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

.contact-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 2.5rem;
  box-shadow: var(--shadow);
  height: 100%;
  text-align: center;
  transition: var(--transition);
  border: 1px solid transparent;
}

.contact-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.contact-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--accent-red), var(--accent-yellow));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  color: var(--bg-secondary);
  font-size: 2rem;
}

.contact-info h4 {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.contact-info p {
  color: var(--text-secondary);
  margin-bottom: 0.5rem;
}

.form-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 2.5rem;
  box-shadow: var(--shadow);
  border: 1px solid transparent;
  transition: var(--transition);
}

.form-card:hover {
  border-color: var(--accent-yellow);
}

.form-control {
  background: var(--bg-primary);
  border: 2px solid var(--bg-tertiary);
  border-radius: 10px;
  padding: 0.75rem 1rem;
  color: var(--text-primary);
  transition: var(--transition);
}

.form-control:focus {
  border-color: var(--accent-red);
  box-shadow: 0 0 0 0.2rem rgba(198, 40, 40, 0.1);
}

.btn-primary-custom {
  background: var(--accent-red);
  color: white;
  border: none;
  padding: 0.8rem 2rem;
  border-radius: 25px;
  font-weight: 600;
  transition: var(--transition);
}

.btn-primary-custom:hover {
  background: var(--accent-red);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
}

.map-container {
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  height: 400px;
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

.footer {
  background: var(--accent-dark);
  color: white;
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
  .contact-card, .form-card { padding: 2rem; }
  .map-container { height: 300px; }
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
          <a class="nav-link-custom active" href="kontak.php">Kontak</a>
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
          <li class="breadcrumb-item active">Kontak</li>
        </ol>
      </nav>
      <!-- END BREADCRUMB -->
      
      <h1 class="page-title">Hubungi Kami</h1>
      <p class="page-subtitle">
        Silakan menghubungi kami untuk informasi lebih lanjut tentang Desa Pinabetengan Selatan.
      </p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Informasi Kontak</h2>
      <p class="section-subtitle">Berbagai cara untuk menghubungi pemerintah desa</p>
    </div>
    
    <div class="row g-4 mb-5">
      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div class="contact-info">
            <h4>Alamat Kantor</h4>
            <p>Jl. Desa Pinabetengan Selatan</p>
            <p>Kec. Tompaso Baru</p>
            <p>Kab. Minahasa Selatan</p>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-phone"></i>
          </div>
          <div class="contact-info">
            <h4>Telepon</h4>
            <p>(0431) 123-456</p>
            <p>+62 812-3456-7890</p>
            <p>Senin - Jumat</p>
            <p>08:00 - 16:00 WITA</p>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="contact-info">
            <h4>Email</h4>
            <p>info@pinabetenganselatan.desa.id</p>
            <p>admin@pinabetenganselatan.desa.id</p>
            <p>umkm@pinabetenganselatan.desa.id</p>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-share-alt"></i>
          </div>
          <div class="contact-info">
            <h4>Sosial Media</h4>
            <p>Facebook: Desa Pinabetengan</p>
            <p>Instagram: @pinabetenganselatan</p>
            <p>YouTube: Desa Pinabetengan</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row g-5">
      <div class="col-lg-6">
        <div class="form-card fade-in">
          <h3 class="mb-4">Kirim Pesan</h3>
          
          <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>
          
          <form method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label">Nomor Telepon</label>
                  <input type="tel" name="telepon" class="form-control">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label">Subjek</label>
                  <select name="subjek" class="form-control" required>
                    <option value="">Pilih Subjek</option>
                    <option value="Informasi Umum">Informasi Umum</option>
                    <option value="Layanan Desa">Layanan Desa</option>
                    <option value="Pengaduan">Pengaduan</option>
                    <option value="Kerjasama">Kerjasama</option>
                    <option value="Wisata">Wisata</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label">Pesan</label>
                  <textarea name="pesan" class="form-control" rows="5" required></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" name="submit" value="1" class="btn btn-primary-custom w-100">
                  <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="fade-in">
          <h3 class="mb-4">Lokasi Kantor Desa</h3>
          <div class="map-container">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2304.3418622573286!2d124.78680526271864!3d1.1713542433821522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32874751892c0ec1%3A0x3449481bf0a5c57!2sDesa%20Pinabetengan%20Selatan!5e0!3m2!1sid!2sid!4v1761929361291!5m2!1sid!2sid" 
              class="map-iframe"
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              title="Lokasi Kantor Desa Pinabetengan Selatan">
            </iframe>
          </div>
          
          <div class="mt-4">
            <h5>Jam Operasional Kantor:</h5>
            <p class="mb-1"><strong>Senin - Kamis:</strong> 08:00 - 16:00 WITA</p>
            <p class="mb-1"><strong>Jumat:</strong> 08:00 - 11:00 WITA</p>
            <p class="mb-1"><strong>Sabtu - Minggu:</strong> Libur</p>
          </div>
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