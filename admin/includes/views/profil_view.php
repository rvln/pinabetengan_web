<?php
require_once 'includes/header.php';
?>

<!-- ========== MAIN CONTENT ========== -->
<main class="dashboard-main">
    <div class="container-fluid">
        <!-- Profil Desa Section -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-edit"></i>
                Edit Profil Desa
            </h2>

            <?php if (!empty($success_profil)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success_profil) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_profil)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error_profil) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Desa</label>
                            <input type="text" name="nama_desa" class="form-control"
                                   value="<?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                   placeholder="Masukkan nama desa" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Jumlah Penduduk</label>
                            <input type="number" name="jumlah_penduduk" class="form-control"
                                   value="<?= htmlspecialchars($profil_data['jumlah_penduduk']) ?>"
                                   placeholder="Masukkan jumlah penduduk" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Gambar Desa</label>
                    <input type="file" name="gambar_desa" class="file-input"
                           accept=".jpg,.jpeg,.png,.gif,.webp">
                    <div class="file-info">
                        <small>Format: JPG, PNG, GIF, WebP (Max: 2MB) - Direkomendasikan: WebP untuk ukuran lebih kecil</small>
                    </div>

                    <?php if (!empty($profil_data['gambar_desa'])): ?>
                        <div class="current-image">
                            <p><strong>Gambar Saat Ini:</strong></p>
                            <img src="../<?= htmlspecialchars($profil_data['gambar_desa']) ?>"
                                 alt="Gambar Desa <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                 onerror="this.style.display='none'">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Tentang Desa</label>
                    <textarea name="tentang" class="form-control form-textarea"
                              placeholder="Tuliskan deskripsi tentang desa..."
                              rows="6" required><?= htmlspecialchars($profil_data['tentang']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Visi Desa</label>
                    <textarea name="visi" class="form-control form-textarea"
                              placeholder="Tuliskan visi desa..."
                              rows="4" required><?= htmlspecialchars($profil_data['visi']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Misi Desa</label>
                    <textarea name="misi" class="form-control form-textarea"
                              placeholder="Tuliskan misi desa..."
                              rows="4" required><?= htmlspecialchars($profil_data['misi']) ?></textarea>
                </div>

                <!-- Field Sejarah dengan Upload Foto -->
                <div class="form-group">
                    <label class="form-label">Sejarah Desa</label>

                    <!-- Input Upload Foto Sejarah -->
                    <div class="mb-3">
                        <label class="form-label">Foto Sejarah Desa</label>
                        <input type="file" name="gambar_sejarah" class="file-input"
                               accept=".jpg,.jpeg,.png,.gif,.webp">
                        <div class="file-info">
                            <small>Format: JPG, PNG, GIF, WebP (Max: 2MB) - Gambar yang mendukung konten sejarah</small>
                        </div>

                        <?php if (!empty($profil_data['gambar_sejarah'])): ?>
                            <div class="current-image mt-2">
                                <p><strong>Foto Sejarah Saat Ini:</strong></p>
                                <img src="../<?= htmlspecialchars($profil_data['gambar_sejarah']) ?>"
                                     alt="Foto Sejarah <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                     style="max-width: 300px; max-height: 200px; border-radius: 10px; border: 2px solid var(--yellow);"
                                     onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>
                    </div>

                    <textarea name="sejarah" class="form-control form-textarea"
                              placeholder="Tuliskan sejarah lengkap desa..."
                              rows="8"><?= htmlspecialchars($profil_data['sejarah']) ?></textarea>
                    <div class="file-info">
                        <small>Sejarah ini akan ditampilkan di halaman "Sejarah Desa" dan ringkasan di halaman profil utama.</small>
                    </div>
                </div>

                <button type="submit" name="update_profil_desa" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Update Profil Desa
                </button>
            </form>

            <!-- Preview Profil Desa -->
            <div class="preview-section">
                <h3 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview Profil Desa
                </h3>

                <div class="preview-content">
                    <?php if (!empty($profil_data['gambar_desa'])): ?>
                        <div class="image-preview">
                            <img src="../<?= htmlspecialchars($profil_data['gambar_desa']) ?>"
                                 alt="Gambar Desa <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                 onerror="this.style.display='none'">
                        </div>
                    <?php endif; ?>

                    <div class="preview-item">
                        <h4><i class="fas fa-building"></i> Tentang Desa</h4>
                        <p><?= nl2br(htmlspecialchars($profil_data['tentang'])) ?></p>
                    </div>

                    <div class="preview-item">
                        <h4><i class="fas fa-bullseye"></i> Visi Desa</h4>
                        <p><?= nl2br(htmlspecialchars($profil_data['visi'])) ?></p>
                    </div>

                    <div class="preview-item">
                        <h4><i class="fas fa-tasks"></i> Misi Desa</h4>
                        <p><?= nl2br(htmlspecialchars($profil_data['misi'])) ?></p>
                    </div>

                    <!-- Preview Sejarah dengan Foto -->
                    <div class="preview-item">
                        <h4><i class="fas fa-history"></i> Sejarah Desa</h4>

                        <?php if (!empty($profil_data['gambar_sejarah'])): ?>
                            <div class="image-preview mb-3">
                                <img src="../<?= htmlspecialchars($profil_data['gambar_sejarah']) ?>"
                                     alt="Foto Sejarah <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                     style="max-width: 100%; max-height: 300px; border-radius: 10px; border: 2px solid var(--yellow);"
                                     onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>

                        <p><?= nl2br(htmlspecialchars($profil_data['sejarah'])) ?></p>
                        <div class="file-info mt-2">
                            <small><i class="fas fa-info-circle"></i> Ini adalah preview ringkasan. Untuk melihat sejarah lengkap, buka halaman <strong>detail_sejarah.php</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelola Kepala Desa Section -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-user-tie"></i>
                Kelola Kepala Desa
            </h2>

            <?php if (!empty($success_kepala)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success_kepala) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_kepala)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error_kepala) ?>
                </div>
            <?php endif; ?>

            <!-- Form Tambah Kepala Desa -->
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control"
                                   value="Kepala Desa"
                                   placeholder="Masukkan jabatan" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Periode Mulai</label>
                            <input type="date" name="periode_mulai" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Periode Selesai</label>
                            <input type="date" name="periode_selesai" class="form-control"
                                   placeholder="Kosongkan jika masih menjabat">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="file-input"
                                   accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="file-info">
                                <small>Format: JPG, PNG, GIF, WebP (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" name="update_kepala_desa" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Kepala Desa
                </button>
            </form>

            <!-- Daftar Kepala Desa -->
            <h3 class="preview-title">
                <i class="fas fa-list"></i>
                Daftar Kepala Desa
            </h3>

            <?php if (!empty($pejabat)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pejabat as $p): ?>
                            <tr>
                                <td>
                                    <?php if(!empty($p['foto_thumb'])): ?>
                                        <img src="../<?= htmlspecialchars($p['foto_thumb']) ?>"
                                             alt="<?= htmlspecialchars($p['nama']) ?>"
                                             class="photo-thumbnail"
                                             onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; border-radius: 50%; background: #f2f2f2; display: flex; align-items: center; justify-content: center; color: #999;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td><?= htmlspecialchars($p['jabatan']) ?></td>
                                <td>
                                    <?= date('Y', strtotime($p['periode_mulai'])) ?> -
                                    <?= $p['periode_selesai'] ? date('Y', strtotime($p['periode_selesai'])) : 'Sekarang' ?>
                                </td>
                                <td>
                                    <span style="color: <?= $p['periode_selesai'] ? '#6c757d' : '#28a745' ?>; font-weight: 500;">
                                        <?= $p['periode_selesai'] ? 'Selesai' : 'Aktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" name="delete_kepala_desa" class="btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="preview-section text-center">
                    <i class="fas fa-user-tie" style="font-size: 3rem; color: var(--grey); margin-bottom: 1rem;"></i>
                    <p style="color: var(--grey);">Belum ada data kepala desa.</p>
                </div>
            <?php endif; ?>

            <!-- Preview Kepala Desa -->
            <div class="preview-section">
                <h3 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview di Halaman Publik
                </h3>

                <div class="row">
                    <?php foreach(array_slice($pejabat, 0, 4) as $p): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="official-card-preview">
                                <div class="official-photo-preview">
                                    <?php if(!empty($p['foto'])): ?>
                                        <img src="../<?= htmlspecialchars($p['foto']) ?>"
                                             alt="<?= htmlspecialchars($p['nama']) ?>"
                                             loading="lazy"
                                             onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <i class="fas fa-user-tie"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="official-info-preview">
                                    <h4 class="official-name-preview"><?= htmlspecialchars($p['nama']) ?></h4>
                                    <p class="official-position-preview"><?= htmlspecialchars($p['jabatan']) ?></p>
                                    <p class="official-period-preview">
                                        <i class="fas fa-calendar"></i>
                                        <?= date('Y', strtotime($p['periode_mulai'])) ?> - <?= $p['periode_selesai'] ? date('Y', strtotime($p['periode_selesai'])) : 'Sekarang' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Auto-fill current year for period end if empty
document.addEventListener('DOMContentLoaded', function() {
    const periodeMulaiInput = document.querySelector('input[name="periode_mulai"]');
    const periodeSelesaiInput = document.querySelector('input[name="periode_selesai"]');

    if (periodeMulaiInput) {
        periodeMulaiInput.addEventListener('change', function() {
            if (!periodeSelesaiInput.value) {
                const startYear = new Date(this.value).getFullYear();
                const endDate = new Date(startYear + 5, 11, 31); // 5 years later
                periodeSelesaiInput.value = endDate.toISOString().split('T')[0];
            }
        });
    }

    // File input preview setup
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(fileInput => {
        fileInput.addEventListener('change', function() {
            const fileInfo = this.nextElementSibling;
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                fileInfo.innerHTML = `<small>File: ${file.name} (${fileSize} MB)</small>`;

                // Preview image
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = 'Preview';
                img.onload = () => URL.revokeObjectURL(img.src);
                preview.appendChild(img);

                // Remove existing preview
                const existingPreview = this.parentNode.querySelector('.image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }

                this.parentNode.appendChild(preview);
            } else {
                fileInfo.innerHTML = '<small>Format: JPG, PNG, GIF, WebP (Max: 2MB)</small>';
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
