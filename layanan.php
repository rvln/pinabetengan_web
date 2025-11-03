<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data layanan dengan error handling
try {
    $layanan = $pdo->query("SELECT * FROM layanan WHERE status = 'active' ORDER BY urutan")->fetchAll();
} catch (PDOException $e) {
    $layanan = [];
}

// Ambil data APBDes terbaru
try {
    $apbdes = $pdo->query("SELECT * FROM apbdes ORDER BY tahun DESC, created_at DESC LIMIT 1")->fetch();
} catch (PDOException $e) {
    $apbdes = null;
}

// Ambil data Rencana Kerja
try {
    $rencana_kerja = $pdo->query("SELECT * FROM rencana_kerja WHERE status = 'active' ORDER BY tahun DESC, created_at DESC")->fetchAll();
} catch (PDOException $e) {
    $rencana_kerja = [];
}

// Data fallback
if (empty($layanan)) {
    $layanan = [
        [
            'id' => 1,
            'nama' => 'Surat Keterangan Domisili',
            'deskripsi' => 'Pengurusan surat keterangan domisili untuk keperluan administrasi',
            'icon' => 'file-alt',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Foto 3x4', 'Surat Pengantar RT'],
            'waktu_proses' => '1-2 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 2,
            'nama' => 'Surat Keterangan Tidak Mampu',
            'deskripsi' => 'Surat keterangan tidak mampu untuk berbagai keperluan bantuan',
            'icon' => 'file-contract',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Surat Pengantar RT', 'Foto 3x4'],
            'waktu_proses' => '1-2 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 3,
            'nama' => 'Surat Keterangan Usaha',
            'deskripsi' => 'Pengurusan surat keterangan usaha untuk UMKM dan pedagang',
            'icon' => 'store',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Foto Usaha', 'Surat Pengantar RT'],
            'waktu_proses' => '2-3 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 4,
            'nama' => 'Surat Keterangan Kelahiran',
            'deskripsi' => 'Pengurusan surat keterangan kelahiran untuk administrasi kependudukan',
            'icon' => 'baby',
            'persyaratan' => ['Surat Keterangan Lahir dari Bidan/Rumah Sakit', 'KTP Orang Tua', 'Kartu Keluarga', 'Surat Nikah'],
            'waktu_proses' => '1 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 5,
            'nama' => 'Surat Keterangan Kematian',
            'deskripsi' => 'Pengurusan surat keterangan kematian untuk keperluan administrasi',
            'icon' => 'heartbeat',
            'persyaratan' => ['Surat Keterangan Kematian dari Dokter', 'KTP Alm', 'Kartu Keluarga', 'KTP Pelapor'],
            'waktu_proses' => '1 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 6,
            'nama' => 'Bantuan Hukum',
            'deskripsi' => 'Konsultasi dan pendampingan hukum bagi masyarakat desa',
            'icon' => 'balance-scale',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Dokumen terkait permasalahan'],
            'waktu_proses' => 'Sesuai Jadwal',
            'biaya' => 'Gratis'
        ]
    ];
}

if (!$apbdes) {
    $apbdes = [
        'tahun' => '2024',
        'anggaran_pendapatan' => 2500000000,
        'anggaran_belanja' => 2450000000,
        'saldo_awal' => 500000000,
        'file_url' => '#'
    ];
}

if (empty($rencana_kerja)) {
    $rencana_kerja = [
        [
            'id' => 1,
            'judul' => 'Pembangunan Jalan Desa',
            'tahun' => '2024',
            'deskripsi' => 'Peningkatan dan perbaikan infrastruktur jalan desa sepanjang 2 km',
            'lokasi' => 'Seluruh Desa',
            'anggaran' => 500000000,
            'status' => 'Dalam Pengerjaan'
        ],
        [
            'id' => 2,
            'judul' => 'Program Pemberdayaan UMKM',
            'tahun' => '2024',
            'deskripsi' => 'Pelatihan dan pendampingan untuk pengusaha mikro dan kecil',
            'lokasi' => 'Balai Desa',
            'anggaran' => 150000000,
            'status' => 'Perencanaan'
        ],
        [
            'id' => 3,
            'judul' => 'Rehabilitasi Saluran Irigasi',
            'tahun' => '2024',
            'deskripsi' => 'Perbaikan saluran irigasi untuk mendukung pertanian',
            'lokasi' => 'Area Persawahan',
            'anggaran' => 300000000,
            'status' => 'Selesai'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Layanan Desa - Desa Pinabetengan Selatan</title>

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
  --accent-green: #7CB342;
  --accent-blue: #2196F3;
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
  --accent-green: #8BC34A;
  --accent-blue: #42A5F5;
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

/* === LAYANAN CARDS === */
.layanan-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 2.5rem;
  box-shadow: var(--shadow);
  transition: var(--transition);
  border: 1px solid transparent;
  height: 100%;
  position: relative;
  overflow: hidden;
}

.layanan-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.layanan-icon {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, var(--accent-red), var(--accent-yellow));
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
  color: var(--bg-secondary);
}

.layanan-title {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--text-primary);
}

.layanan-desc {
  color: var(--text-secondary);
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.layanan-details {
  border-top: 1px solid var(--bg-tertiary);
  padding-top: 1.5rem;
  margin-top: 1.5rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.8rem 0;
  border-bottom: 1px solid var(--bg-tertiary);
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  color: var(--text-secondary);
  font-weight: 500;
}

.detail-value {
  color: var(--text-primary);
  font-weight: 600;
}

.badge-gratis {
  background: var(--accent-green);
  color: white;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

/* === APBDES CARD === */
.apbdes-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 2.5rem;
  box-shadow: var(--shadow);
  border: 1px solid var(--accent-blue);
  position: relative;
  overflow: hidden;
}

.apbdes-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.apbdes-year {
  background: var(--accent-blue);
  color: white;
  padding: 0.5rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  font-size: 1.1rem;
}

.budget-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.budget-item {
  background: var(--bg-tertiary);
  padding: 1.5rem;
  border-radius: var(--radius);
  text-align: center;
  border-left: 4px solid transparent;
}

.budget-label {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.budget-amount {
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--text-primary);
  font-family: 'Poppins', sans-serif;
}

.budget-pendapatan {
  border-left-color: var(--accent-green);
}

.budget-belanja {
  border-left-color: var(--accent-red);
}

.budget-saldo {
  border-left-color: var(--accent-blue);
}

/* === RENCANA KERJA CARDS === */
.rencana-card {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 2rem;
  box-shadow: var(--shadow);
  transition: var(--transition);
  border: 1px solid transparent;
  height: 100%;
}

.rencana-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  border-color: var(--accent-yellow);
}

.rencana-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.rencana-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  flex: 1;
}

.rencana-year {
  background: var(--accent-blue);
  color: white;
  padding: 0.3rem 1rem;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.rencana-desc {
  color: var(--text-secondary);
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.rencana-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.detail-box {
  background: var(--bg-tertiary);
  padding: 1rem;
  border-radius: 10px;
  text-align: center;
}

.detail-box-label {
  font-size: 0.8rem;
  color: var(--text-secondary);
  margin-bottom: 0.3rem;
}

.detail-box-value {
  font-size: 1rem;
  font-weight: 600;
  color: var(--text-primary);
}

.status-badge {
  padding: 0.4rem 1rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-selesai {
  background: var(--accent-green);
  color: white;
}

.status-proses {
  background: var(--accent-yellow);
  color: var(--accent-dark);
}

.status-rencana {
  background: var(--accent-blue);
  color: white;
}

/* === BUTTONS === */
.btn-primary-custom {
  background: var(--accent-red);
  color: white;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  transition: var(--transition);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary-custom:hover {
  background: var(--accent-red);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
}

.btn-outline-custom {
  background: transparent;
  color: var(--accent-red);
  border: 2px solid var(--accent-red);
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  transition: var(--transition);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-outline-custom:hover {
  background: var(--accent-red);
  color: white;
  transform: translateY(-2px);
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

.text-money {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .section { padding: 3rem 0; }
  .page-hero { padding: 90px 0 30px; min-height: 40vh; }
  .layanan-card, .apbdes-card, .rencana-card { padding: 2rem; }
  .budget-grid { grid-template-columns: 1fr; }
  .rencana-details { grid-template-columns: 1fr; }
  .apbdes-header { flex-direction: column; align-items: start; }
}

@media (max-width: 480px) {
  .layanan-card, .apbdes-card, .rencana-card { padding: 1.5rem; }
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
          <li class="breadcrumb-item active">Layanan Desa</li>
        </ol>
      </nav>
      <h1 class="page-title">Layanan Desa</h1>
      <p class="page-subtitle">
        Akses berbagai layanan publik desa, informasi APBDes, dan rencana kerja pemerintah desa secara transparan.
      </p>
    </div>
  </div>
</section>

<!-- LAYANAN PUBLIK -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Layanan Publik Desa</h2>
      <p class="section-subtitle">Berbagai layanan administrasi yang dapat diakses oleh masyarakat desa</p>
    </div>
    
    <div class="row g-4">
      <?php foreach($layanan as $l): ?>
        <div class="col-lg-6">
          <div class="layanan-card fade-in">
            <div class="layanan-icon">
              <i class="fas fa-<?= $l['icon'] ?? 'file-alt' ?>"></i>
            </div>
            <h3 class="layanan-title"><?= htmlspecialchars($l['nama']) ?></h3>
            <p class="layanan-desc"><?= htmlspecialchars($l['deskripsi']) ?></p>
            
            <div class="layanan-details">
              <?php if(isset($l['persyaratan'])): ?>
                <div class="detail-item">
                  <span class="detail-label">Persyaratan:</span>
                  <span class="detail-value"><?= count($l['persyaratan']) ?> Dokumen</span>
                </div>
              <?php endif; ?>
              
              <div class="detail-item">
                <span class="detail-label">Waktu Proses:</span>
                <span class="detail-value"><?= htmlspecialchars($l['waktu_proses']) ?></span>
              </div>
              
              <div class="detail-item">
                <span class="detail-label">Biaya:</span>
                <span class="detail-value">
                  <?php if(($l['biaya'] ?? '') === 'Gratis'): ?>
                    <span class="badge-gratis">GRATIS</span>
                  <?php else: ?>
                    <?= htmlspecialchars($l['biaya']) ?>
                  <?php endif; ?>
                </span>
              </div>
            </div>
            
            <div class="mt-3">
              <a href="form-layanan.php?id=<?= $l['id'] ?>" class="btn-primary-custom">
                <i class="fas fa-file-signature"></i> Ajukan Permohonan
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- APBDES -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Anggaran Pendapatan dan Belanja Desa (APBDes)</h2>
      <p class="section-subtitle">Transparansi pengelolaan keuangan desa tahun <?= htmlspecialchars($apbdes['tahun']) ?></p>
    </div>
    
    <div class="apbdes-card fade-in">
      <div class="apbdes-header">
        <div>
          <h3 class="mb-2">APBDes Tahun <?= htmlspecialchars($apbdes['tahun']) ?></h3>
          <p class="text-muted mb-0">Rencana Anggaran Pendapatan dan Belanja Desa</p>
        </div>
        <div class="apbdes-year">TAHUN <?= htmlspecialchars($apbdes['tahun']) ?></div>
      </div>
      
      <div class="budget-grid">
        <div class="budget-item budget-pendapatan">
          <div class="budget-label">PENDAPATAN</div>
          <div class="budget-amount text-money">Rp <?= number_format($apbdes['anggaran_pendapatan'], 0, ',', '.') ?></div>
        </div>
        
        <div class="budget-item budget-belanja">
          <div class="budget-label">BELANJA</div>
          <div class="budget-amount text-money">Rp <?= number_format($apbdes['anggaran_belanja'], 0, ',', '.') ?></div>
        </div>
        
        <div class="budget-item budget-saldo">
          <div class="budget-label">SALDO AWAL</div>
          <div class="budget-amount text-money">Rp <?= number_format($apbdes['saldo_awal'], 0, ',', '.') ?></div>
        </div>
      </div>
      
      <div class="d-flex gap-3 flex-wrap">
        <a href="<?= $apbdes['file_url'] ?>" class="btn-primary-custom" target="_blank">
          <i class="fas fa-download"></i> Download Dokumen APBDes
        </a>
        <a href="apbdes-detail.php" class="btn-outline-custom">
          <i class="fas fa-chart-bar"></i> Lihat Detail Anggaran
        </a>
      </div>
    </div>
  </div>
</section>

<!-- RENCANA KERJA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Rencana Kerja Pemerintah Desa</h2>
      <p class="section-subtitle">Program dan kegiatan pembangunan desa yang direncanakan</p>
    </div>
    
    <div class="row g-4">
      <?php foreach($rencana_kerja as $rk): ?>
        <div class="col-lg-6">
          <div class="rencana-card fade-in">
            <div class="rencana-header">
              <h3 class="rencana-title"><?= htmlspecialchars($rk['judul']) ?></h3>
              <div class="rencana-year"><?= htmlspecialchars($rk['tahun']) ?></div>
            </div>
            
            <p class="rencana-desc"><?= htmlspecialchars($rk['deskripsi']) ?></p>
            
            <div class="rencana-details">
              <div class="detail-box">
                <div class="detail-box-label">Lokasi</div>
                <div class="detail-box-value"><?= htmlspecialchars($rk['lokasi']) ?></div>
              </div>
              
              <div class="detail-box">
                <div class="detail-box-label">Anggaran</div>
                <div class="detail-box-value text-money">Rp <?= number_format($rk['anggaran'], 0, ',', '.') ?></div>
              </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
              <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $rk['status'])) ?>">
                <?= htmlspecialchars($rk['status']) ?>
              </span>
              <a href="rencana-detail.php?id=<?= $rk['id'] ?>" class="btn-outline-custom btn-sm">
                Detail <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-5">
      <a href="rencana-kerja.php" class="btn-primary-custom">
        <i class="fas fa-list"></i> Lihat Semua Rencana Kerja
      </a>
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  initTheme();
  
  // Theme toggle
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