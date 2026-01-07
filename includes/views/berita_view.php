<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<!-- HEADER BERITA YANG MENARIK -->
<header class="berita-header">
  <div class="container">
    <div class="berita-header-content fade-in">
      <h1 class="berita-title"><?= $page_title ?></h1>
      <p class="berita-subtitle">
        <?= $kategori === 'pengumuman'
            ? 'Informasi resmi dan pengumuman penting dari Pemerintah Desa Pinabetengan Selatan'
            : 'Update terbaru seputar kegiatan, program, dan perkembangan Desa Pinabetengan Selatan' ?>
      </p>

      <div class="berita-stats">
        <div class="stat-item">
          <div class="stat-number"><?= count($berita) ?></div>
          <div class="stat-label">Total <?= $page_title ?></div>
        </div>
        <div class="stat-item">
          <div class="stat-number">
            <?= $kategori === 'pengumuman' ? '2.1K' : '4.3K' ?>
          </div>
          <div class="stat-label">Pembaca Bulanan</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">
            <?= $kategori === 'pengumuman' ? date('M Y') : date('Y') ?>
          </div>
          <div class="stat-label">
            <?= $kategori === 'pengumuman' ? 'Update Terbaru' : 'Tahun Aktif' ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- FILTER KATEGORI -->
<section class="kategori-filter">
  <div class="container">
    <div class="filter-container">
      <a href="berita.php?kategori=kegiatan" class="filter-btn <?= $kategori === 'kegiatan' ? 'active' : '' ?>">
        <i class="fas fa-calendar-alt me-2"></i>Kegiatan & Program
      </a>
      <a href="berita.php?kategori=pengumuman" class="filter-btn <?= $kategori === 'pengumuman' ? 'active' : '' ?>">
        <i class="fas fa-bullhorn me-2"></i>Pengumuman Resmi
      </a>
    </div>
  </div>
</section>

<!-- BERITA UTAMA -->
<?php if (!empty($berita)): ?>
<section class="berita-utama">
  <div class="container">
    <div class="utama-card fade-in">
      <div class="utama-image">
        <i class="<?= $kategori === 'pengumuman' ? 'fas fa-bullhorn' : 'fas fa-calendar-check' ?>"></i>
      </div>
      <div class="utama-content">
        <span class="utama-badge"><?= $kategori === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?></span>
        <h2 class="utama-title"><?= htmlspecialchars($berita[0]['judul']) ?></h2>
        <p class="utama-excerpt"><?= substr(strip_tags($berita[0]['isi']), 0, 200) ?>...</p>
        <div class="utama-meta">
          <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($berita[0]['tanggal'])) ?></span>
          <?php if (isset($berita[0]['penulis'])): ?>
          <span><i class="fas fa-user"></i> <?= $berita[0]['penulis'] ?></span>
          <?php endif; ?>
          <?php if (isset($berita[0]['dibaca'])): ?>
          <span><i class="fas fa-eye"></i> <?= $berita[0]['dibaca'] ?> dibaca</span>
          <?php endif; ?>
        </div>
        <a href="berita-detail.php?id=<?= $berita[0]['id'] ?>&kategori=<?= $kategori ?>" class="btn-read-more align-self-start">
          Baca Selengkapnya <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- DAFTAR BERITA -->
<section class="section">
  <div class="container">
    <?php if (empty($berita)): ?>
      <div class="empty-state fade-in">
        <i class="<?= $kategori === 'pengumuman' ? 'fas fa-bullhorn' : 'fas fa-calendar-alt' ?>"></i>
        <h4>Belum Ada <?= $page_title ?></h4>
        <p>
          <?= $kategori === 'pengumuman'
              ? 'Saat ini belum ada pengumuman resmi yang diterbitkan. Silakan kembali lagi nanti.'
              : 'Belum ada kegiatan atau program yang dijadwalkan saat ini. Pantau terus untuk update terbaru.' ?>
        </p>
      </div>
    <?php else: ?>
      <div class="berita-grid">
        <?php foreach(array_slice($berita, 1) as $b): ?>
          <article class="berita-card fade-in">
            <div class="berita-image">
              <span class="berita-badge"><?= $kategori === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?></span>
              <i class="<?= $kategori === 'pengumuman' ? 'fas fa-bullhorn' : 'fas fa-newspaper' ?>"></i>
            </div>
            <div class="berita-content">
              <div class="berita-meta">
                <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?></span>
                <?php if (isset($b['penulis'])): ?>
                <span><i class="fas fa-user"></i> <?= $b['penulis'] ?></span>
                <?php endif; ?>
              </div>
              <h3 class="berita-title"><?= htmlspecialchars($b['judul']) ?></h3>
              <p class="berita-excerpt"><?= substr(strip_tags($b['isi']), 0, 120) ?>...</p>
              <div class="berita-footer">
                <a href="berita-detail.php?id=<?= $b['id'] ?>&kategori=<?= $kategori ?>" class="btn-read-more">
                  Baca <i class="fas fa-arrow-right"></i>
                </a>
                <?php if (isset($b['dibaca'])): ?>
                <span class="berita-views">
                  <i class="fas fa-eye"></i> <?= $b['dibaca'] ?>
                </span>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- PAGINATION -->
      <nav>
        <ul class="pagination">
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
