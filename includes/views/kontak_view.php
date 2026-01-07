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
          <li class="breadcrumb-item active">Kontak</li>
        </ol>
      </nav>
      <!-- END BREADCRUMB -->

      <h1 class="page-title">Hubungi Kami</h1>
      <p class="page-subtitle">
        Silakan menghubungi kami untuk informasi lebih lanjut tentang Desa Pinabetengan Selatan.
      </p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Informasi Kontak</h2>
      <p class="section-subtitle">Berbagai cara untuk menghubungi pemerintah desa</p>
    </div>

    <div class="row g-4 mb-5">
      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div class="contact-info">
            <h4>Alamat Kantor</h4>
            <p>Jl. Desa Pinabetengan Selatan</p>
            <p>Kec. Tompaso Baru</p>
            <p>Kab. Minahasa Selatan</p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-phone"></i>
          </div>
          <div class="contact-info">
            <h4>Telepon</h4>
            <p>(0431) 123-456</p>
            <p>+62 812-3456-7890</p>
            <p>Senin - Jumat</p>
            <p>08:00 - 16:00 WITA</p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="contact-info">
            <h4>Email</h4>
            <p>info@pinabetenganselatan.desa.id</p>
            <p>admin@pinabetenganselatan.desa.id</p>
            <p>umkm@pinabetenganselatan.desa.id</p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="contact-card fade-in">
          <div class="contact-icon">
            <i class="fas fa-share-alt"></i>
          </div>
          <div class="contact-info">
            <h4>Sosial Media</h4>
            <p>Facebook: Desa Pinabetengan</p>
            <p>Instagram: @pinabetenganselatan</p>
            <p>YouTube: Desa Pinabetengan</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg-6">
        <div class="form-card fade-in">
          <h3 class="mb-4">Kirim Pesan</h3>

          <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label">Nomor Telepon</label>
                  <input type="tel" name="telepon" class="form-control">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label">Subjek</label>
                  <select name="subjek" class="form-control" required>
                    <option value="">Pilih Subjek</option>
                    <option value="Informasi Umum">Informasi Umum</option>
                    <option value="Layanan Desa">Layanan Desa</option>
                    <option value="Pengaduan">Pengaduan</option>
                    <option value="Kerjasama">Kerjasama</option>
                    <option value="Wisata">Wisata</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label">Pesan</label>
                  <textarea name="pesan" class="form-control" rows="5" required></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" name="submit" value="1" class="btn btn-primary-custom w-100">
                  <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="fade-in">
          <h3 class="mb-4">Lokasi Kantor Desa</h3>
          <div class="map-container">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2304.3418622573286!2d124.78680526271864!3d1.1713542433821522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32874751892c0ec1%3A0x3449481bf0a5c57!2sDesa%20Pinabetengan%20Selatan!5e0!3m2!1sid!2sid!4v1761929361291!5m2!1sid!2sid"
              class="map-iframe"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Lokasi Kantor Desa Pinabetengan Selatan">
            </iframe>
          </div>

          <div class="mt-4">
            <h5>Jam Operasional Kantor:</h5>
            <p class="mb-1"><strong>Senin - Kamis:</strong> 08:00 - 16:00 WITA</p>
            <p class="mb-1"><strong>Jumat:</strong> 08:00 - 11:00 WITA</p>
            <p class="mb-1"><strong>Sabtu - Minggu:</strong> Libur</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
