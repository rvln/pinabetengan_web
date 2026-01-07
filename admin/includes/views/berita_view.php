<?php
require_once 'includes/header.php';
?>

<main class="dashboard-main">
    <div class="container-fluid">
        <?php if (isset($success) && !empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i>
                <?= $success ?>
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        <?php endif; ?>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <?= $error ?>
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        <?php endif; ?>

        <!-- Statistik Dashboard -->
        <div class="stats-grid">
            <div class="stat-card kegiatan">
                <i class="fas fa-calendar-alt stat-icon"></i>
                <div class="stat-number"><?= $total_kegiatan ?></div>
                <div class="stat-label">Total Kegiatan</div>
            </div>
            <div class="stat-card pengumuman">
                <i class="fas fa-bullhorn stat-icon"></i>
                <div class="stat-number"><?= $total_pengumuman ?></div>
                <div class="stat-label">Total Pengumuman</div>
            </div>
            <div class="stat-card total">
                <i class="fas fa-newspaper stat-icon"></i>
                <div class="stat-number"><?= $total_berita ?></div>
                <div class="stat-label">Total Semua Berita</div>
            </div>
            <?php if ($berita_terbaru): ?>
            <div class="stat-card">
                <i class="fas fa-clock stat-icon"></i>
                <div class="stat-number" style="font-size: 1.2rem;"><?= date('d/m/Y', strtotime($berita_terbaru['tanggal'])) ?></div>
                <div class="stat-label"><?= htmlspecialchars($berita_terbaru['judul']) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Header Card -->
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title mb-2">
                                <i class="fas fa-newspaper me-2"></i>
                                Kelola <?= $page_title ?>
                            </h2>
                            <p class="text-muted mb-0">
                                Kelola konten <?= strtolower($page_title) ?> desa secara lengkap
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="kategori-group">
                                <a href="berita.php?kategori=kegiatan"
                                   class="btn btn-outline-primary <?= $kategori === 'kegiatan' ? 'active' : '' ?>">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Kegiatan & Program
                                </a>
                                <a href="berita.php?kategori=pengumuman"
                                   class="btn btn-outline-primary <?= $kategori === 'pengumuman' ? 'active' : '' ?>">
                                    <i class="fas fa-bullhorn me-1"></i>
                                    Pengumuman Resmi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah Berita -->
                <div class="card">
                    <h4 class="card-title mb-3">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah <?= $page_title ?> Baru
                    </h4>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="kategori" value="<?= $kategori ?>">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">Judul <?= $page_title ?></label>
                                    <input type="text" name="judul" class="form-control"
                                           placeholder="Masukkan judul <?= strtolower($page_title) ?>..."
                                           required maxlength="255" value="<?= isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Publikasi</label>
                                    <input type="date" name="tanggal" class="form-control" required
                                           value="<?= isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gambar <?= $page_title ?> (Opsional)</label>
                            <input type="file" name="gambar" class="form-control form-file"
                                   accept="image/jpeg,image/jpg,image/png,image/gif">
                            <div class="file-info">Format: JPG, PNG, GIF (Maks. 2MB)</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Isi <?= $page_title ?></label>
                            <textarea name="isi" class="form-control" rows="8"
                                      placeholder="Tulis isi lengkap <?= strtolower($page_title) ?> di sini..."
                                      required><?= isset($_POST['isi']) ? htmlspecialchars($_POST['isi']) : '' ?></textarea>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" name="tambah_berita" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Simpan <?= $page_title ?>
                            </button>
                            <button type="button" class="btn btn-success" onclick="previewContent()">
                                <i class="fas fa-eye me-2"></i>
                                Preview
                            </button>
                            <button type="reset" class="btn btn-warning">
                                <i class="fas fa-undo me-2"></i>
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Berita -->
                <div class="card">
                    <h4 class="card-title mb-3">
                        <i class="fas fa-list me-2"></i>
                        Daftar <?= $page_title ?>
                        <span class="badge bg-light text-dark ms-2"><?= count($berita) ?></span>
                    </h4>

                    <?php if (empty($berita)): ?>
                        <div class="empty-state">
                            <i class="fas fa-newspaper"></i>
                            <h5>Belum ada <?= strtolower($page_title) ?> tersedia</h5>
                            <p>Mulai tambahkan <?= strtolower($page_title) ?> pertama Anda</p>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Judul</th>
                                        <th width="10%">Gambar</th>
                                        <th width="12%">Tanggal</th>
                                        <th width="10%">Kategori</th>
                                        <th width="23%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($berita as $index => $item): ?>
                                    <tr>
                                        <td>
                                            <strong class="text-muted"><?= $index + 1 ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="d-block mb-1 text-truncate" style="max-width: 250px;">
                                                    <?= htmlspecialchars($item['judul']) ?>
                                                </strong>
                                                <small class="text-muted text-truncate d-block" style="max-width: 250px;">
                                                    <?= substr(strip_tags($item['isi']), 0, 60) ?>...
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($item['gambar']): ?>
                                                <img src="../uploads/berita/<?= $item['gambar'] ?>"
                                                     alt="Gambar <?= htmlspecialchars($item['judul']) ?>"
                                                     class="preview-image"
                                                     onerror="this.style.display='none'">
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('d/m/Y', strtotime($item['tanggal'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $item['kategori'] === 'pengumuman' ? 'badge-pengumuman' : 'badge-kegiatan' ?>">
                                                <i class="fas <?= $item['kategori'] === 'pengumuman' ? 'fa-bullhorn' : 'fa-calendar-alt' ?> me-1"></i>
                                                <?= $item['kategori'] === 'pengumuman' ? 'Pengumuman' : 'Kegiatan' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="viewBerita(<?= $item['id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                    Lihat
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="editBerita(<?= $item['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </button>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                    <button type="submit" name="hapus_berita"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus <?= $kategori === 'pengumuman' ? 'pengumuman' : 'kegiatan' ?> ini?\n\nJudul: <?= htmlspecialchars(addslashes($item['judul'])) ?>')">
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

<!-- Modal Preview -->
<div id="previewModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal('previewModal')">&times;</span>
        <h3 class="card-title mb-3">
            <i class="fas fa-eye me-2"></i>
            Preview <?= $page_title ?>
        </h3>
        <div id="previewContent"></div>
    </div>
</div>

<!-- Modal View -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal('viewModal')">&times;</span>
        <div id="viewContent"></div>
    </div>
</div>

<script>
function previewContent() {
    const judul = document.querySelector('input[name="judul"]').value;
    const isi = document.querySelector('textarea[name="isi"]').value;
    const tanggal = document.querySelector('input[name="tanggal"]').value;

    if (!judul || !isi) {
        alert('Judul dan isi harus diisi untuk preview!');
        return;
    }

    const previewHTML = `
        <div class="preview-berita">
            <h4 class="mb-2">${judul}</h4>
            <p class="text-muted mb-3"><small>Tanggal: ${new Date(tanggal).toLocaleDateString('id-ID')}</small></p>
            <div class="preview-body" style="border-top: 1px solid #eee; padding-top: 1rem;">
                ${isi.replace(/\n/g, '<br>')}
            </div>
        </div>
    `;

    document.getElementById('previewContent').innerHTML = previewHTML;
    document.getElementById('previewModal').style.display = 'block';
}

function viewBerita(id) {
    // In a real application, you would fetch data via AJAX
    // For this refactoring, we'll simulate or you'd need a separate endpoint
    alert("Fitur view detail via AJAX perlu implementasi endpoint get_berita.php");
}

function editBerita(id) {
    if (confirm('Edit berita ini?')) {
        window.location.href = `edit_berita.php?id=${id}`;
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let modal of modals) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
}

// Set today's date as default
const tanggalInput = document.querySelector('input[name="tanggal"]');
if (tanggalInput && !tanggalInput.value) {
    tanggalInput.valueAsDate = new Date();
}

// Character counter for textarea
const textarea = document.querySelector('textarea[name="isi"]');
const judulInput = document.querySelector('input[name="judul"]');

if (textarea) {
    textarea.addEventListener('input', function() {
        const charCount = this.value.length;
        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('char-count')) {
            const counter = document.createElement('div');
            counter.className = 'char-count text-muted';
            counter.style.fontSize = '0.8rem';
            counter.style.marginTop = '0.5rem';
            this.parentNode.appendChild(counter);
        }
        this.nextElementSibling.textContent = `${charCount} karakter`;
    });
}

if (judulInput) {
    judulInput.addEventListener('input', function() {
        const maxLength = 255;
        const currentLength = this.value.length;
        if (currentLength > maxLength) {
            this.value = this.value.substring(0, maxLength);
        }
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>
