<?php
session_start();
require_once 'config/db.php';

// Ambil data statistik
try {
    $stats = $pdo->query("SELECT * FROM statistik WHERE id = 1")->fetch();
} catch (Exception $e) {
    $stats = ['penduduk' => 2500, 'rukun_tetangga' => 8, 'umkm_aktif' => 15, 'destinasi_wisata' => 5];
}

// Ambil data berita terbaru
try {
    $berita_terbaru = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3")->fetchAll();
} catch (Exception $e) {
    $berita_terbaru = [];
}

// Ambil data potensi
try {
    $potensi_data = $pdo->query("SELECT * FROM potensi WHERE status = 'active' ORDER BY urutan ASC")->fetchAll();
} catch (Exception $e) {
    $potensi_data = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Desa Pinabetengan Selatan</title>

<!-- Bootstrap 5.3.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome 6.5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ========== NATURE COLOR PALETTE ========== */
:root {
  /* Primary - Putih Gading (Off-white/Ivory) */
  --ivory: #F5F3EF;
  --ivory-light: #FAFAF8;
  --ivory-dark: #E8E4DC;
  
  /* Secondary - Merah */
  --red: #C62828;
  --red-dark: #B71C1C;
  --red-light: #E53935;
  
  /* Accent - Kuning Jagung */
  --yellow: #FFD54F;
  --yellow-dark: #FFC107;
  --yellow-light: #FFECB3;
  
  /* Text - Hitam (ALL TEXT) */
  --black: #2C2C2C;
  --black-light: #4A4A4A;
  --grey: #6B6B6B;
  
  /* Nature accents */
  --green-leaf: #7CB342;
  --green-dark: #558B2F;
  --brown-earth: #8D6E63;
  --sky-blue: #64B5F6;
  --cream: #FFF9E6;
  
  /* Effects */
  --shadow-soft: 0 4px 20px rgba(44, 44, 44, 0.08);
  --shadow-medium: 0 8px 32px rgba(44, 44, 44, 0.12);
  --shadow-strong: 0 12px 48px rgba(44, 44, 44, 0.15);
  --shadow-glow: 0 0 40px rgba(255, 213, 79, 0.3);
  --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ========== GLOBAL STYLES ========== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--ivory);
  color: var(--black);
  line-height: 1.7;
  overflow-x: hidden;
  transition: background-color 0.4s ease, color 0.4s ease;
}

h1, h2, h3, h4, h5, h6 {
  font-family: 'Poppins', sans-serif;
  color: var(--black);
  font-weight: 700;
}

/* ========== DARK MODE ========== */
body.dark-mode {
  --ivory: #1a1a1a;
  --ivory-light: #1a1a1a;
  --ivory-dark: #151515;
  --black: #FFFFFF;
  --black-light: #E0E0E0;
  --grey: #B0B0B0;
  --cream: #1a1a1a;
  --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.5);
  --shadow-medium: 0 8px 32px rgba(0, 0, 0, 0.6);
  --shadow-strong: 0 12px 48px rgba(0, 0, 0, 0.7);
}

/* Dark mode - Hero Section - Soft Dark Solid */
body.dark-mode .hero-section {
  background: #1a1a1a;
}

body.dark-mode .hero-section::before {
  background: radial-gradient(circle, rgba(255, 213, 79, 0.04) 0%, transparent 70%);
}

body.dark-mode .hero-section::after {
  background: radial-gradient(circle, rgba(124, 179, 66, 0.03) 0%, transparent 70%);
}

body.dark-mode .deco-leaf {
  opacity: 0.03;
  color: var(--yellow);
}

/* Dark mode - Navbar */
body.dark-mode .navbar-custom {
  background: rgba(20, 20, 20, 0.95);
  box-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
  border-bottom: 1px solid rgba(255, 213, 79, 0.1);
}

body.dark-mode .navbar-custom.scrolled {
  background: rgba(20, 20, 20, 0.98);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
}

body.dark-mode .navbar-brand-custom {
  color: #FFFFFF !important;
}

body.dark-mode .navbar-brand-custom .icon-leaf {
  color: var(--yellow);
}

body.dark-mode .nav-link-custom {
  color: #E0E0E0 !important;
}

body.dark-mode .nav-link-custom:hover {
  color: var(--yellow) !important;
  background: rgba(255, 213, 79, 0.12);
}

body.dark-mode .nav-link-custom.active {
  color: var(--yellow) !important;
}

body.dark-mode .nav-link-custom::before {
  background: linear-gradient(90deg, var(--yellow), var(--red));
}

body.dark-mode .btn-login-custom {
  background: var(--red);
  color: #FFFFFF !important;
  border-color: var(--red);
}

body.dark-mode .btn-login-custom:hover {
  background: rgba(198, 40, 40, 0.2);
  color: var(--red) !important;
  border-color: var(--red);
}

/* Dark mode - Badges */
body.dark-mode .hero-badge,
body.dark-mode .section-badge {
  background: rgba(30, 30, 30, 0.9);
  border-color: var(--yellow);
  color: var(--yellow);
  box-shadow: 0 4px 20px rgba(255, 213, 79, 0.2);
}

/* Dark mode - Titles */
body.dark-mode .hero-title,
body.dark-mode .section-title {
  color: #FFFFFF;
}

body.dark-mode .hero-title .text-highlight {
  color: var(--yellow);
}

body.dark-mode .hero-title .text-highlight::after {
  background: linear-gradient(90deg, rgba(198, 40, 40, 0.3), rgba(198, 40, 40, 0.15));
}

body.dark-mode .hero-subtitle,
body.dark-mode .section-subtitle {
  color: #B0B0B0;
}

/* Dark mode - Cards - Solid Soft Background */
body.dark-mode .stat-card,
body.dark-mode .news-card,
body.dark-mode .potential-card {
  background: #222222;
  border: 1px solid rgba(255, 213, 79, 0.15);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}

body.dark-mode .stat-card:hover,
body.dark-mode .news-card:hover,
body.dark-mode .potential-card:hover {
  background: #282828;
  border-color: var(--yellow);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
}

body.dark-mode .feature-badge {
  background: #222222;
  border-color: rgba(255, 213, 79, 0.2);
}

body.dark-mode .feature-badge:hover {
  background: #282828;
  border-color: var(--yellow);
}

/* Dark mode - Stats Section - Solid Soft Dark */
body.dark-mode .stats-section {
  background: #1f1f1f;
}

body.dark-mode .stats-section::before {
  display: none;
}

body.dark-mode .stat-number {
  color: var(--yellow);
}

body.dark-mode .stat-label {
  color: #B0B0B0;
}

/* Dark mode - News Section - Solid Soft Dark */
body.dark-mode .news-section {
  background: #1a1a1a;
}

body.dark-mode .news-badge {
  background: linear-gradient(135deg, var(--yellow-dark), var(--yellow));
  color: #1a1a1a;
  border-color: var(--yellow);
}

body.dark-mode .news-image {
  background: linear-gradient(135deg, rgba(124, 179, 66, 0.25), rgba(255, 193, 7, 0.25));
}

body.dark-mode .news-title {
  color: #FFFFFF;
}

body.dark-mode .news-date,
body.dark-mode .news-excerpt {
  color: #B0B0B0;
}

body.dark-mode .news-date i {
  color: var(--yellow);
}

body.dark-mode .btn-read-more {
  color: var(--yellow);
}

body.dark-mode .btn-read-more:hover {
  color: var(--yellow-dark);
}

body.dark-mode .btn-read-more::after {
  background: linear-gradient(90deg, var(--yellow), var(--red));
}

/* Dark mode - Potentials Section - Solid Soft Dark */
body.dark-mode .potentials-section {
  background: #1f1f1f;
}

body.dark-mode .potential-title {
  color: #FFFFFF;
}

body.dark-mode .potential-description {
  color: #B0B0B0;
}

body.dark-mode .potential-card::before {
  background: radial-gradient(circle, rgba(255, 213, 79, 0.08) 0%, transparent 60%);
}

/* Dark mode - Hero Image Circle */
body.dark-mode .hero-image-circle {
  background: linear-gradient(135deg, rgba(124, 179, 66, 0.3), rgba(255, 193, 7, 0.3));
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
}

body.dark-mode .hero-image-circle::before {
  background: linear-gradient(135deg, rgba(124, 179, 66, 0.08), rgba(255, 213, 79, 0.08));
}

body.dark-mode .hero-image-circle::after {
  border-color: rgba(255, 255, 255, 0.15);
}

/* Dark mode - Icons */
body.dark-mode .stat-icon,
body.dark-mode .potential-icon {
  background: linear-gradient(135deg, rgba(255, 213, 79, 0.25), rgba(255, 193, 7, 0.35));
}

body.dark-mode .stat-icon i,
body.dark-mode .potential-icon i {
  color: var(--yellow-dark);
}

body.dark-mode .feature-badge i {
  background: linear-gradient(135deg, rgba(255, 213, 79, 0.25), rgba(255, 193, 7, 0.3));
  color: var(--yellow-dark);
}

/* Dark mode - Footer */
body.dark-mode .footer-section {
  background: #151515;
  border-top: 1px solid rgba(255, 213, 79, 0.1);
}

body.dark-mode .footer-brand {
  color: var(--yellow);
}

body.dark-mode .footer-info {
  color: rgba(224, 224, 224, 0.8);
}

body.dark-mode .footer-bottom {
  color: rgba(224, 224, 224, 0.6);
  border-top-color: rgba(255, 213, 79, 0.1);
}

body.dark-mode .social-link {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 213, 79, 0.2);
}

body.dark-mode .social-link:hover {
  background: var(--yellow);
  color: #1a1a1a;
  border-color: var(--yellow);
}

/* Dark mode - Buttons */
body.dark-mode .btn-hero-primary {
  background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
  box-shadow: 0 8px 30px rgba(198, 40, 40, 0.4);
}

body.dark-mode .btn-hero-primary:hover {
  box-shadow: 0 12px 40px rgba(198, 40, 40, 0.6);
}

body.dark-mode .btn-theme-toggle {
  background: rgba(255, 193, 7, 0.9);
  color: #1a1a1a;
}

body.dark-mode .btn-theme-toggle:hover {
  background: var(--yellow);
  color: #1a1a1a;
}

/* Dark mode - Scrollbar */
body.dark-mode::-webkit-scrollbar-track {
  background: #151515;
}

body.dark-mode::-webkit-scrollbar-thumb {
  background: rgba(255, 193, 7, 0.5);
  border: 2px solid #151515;
}

body.dark-mode::-webkit-scrollbar-thumb:hover {
  background: var(--yellow);
}

/* Dark mode - Remove all white/gray gradients */
body.dark-mode .stat-card::after,
body.dark-mode .news-card::before {
  background: linear-gradient(135deg, transparent, rgba(255, 213, 79, 0.03));
}

/* ========== NAVBAR IMPROVED ========== */
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

/* ========== NAVBAR BRAND FIX ========== */
.navbar-brand-custom {
  font-family: 'Poppins', sans-serif;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--black) !important;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
  white-space: nowrap;
  text-align: center;
  line-height: 1.2;
  flex-shrink: 0;
}

.navbar-brand-custom span {
  white-space: nowrap;
  display: inline-block;
}

.navbar-brand-custom .icon-leaf {
  color: var(--green-leaf);
  font-size: 1.5rem;
  transition: var(--transition);
  flex-shrink: 0;
}

.navbar-nav {
  gap: 0.2rem;
}

.nav-link-custom {
  color: var(--black) !important;
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
  background: linear-gradient(90deg, var(--red), var(--yellow));
  border-radius: 2px;
  transition: var(--transition);
}

.nav-link-custom:hover::before,
.nav-link-custom.active::before {
  width: 50%;
}

.nav-link-custom:hover {
  color: var(--red) !important;
  background: var(--yellow-light);
}

/* Improved dropdown styles */
.nav-link-custom.dropdown-toggle {
  padding-right: 2rem !important;
}

.nav-link-custom.dropdown-toggle::after {
  font-size: 0.7rem;
  right: 0.8rem;
}

.dropdown-menu {
  min-width: 200px;
  border-radius: 15px;
  padding: 0.5rem 0;
  margin-top: 0.5rem !important;
}

.dropdown-item {
  padding: 0.6rem 1.2rem;
  font-size: 0.9rem;
  border-left: 2px solid transparent;
}

/* Login button improvements */
.btn-login-custom {
  background: var(--red);
  color: var(--ivory) !important;
  padding: 0.6rem 1.8rem;
  border-radius: 50px;
  font-weight: 600;
  border: 2px solid var(--red);
  transition: var(--transition);
  box-shadow: 0 4px 15px rgba(198, 40, 40, 0.2);
  text-decoration: none;
  display: inline-block;
}

.btn-login-custom:hover {
  background: transparent;
  color: var(--red) !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(198, 40, 40, 0.3);
}

.btn-theme-toggle {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: var(--yellow);
  border: none;
  color: var(--black);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
  box-shadow: var(--shadow-soft);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.btn-theme-toggle::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
  opacity: 0;
  transition: var(--transition);
}

.btn-theme-toggle:hover::before {
  opacity: 1;
}

.btn-theme-toggle:hover {
  background: var(--yellow-dark);
  transform: scale(1.15) rotate(180deg);
  box-shadow: 0 6px 25px rgba(255, 213, 79, 0.4);
}

.btn-theme-toggle i {
  font-size: 1.2rem;
  transition: var(--transition);
}

/* Dark mode toggle icon */
body.dark-mode .btn-theme-toggle {
  background: var(--yellow-dark);
  color: var(--ivory);
}

body.dark-mode .btn-theme-toggle:hover {
  background: var(--yellow);
  color: var(--black);
}

/* ========== DROPDOWN STYLES ========== */
.nav-item.dropdown {
  position: relative;
}

.nav-link-custom.dropdown-toggle {
  position: relative;
  padding-right: 2.5rem !important;
}

.nav-link-custom.dropdown-toggle::after {
  content: '\f078';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  border: none;
  position: absolute;
  right: 1.2rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.8rem;
  transition: var(--transition);
}

.nav-link-custom.dropdown-toggle.show::after {
  transform: translateY(-50%) rotate(180deg);
}

.dropdown-menu {
  background: white;
  border: none;
  border-radius: 20px;
  padding: 1rem 0;
  box-shadow: var(--shadow-strong);
  margin-top: 0.8rem !important;
  animation: dropdownFadeIn 0.3s ease-out;
  min-width: 250px;
  border: 2px solid var(--ivory-dark);
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
  padding: 0.8rem 1.5rem;
  color: var(--black);
  font-weight: 500;
  transition: var(--transition);
  position: relative;
  border-left: 3px solid transparent;
}

.dropdown-item:hover,
.dropdown-item:focus {
  background: linear-gradient(135deg, var(--yellow-light), white);
  color: var(--red);
  border-left-color: var(--red);
  transform: translateX(5px);
}

.dropdown-item:hover::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background: linear-gradient(to bottom, var(--red), var(--yellow));
  border-radius: 0 2px 2px 0;
}

/* Dark mode dropdown styles */
body.dark-mode .dropdown-menu {
  background: #222222;
  border-color: rgba(255, 213, 79, 0.15);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
}

body.dark-mode .dropdown-item {
  color: #E0E0E0;
}

body.dark-mode .dropdown-item:hover,
body.dark-mode .dropdown-item:focus {
  background: rgba(255, 213, 79, 0.12);
  color: var(--yellow);
}

/* ========== MOBILE NAVBAR FIXES ========== */
@media (max-width: 991px) {
  /* Navbar brand adjustment for mobile */
  .navbar-brand-custom {
    font-size: 1.1rem;
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .navbar-brand-custom .icon-leaf {
    font-size: 1.2rem;
  }
  
  /* Navbar collapse fixes */
  .navbar-collapse {
    background: rgba(245, 243, 239, 0.98);
    backdrop-filter: blur(20px);
    padding: 1rem;
    margin-top: 1rem;
    border-radius: 20px;
    box-shadow: var(--shadow-medium);
    border: 1px solid var(--ivory-dark);
  }
  
  body.dark-mode .navbar-collapse {
    background: rgba(20, 20, 20, 0.98);
    border-color: rgba(255, 213, 79, 0.1);
  }
  
  /* Nav items mobile optimization */
  .navbar-nav {
    gap: 0.5rem;
    margin-bottom: 1rem;
  }
  
  .nav-link-custom {
    padding: 1rem 1.2rem !important;
    margin: 0.2rem 0;
    border-radius: 15px;
    text-align: center;
    font-size: 1rem;
    border: 1px solid transparent;
  }
  
  .nav-link-custom:hover {
    border-color: var(--yellow);
  }
  
  /* Dropdown menu mobile fixes */
  .dropdown-menu {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    box-shadow: none;
    margin: 0.5rem 0 !important;
    border-radius: 15px;
  }
  
  body.dark-mode .dropdown-menu {
    background: rgba(30, 30, 30, 0.9);
  }
  
  .dropdown-item {
    padding: 0.8rem 1.5rem;
    border-left: 3px solid transparent;
    margin: 0.2rem 0;
    border-radius: 10px;
  }
  
  .dropdown-item:hover {
    border-left-color: var(--red);
    transform: none;
  }
  
  /* Login and theme buttons mobile */
  .d-flex.gap-3 {
    flex-direction: column;
    gap: 1rem !important;
    width: 100%;
  }
  
  .btn-login-custom {
    width: 100%;
    text-align: center;
    justify-content: center;
    margin: 0;
  }
  
  .btn-theme-toggle {
    align-self: center;
  }
}

/* Navbar toggler button improvements */
.navbar-toggler {
  border: 2px solid var(--yellow);
  padding: 0.4rem 0.6rem;
  border-radius: 12px;
  transition: var(--transition);
}

.navbar-toggler:hover {
  background: var(--yellow-light);
  transform: scale(1.05);
}

.navbar-toggler:focus {
  box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.3);
}

.navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2844, 44, 44, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  width: 1.2em;
  height: 1.2em;
}

body.dark-mode .navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Extra small devices fixes */
@media (max-width: 576px) {
  .navbar-brand-custom {
    font-size: 1rem;
    max-width: 160px;
  }
  
  .container {
    padding-left: 15px;
    padding-right: 15px;
  }
  
  .navbar > .container {
    flex-wrap: wrap;
  }
}

/* Ensure navbar doesn't break on very small screens */
@media (max-width: 360px) {
  .navbar-brand-custom {
    font-size: 0.9rem;
    max-width: 140px;
  }
  
  .navbar-brand-custom .icon-leaf {
    font-size: 1rem;
  }
}

/* Smooth navbar collapse animation */
.navbar-collapse {
  transition: all 0.3s ease-in-out;
}

/* Prevent horizontal scrolling on mobile */
html, body {
  max-width: 100%;
  overflow-x: hidden;
}

/* Mobile responsive improvements */
@media (max-width: 991px) {
  .navbar-nav {
    gap: 0.5rem;
    margin-top: 1rem;
  }
  
  .nav-link-custom {
    padding: 0.8rem 1rem !important;
    margin: 0.1rem 0;
  }
  
  .dropdown-menu {
    margin-top: 0 !important;
    border-radius: 10px;
  }
  
  .dropdown-item {
    padding-left: 2rem;
  }
}

/* Untuk layar sangat kecil, atur font-size lebih kecil */
@media (max-width: 400px) {
  .navbar-brand-custom {
    font-size: 1.1rem;
    gap: 0.3rem;
  }
  
  .navbar-brand-custom .icon-leaf {
    font-size: 1.3rem;
  }
}

/* Pastikan navbar toggle button tidak mempengaruhi layout brand */
.navbar-toggler {
  border: none;
  padding: 0.25rem 0.5rem;
}

.navbar-toggler:focus {
  box-shadow: none;
}

/* Improve navbar collapse behavior */
.navbar-collapse {
  flex-grow: 0;
}

/* Container adjustment for better spacing */
.navbar > .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap;
}

/* Dark mode navbar improvements */
body.dark-mode .navbar-custom {
  background: rgba(20, 20, 20, 0.95);
  padding: 0.8rem 0;
}

body.dark-mode .navbar-custom.scrolled {
  background: rgba(20, 20, 20, 0.98);
  padding: 0.5rem 0;
}

/* Smooth hover effects for dropdown */
.nav-item.dropdown:hover .dropdown-menu {
  display: block;
}

@media (min-width: 992px) {
  .dropdown-menu {
    display: block;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
  }
  
  .nav-item.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
}

/* ========== HERO SECTION ========== */
.hero-section {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--ivory) 0%, var(--cream) 50%, var(--ivory-light) 100%);
  padding-top: 100px;
  position: relative;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: -100px;
  right: -100px;
  width: 800px;
  height: 800px;
  background: radial-gradient(circle, rgba(255, 213, 79, 0.2) 0%, rgba(255, 213, 79, 0.05) 50%, transparent 70%);
  border-radius: 50%;
  animation: float 15s ease-in-out infinite;
}

.hero-section::after {
  content: '';
  position: absolute;
  bottom: -150px;
  left: -150px;
  width: 700px;
  height: 700px;
  background: radial-gradient(circle, rgba(124, 179, 66, 0.15) 0%, rgba(124, 179, 66, 0.05) 50%, transparent 70%);
  border-radius: 50%;
  animation: float 20s ease-in-out infinite reverse;
}

/* Decorative elements */
.hero-section .deco-leaf {
  position: absolute;
  opacity: 0.08;
  color: var(--green-leaf);
  animation: floatLeaf 25s ease-in-out infinite;
}

.hero-section .deco-leaf-1 {
  top: 20%;
  left: 10%;
  font-size: 4rem;
  animation-delay: 0s;
}

.hero-section .deco-leaf-2 {
  top: 60%;
  right: 15%;
  font-size: 3rem;
  animation-delay: 5s;
}

.hero-section .deco-leaf-3 {
  bottom: 25%;
  left: 20%;
  font-size: 3.5rem;
  animation-delay: 10s;
}

@keyframes floatLeaf {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  25% {
    transform: translateY(-30px) rotate(10deg);
  }
  50% {
    transform: translateY(-60px) rotate(-5deg);
  }
  75% {
    transform: translateY(-30px) rotate(5deg);
  }
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, var(--yellow-light), white);
  color: var(--black);
  padding: 0.7rem 1.5rem;
  border-radius: 50px;
  font-size: 0.95rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  box-shadow: var(--shadow-soft);
  border: 2px solid var(--yellow);
  position: relative;
  overflow: hidden;
}

.hero-badge::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.5), transparent);
  animation: shine 3s infinite;
}

@keyframes shine {
  0% {
    transform: translateX(-100%) translateY(-100%) rotate(45deg);
  }
  100% {
    transform: translateX(100%) translateY(100%) rotate(45deg);
  }
}

.hero-title {
  font-size: clamp(2.8rem, 6vw, 5.5rem);
  font-weight: 800;
  color: var(--black);
  line-height: 1.1;
  margin-bottom: 1.5rem;
  position: relative;
  letter-spacing: -1px;
}

.hero-title .text-highlight {
  color: var(--red);
  position: relative;
  display: inline-block;
}

.hero-title .text-highlight::after {
  content: '';
  position: absolute;
  bottom: 0px;
  left: -5px;
  right: -5px;
  height: 15px;
  background: linear-gradient(90deg, var(--yellow), var(--yellow-light));
  opacity: 0.5;
  border-radius: 8px;
  z-index: -1;
  transform: skewX(-5deg);
}

.hero-subtitle {
  font-size: 1.25rem;
  color: var(--grey);
  line-height: 1.9;
  margin-bottom: 3rem;
  max-width: 600px;
  font-weight: 400;
}

.hero-buttons {
  display: flex;
  gap: 1.2rem;
  flex-wrap: wrap;
  margin-bottom: 3.5rem;
}

.btn-hero-primary {
  background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
  color: var(--ivory);
  padding: 1.2rem 3rem;
  border-radius: 50px;
  font-weight: 600;
  border: none;
  box-shadow: 0 8px 30px rgba(198, 40, 40, 0.35);
  transition: var(--transition);
  display: inline-flex;
  align-items: center;
  gap: 0.8rem;
  font-size: 1.1rem;
  position: relative;
  overflow: hidden;
}

.btn-hero-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.6s;
}

.btn-hero-primary:hover::before {
  left: 100%;
}

.btn-hero-primary:hover {
  background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
  transform: translateY(-4px) scale(1.05);
  box-shadow: 0 12px 40px rgba(198, 40, 40, 0.45);
  color: var(--ivory);
}

.btn-hero-primary i {
  transition: transform 0.3s;
}

.btn-hero-primary:hover i {
  transform: translateX(5px);
}

.hero-features {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}

.feature-badge {
  display: flex;
  align-items: center;
  gap: 1rem;
  color: var(--black);
  font-weight: 500;
  background: white;
  padding: 0.8rem 1.5rem;
  border-radius: 50px;
  box-shadow: var(--shadow-soft);
  transition: var(--transition);
  border: 1px solid var(--ivory-dark);
}

.feature-badge:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-medium);
  border-color: var(--yellow);
}

.feature-badge i {
  width: 45px;
  height: 45px;
  background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
  color: var(--red);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  box-shadow: 0 4px 15px rgba(255, 213, 79, 0.3);
}

.hero-image-wrapper {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-image-circle {
  width: 500px;
  height: 500px;
  background: linear-gradient(135deg, var(--green-leaf) 0%, var(--yellow) 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  box-shadow: 0 20px 60px rgba(124, 179, 66, 0.35);
  animation: float 6s ease-in-out infinite;
}

.hero-image-circle::before {
  content: '';
  position: absolute;
  inset: -30px;
  background: linear-gradient(135deg, rgba(124, 179, 66, 0.15), rgba(255, 213, 79, 0.15));
  border-radius: 50%;
  z-index: -1;
  animation: pulse 4s ease-in-out infinite;
}

.hero-image-circle::after {
  content: '';
  position: absolute;
  inset: 20px;
  border: 3px dashed rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  animation: rotate 30s linear infinite;
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.hero-image-circle i {
  font-size: 12rem;
  color: var(--ivory);
  opacity: 0.95;
  filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.2));
  animation: breathe 4s ease-in-out infinite;
}

@keyframes breathe {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.1); opacity: 0.3; }
}

/* ========== SECTION HEADERS ========== */
.section-wrapper {
  padding: 6rem 0;
  position: relative;
}

.section-header {
  text-align: center;
  margin-bottom: 5rem;
  position: relative;
}

.section-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background: linear-gradient(135deg, var(--yellow-light), white);
  color: var(--black);
  padding: 0.6rem 1.5rem;
  border-radius: 50px;
  font-size: 0.95rem;
  font-weight: 600;
  margin-bottom: 1.2rem;
  box-shadow: var(--shadow-soft);
  border: 2px solid var(--yellow);
}

.section-title {
  font-size: clamp(2.5rem, 5vw, 4rem);
  color: var(--black);
  margin-bottom: 1.5rem;
  position: relative;
  display: inline-block;
  letter-spacing: -0.5px;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 120px;
  height: 5px;
  background: linear-gradient(90deg, var(--red), var(--yellow));
  border-radius: 3px;
  box-shadow: 0 2px 10px rgba(198, 40, 40, 0.3);
}

.section-subtitle {
  font-size: 1.15rem;
  color: var(--grey);
  max-width: 750px;
  margin: 2.5rem auto 0;
  line-height: 1.9;
}

/* ========== STATISTICS SECTION ========== */
.stats-section {
  background: linear-gradient(135deg, var(--ivory-light) 0%, white 50%, var(--ivory-light) 100%);
  position: relative;
}

.stats-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 200px;
  background: linear-gradient(to bottom, var(--ivory), transparent);
}

.stat-card {
  background: white;
  padding: 3.5rem 2.5rem;
  border-radius: 30px;
  text-align: center;
  transition: var(--transition);
  box-shadow: var(--shadow-soft);
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  background: linear-gradient(90deg, var(--red), var(--yellow));
  transform: scaleX(0);
  transform-origin: left;
  transition: var(--transition);
}

.stat-card::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(255, 213, 79, 0.05));
  opacity: 0;
  transition: var(--transition);
}

.stat-card:hover::before {
  transform: scaleX(1);
}

.stat-card:hover::after {
  opacity: 1;
}

.stat-card:hover {
  transform: translateY(-12px);
  box-shadow: 0 20px 60px rgba(44, 44, 44, 0.2);
  border-color: var(--yellow);
}

.stat-icon {
  width: 90px;
  height: 90px;
  background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  transition: var(--transition);
  box-shadow: 0 8px 25px rgba(255, 213, 79, 0.3);
  position: relative;
  z-index: 1;
}

.stat-icon::before {
  content: '';
  position: absolute;
  inset: -8px;
  background: linear-gradient(135deg, rgba(255, 213, 79, 0.2), transparent);
  border-radius: 50%;
  z-index: -1;
  opacity: 0;
  transition: var(--transition);
}

.stat-card:hover .stat-icon::before {
  opacity: 1;
}

.stat-icon i {
  font-size: 2.8rem;
  color: var(--red);
}

.stat-card:hover .stat-icon {
  transform: scale(1.15) rotate(10deg);
  box-shadow: 0 12px 35px rgba(255, 213, 79, 0.4);
}

.stat-number {
  font-size: 4rem;
  font-weight: 800;
  color: var(--red);
  margin-bottom: 0.8rem;
  font-family: 'Poppins', sans-serif;
  letter-spacing: -2px;
}

.stat-label {
  font-size: 1.15rem;
  color: var(--grey);
  font-weight: 500;
  letter-spacing: 0.3px;
}

/* ========== NEWS SECTION ========== */
.news-section {
  background: var(--ivory);
  position: relative;
}

.news-card {
  background: white;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: var(--shadow-soft);
  transition: var(--transition);
  height: 100%;
  border: 2px solid transparent;
  position: relative;
}

.news-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(255, 213, 79, 0.05));
  opacity: 0;
  transition: var(--transition);
  pointer-events: none;
}

.news-card:hover::before {
  opacity: 1;
}

.news-card:hover {
  transform: translateY(-15px);
  box-shadow: 0 25px 70px rgba(44, 44, 44, 0.18);
  border-color: var(--yellow);
}

.news-image {
  height: 280px;
  background: linear-gradient(135deg, var(--green-leaf), var(--yellow-dark));
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.news-image::before {
  content: '';
  position: absolute;
  inset: 0;
  background: 
    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
}

.news-image::after {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  animation: shimmer 8s infinite;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%) translateY(-100%) rotate(45deg);
  }
  100% {
    transform: translateX(100%) translateY(100%) rotate(45deg);
  }
}

.news-image i {
  font-size: 5.5rem;
  color: white;
  opacity: 0.95;
  z-index: 1;
  position: relative;
  filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.2));
}

.news-badge {
  position: absolute;
  top: 1.2rem;
  right: 1.2rem;
  background: linear-gradient(135deg, var(--yellow), var(--yellow-dark));
  color: var(--black);
  padding: 0.6rem 1.5rem;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.9rem;
  z-index: 2;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
  border: 2px solid white;
}

.news-content {
  padding: 2.5rem;
}

.news-date {
  color: var(--grey);
  font-size: 1rem;
  margin-bottom: 1.2rem;
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-weight: 500;
}

.news-date i {
  color: var(--red);
  font-size: 1.1rem;
}

.news-title {
  font-size: 1.5rem;
  color: var(--black);
  margin-bottom: 1.2rem;
  line-height: 1.5;
  font-weight: 700;
  letter-spacing: -0.3px;
}

.news-excerpt {
  color: var(--grey);
  line-height: 1.8;
  margin-bottom: 1.8rem;
  font-size: 1.05rem;
}

.btn-read-more {
  color: var(--red);
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  transition: var(--transition);
  position: relative;
  font-size: 1.05rem;
}

.btn-read-more::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--red), var(--yellow));
  transition: var(--transition);
  border-radius: 2px;
}

.btn-read-more:hover {
  color: var(--red-dark);
  gap: 1.2rem;
}

.btn-read-more:hover::after {
  width: calc(100% - 30px);
}

/* ========== POTENTIALS SECTION ========== */
.potentials-section {
  background: linear-gradient(135deg, var(--ivory-light) 0%, white 50%, var(--ivory-light) 100%);
  position: relative;
}

.potential-card {
  background: white;
  padding: 4rem 3rem;
  border-radius: 30px;
  text-align: center;
  transition: var(--transition);
  box-shadow: var(--shadow-soft);
  height: 100%;
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.potential-card::before {
  content: '';
  position: absolute;
  top: -100%;
  left: -100%;
  width: 300%;
  height: 300%;
  background: radial-gradient(circle, var(--yellow-light) 0%, transparent 60%);
  opacity: 0;
  transition: var(--transition);
}

.potential-card:hover::before {
  opacity: 0.8;
  top: -50%;
  left: -50%;
}

.potential-card:hover {
  transform: translateY(-15px);
  box-shadow: 0 25px 70px rgba(44, 44, 44, 0.18);
  border-color: var(--yellow);
}

.potential-icon {
  width: 110px;
  height: 110px;
  background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2.5rem;
  transition: var(--transition);
  position: relative;
  z-index: 1;
  box-shadow: 0 10px 35px rgba(255, 213, 79, 0.35);
}

.potential-icon::before {
  content: '';
  position: absolute;
  inset: -10px;
  background: linear-gradient(135deg, rgba(255, 213, 79, 0.2), transparent);
  border-radius: 50%;
  z-index: -1;
  opacity: 0;
  transition: var(--transition);
}

.potential-card:hover .potential-icon::before {
  opacity: 1;
  inset: -15px;
}

.potential-icon i {
  font-size: 3.5rem;
  color: var(--red);
  filter: drop-shadow(0 4px 10px rgba(198, 40, 40, 0.2));
}

.potential-card:hover .potential-icon {
  transform: scale(1.2) rotate(15deg);
  box-shadow: 0 15px 45px rgba(255, 213, 79, 0.45);
}

.potential-title {
  font-size: 1.8rem;
  color: var(--black);
  margin-bottom: 1.5rem;
  font-weight: 700;
  position: relative;
  z-index: 1;
  letter-spacing: -0.3px;
}

.potential-description {
  color: var(--grey);
  line-height: 1.9;
  font-size: 1.1rem;
  position: relative;
  z-index: 1;
}

/* ========== FOOTER ========== */
.footer-section {
  background: linear-gradient(135deg, var(--black) 0%, var(--black-light) 100%);
  color: var(--ivory);
  padding: 4rem 0 2rem;
  position: relative;
  overflow: hidden;
}

.footer-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="1" fill="white" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.05"/></svg>');
  opacity: 0.5;
}

.footer-content {
  position: relative;
  z-index: 2;
}

.footer-brand {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--yellow);
  margin-bottom: 1rem;
  font-family: 'Poppins', sans-serif;
}

.footer-info {
  color: rgba(245, 243, 239, 0.8);
  line-height: 1.8;
  margin-bottom: 2rem;
}

.social-links {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 2.5rem;
}

.social-link {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--ivory);
  font-size: 1.3rem;
  transition: var(--transition);
  text-decoration: none;
  backdrop-filter: blur(10px);
}

.social-link:hover {
  background: var(--yellow);
  color: var(--black);
  transform: translateY(-5px) scale(1.1);
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 2rem;
  margin-top: 2rem;
  text-align: center;
  color: rgba(245, 243, 239, 0.7);
}

/* ========== ANIMATIONS ========== */
.fade-in-up {
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.scroll-reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.scroll-reveal.revealed {
  opacity: 1;
  transform: translateY(0);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.1rem;
  }
  
  .hero-image-circle {
    width: 300px;
    height: 300px;
  }
  
  .hero-image-circle i {
    font-size: 6rem;
  }
  
  .section-title {
    font-size: 2.2rem;
  }
  
  .stat-number {
    font-size: 2.8rem;
  }
  
  .hero-buttons {
    flex-direction: column;
  }
  
  .btn-hero-primary {
    width: 100%;
    justify-content: center;
  }
  
  .hero-features {
    flex-direction: column;
    gap: 1rem;
  }
  
  .deco-leaf {
    display: none;
  }
}

/* ========== SCROLLBAR ========== */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: var(--ivory);
}

::-webkit-scrollbar-thumb {
  background: var(--red);
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--red-dark);
}

body.dark-mode::-webkit-scrollbar-track {
  background: var(--ivory-dark);
}

body.dark-mode::-webkit-scrollbar-thumb {
  background: var(--yellow);
}

body.dark-mode::-webkit-scrollbar-thumb:hover {
  background: var(--yellow-dark);
}

/* ========== THEME TRANSITION ========== */
body, 
.navbar-custom,
.stat-card,
.news-card,
.potential-card,
.feature-badge,
.hero-badge,
.section-badge,
.news-badge,
.hero-section,
.hero-section::before,
.hero-section::after,
.hero-title,
.hero-subtitle,
.section-title,
.section-subtitle,
.hero-image-circle,
.hero-image-circle::before,
.stat-icon,
.potential-icon,
.feature-badge i,
.stat-number,
.stat-label,
.news-title,
.news-date,
.news-excerpt,
.potential-title,
.potential-description,
.footer-section,
.footer-brand,
.footer-info,
.footer-bottom,
.social-link,
.nav-link-custom,
.navbar-brand-custom,
.btn-login-custom,
.deco-leaf {
  transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease, opacity 0.4s ease;
}
</style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand-custom" href="#">
      <i class="fas fa-leaf icon-leaf"></i>
      <span>Pinabetengan Selatan</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link-custom active" href="#home">Beranda</a>
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
      <div class="d-flex gap-3 align-items-center">
        <a class="btn-login-custom" href="login.php">Login</a>
        <button class="btn-theme-toggle" id="themeToggle">
          <i class="fas fa-moon"></i>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- ========== HERO SECTION ========== -->
<section id="home" class="hero-section">
  <!-- Decorative floating leaves -->
  <i class="fas fa-leaf deco-leaf deco-leaf-1"></i>
  <i class="fas fa-seedling deco-leaf deco-leaf-2"></i>
  <i class="fas fa-spa deco-leaf deco-leaf-3"></i>
  
  <div class="container">
    <div class="row align-items-center min-vh-100">
      <div class="col-lg-6 hero-content fade-in-up">
        <div class="hero-badge">
          <i class="fas fa-leaf"></i>
          <span>Desa Wisata Alam & Budaya</span>
        </div>
        <h1 class="hero-title">
          Desa <span class="text-highlight">Pinabetengan</span> Selatan
        </h1>
        <p class="hero-subtitle">
          Sebuah permata tersembunyi di Minahasa yang memadukan keindahan alam, kekayaan budaya, dan keramahan masyarakat dalam harmoni yang sempurna.
        </p>
        <div class="hero-buttons">
          <button class="btn-hero-primary">
            Jelajahi Keindahan
            <i class="fas fa-arrow-right"></i>
          </button>
        </div>
        <div class="hero-features mt-4">
          <div class="feature-badge">
            <i class="fas fa-mountain"></i>
            <span>Alam Memukau</span>
          </div>
          <div class="feature-badge">
            <i class="fas fa-monument"></i>
            <span>Warisan Budaya</span>
          </div>
          <div class="feature-badge">
            <i class="fas fa-heart"></i>
            <span>Ramah & Sejahtera</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image-wrapper">
          <div class="hero-image-circle">
            <i class="fas fa-tree"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== STATISTICS SECTION ========== -->
<section class="section-wrapper stats-section">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">
        <i class="fas fa-chart-line"></i>
        <span>Data & Statistik</span>
      </div>
      <h2 class="section-title">Desa Dalam Angka</h2>
      <p class="section-subtitle">
        Mengenal Desa Pinabetengan Selatan melalui data dan pencapaian yang membanggakan
      </p>
    </div>
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="stat-card scroll-reveal">
          <div class="stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['penduduk'] ?? 2500) ?></div>
          <div class="stat-label">Jiwa Penduduk</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card scroll-reveal">
          <div class="stat-icon">
            <i class="fas fa-home"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['rukun_tetangga'] ?? 8) ?></div>
          <div class="stat-label">Rukun Tetangga</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card scroll-reveal">
          <div class="stat-icon">
            <i class="fas fa-store"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['umkm_aktif'] ?? 15) ?></div>
          <div class="stat-label">UMKM Aktif</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card scroll-reveal">
          <div class="stat-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['destinasi_wisata'] ?? 5) ?></div>
          <div class="stat-label">Destinasi Wisata</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== NEWS SECTION ========== -->
<section class="section-wrapper news-section">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">
        <i class="fas fa-newspaper"></i>
        <span>Berita Terbaru</span>
      </div>
      <h2 class="section-title">Kabar Desa</h2>
      <p class="section-subtitle">
        Ikuti perkembangan dan kegiatan terbaru dari Desa Pinabetengan Selatan
      </p>
    </div>
    <div class="row g-4">
      <?php if (empty($berita_terbaru)): ?>
      <div class="col-12">
        <div class="text-center py-5">
          <i class="fas fa-newspaper" style="font-size: 4rem; color: var(--grey); opacity: 0.5;"></i>
          <p class="mt-3" style="color: var(--grey);">Belum ada berita tersedia.</p>
        </div>
      </div>
      <?php else: 
      foreach ($berita_terbaru as $berita): ?>
      <div class="col-lg-4 col-md-6">
        <div class="news-card scroll-reveal">
          <div class="news-image">
            <i class="fas fa-newspaper"></i>
            <div class="news-badge">
              <?= $berita['kategori'] === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?>
            </div>
          </div>
          <div class="news-content">
            <div class="news-date">
              <i class="fas fa-calendar"></i>
              <?= date('d F Y', strtotime($berita['tanggal'])) ?>
            </div>
            <h3 class="news-title"><?= htmlspecialchars($berita['judul']) ?></h3>
            <p class="news-excerpt">
              <?= substr(strip_tags($berita['isi']), 0, 120) ?>...
            </p>
            <a href="berita-detail.php?id=<?= $berita['id'] ?>" class="btn-read-more">
              Baca Selengkapnya
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; 
      endif; ?>
    </div>
  </div>
</section>

<!-- ========== POTENTIALS SECTION ========== -->
<section class="section-wrapper potentials-section">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">
        <i class="fas fa-gem"></i>
        <span>Keunggulan Desa</span>
      </div>
      <h2 class="section-title">Potensi Unggulan</h2>
      <p class="section-subtitle">
        Menjelajahi kekayaan alam dan budaya yang menjadi kebanggaan desa kami
      </p>
    </div>
    <div class="row g-4">
      <?php 
      if (empty($potensi_data)) {
          // Fallback ke data default
          $default_potensi = [
              ['icon' => 'fas fa-monument', 'nama' => 'Watu Pinawetengan', 'deskripsi' => 'Situs bersejarah megalitikum yang menjadi saksi bisu peradaban Minahasa...'],
              ['icon' => 'fas fa-utensils', 'nama' => 'Kuliner Tradisional', 'deskripsi' => 'Pangsit jagung dan beragam hidangan khas yang memadukan cita rasa autentik...'],
              ['icon' => 'fas fa-horse', 'nama' => 'Wisata Alam', 'deskripsi' => 'Bendang Stable dan panorama alam yang memukau...']
          ];
          
          foreach ($default_potensi as $item): ?>
          <div class="col-lg-4 col-md-6">
            <div class="potential-card scroll-reveal">
              <div class="potential-icon">
                <i class="<?= $item['icon'] ?>"></i>
              </div>
              <h3 class="potential-title"><?= $item['nama'] ?></h3>
              <p class="potential-description">
                <?= $item['deskripsi'] ?>
              </p>
            </div>
          </div>
          <?php endforeach;
      } else {
          foreach ($potensi_data as $item): ?>
          <div class="col-lg-4 col-md-6">
            <div class="potential-card scroll-reveal">
              <div class="potential-icon">
                <i class="<?= $item['icon'] ?>"></i>
              </div>
              <h3 class="potential-title"><?= htmlspecialchars($item['nama_potensi']) ?></h3>
              <p class="potential-description">
                <?= htmlspecialchars($item['deskripsi']) ?>
              </p>
            </div>
          </div>
          <?php endforeach;
      }
      ?>
    </div>
  </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="footer-section">
  <div class="container">
    <div class="footer-content text-center">
      <h3 class="footer-brand">Desa Pinabetengan Selatan</h3>
      <p class="footer-info">
        Jl. Desa Pinabetengan Selatan, Kecamatan Tompaso Baru<br>
        Kabupaten Minahasa Selatan, Sulawesi Utara 95362<br>
        <i class="fas fa-phone me-2"></i>(0431) 123-456 | 
        <i class="fas fa-envelope mx-2"></i>info@pinabetenganselatan.desa.id
      </p>
      <div class="social-links">
        <a href="#" class="social-link" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="social-link" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#" class="social-link" title="YouTube">
          <i class="fab fa-youtube"></i>
        </a>
        <a href="#" class="social-link" title="WhatsApp">
          <i class="fab fa-whatsapp"></i>
        </a>
      </div>
      <div class="footer-bottom">
        <p class="mb-0">&copy; 2025 Desa Pinabetengan Selatan. Semua hak dilindungi undang-undang.</p>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Scripts -->
<script>
// ========== THEME TOGGLE (DARK MODE) ==========
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle.querySelector('i');
const body = document.body;

// Function to apply theme
function applyTheme(theme) {
  if (theme === 'dark') {
    body.classList.add('dark-mode');
    themeIcon.classList.remove('fa-moon');
    themeIcon.classList.add('fa-sun');
  } else {
    body.classList.remove('dark-mode');
    themeIcon.classList.remove('fa-sun');
    themeIcon.classList.add('fa-moon');
  }
  localStorage.setItem('theme', theme);
}

// Check saved theme or system preference
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
  applyTheme(savedTheme);
} else if (systemPrefersDark) {
  applyTheme('dark');
} else {
  applyTheme('light');
}

// Toggle theme on button click
themeToggle.addEventListener('click', () => {
  const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  applyTheme(newTheme);
  
  // Add a little animation feedback
  themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
  setTimeout(() => {
    themeToggle.style.transform = '';
  }, 300);
});

// Listen to system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  if (!localStorage.getItem('theme')) {
    applyTheme(e.matches ? 'dark' : 'light');
  }
});

// ========== NAVBAR SCROLL EFFECT ==========
const navbar = document.querySelector('.navbar-custom');

window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// ========== SCROLL REVEAL ANIMATION ==========
const observerOptions = {
  threshold: 0.15,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry, index) => {
    if (entry.isIntersecting) {
      setTimeout(() => {
        entry.target.classList.add('revealed');
      }, index * 100);
    }
  });
}, observerOptions);

document.querySelectorAll('.scroll-reveal').forEach(el => {
  observer.observe(el);
});

// ========== COUNTER ANIMATION FOR STATS ==========
const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const numberElement = entry.target.querySelector('.stat-number');
      const target = parseInt(numberElement.textContent.replace(/\D/g, ''));
      let current = 0;
      const increment = target / 60;
      const duration = 2000; // 2 seconds
      const stepTime = duration / 60;
      
      const updateCounter = () => {
        if (current < target) {
          current += increment;
          numberElement.textContent = Math.ceil(current).toLocaleString('id-ID');
          setTimeout(updateCounter, stepTime);
        } else {
          numberElement.textContent = target.toLocaleString('id-ID');
        }
      };
      
      updateCounter();
      counterObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-card').forEach(card => {
  counterObserver.observe(card);
});

// ========== SMOOTH SCROLL FOR ANCHOR LINKS ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const offsetTop = target.offsetTop - 80;
      window.scrollTo({
        top: offsetTop,
        behavior: 'smooth'
      });
    }
  });
});

// ========== HERO BUTTON SCROLL ==========
const heroPrimaryBtn = document.querySelector('.btn-hero-primary');
if (heroPrimaryBtn) {
  heroPrimaryBtn.addEventListener('click', () => {
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
      const offsetTop = statsSection.offsetTop - 80;
      window.scrollTo({
        top: offsetTop,
        behavior: 'smooth'
      });
    }
  });
}

// ========== MOBILE NAVBAR ENHANCEMENTS ==========
function enhanceMobileNavbar() {
  const navbarToggler = document.querySelector('.navbar-toggler');
  const navbarCollapse = document.querySelector('.navbar-collapse');
  
  if (navbarToggler && navbarCollapse) {
    // Close navbar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 991) {
        const isClickInsideNavbar = e.target.closest('.navbar');
        const isNavbarOpen = navbarCollapse.classList.contains('show');
        
        if (!isClickInsideNavbar && isNavbarOpen) {
          const bsCollapse = new bootstrap.Collapse(navbarCollapse);
          bsCollapse.hide();
        }
      }
    });
    
    // Smooth close animation for mobile
    navbarCollapse.addEventListener('show.bs.collapse', () => {
      document.body.style.overflow = 'hidden';
    });
    
    navbarCollapse.addEventListener('hidden.bs.collapse', () => {
      document.body.style.overflow = '';
    });
  }
}

// Initialize mobile enhancements
enhanceMobileNavbar();

// Re-initialize on window resize
window.addEventListener('resize', enhanceMobileNavbar);

// ========== MOBILE MENU CLOSE ON LINK CLICK ==========
const navLinks = document.querySelectorAll('.nav-link-custom');
const navbarCollapse = document.querySelector('.navbar-collapse');

navLinks.forEach(link => {
  link.addEventListener('click', () => {
    if (navbarCollapse.classList.contains('show')) {
      const bsCollapse = new bootstrap.Collapse(navbarCollapse);
      bsCollapse.hide();
    }
  });
});

// ========== ACTIVE NAV LINK ON SCROLL ==========
const sections = document.querySelectorAll('section[id]');

function highlightNavOnScroll() {
  const scrollY = window.pageYOffset;

  sections.forEach(current => {
    const sectionHeight = current.offsetHeight;
    const sectionTop = current.offsetTop - 100;
    const sectionId = current.getAttribute('id');
    
    if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
      document.querySelectorAll('.nav-link-custom').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${sectionId}`) {
          link.classList.add('active');
        }
      });
    }
  });
}

window.addEventListener('scroll', highlightNavOnScroll);

// ========== DROPDOWN SMOOTH ANIMATIONS ==========
document.addEventListener('DOMContentLoaded', function() {
  // Add smooth animation to dropdown items
  const dropdownItems = document.querySelectorAll('.dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
      this.style.transform = 'translateX(8px)';
    });
    
    item.addEventListener('mouseleave', function() {
      this.style.transform = 'translateX(0)';
    });
  });
  
  // Enhanced dropdown toggle animation
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const dropdownMenu = this.nextElementSibling;
      if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
        if (dropdownMenu.style.display === 'block') {
          dropdownMenu.style.opacity = '0';
          dropdownMenu.style.transform = 'translateY(-10px)';
          setTimeout(() => {
            dropdownMenu.style.display = 'none';
          }, 300);
        }
      }
    });
  });
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
      openDropdowns.forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    }
  });
});

// ========== PRELOAD IMAGES FOR SMOOTH TRANSITIONS ==========
window.addEventListener('load', () => {
  document.body.style.visibility = 'visible';
  document.body.style.opacity = '1';
});

// ========== CONSOLE WELCOME MESSAGE ==========
console.log('%c🌿 Desa Pinabetengan Selatan 🌿', 'color: #7CB342; font-size: 24px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);');
console.log('%c✨ Website dengan Dark Mode berhasil dimuat!', 'color: #C62828; font-size: 14px; font-weight: 600;');
console.log('%c💡 Tip: Tekan tombol bulan/matahari untuk toggle dark mode', 'color: #FFD54F; font-size: 12px;');
</script>

</body>
</html>