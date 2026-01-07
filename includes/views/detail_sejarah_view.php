<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

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

                        <div class="text-center mt-4">
                            <a href="profil.php" class="btn-read-more">
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

<?php require_once 'includes/footer.php'; ?>
