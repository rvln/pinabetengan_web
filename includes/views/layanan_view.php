<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

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

<?php require_once 'includes/footer.php'; ?>
