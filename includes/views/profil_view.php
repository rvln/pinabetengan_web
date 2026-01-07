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
          <li class="breadcrumb-item active">Profil Desa</li>
        </ol>
      </nav>
      <h1 class="page-title">Profil Desa</h1>
      <p class="page-subtitle">
        Mengenal lebih dekat tentang <?= htmlspecialchars($profil_data['nama_desa']) ?>, sejarah, visi misi, dan pemerintahan desa.
      </p>
    </div>
  </div>
</section>

<!-- TENTANG DESA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Tentang Kami</h2>
      <p class="section-subtitle">Kantor <?= htmlspecialchars($profil_data['nama_desa']) ?></p>
    </div>

    <div class="row g-4 align-items-center mb-5">
      <div class="col-lg-6">
        <div class="profile-card fade-in">
          <div class="profile-image">
            <?php if(!empty($profil_data['gambar_desa'])): ?>
              <img src="<?= htmlspecialchars($profil_data['gambar_desa']) ?>" alt="Gambar <?= htmlspecialchars($profil_data['nama_desa']) ?>">
            <?php else: ?>
              <i class="fas fa-building"></i>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="profile-content fade-in">
          <h3>Selamat Datang di <?= htmlspecialchars($profil_data['nama_desa']) ?></h3>
          <p><?= nl2br(htmlspecialchars($profil_data['tentang'])) ?></p>
          <p>Nama Pinabetengan berasal dari bahasa Minahasa yang memiliki makna historis dan budaya yang mendalam bagi masyarakat Minahasa. Desa ini dikenal dengan keramahan penduduknya dan kekayaan alamnya yang masih terjaga dengan baik.</p>
          <p>Dengan jumlah penduduk sekitar <?= number_format($profil_data['jumlah_penduduk']) ?> jiwa, masyarakat <?= htmlspecialchars($profil_data['nama_desa']) ?> sebagian besar bermata pencaharian sebagai petani, pedagang, dan pegawai.</p>

          <a href="detail_sejarah.php" class="btn-read-more">
            <i class="fas fa-book me-1"></i>
            Baca Selengkapnya
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VISI MISI & SEJARAH -->
<section class="section section-alt">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="info-card fade-in">
          <div class="info-card-icon">
            <i class="fas fa-eye"></i>
          </div>
          <h4>Visi Desa</h4>
          <p><?= nl2br(htmlspecialchars($profil_data['visi'])) ?></p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="info-card fade-in">
          <div class="info-card-icon">
            <i class="fas fa-bullseye"></i>
          </div>
          <h4>Misi Desa</h4>
          <p><?= nl2br(htmlspecialchars($profil_data['misi'])) ?></p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="info-card fade-in">
          <div class="info-card-icon">
            <i class="fas fa-history"></i>
          </div>
          <h4>Sejarah Singkat</h4>
          <?php if (!empty($profil_data['sejarah'])): ?>
            <p><?= substr(nl2br(htmlspecialchars($profil_data['sejarah'])), 0, 150) ?>...</p>
          <?php else: ?>
            <p>Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan". Desa ini telah melalui berbagai fase perkembangan dari masa ke masa.</p>
          <?php endif; ?>

          <a href="detail_sejarah.php" class="btn-read-more">
            <i class="fas fa-book me-1"></i>
            Baca Selengkapnya
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- KEPALA DESA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Kepala Desa</h2>
      <p class="section-subtitle">Masa periode jabatan kepala pemerintahan desa</p>
    </div>

    <div class="row g-4">
      <?php foreach($pejabat as $p): ?>
        <div class="col-lg-4 col-md-6">
          <div class="official-card fade-in">
            <div class="official-photo">
              <?php if(!empty($p['foto'])): ?>
                <img src="<?= htmlspecialchars($p['foto']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
              <?php else: ?>
                <i class="fas fa-user-tie"></i>
              <?php endif; ?>
            </div>
            <div class="official-info">
              <h3 class="official-name"><?= htmlspecialchars($p['nama']) ?></h3>
              <p class="official-position"><?= htmlspecialchars($p['jabatan']) ?></p>
              <div class="official-period">
                <i class="fas fa-calendar"></i>
                <span><?= date('Y', strtotime($p['periode_mulai'])) ?> - <?= $p['periode_selesai'] ? date('Y', strtotime($p['periode_selesai'])) : 'Sekarang' ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PETA GOOGLE MAPS -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Peta Lokasi</h2>
      <p class="section-subtitle">Lokasi <?= htmlspecialchars($profil_data['nama_desa']) ?> di Kecamatan Tompaso Baru</p>
    </div>

    <div class="map-container fade-in">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2304.3418622573286!2d124.78680526271864!3d1.1713542433821522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32874751892c0ec1%3A0x3449481bf0a5c57!2sDesa%20Pinabetengan%20Selatan!5e0!3m2!1sid!2sid!4v1761929361291!5m2!1sid!2sid"
        class="map-iframe"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Lokasi <?= htmlspecialchars($profil_data['nama_desa']) ?>">
      </iframe>
    </div>

    <div class="row mt-4">
      <div class="col-12 text-center">
        <div class="info-card" style="max-width: 500px; margin: 0 auto;">
          <h4><i class="fas fa-info-circle me-2"></i>Informasi Lokasi</h4>
          <p class="mb-2"><strong>Alamat:</strong> <?= htmlspecialchars($profil_data['nama_desa']) ?>, Kec. Tompaso Baru</p>
          <p class="mb-2"><strong>Kabupaten:</strong> Minahasa Selatan</p>
          <p class="mb-0"><strong>Provinsi:</strong> Sulawesi Utara</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
