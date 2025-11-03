<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data profil desa
try {
    $profil = $pdo->query("SELECT * FROM profil WHERE id = 1")->fetch();
} catch (PDOException $e) {
    $profil = null;
}

// Data fallback
$profil_data = $profil ?: [
    'nama_desa' => 'Desa Pinabetengan Selatan',
    'sejarah' => 'Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan". Nama Pinabetengan berasal dari bahasa Minahasa kuno yang berarti "tempat bermusyawarah". 

Desa ini didirikan pada awal abad ke-19 oleh sekelompok masyarakat Minahasa yang mencari tempat tinggal baru. Mereka adalah keturunan dari para leluhur yang bermukim di sekitar situs Watu Pinawetengan, sebuah batu besar yang menjadi tempat musyawarah penting dalam sejarah Minahasa.

Pada tahun 1920-an, desa ini mulai berkembang pesat dengan dibukanya lahan pertanian baru dan didirikannya sekolah pertama. Masyarakat desa hidup dari bertani jagung, ubi, dan sayuran, serta beternak ayam dan babi.

Selama masa penjajahan Belanda dan Jepang, masyarakat Pinabetengan Selatan dikenal dengan semangat perlawanannya. Banyak pemuda desa yang bergabung dengan gerakan perlawanan untuk mempertahankan tanah air.

Setelah Indonesia merdeka, desa ini terus berkembang dengan pembangunan infrastruktur seperti jalan, jembatan, dan fasilitas umum. Pada tahun 1985, dibangunlah balai desa yang menjadi pusat kegiatan masyarakat.

Di era modern, Pinabetengan Selatan terus melestarikan budaya dan tradisi Minahasa sambil mengembangkan potensi ekonomi melalui pertanian organik dan pariwisata budaya. Situs Watu Pinawetengan yang terletak tidak jauh dari desa menjadi daya tarik wisata yang penting.

Hingga kini, Desa Pinabetengan Selatan tetap mempertahankan nilai-nilai kearifan lokal dan semangat gotong royong yang menjadi ciri khas masyarakat Minahasa.',
    'gambar_sejarah' => ''
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah Desa - Desa Pinabetengan Selatan</title>
    
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

    /* === IMPROVED NAVBAR - SAME AS LAYANAN.PHP === */
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

    /* === HISTORY CARD === */
    .history-card {
        background: var(--bg-secondary);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid transparent;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .history-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--accent-yellow);
    }

    .history-content { 
        padding: 3rem; 
    }

    .history-content h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: var(--text-primary);
        border-bottom: 3px solid var(--accent-red);
        padding-bottom: 1rem;
    }

    .history-text {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .history-text p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    /* Image Styles */
    .history-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 10px;
        margin: 2rem 0;
        box-shadow: var(--shadow);
        border: 3px solid var(--accent-yellow);
    }

    .image-caption {
        text-align: center;
        color: var(--text-muted);
        font-style: italic;
        margin-top: -1rem;
        margin-bottom: 2rem;
        font-size: 0.9rem;
    }

    /* Button Styles */
    .btn-back {
        background: var(--accent-red);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        margin-top: 2rem;
    }

    .btn-back:hover {
        background: var(--accent-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
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

    .text-justify {
        text-align: justify;
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .section { padding: 3rem 0; }
        .page-hero { padding: 90px 0 30px; min-height: 40vh; }
        .history-content { padding: 2rem; }
        .history-image { max-height: 300px; }
    }

    @media (max-width: 480px) {
        .history-content { padding: 1.5rem; }
        .history-image { max-height: 250px; }
    }
    </style>
</head>
<body data-theme="light">

<!-- NAVBAR -->
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
                        <li><a class="dropdown-item active" href="detail_sejarah.php">Sejarah Desa</a></li>
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

<!-- HERO SECTION -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-content fade-in">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="profil.php">Profil Desa</a></li>
                    <li class="breadcrumb-item active">Sejarah Desa</li>
                </ol>
            </nav>
            <h1 class="page-title">Sejarah Desa</h1>
            <p class="page-subtitle">
                Menelusuri jejak sejarah dan perjalanan panjang Desa Pinabetengan Selatan dari masa ke masa
            </p>
        </div>
    </div>
</section>

<!-- SEJARAH UTAMA -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="history-card fade-in">
                    <div class="history-content">
                        <h2>Sejarah Desa Pinabetengan Selatan</h2>
                        
                        <!-- Tampilkan gambar sejarah jika ada -->
                        <?php if (!empty($profil_data['gambar_sejarah'])): ?>
                            <div class="text-center">
                                <img src="<?= htmlspecialchars($profil_data['gambar_sejarah']) ?>" 
                                     alt="Gambar Sejarah Desa <?= htmlspecialchars($profil_data['nama_desa']) ?>" 
                                     class="history-image"
                                     onerror="this.style.display='none'">
                                <p class="image-caption">Dokumentasi Sejarah Desa <?= htmlspecialchars($profil_data['nama_desa']) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="history-text">
                            <?= nl2br(htmlspecialchars($profil_data['sejarah'])) ?>
                        </div>
                        
                        <div class="text-center">
                            <a href="profil.php" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali ke Profil Desa
                            </a>
                        </div>
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
// UNIFIED THEME MANAGEMENT
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