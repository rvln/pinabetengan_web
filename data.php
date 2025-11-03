<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data statistik
try {
    $penduduk = $pdo->query("SELECT * FROM data_penduduk ORDER BY tahun DESC LIMIT 1")->fetch();
} catch (PDOException $e) {
    $penduduk = null;
}

try {
    $pendidikan = $pdo->query("SELECT * FROM data_pendidikan")->fetchAll();
} catch (PDOException $e) {
    $pendidikan = [];
}

try {
    $pekerjaan = $pdo->query("SELECT * FROM data_pekerjaan")->fetchAll();
} catch (PDOException $e) {
    $pekerjaan = [];
}

// Data fallback
$data_penduduk = $penduduk ?: [
    'total_penduduk' => 5247,
    'laki_laki' => 2621,
    'perempuan' => 2626,
    'kepala_keluarga' => 1458,
    'kepadatan_penduduk' => 245,
    'tahun' => 2024
];

// Hitung persentase untuk pendidikan
if (!empty($pendidikan)) {
    $total_pendidikan = array_sum(array_column($pendidikan, 'jumlah'));
    foreach ($pendidikan as &$edu) {
        $edu['persentase'] = $total_pendidikan > 0 ? ($edu['jumlah'] / $total_pendidikan) * 100 : 0;
    }
    unset($edu); // Unset reference
} else {
    $pendidikan = [
        ['tingkat' => 'Tidak Sekolah', 'jumlah' => 187, 'persentase' => 3.6],
        ['tingkat' => 'SD/Sederajat', 'jumlah' => 1458, 'persentase' => 27.8],
        ['tingkat' => 'SMP/Sederajat', 'jumlah' => 1362, 'persentase' => 26.0],
        ['tingkat' => 'SMA/Sederajat', 'jumlah' => 1524, 'persentase' => 29.0],
        ['tingkat' => 'Diploma/Sarjana', 'jumlah' => 716, 'persentase' => 13.6]
    ];
}

// Hitung persentase untuk pekerjaan
if (!empty($pekerjaan)) {
    $total_pekerjaan = array_sum(array_column($pekerjaan, 'jumlah'));
    foreach ($pekerjaan as &$job) {
        $job['persentase'] = $total_pekerjaan > 0 ? ($job['jumlah'] / $total_pekerjaan) * 100 : 0;
    }
    unset($job); // Unset reference
} else {
    $pekerjaan = [
        ['jenis' => 'Petani', 'jumlah' => 1245, 'persentase' => 23.7],
        ['jenis' => 'Pedagang', 'jumlah' => 856, 'persentase' => 16.3],
        ['jenis' => 'PNS/TNI/Polri', 'jumlah' => 324, 'persentase' => 6.2],
        ['jenis' => 'Karyawan Swasta', 'jumlah' => 1087, 'persentase' => 20.7],
        ['jenis' => 'Wiraswasta', 'jumlah' => 892, 'persentase' => 17.0],
        ['jenis' => 'Lainnya', 'jumlah' => 843, 'persentase' => 16.1]
    ];
}

// Siapkan data untuk grafik
$penduduk_labels = ['Laki-laki', 'Perempuan'];
$penduduk_data = [$data_penduduk['laki_laki'], $data_penduduk['perempuan']];
$penduduk_colors = ['#C62828', '#FFD54F'];

$pendidikan_labels = array_column($pendidikan, 'tingkat');
$pendidikan_data = array_column($pendidikan, 'jumlah');
$pendidikan_colors = ['#C62828', '#FF9800', '#4CAF50', '#2196F3', '#9C27B0'];

$pekerjaan_labels = array_column($pekerjaan, 'jenis');
$pekerjaan_data = array_column($pekerjaan, 'jumlah');
$pekerjaan_colors = ['#C62828', '#FF9800', '#4CAF50', '#2196F3', '#9C27B0', '#607D8B'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Data Statistik - Desa Pinabetengan Selatan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

/* === IMPROVED NAVBAR - SAME AS PROFIL.PHP === */
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

/* Dropdown Styles */
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
  min-height: 40vh;
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

/* === STATS CARDS === */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.stat-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 2rem;
  text-align: center;
  transition: var(--transition);
  border: 1px solid transparent;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--accent-red);
  margin-bottom: 0.5rem;
  line-height: 1;
}

.stat-label {
  color: var(--text-secondary);
  font-weight: 500;
  font-size: 0.95rem;
}

/* === DATA CARDS === */
.data-section {
  margin-bottom: 4rem;
}

.data-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 2rem;
  margin-bottom: 2rem;
  transition: var(--transition);
  border: 1px solid transparent;
}

.data-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.data-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid var(--accent-yellow);
}

.data-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: white;
}

.icon-population { background: var(--accent-red); }
.icon-education { background: #2196F3; }
.icon-work { background: #FF9800; }

.data-title {
  font-size: 1.4rem;
  font-weight: 600;
  color: var(--text-primary);
  margin: 0;
}

.data-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.data-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.8rem 0;
  border-bottom: 1px solid var(--bg-tertiary);
}

.data-item:last-child {
  border-bottom: none;
}

.data-label {
  color: var(--text-secondary);
  font-weight: 500;
}

.data-value {
  font-weight: 600;
  color: var(--text-primary);
}

.data-percentage {
  background: var(--accent-red);
  color: white;
  padding: 0.2rem 0.6rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 500;
  margin-left: 0.5rem;
}

/* === CHART CONTAINERS === */
.chart-container {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 1.5rem;
  box-shadow: var(--shadow);
  margin-bottom: 1.5rem;
  position: relative;
}

.chart-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.chart-wrapper {
  position: relative;
  height: 300px;
  width: 100%;
}

/* === INFO CARDS === */
.info-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 1.5rem;
  border-left: 4px solid var(--accent-yellow);
}

.info-card h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.info-card p {
  color: var(--text-secondary);
  margin: 0;
  font-size: 0.9rem;
  line-height: 1.5;
}

/* === FOOTER === */
.footer {
  background: var(--accent-dark);
  color: var(--text-primary);
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
  width: 40px;
  height: 40px;
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
  padding-top: 1.5rem;
  color: var(--text-muted);
  font-size: 0.9rem;
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
  .page-hero { padding: 90px 0 30px; min-height: 30vh; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .data-header { flex-direction: column; text-align: center; gap: 0.5rem; }
  .data-item { flex-direction: column; align-items: start; gap: 0.3rem; }
  .data-percentage { margin-left: 0; }
  .chart-wrapper { height: 250px; }
}

@media (max-width: 576px) {
  .stats-grid { grid-template-columns: 1fr; }
  .section { padding: 2rem 0; }
  .chart-wrapper { height: 200px; }
}
</style>
</head>
<body data-theme="light">

<!-- NAVBAR - SAME AS PROFIL.PHP -->
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
          <a class="nav-link-custom dropdown-toggle active" href="#" id="dataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
          <li class="breadcrumb-item active">Data Statistik</li>
        </ol>
      </nav>
      <h1 class="page-title">Data Statistik Desa</h1>
      <p class="page-subtitle">
        Data terkini mengenai kependudukan, pendidikan, dan pekerjaan masyarakat Desa Pinabetengan Selatan.
      </p>
    </div>
  </div>
</section>

<!-- STATISTIK UTAMA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Statistik Utama</h2>
      <p class="section-subtitle">Data pokok kependudukan Desa Pinabetengan Selatan Tahun <?= $data_penduduk['tahun'] ?></p>
    </div>
    
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['total_penduduk']) ?></div>
        <div class="stat-label">Total Penduduk</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['kepala_keluarga']) ?></div>
        <div class="stat-label">Kepala Keluarga</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['laki_laki']) ?></div>
        <div class="stat-label">Penduduk Laki-laki</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['perempuan']) ?></div>
        <div class="stat-label">Penduduk Perempuan</div>
      </div>
    </div>
  </div>
</section>

<!-- DATA PENDUDUK -->
<section class="section section-alt">
  <div class="container">
    <div class="data-section">
      <div class="section-header">
        <h2 class="section-title">Data Kependudukan</h2>
        <p class="section-subtitle">Komposisi dan karakteristik penduduk Desa Pinabetengan Selatan</p>
      </div>
      
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="data-header">
              <div class="data-icon icon-population">
                <i class="fas fa-users"></i>
              </div>
              <h3 class="data-title">Komposisi Penduduk</h3>
            </div>
            
            <ul class="data-list">
              <li class="data-item">
                <span class="data-label">Total Penduduk</span>
                <span class="data-value"><?= number_format($data_penduduk['total_penduduk']) ?> Jiwa</span>
              </li>
              <li class="data-item">
                <span class="data-label">Laki-laki</span>
                <div>
                  <span class="data-value"><?= number_format($data_penduduk['laki_laki']) ?></span>
                  <span class="data-percentage"><?= number_format(($data_penduduk['laki_laki'] / $data_penduduk['total_penduduk']) * 100, 1) ?>%</span>
                </div>
              </li>
              <li class="data-item">
                <span class="data-label">Perempuan</span>
                <div>
                  <span class="data-value"><?= number_format($data_penduduk['perempuan']) ?></span>
                  <span class="data-percentage"><?= number_format(($data_penduduk['perempuan'] / $data_penduduk['total_penduduk']) * 100, 1) ?>%</span>
                </div>
              </li>
              <li class="data-item">
                <span class="data-label">Kepala Keluarga</span>
                <span class="data-value"><?= number_format($data_penduduk['kepala_keluarga']) ?> KK</span>
              </li>
              <li class="data-item">
                <span class="data-label">Kepadatan Penduduk</span>
                <span class="data-value"><?= number_format($data_penduduk['kepadatan_penduduk']) ?> jiwa/km²</span>
              </li>
            </ul>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="chart-container">
            <h4 class="chart-title"><i class="fas fa-chart-pie"></i> Grafik Komposisi Penduduk</h4>
            <div class="chart-wrapper">
              <canvas id="pendudukChart"></canvas>
            </div>
          </div>
          
          <div class="info-card">
            <h4><i class="fas fa-info-circle"></i> Informasi Data</h4>
            <p>Data kependudukan diperbarui secara berkala berdasarkan hasil pendataan dan administrasi kependudukan desa.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DATA PENDIDIKAN -->
<section class="section">
  <div class="container">
    <div class="data-section">
      <div class="section-header">
        <h2 class="section-title">Data Pendidikan</h2>
        <p class="section-subtitle">Tingkat pendidikan masyarakat Desa Pinabetengan Selatan</p>
      </div>
      
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="data-header">
              <div class="data-icon icon-education">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <h3 class="data-title">Tingkat Pendidikan</h3>
            </div>
            
            <ul class="data-list">
              <?php foreach($pendidikan as $edu): ?>
              <li class="data-item">
                <span class="data-label"><?= htmlspecialchars($edu['tingkat']) ?></span>
                <div>
                  <span class="data-value"><?= number_format($edu['jumlah']) ?></span>
                  <span class="data-percentage"><?= number_format($edu['persentase'], 1) ?>%</span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="chart-container">
            <h4 class="chart-title"><i class="fas fa-chart-bar"></i> Grafik Tingkat Pendidikan</h4>
            <div class="chart-wrapper">
              <canvas id="pendidikanChart"></canvas>
            </div>
          </div>
          
          <div class="info-card">
            <h4><i class="fas fa-chart-line"></i> Trend Pendidikan</h4>
            <p>Terdapat peningkatan jumlah masyarakat dengan pendidikan tinggi dalam 5 tahun terakhir.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DATA PEKERJAAN -->
<section class="section section-alt">
  <div class="container">
    <div class="data-section">
      <div class="section-header">
        <h2 class="section-title">Data Pekerjaan</h2>
        <p class="section-subtitle">Jenis pekerjaan dan mata pencaharian masyarakat</p>
      </div>
      
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="data-header">
              <div class="data-icon icon-work">
                <i class="fas fa-briefcase"></i>
              </div>
              <h3 class="data-title">Jenis Pekerjaan</h3>
            </div>
            
            <ul class="data-list">
              <?php foreach($pekerjaan as $job): ?>
              <li class="data-item">
                <span class="data-label"><?= htmlspecialchars($job['jenis']) ?></span>
                <div>
                  <span class="data-value"><?= number_format($job['jumlah']) ?></span>
                  <span class="data-percentage"><?= number_format($job['persentase'], 1) ?>%</span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="chart-container">
            <h4 class="chart-title"><i class="fas fa-chart-bar"></i> Grafik Distribusi Pekerjaan</h4>
            <div class="chart-wrapper">
              <canvas id="pekerjaanChart"></canvas>
            </div>
          </div>
          
          <div class="info-card">
            <h4><i class="fas fa-industry"></i> Potensi Ekonomi</h4>
            <p>Sebagian besar masyarakat bergerak di sektor pertanian dan UMKM.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <h3 class="footer-brand">Desa Pinabetengan Selatan</h3>
    <p class="footer-info">
      Jl. Desa Pinabetengan Selatan, Kec. Tompaso Baru<br>
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
      <p>&copy; <?= date('Y') ?> Desa Pinabetengan Selatan. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
// UNIFIED THEME MANAGEMENT - SAME AS PROFIL.PHP
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

// Chart.js Configuration
function getChartColors() {
  const isDark = document.body.getAttribute('data-theme') === 'dark';
  return {
    textColor: isDark ? '#E0E0E0' : '#2C2C2C',
    gridColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
    borderColor: isDark ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)'
  };
}

// Initialize Charts
function initCharts() {
  const colors = getChartColors();
  
  // Chart Komposisi Penduduk (Pie Chart)
  const pendudukCtx = document.getElementById('pendudukChart').getContext('2d');
  new Chart(pendudukCtx, {
    type: 'pie',
    data: {
      labels: <?= json_encode($penduduk_labels) ?>,
      datasets: [{
        data: <?= json_encode($penduduk_data) ?>,
        backgroundColor: <?= json_encode($penduduk_colors) ?>,
        borderColor: colors.borderColor,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: colors.textColor,
            font: {
              family: 'Inter, sans-serif'
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value.toLocaleString()} (${percentage}%)`;
            }
          }
        }
      }
    }
  });

  // Chart Pendidikan (Bar Chart)
  const pendidikanCtx = document.getElementById('pendidikanChart').getContext('2d');
  new Chart(pendidikanCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($pendidikan_labels) ?>,
      datasets: [{
        label: 'Jumlah Penduduk',
        data: <?= json_encode($pendidikan_data) ?>,
        backgroundColor: <?= json_encode($pendidikan_colors) ?>,
        borderColor: colors.borderColor,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `Jumlah: ${context.raw.toLocaleString()}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: colors.gridColor
          },
          ticks: {
            color: colors.textColor,
            font: {
              family: 'Inter, sans-serif'
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            color: colors.textColor,
            font: {
              family: 'Inter, sans-serif'
            }
          }
        }
      }
    }
  });

  // Chart Pekerjaan (Bar Chart)
  const pekerjaanCtx = document.getElementById('pekerjaanChart').getContext('2d');
  new Chart(pekerjaanCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($pekerjaan_labels) ?>,
      datasets: [{
        label: 'Jumlah Pekerja',
        data: <?= json_encode($pekerjaan_data) ?>,
        backgroundColor: <?= json_encode($pekerjaan_colors) ?>,
        borderColor: colors.borderColor,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `Jumlah: ${context.raw.toLocaleString()}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: colors.gridColor
          },
          ticks: {
            color: colors.textColor,
            font: {
              family: 'Inter, sans-serif'
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            color: colors.textColor,
            font: {
              family: 'Inter, sans-serif'
            }
          }
        }
      }
    }
  });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  initTheme();
  initCharts();
  
  // Theme toggle
  document.getElementById('themeToggle').addEventListener('click', function() {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
    
    // Reinitialize charts with new theme
    setTimeout(initCharts, 100);
    
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

  // Navbar scroll effect
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
      setTimeout(initCharts, 100);
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