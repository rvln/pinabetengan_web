<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

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
              <?= isset($berita['kategori']) && $berita['kategori'] === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?>
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

<?php require_once 'includes/footer.php'; ?>
