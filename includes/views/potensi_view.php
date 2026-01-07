<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

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

<?php require_once 'includes/footer.php'; ?>
