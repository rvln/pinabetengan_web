<?php
require_once 'includes/header.php';
?>

<!-- ========== MAIN CONTENT ========== -->
<main class="dashboard-main">
    <div class="container-fluid">
        <!-- Data Penduduk Section -->
        <div class="card">
            <h4 class="card-title">
                <i class="fas fa-users"></i>
                Kelola Data Penduduk
            </h4>

            <?php if (isset($success_penduduk)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $success_penduduk ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_penduduk)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= $error_penduduk ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Total Penduduk</label>
                            <input type="number" name="total_penduduk" class="form-control"
                                   value="<?= $data_penduduk['total_penduduk'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Tahun Data</label>
                            <input type="number" name="tahun" class="form-control"
                                   value="<?= $data_penduduk['tahun'] ?>" min="2000" max="2030" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Penduduk Laki-laki</label>
                            <input type="number" name="laki_laki" class="form-control"
                                   value="<?= $data_penduduk['laki_laki'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Penduduk Perempuan</label>
                            <input type="number" name="perempuan" class="form-control"
                                   value="<?= $data_penduduk['perempuan'] ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kepala Keluarga</label>
                            <input type="number" name="kepala_keluarga" class="form-control"
                                   value="<?= $data_penduduk['kepala_keluarga'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kepadatan Penduduk (jiwa/km²)</label>
                            <input type="number" name="kepadatan_penduduk" class="form-control"
                                   value="<?= $data_penduduk['kepadatan_penduduk'] ?>" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="update_penduduk" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Update Data Penduduk
                </button>
            </form>

            <!-- Preview Data Penduduk -->
            <div class="preview-section">
                <h5 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview di Halaman Publik
                </h5>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-number"><?= number_format($data_penduduk['total_penduduk']) ?></div>
                            <div class="stat-label">Total Penduduk</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="stat-number"><?= number_format($data_penduduk['kepala_keluarga']) ?></div>
                            <div class="stat-label">Kepala Keluarga</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-male"></i>
                            </div>
                            <div class="stat-number"><?= number_format($data_penduduk['laki_laki']) ?></div>
                            <div class="stat-label">Laki-laki</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-female"></i>
                            </div>
                            <div class="stat-number"><?= number_format($data_penduduk['perempuan']) ?></div>
                            <div class="stat-label">Perempuan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pendidikan Section -->
        <div class="card">
            <h4 class="card-title">
                <i class="fas fa-graduation-cap"></i>
                Kelola Data Pendidikan
            </h4>

            <?php if (isset($success_pendidikan)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $success_pendidikan ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="pendidikanForm">
                <div class="form-group">
                    <label class="form-label">Data Tingkat Pendidikan</label>
                    <div id="pendidikanFields">
                        <?php foreach($pendidikan as $index => $edu): ?>
                        <div class="row mb-2 pendidikan-row">
                            <div class="col-md-6">
                                <input type="text" name="tingkat[]" class="form-control"
                                       value="<?= htmlspecialchars($edu['tingkat']) ?>"
                                       placeholder="Tingkat Pendidikan" required>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="jumlah[]" class="form-control"
                                       value="<?= $edu['jumlah'] ?>"
                                       placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn-secondary remove-row" style="width: 100%;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="addPendidikan" class="btn-secondary">
                        <i class="fas fa-plus"></i>
                        Tambah Baris
                    </button>
                    <button type="submit" name="update_pendidikan" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Update Data Pendidikan
                    </button>
                </div>
            </form>

            <!-- Preview Data Pendidikan -->
            <div class="preview-section">
                <h5 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview di Halaman Publik
                </h5>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tingkat Pendidikan</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_pendidikan = array_sum(array_column($pendidikan, 'jumlah'));
                        foreach($pendidikan as $edu):
                            $persentase = $total_pendidikan > 0 ? ($edu['jumlah'] / $total_pendidikan) * 100 : 0;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($edu['tingkat']) ?></td>
                            <td><?= number_format($edu['jumlah']) ?></td>
                            <td><?= number_format($persentase, 1) ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Data Pekerjaan Section -->
        <div class="card">
            <h4 class="card-title">
                <i class="fas fa-briefcase"></i>
                Kelola Data Pekerjaan
            </h4>

            <?php if (isset($success_pekerjaan)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $success_pekerjaan ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="pekerjaanForm">
                <div class="form-group">
                    <label class="form-label">Data Jenis Pekerjaan</label>
                    <div id="pekerjaanFields">
                        <?php foreach($pekerjaan as $index => $job): ?>
                        <div class="row mb-2 pekerjaan-row">
                            <div class="col-md-6">
                                <input type="text" name="jenis[]" class="form-control"
                                       value="<?= htmlspecialchars($job['jenis']) ?>"
                                       placeholder="Jenis Pekerjaan" required>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="jumlah[]" class="form-control"
                                       value="<?= $job['jumlah'] ?>"
                                       placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn-secondary remove-row" style="width: 100%;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="addPekerjaan" class="btn-secondary">
                        <i class="fas fa-plus"></i>
                        Tambah Baris
                    </button>
                    <button type="submit" name="update_pekerjaan" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Update Data Pekerjaan
                    </button>
                </div>
            </form>

            <!-- Preview Data Pekerjaan -->
            <div class="preview-section">
                <h5 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview di Halaman Publik
                </h5>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Jenis Pekerjaan</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_pekerjaan = array_sum(array_column($pekerjaan, 'jumlah'));
                        foreach($pekerjaan as $job):
                            $persentase = $total_pekerjaan > 0 ? ($job['jumlah'] / $total_pekerjaan) * 100 : 0;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($job['jenis']) ?></td>
                            <td><?= number_format($job['jumlah']) ?></td>
                            <td><?= number_format($persentase, 1) ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
// Dynamic form fields for pendidikan
document.getElementById('addPendidikan').addEventListener('click', function() {
    const fieldsContainer = document.getElementById('pendidikanFields');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 pendidikan-row';
    newRow.innerHTML = `
        <div class="col-md-6">
            <input type="text" name="tingkat[]" class="form-control" placeholder="Tingkat Pendidikan" required>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn-secondary remove-row" style="width: 100%;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    fieldsContainer.appendChild(newRow);
});

// Dynamic form fields for pekerjaan
document.getElementById('addPekerjaan').addEventListener('click', function() {
    const fieldsContainer = document.getElementById('pekerjaanFields');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 pekerjaan-row';
    newRow.innerHTML = `
        <div class="col-md-6">
            <input type="text" name="jenis[]" class="form-control" placeholder="Jenis Pekerjaan" required>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn-secondary remove-row" style="width: 100%;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    fieldsContainer.appendChild(newRow);
});

// Remove row functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
        const button = e.target.classList.contains('remove-row') ? e.target : e.target.closest('.remove-row');
        const row = button.closest('.pendidikan-row, .pekerjaan-row');
        if (row) {
            row.remove();
        }
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
