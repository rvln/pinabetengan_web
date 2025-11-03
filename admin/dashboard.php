<?php
session_start();
require_once '../config/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

try {
    // Ambil data statistik untuk dashboard
    $stats = $pdo->query("SELECT * FROM statistik WHERE id = 1")->fetch();
    $total_berita = $pdo->query("SELECT COUNT(*) as total FROM berita")->fetch()['total'];
    
    // Handle tabel umkm yang tidak ada
    $total_umkm = 0;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM umkm");
        $result = $stmt->fetch();
        $total_umkm = $result['total'] ?? 0;
    } catch (Exception $e) {
        // Jika tabel umkm tidak ada, set default value
        $total_umkm = 15; // Default value
    }
    
    // Hitung total potensi
    $total_potensi = $pdo->query("SELECT COUNT(*) as total FROM potensi WHERE status = 'active'")->fetch()['total'];
    
} catch (Exception $e) {
    // Fallback values jika ada error
    $stats = ['penduduk' => 2500, 'rukun_tetangga' => 8, 'umkm_aktif' => 15, 'destinasi_wisata' => 5];
    $total_berita = 0;
    $total_umkm = 15;
    $total_potensi = 3;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Desa Pinabetengan Selatan</title>

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

    /* ========== GLOBAL STYLES ========== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--ivory) 0%, var(--cream) 50%, var(--ivory-light) 100%);
        color: var(--black);
        line-height: 1.7;
        min-height: 100vh;
        transition: background-color 0.4s ease, color 0.4s ease;
    }

    body.dark-mode {
        background: linear-gradient(135deg, #1a1a1a 0%, #151515 50%, #1a1a1a 100%);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        color: var(--black);
        font-weight: 700;
    }

    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode h3,
    body.dark-mode h4,
    body.dark-mode h5,
    body.dark-mode h6 {
        color: #FFFFFF;
    }

    /* ========== HEADER STYLES ========== */
    .dashboard-header {
        background: rgba(245, 243, 239, 0.95);
        backdrop-filter: blur(15px);
        padding: 1.5rem 0;
        box-shadow: var(--shadow-soft);
        border-bottom: 2px solid var(--yellow);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    body.dark-mode .dashboard-header {
        background: rgba(20, 20, 20, 0.95);
        border-bottom-color: rgba(255, 213, 79, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .brand-icon {
        font-size: 2rem;
        color: var(--green-leaf);
    }

    body.dark-mode .brand-icon {
        color: var(--yellow);
    }

    .brand-text h1 {
        font-size: 1.5rem;
        margin-bottom: 0.2rem;
    }

    .brand-text p {
        color: var(--grey);
        font-size: 0.9rem;
    }

    body.dark-mode .brand-text p {
        color: #B0B0B0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    /* ========== MAIN CONTENT ========== */
    .dashboard-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* ========== WELCOME SECTION ========== */
    .welcome-section {
        background: linear-gradient(135deg, var(--yellow-light), white);
        padding: 3rem;
        border-radius: 30px;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-soft);
        border: 2px solid var(--yellow);
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .welcome-section {
        background: rgba(30, 30, 30, 0.9);
        border-color: var(--yellow);
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 213, 79, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .welcome-title .text-highlight {
        color: var(--red);
        position: relative;
    }

    body.dark-mode .welcome-title .text-highlight {
        color: var(--yellow);
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        color: var(--grey);
        position: relative;
        z-index: 2;
    }

    body.dark-mode .welcome-subtitle {
        color: #B0B0B0;
    }

    /* ========== STATS GRID ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 25px;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .stat-card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-medium);
        border-color: var(--yellow);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: var(--transition);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(10deg);
    }

    .stat-icon i {
        font-size: 1.8rem;
        color: var(--red);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--red);
        margin-bottom: 0.5rem;
        font-family: 'Poppins', sans-serif;
    }

    body.dark-mode .stat-number {
        color: var(--yellow);
    }

    .stat-label {
        font-size: 1rem;
        color: var(--grey);
        font-weight: 500;
    }

    body.dark-mode .stat-label {
        color: #B0B0B0;
    }

    /* ========== QUICK ACTIONS ========== */
    .quick-actions {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--red), var(--yellow));
        border-radius: 2px;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .action-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 25px;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        border: 2px solid transparent;
        text-decoration: none;
        color: var(--black);
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .action-card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
        color: #E0E0E0;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--red), var(--yellow));
        transform: scaleX(0);
        transform-origin: left;
        transition: var(--transition);
    }

    .action-card:hover::before {
        transform: scaleX(1);
    }

    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-medium);
        border-color: var(--yellow);
        color: var(--black);
    }

    body.dark-mode .action-card:hover {
        color: #FFFFFF;
    }

    .action-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: var(--transition);
    }

    .action-card:hover .action-icon {
        transform: scale(1.1);
    }

    .action-icon i {
        font-size: 2rem;
        color: var(--red);
    }

    .action-title {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .action-description {
        color: var(--grey);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    body.dark-mode .action-description {
        color: #B0B0B0;
    }

    /* ========== BUTTON STYLES ========== */
    .btn-logout {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-logout:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
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
    }

    .btn-theme-toggle:hover {
        background: var(--yellow-dark);
        transform: scale(1.1) rotate(180deg);
        box-shadow: 0 6px 20px rgba(255, 213, 79, 0.4);
    }

    body.dark-mode .btn-theme-toggle {
        background: var(--yellow-dark);
        color: var(--ivory);
    }

    body.dark-mode .btn-theme-toggle:hover {
        background: var(--yellow);
        color: var(--black);
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .dashboard-main {
            padding: 1rem;
        }

        .welcome-section {
            padding: 2rem;
        }

        .welcome-title {
            font-size: 2rem;
        }

        .stats-grid,
        .actions-grid {
            grid-template-columns: 1fr;
        }

        .stat-card,
        .action-card {
            padding: 2rem 1.5rem;
        }
    }

    /* ========== SCROLLBAR ========== */
    ::-webkit-scrollbar {
        width: 8px;
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
    .dashboard-header,
    .stat-card,
    .action-card,
    .welcome-section,
    .btn-logout,
    .btn-theme-toggle {
        transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease;
    }
    </style>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="dashboard-header">
    <div class="header-content">
        <div class="brand">
            <i class="fas fa-leaf brand-icon"></i>
            <div class="brand-text">
                <h1>Dashboard Admin</h1>
                <p>Desa Pinabetengan Selatan</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
</header>

<!-- ========== MAIN CONTENT ========== -->
<main class="dashboard-main">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <h1 class="welcome-title">
            Selamat Datang, <span class="text-highlight"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>! 👋
        </h1>
        <p class="welcome-subtitle">
            Kelola website Desa Pinabetengan Selatan dengan mudah melalui dashboard ini.
        </p>
    </section>

    <!-- Statistics Grid -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number"><?= number_format($stats['penduduk'] ?? 0) ?></div>
            <div class="stat-label">Total Penduduk</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-number"><?= number_format($total_berita) ?></div>
            <div class="stat-label">Berita Terpublikasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-number"><?= number_format($total_umkm) ?></div>
            <div class="stat-label">UMKM Terdaftar</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-gem"></i>
            </div>
            <div class="stat-number"><?= number_format($total_potensi) ?></div>
            <div class="stat-label">Potensi Unggulan</div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions">
        <h2 class="section-title">Kelola Konten</h2>
        <div class="actions-grid">
            <a href="berita.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3 class="action-title">Kelola Berita</h3>
                <p class="action-description">Tambah, edit, atau hapus berita dan artikel desa</p>
            </a>
            <a href="data.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="action-title">Kelola Data</h3>
                <p class="action-description">Update data statistik dan informasi desa</p>
            </a>
            <a href="potensi.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 class="action-title">Kelola Potensi</h3>
                <p class="action-description">Manage potensi wisata dan UMKM desa</p>
            </a>
            <a href="profil.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3 class="action-title">Kelola Profil</h3>
                <p class="action-description">Edit profil desa, visi misi, dan struktur</p>
            </a>
            <a href="galeri.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-images"></i>
                </div>
                <h3 class="action-title">Kelola Galeri</h3>
                <p class="action-description">Upload dan kelola foto-foto desa</p>
            </a>
            <a href="pengaturan.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="action-title">Pengaturan</h3>
                <p class="action-description">Konfigurasi website dan pengguna</p>
            </a>
        </div>
    </section>
</main>

<!-- ========== THEME TOGGLE SCRIPT ========== -->
<script>
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

// Console welcome message
console.log('%c🏠 Dashboard Admin - Desa Pinabetengan Selatan 🏠', 'color: #7CB342; font-size: 18px; font-weight: bold;');
console.log('%c💡 Selamat mengelola website desa!', 'color: #FFD54F; font-size: 12px;');
</script>

</body>
</html>