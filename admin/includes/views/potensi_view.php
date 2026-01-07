<?php
require_once 'includes/header.php';
?>

<!-- ========== MAIN CONTENT ========== -->
<main class="dashboard-main">
    <div class="container-fluid">
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <!-- Form Tambah/Edit Potensi -->
                <div class="card">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-<?= $potensi_edit ? 'edit' : 'plus-circle' ?> me-2"></i>
                        <?= $potensi_edit ? 'Edit Potensi' : 'Tambah Potensi Baru' ?>
                    </h4>
                    <form method="POST">
                        <?php if ($potensi_edit): ?>
                            <input type="hidden" name="id" value="<?= $potensi_edit['id'] ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Potensi</label>
                                    <input type="text" name="nama_potensi" class="form-control"
                                           value="<?= $potensi_edit ? htmlspecialchars($potensi_edit['nama_potensi']) : '' ?>"
                                           placeholder="Masukkan nama potensi..." required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Icon Font Awesome</label>
                                    <input type="text" name="icon" class="form-control"
                                           value="<?= $potensi_edit ? $potensi_edit['icon'] : 'fas fa-gem' ?>"
                                           placeholder="fas fa-icon" required>
                                    <small class="text-muted">Contoh: fas fa-monument, fas fa-utensils</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Urutan Tampil</label>
                                    <input type="number" name="urutan" class="form-control"
                                           value="<?= $potensi_edit ? $potensi_edit['urutan'] : '0' ?>" required>
                                </div>
                            </div>
                        </div>

                        <?php if ($potensi_edit): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active" <?= $potensi_edit['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="inactive" <?= $potensi_edit['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="form-label">Deskripsi Potensi</label>
                            <textarea name="deskripsi" class="form-control form-textarea" rows="4"
                                      placeholder="Tulis deskripsi potensi di sini..." required><?= $potensi_edit ? htmlspecialchars($potensi_edit['deskripsi']) : '' ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="<?= $potensi_edit ? 'edit_potensi' : 'tambah_potensi' ?>" class="btn-primary">
                                <i class="fas fa-save me-2"></i>
                                <?= $potensi_edit ? 'Update Potensi' : 'Simpan Potensi' ?>
                            </button>

                            <?php if ($potensi_edit): ?>
                                <a href="potensi.php" class="btn-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Batal
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Daftar Potensi -->
                <div class="card">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-list me-2"></i>
                        Daftar Potensi Desa
                    </h4>

                    <?php if (empty($potensi)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-gem fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada potensi tersedia</h5>
                            <p class="text-muted">Mulai tambahkan potensi pertama Anda</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Icon</th>
                                        <th width="20%">Nama Potensi</th>
                                        <th width="30%">Deskripsi</th>
                                        <th width="10%">Urutan</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($potensi as $index => $item): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <i class="<?= $item['icon'] ?> fa-2x text-warning"></i>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($item['nama_potensi']) ?></strong>
                                        </td>
                                        <td>
                                            <?= substr(htmlspecialchars($item['deskripsi']), 0, 80) ?>...
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= $item['urutan'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $item['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                                <?= $item['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="potensi.php?edit=<?= $item['id'] ?>" class="btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                    <button type="submit" name="hapus_potensi"
                                                            class="btn-danger"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus potensi ini?')">
                                                        <i class="fas fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
