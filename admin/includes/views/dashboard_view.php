<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';
?>

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

<script>
// Console welcome message
console.log('%c🏠 Dashboard Admin - Desa Pinabetengan Selatan 🏠', 'color: #7CB342; font-size: 18px; font-weight: bold;');
console.log('%c💡 Selamat mengelola website desa!', 'color: #FFD54F; font-size: 12px;');
</script>

<?php require_once 'includes/footer.php'; ?>
