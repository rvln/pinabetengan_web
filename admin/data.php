<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Handle update data penduduk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_penduduk'])) {
    $total_penduduk = $_POST['total_penduduk'];
    $laki_laki = $_POST['laki_laki'];
    $perempuan = $_POST['perempuan'];
    $kepala_keluarga = $_POST['kepala_keluarga'];
    $kepadatan_penduduk = $_POST['kepadatan_penduduk'];
    $tahun = $_POST['tahun'];
    
    try {
        // Cek apakah data sudah ada
        $check = $pdo->query("SELECT COUNT(*) FROM data_penduduk WHERE tahun = $tahun")->fetchColumn();
        
        if ($check > 0) {
            $stmt = $pdo->prepare("UPDATE data_penduduk SET total_penduduk = ?, laki_laki = ?, perempuan = ?, kepala_keluarga = ?, kepadatan_penduduk = ? WHERE tahun = ?");
        } else {
            $stmt = $pdo->prepare("INSERT INTO data_penduduk (total_penduduk, laki_laki, perempuan, kepala_keluarga, kepadatan_penduduk, tahun) VALUES (?, ?, ?, ?, ?, ?)");
        }
        
        $stmt->execute([$total_penduduk, $laki_laki, $perempuan, $kepala_keluarga, $kepadatan_penduduk, $tahun]);
        $success_penduduk = "Data penduduk berhasil diupdate!";
    } catch (Exception $e) {
        $error_penduduk = "Error: " . $e->getMessage();
    }
}

// Handle update data pendidikan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pendidikan'])) {
    // Hapus data lama
    $pdo->query("DELETE FROM data_pendidikan");
    
    // Insert data baru
    $tingkat = $_POST['tingkat'];
    $jumlah = $_POST['jumlah'];
    
    $stmt = $pdo->prepare("INSERT INTO data_pendidikan (tingkat, jumlah) VALUES (?, ?)");
    
    for ($i = 0; $i < count($tingkat); $i++) {
        if (!empty($tingkat[$i]) && !empty($jumlah[$i])) {
            $stmt->execute([$tingkat[$i], $jumlah[$i]]);
        }
    }
    
    $success_pendidikan = "Data pendidikan berhasil diupdate!";
}

// Handle update data pekerjaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pekerjaan'])) {
    // Hapus data lama
    $pdo->query("DELETE FROM data_pekerjaan");
    
    // Insert data baru
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    
    $stmt = $pdo->prepare("INSERT INTO data_pekerjaan (jenis, jumlah) VALUES (?, ?)");
    
    for ($i = 0; $i < count($jenis); $i++) {
        if (!empty($jenis[$i]) && !empty($jumlah[$i])) {
            $stmt->execute([$jenis[$i], $jumlah[$i]]);
        }
    }
    
    $success_pekerjaan = "Data pekerjaan berhasil diupdate!";
}

// Ambil data untuk form
try {
    $penduduk = $pdo->query("SELECT * FROM data_penduduk ORDER BY tahun DESC LIMIT 1")->fetch();
} catch (Exception $e) {
    $penduduk = null;
}

try {
    $pendidikan = $pdo->query("SELECT * FROM data_pendidikan")->fetchAll();
} catch (Exception $e) {
    $pendidikan = [];
}

try {
    $pekerjaan = $pdo->query("SELECT * FROM data_pekerjaan")->fetchAll();
} catch (Exception $e) {
    $pekerjaan = [];
}

// Data fallback untuk form
$data_penduduk = $penduduk ?: [
    'total_penduduk' => 5247,
    'laki_laki' => 2621,
    'perempuan' => 2626,
    'kepala_keluarga' => 1458,
    'kepadatan_penduduk' => 245,
    'tahun' => date('Y')
];

if (empty($pendidikan)) {
    $pendidikan = [
        ['tingkat' => 'Tidak Sekolah', 'jumlah' => 187],
        ['tingkat' => 'SD/Sederajat', 'jumlah' => 1458],
        ['tingkat' => 'SMP/Sederajat', 'jumlah' => 1362],
        ['tingkat' => 'SMA/Sederajat', 'jumlah' => 1524],
        ['tingkat' => 'Diploma/Sarjana', 'jumlah' => 716]
    ];
}

if (empty($pekerjaan)) {
    $pekerjaan = [
        ['jenis' => 'Petani', 'jumlah' => 1245],
        ['jenis' => 'Pedagang', 'jumlah' => 856],
        ['jenis' => 'PNS/TNI/Polri', 'jumlah' => 324],
        ['jenis' => 'Karyawan Swasta', 'jumlah' => 1087],
        ['jenis' => 'Wiraswasta', 'jumlah' => 892],
        ['jenis' => 'Lainnya', 'jumlah' => 843]
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Statistik - Admin</title>

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

    .card-title {
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: var(--black);
    }

    body.dark-mode .card-title {
        color: #FFFFFF;
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
        cursor: pointer;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
    }

    .btn-secondary {
        background: transparent;
        color: var(--red);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        border: 2px solid var(--red);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: var(--red);
        color: var(--ivory);
        transform: translateY(-2px);
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

    /* ========== TABLE STYLES ========== */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .data-table th,
    .data-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--ivory-dark);
    }

    .data-table th {
        background: var(--yellow-light);
        color: var(--black);
        font-weight: 600;
    }

    body.dark-mode .data-table th {
        background: rgba(255, 213, 79, 0.2);
        color: var(--yellow);
    }

    .data-table tr:hover {
        background: var(--ivory-light);
    }

    body.dark-mode .data-table tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    /* ========== STAT CARD STYLES ========== */
    .stat-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 25px;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .stat-card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-medium);
        border-color: var(--yellow);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: var(--transition);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(10deg);
    }

    .stat-icon i {
        font-size: 1.8rem;
        color: var(--red);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--red);
        margin-bottom: 0.5rem;
        font-family: 'Poppins', sans-serif;
    }

    body.dark-mode .stat-number {
        color: var(--yellow);
    }

    .stat-label {
        font-size: 1rem;
        color: var(--grey);
        font-weight: 500;
    }

    body.dark-mode .stat-label {
        color: #B0B0B0;
    }

    /* ========== ALERT STYLES ========== */
    .alert {
        border-radius: 15px;
        border: none;
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    .alert-error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }

    body.dark-mode .alert-success {
        background: rgba(21, 87, 36, 0.2);
        color: #d4edda;
    }

    body.dark-mode .alert-error {
        background: rgba(114, 28, 36, 0.2);
        color: #f8d7da;
    }

    /* ========== GRID SYSTEM ========== */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -1rem;
    }

    .col-12, .col-md-6, .col-lg-4, .col-lg-3 {
        padding: 0 1rem;
    }

    .col-12 { width: 100%; }
    .col-md-6 { width: 50%; }
    .col-lg-4 { width: 33.333%; }
    .col-lg-3 { width: 25%; }

    /* ========== PREVIEW SECTION ========== */
    .preview-section {
        background: var(--ivory-light);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        border: 2px dashed var(--yellow);
    }

    body.dark-mode .preview-section {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.3);
    }

    .preview-title {
        font-size: 1.2rem;
        color: var(--black);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    body.dark-mode .preview-title {
        color: #FFFFFF;
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

        .col-md-6, .col-lg-4, .col-lg-3 {
            width: 100%;
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
    .stat-card,
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
            <i class="fas fa-database brand-icon"></i>
            <div class="brand-text">
                <h1>Kelola Data Statistik</h1>
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

<!-- ========== JAVASCRIPT ========== -->
<script>
// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle.querySelector('i');
const body = document.body;

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

// Check saved theme
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
    applyTheme(savedTheme);
} else if (systemPrefersDark) {
    applyTheme('dark');
} else {
    applyTheme('light');
}

// Theme toggle event
themeToggle.addEventListener('click', () => {
    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
    
    themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
        themeToggle.style.transform = '';
    }, 300);
});

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

// Listen to system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
        const newTheme = e.matches ? 'dark' : 'light';
        applyTheme(newTheme);
    }
});
</script>

</body>
</html>