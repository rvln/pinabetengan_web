<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah_potensi'])) {
        $nama_potensi = $_POST['nama_potensi'];
        $deskripsi = $_POST['deskripsi'];
        $icon = $_POST['icon'];
        $urutan = $_POST['urutan'];
        
        $stmt = $pdo->prepare("INSERT INTO potensi (nama_potensi, deskripsi, icon, urutan) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama_potensi, $deskripsi, $icon, $urutan]);
        $success = "Potensi berhasil ditambahkan!";
    }
    
    if (isset($_POST['edit_potensi'])) {
        $id = $_POST['id'];
        $nama_potensi = $_POST['nama_potensi'];
        $deskripsi = $_POST['deskripsi'];
        $icon = $_POST['icon'];
        $urutan = $_POST['urutan'];
        $status = $_POST['status'];
        
        $stmt = $pdo->prepare("UPDATE potensi SET nama_potensi=?, deskripsi=?, icon=?, urutan=?, status=? WHERE id=?");
        $stmt->execute([$nama_potensi, $deskripsi, $icon, $urutan, $status, $id]);
        $success = "Potensi berhasil diupdate!";
    }
    
    if (isset($_POST['hapus_potensi'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM potensi WHERE id=?");
        $stmt->execute([$id]);
        $success = "Potensi berhasil dihapus!";
    }
}

// Ambil data potensi
try {
    $potensi = $pdo->query("SELECT * FROM potensi ORDER BY urutan ASC")->fetchAll();
} catch (Exception $e) {
    $potensi = [];
}

// Handle edit form display
$edit_id = $_GET['edit'] ?? null;
$potensi_edit = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM potensi WHERE id = ?");
    $stmt->execute([$edit_id]);
    $potensi_edit = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Potensi - Admin</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    /* ========== NATURE COLOR PALETTE ========== */
    :root {
        /* Primary - Putih Gading (Off-white/Ivory) */
        --ivory: #F5F3EF;
        --ivory-light: #FAFAF8;
        --ivory-dark: #E8E4DC;
        
        /* Secondary - Merah */
        --red: #C62828;
        --red-dark: #B71C1C;
        --red-light: #E53935;
        
        /* Accent - Kuning Jagung */
        --yellow: #FFD54F;
        --yellow-dark: #FFC107;
        --yellow-light: #FFECB3;
        
        /* Text - Hitam (ALL TEXT) */
        --black: #2C2C2C;
        --black-light: #4A4A4A;
        --grey: #6B6B6B;
        
        /* Nature accents */
        --green-leaf: #7CB342;
        --green-dark: #558B2F;
        --brown-earth: #8D6E63;
        --sky-blue: #64B5F6;
        --cream: #FFF9E6;
        
        /* Effects */
        --shadow-soft: 0 4px 20px rgba(44, 44, 44, 0.08);
        --shadow-medium: 0 8px 32px rgba(44, 44, 44, 0.12);
        --shadow-strong: 0 12px 48px rgba(44, 44, 44, 0.15);
        --shadow-glow: 0 0 40px rgba(255, 213, 79, 0.3);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ========== DARK MODE ========== */
    body.dark-mode {
        --ivory: #1a1a1a;
        --ivory-light: #1a1a1a;
        --ivory-dark: #151515;
        --black: #FFFFFF;
        --black-light: #E0E0E0;
        --grey: #B0B0B0;
        --cream: #1a1a1a;
        --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.5);
        --shadow-medium: 0 8px 32px rgba(0, 0, 0, 0.6);
        --shadow-strong: 0 12px 48px rgba(0, 0, 0, 0.7);
    }

    /* ========== GLOBAL STYLES ========== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--ivory) 0%, var(--cream) 50%, var(--ivory-light) 100%);
        color: var(--black);
        line-height: 1.7;
        min-height: 100vh;
        transition: background-color 0.4s ease, color 0.4s ease;
    }

    body.dark-mode {
        background: linear-gradient(135deg, #1a1a1a 0%, #151515 50%, #1a1a1a 100%);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        color: var(--black);
        font-weight: 700;
    }

    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode h3,
    body.dark-mode h4,
    body.dark-mode h5,
    body.dark-mode h6 {
        color: #FFFFFF;
    }

    /* ========== HEADER STYLES ========== */
    .dashboard-header {
        background: rgba(245, 243, 239, 0.95);
        backdrop-filter: blur(15px);
        padding: 1.5rem 0;
        box-shadow: var(--shadow-soft);
        border-bottom: 2px solid var(--yellow);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    body.dark-mode .dashboard-header {
        background: rgba(20, 20, 20, 0.95);
        border-bottom-color: rgba(255, 213, 79, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .brand-icon {
        font-size: 2rem;
        color: var(--green-leaf);
    }

    body.dark-mode .brand-icon {
        color: var(--yellow);
    }

    .brand-text h1 {
        font-size: 1.5rem;
        margin-bottom: 0.2rem;
    }

    .brand-text p {
        color: var(--grey);
        font-size: 0.9rem;
    }

    body.dark-mode .brand-text p {
        color: #B0B0B0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    /* ========== MAIN CONTENT ========== */
    .dashboard-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* ========== CARD STYLES ========== */
    .card {
        background: white;
        padding: 2.5rem;
        border-radius: 25px;
        box-shadow: var(--shadow-soft);
        border: 2px solid transparent;
        transition: var(--transition);
        margin-bottom: 2rem;
    }

    body.dark-mode .card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
        border-color: var(--yellow);
    }

    /* ========== BUTTON STYLES ========== */
    .btn-logout {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-logout:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--yellow), var(--yellow-dark));
        color: var(--black);
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, var(--yellow-dark), var(--yellow));
        transform: translateY(-2px);
        color: var(--black);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, var(--red-dark), var(--red));
        transform: translateY(-2px);
        color: white;
    }

    .btn-theme-toggle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--yellow);
        border: none;
        color: var(--black);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        cursor: pointer;
    }

    .btn-theme-toggle:hover {
        background: var(--yellow-dark);
        transform: scale(1.1) rotate(180deg);
        box-shadow: 0 6px 20px rgba(255, 213, 79, 0.4);
    }

    body.dark-mode .btn-theme-toggle {
        background: var(--yellow-dark);
        color: var(--ivory);
    }

    body.dark-mode .btn-theme-toggle:hover {
        background: var(--yellow);
        color: var(--black);
    }

    /* ========== FORM STYLES ========== */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.8rem;
        color: var(--black);
        font-weight: 600;
    }

    body.dark-mode .form-label {
        color: #E0E0E0;
    }

    .form-control {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px solid var(--ivory-dark);
        border-radius: 15px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
        color: var(--black);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.2);
        transform: translateY(-2px);
    }

    body.dark-mode .form-control {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.2);
        color: #E0E0E0;
    }

    body.dark-mode .form-control:focus {
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.3);
    }

    .form-select {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px solid var(--ivory-dark);
        border-radius: 15px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
        color: var(--black);
    }

    body.dark-mode .form-select {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.2);
        color: #E0E0E0;
    }

    /* ========== TABLE STYLES ========== */
    .table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 15px;
        overflow: hidden;
    }

    .table th {
        background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
        color: var(--black);
        padding: 1.2rem;
        font-weight: 600;
        text-align: left;
        border: none;
    }

    body.dark-mode .table th {
        background: #333;
        color: var(--yellow);
    }

    .table td {
        padding: 1.2rem;
        border-bottom: 1px solid var(--ivory-dark);
        color: var(--black);
    }

    body.dark-mode .table td {
        border-bottom-color: #333;
        color: #E0E0E0;
    }

    /* ========== BADGE STYLES ========== */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-kegiatan {
        background: linear-gradient(135deg, var(--green-leaf), #4CAF50);
        color: white;
    }

    .badge-pengumuman {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        color: white;
    }

    .badge-active {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
    }

    .badge-inactive {
        background: linear-gradient(135deg, #757575, #616161);
        color: white;
    }

    /* ========== ALERT STYLES ========== */
    .alert {
        border-radius: 15px;
        border: none;
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    body.dark-mode .alert-success {
        background: rgba(21, 87, 36, 0.2);
        color: #d4edda;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .dashboard-main {
            padding: 1rem;
        }

        .card {
            padding: 2rem;
        }

        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-group .btn {
            margin-bottom: 0.5rem;
        }
    }

    /* ========== SCROLLBAR ========== */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--ivory);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--red);
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--red-dark);
    }

    body.dark-mode::-webkit-scrollbar-track {
        background: var(--ivory-dark);
    }

    body.dark-mode::-webkit-scrollbar-thumb {
        background: var(--yellow);
    }

    body.dark-mode::-webkit-scrollbar-thumb:hover {
        background: var(--yellow-dark);
    }

    /* ========== THEME TRANSITION ========== */
    body, 
    .dashboard-header,
    .card,
    .btn-logout,
    .btn-theme-toggle {
        transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease;
    }
    </style>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="dashboard-header">
    <div class="header-content">
        <div class="brand">
            <i class="fas fa-gem brand-icon"></i>
            <div class="brand-text">
                <h1>Kelola Potensi Desa</h1>
                <p>Admin Desa Pinabetengan Selatan</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
            <a href="dashboard.php" class="btn-logout">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
</header>

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
                            <textarea name="deskripsi" class="form-control" rows="4" 
                                      placeholder="Tulis deskripsi potensi di sini..." required><?= $potensi_edit ? htmlspecialchars($potensi_edit['deskripsi']) : '' ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" name="<?= $potensi_edit ? 'edit_potensi' : 'tambah_potensi' ?>" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                <?= $potensi_edit ? 'Update Potensi' : 'Simpan Potensi' ?>
                            </button>
                            
                            <?php if ($potensi_edit): ?>
                                <a href="potensi.php" class="btn btn-secondary">
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
                                                <a href="potensi.php?edit=<?= $item['id'] ?>" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                    <button type="submit" name="hapus_potensi" 
                                                            class="btn btn-danger" 
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

<!-- ========== THEME TOGGLE SCRIPT ========== -->
<script>
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle.querySelector('i');
const body = document.body;

// Function to apply theme
function applyTheme(theme) {
    if (theme === 'dark') {
        body.classList.add('dark-mode');
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
    } else {
        body.classList.remove('dark-mode');
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
    }
    localStorage.setItem('theme', theme);
}

// Check saved theme or system preference
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
    applyTheme(savedTheme);
} else if (systemPrefersDark) {
    applyTheme('dark');
} else {
    applyTheme('light');
}

// Toggle theme on button click
themeToggle.addEventListener('click', () => {
    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
    
    // Add a little animation feedback
    themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
        themeToggle.style.transform = '';
    }, 300);
});

// Listen to system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
        applyTheme(e.matches ? 'dark' : 'light');
    }
});
</script>

</body>
</html>