<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Cek dan tambahkan kolom kategori jika belum ada
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM berita LIKE 'kategori'");
    $kategori_column_exists = (bool)$stmt->fetch();
    
    if (!$kategori_column_exists) {
        // Tambahkan kolom kategori
        $pdo->exec("ALTER TABLE berita ADD COLUMN kategori ENUM('kegiatan', 'pengumuman') DEFAULT 'kegiatan' AFTER tanggal");
        $kategori_column_exists = true;
    }
} catch (Exception $e) {
    $kategori_column_exists = false;
}

$kategori = $_GET['kategori'] ?? 'kegiatan';
$page_title = $kategori === 'pengumuman' ? 'Pengumuman Resmi' : 'Kegiatan & Program';
$success = '';
$error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['tambah_berita'])) {
            $judul = trim($_POST['judul']);
            $isi = trim($_POST['isi']);
            $tanggal = $_POST['tanggal'];
            $kategori_input = $_POST['kategori'];
            $gambar = null;
            
            // Handle file upload
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if (in_array($_FILES['gambar']['type'], $allowed_types) && $_FILES['gambar']['size'] <= $max_size) {
                    $upload_dir = '../uploads/berita/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                    $filename = 'berita_' . time() . '_' . uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $filename;
                    
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                        $gambar = $filename;
                    }
                }
            }
            
            // Validasi input
            if (empty($judul) || empty($isi) || empty($tanggal)) {
                $error = "Semua field wajib diisi!";
            } else {
                if ($kategori_column_exists) {
                    if ($gambar) {
                        $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, tanggal, kategori, gambar) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$judul, $isi, $tanggal, $kategori_input, $gambar]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, tanggal, kategori) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$judul, $isi, $tanggal, $kategori_input]);
                    }
                } else {
                    if ($gambar) {
                        $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, tanggal, gambar) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$judul, $isi, $tanggal, $gambar]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, tanggal) VALUES (?, ?, ?)");
                        $stmt->execute([$judul, $isi, $tanggal]);
                    }
                }
                $success = $kategori_input === 'pengumuman' ? "Pengumuman berhasil ditambahkan!" : "Kegiatan berhasil ditambahkan!";
            }
        }
        
        if (isset($_POST['edit_berita'])) {
            $id = $_POST['id'];
            $judul = trim($_POST['judul']);
            $isi = trim($_POST['isi']);
            $tanggal = $_POST['tanggal'];
            $kategori_input = $_POST['kategori'];
            
            if (empty($judul) || empty($isi) || empty($tanggal)) {
                $error = "Semua field wajib diisi!";
            } else {
                if ($kategori_column_exists) {
                    $stmt = $pdo->prepare("UPDATE berita SET judul=?, isi=?, tanggal=?, kategori=? WHERE id=?");
                    $stmt->execute([$judul, $isi, $tanggal, $kategori_input, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE berita SET judul=?, isi=?, tanggal=? WHERE id=?");
                    $stmt->execute([$judul, $isi, $tanggal, $id]);
                }
                $success = $kategori_input === 'pengumuman' ? "Pengumuman berhasil diupdate!" : "Kegiatan berhasil diupdate!";
            }
        }
        
        if (isset($_POST['hapus_berita'])) {
            $id = $_POST['id'];
            
            // Hapus gambar jika ada
            $stmt = $pdo->prepare("SELECT gambar FROM berita WHERE id = ?");
            $stmt->execute([$id]);
            $berita = $stmt->fetch();
            
            if ($berita && $berita['gambar']) {
                $file_path = '../uploads/berita/' . $berita['gambar'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $stmt = $pdo->prepare("DELETE FROM berita WHERE id=?");
            $stmt->execute([$id]);
            $success = "Data berhasil dihapus!";
        }
        
        if (isset($_POST['update_status'])) {
            $id = $_POST['id'];
            $status = $_POST['status'];
            
            $stmt = $pdo->prepare("UPDATE berita SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = "Status berhasil diupdate!";
        }
        
    } catch (Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Ambil data berita berdasarkan kategori
try {
    if ($kategori_column_exists) {
        $stmt = $pdo->prepare("SELECT * FROM berita WHERE kategori = ? ORDER BY tanggal DESC, id DESC");
        $stmt->execute([$kategori]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM berita ORDER BY tanggal DESC, id DESC");
        $stmt->execute();
    }
    $berita = $stmt->fetchAll();
} catch (Exception $e) {
    $berita = [];
    $error = "Gagal memuat data: " . $e->getMessage();
}

// Statistik untuk dashboard
try {
    $total_kegiatan = $pdo->query("SELECT COUNT(*) FROM berita WHERE kategori = 'kegiatan'")->fetchColumn();
    $total_pengumuman = $pdo->query("SELECT COUNT(*) FROM berita WHERE kategori = 'pengumuman'")->fetchColumn();
    $total_berita = $pdo->query("SELECT COUNT(*) FROM berita")->fetchColumn();
    $berita_terbaru = $pdo->query("SELECT judul, tanggal FROM berita ORDER BY tanggal DESC LIMIT 1")->fetch();
} catch (Exception $e) {
    $total_kegiatan = $total_pengumuman = $total_berita = 0;
    $berita_terbaru = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola <?= $page_title ?> - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
    /* ========== NATURE COLOR PALETTE ========== */
    :root {
        --ivory: #F5F3EF;
        --ivory-light: #FAFAF8;
        --ivory-dark: #E8E4DC;
        --red: #C62828;
        --red-dark: #B71C1C;
        --red-light: #E53935;
        --yellow: #FFD54F;
        --yellow-dark: #FFC107;
        --yellow-light: #FFECB3;
        --black: #2C2C2C;
        --black-light: #4A4A4A;
        --grey: #6B6B6B;
        --green-leaf: #7CB342;
        --green-dark: #558B2F;
        --blue: #2196F3;
        --blue-dark: #1976D2;
        --brown-earth: #8D6E63;
        --sky-blue: #64B5F6;
        --cream: #FFF9E6;
        --shadow-soft: 0 4px 20px rgba(44, 44, 44, 0.08);
        --shadow-medium: 0 8px 32px rgba(44, 44, 44, 0.12);
        --shadow-strong: 0 12px 48px rgba(44, 44, 44, 0.15);
        --shadow-glow: 0 0 40px rgba(255, 213, 79, 0.3);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

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

    .dashboard-header {
        background: rgba(245, 243, 239, 0.95);
        backdrop-filter: blur(15px);
        padding: 1.2rem 0;
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
        font-size: 1.8rem;
        color: var(--green-leaf);
    }

    body.dark-mode .brand-icon {
        color: var(--yellow);
    }

    .brand-text h1 {
        font-size: 1.3rem;
        margin-bottom: 0.2rem;
        font-weight: 700;
    }

    .brand-text p {
        color: var(--grey);
        font-size: 0.85rem;
        margin: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .dashboard-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Statistik Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: var(--shadow-soft);
        border-left: 4px solid var(--green-leaf);
        transition: var(--transition);
    }

    .stat-card.kegiatan {
        border-left-color: var(--green-leaf);
    }

    .stat-card.pengumuman {
        border-left-color: var(--red);
    }

    .stat-card.total {
        border-left-color: var(--blue);
    }

    body.dark-mode .stat-card {
        background: #222222;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-medium);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-card.kegiatan .stat-icon {
        color: var(--green-leaf);
    }

    .stat-card.pengumuman .stat-icon {
        color: var(--red);
    }

    .stat-card.total .stat-icon {
        color: var(--blue);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--grey);
        font-size: 0.9rem;
    }

    .card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        border: 2px solid transparent;
        transition: var(--transition);
        margin-bottom: 1.5rem;
    }

    body.dark-mode .card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-medium);
        border-color: var(--yellow);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--black);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    body.dark-mode .card-title {
        color: #FFFFFF;
    }

    .btn-logout {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 0.7rem 1.2rem;
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
        font-size: 0.9rem;
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
        padding: 0.9rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 6px 20px rgba(198, 40, 40, 0.3);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.95rem;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--green-leaf), var(--green-dark));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, var(--green-dark), var(--green-leaf));
        transform: translateY(-1px);
        color: white;
    }

    .btn-outline-primary {
        background: transparent;
        color: var(--red);
        padding: 0.7rem 1.3rem;
        border-radius: 50px;
        font-weight: 600;
        border: 2px solid var(--red);
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary.active {
        background: var(--red);
        color: var(--ivory);
        transform: translateY(-2px);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--yellow), var(--yellow-dark));
        color: var(--black);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, var(--yellow-dark), var(--yellow));
        transform: translateY(-1px);
        color: var(--black);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, var(--red-dark), var(--red));
        transform: translateY(-1px);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, var(--blue), var(--blue-dark));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, var(--blue-dark), var(--blue));
        transform: translateY(-1px);
        color: white;
    }

    .btn-theme-toggle {
        width: 42px;
        height: 42px;
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
        font-size: 1.1rem;
    }

    .btn-theme-toggle:hover {
        background: var(--yellow-dark);
        transform: scale(1.08) rotate(180deg);
        box-shadow: 0 5px 18px rgba(255, 213, 79, 0.4);
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.6rem;
        color: var(--black);
        font-weight: 600;
        font-size: 0.95rem;
    }

    body.dark-mode .form-label {
        color: #E0E0E0;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem 1.1rem;
        border: 2px solid var(--ivory-dark);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: var(--transition);
        background: white;
        color: var(--black);
        font-family: 'Inter', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.2);
        transform: translateY(-1px);
    }

    body.dark-mode .form-control {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.2);
        color: #E0E0E0;
    }

    .form-select {
        width: 100%;
        padding: 0.9rem 1.1rem;
        border: 2px solid var(--ivory-dark);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: var(--transition);
        background: white;
        color: var(--black);
        font-family: 'Inter', sans-serif;
    }

    .form-file {
        padding: 0.5rem;
    }

    .file-info {
        font-size: 0.85rem;
        color: var(--grey);
        margin-top: 0.5rem;
    }

    .table-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table th {
        background: linear-gradient(135deg, var(--yellow-light), var(--yellow));
        color: var(--black);
        padding: 1rem 1.2rem;
        font-weight: 700;
        text-align: left;
        border: none;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    body.dark-mode .table th {
        background: #333;
        color: var(--yellow);
    }

    .table td {
        padding: 1rem 1.2rem;
        border-bottom: 1px solid var(--ivory-dark);
        color: var(--black);
        font-size: 0.9rem;
        vertical-align: top;
    }

    body.dark-mode .table td {
        border-bottom-color: #333;
        color: #E0E0E0;
    }

    .table tbody tr {
        transition: var(--transition);
    }

    .table tbody tr:hover {
        background: rgba(255, 213, 79, 0.05);
    }

    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-kegiatan {
        background: linear-gradient(135deg, var(--green-leaf), #4CAF50);
        color: white;
    }

    .badge-pengumuman {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        color: white;
    }

    .badge-date {
        background: rgba(255, 255, 255, 0.9);
        color: var(--grey);
        border: 1px solid var(--ivory-dark);
    }

    .badge-status {
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .badge-active {
        background: var(--green-leaf);
        color: white;
    }

    .badge-draft {
        background: var(--grey);
        color: white;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.3rem;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    body.dark-mode .alert-success {
        background: rgba(21, 87, 36, 0.2);
        color: #d4edda;
        border-left-color: #28a745;
    }

    body.dark-mode .alert-danger {
        background: rgba(114, 28, 36, 0.2);
        color: #f8d7da;
        border-left-color: #dc3545;
    }

    body.dark-mode .alert-warning {
        background: rgba(133, 100, 4, 0.2);
        color: #fff3cd;
        border-left-color: #ffc107;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        margin-left: auto;
        color: inherit;
        opacity: 0.7;
        transition: var(--transition);
    }

    .btn-close:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .kategori-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-buttons {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--grey);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h5 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 0.95rem;
        opacity: 0.8;
    }

    .text-muted {
        color: var(--grey) !important;
        font-size: 0.85rem;
    }

    .preview-image {
        max-width: 100px;
        max-height: 60px;
        border-radius: 8px;
        object-fit: cover;
    }

    .feature-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--yellow);
        color: var(--black);
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
            padding: 0 1rem;
        }

        .dashboard-main {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card {
            padding: 1.5rem;
        }

        .kategori-group {
            justify-content: center;
            width: 100%;
        }

        .btn-outline-primary {
            flex: 1;
            text-align: center;
            justify-content: center;
            min-width: 140px;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            min-width: 600px;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.3rem;
        }

        .btn-warning,
        .btn-danger {
            width: 100%;
            justify-content: center;
        }
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        box-shadow: var(--shadow-strong);
        position: relative;
    }

    body.dark-mode .modal-content {
        background-color: #222222;
        color: white;
    }

    .close-modal {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--grey);
    }

    .close-modal:hover {
        color: var(--red);
    }
    </style>
</head>
<body>

<header class="dashboard-header">
    <div class="header-content">
        <div class="brand">
            <i class="fas fa-newspaper brand-icon"></i>
            <div class="brand-text">
                <h1>Kelola <?= $page_title ?></h1>
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

const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
    applyTheme(savedTheme);
} else if (systemPrefersDark) {
    applyTheme('dark');
} else {
    applyTheme('light');
}

themeToggle.addEventListener('click', () => {
    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
    
    themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
        themeToggle.style.transform = '';
    }, 300);
});

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
    fetch(`get_berita.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            const viewHTML = `
                <h3 class="card-title mb-3">
                    <i class="fas fa-newspaper me-2"></i>
                    Detail Berita
                </h3>
                <div class="berita-detail">
                    <h4 class="mb-2">${data.judul}</h4>
                    <div class="d-flex gap-3 mb-3">
                        <span class="badge ${data.kategori === 'pengumuman' ? 'badge-pengumuman' : 'badge-kegiatan'}">
                            <i class="fas ${data.kategori === 'pengumuman' ? 'fa-bullhorn' : 'fa-calendar-alt'} me-1"></i>
                            ${data.kategori === 'pengumuman' ? 'Pengumuman' : 'Kegiatan'}
                        </span>
                        <span class="badge badge-date">
                            <i class="fas fa-calendar me-1"></i>
                            ${new Date(data.tanggal).toLocaleDateString('id-ID')}
                        </span>
                    </div>
                    ${data.gambar ? `<img src="../uploads/berita/${data.gambar}" alt="${data.judul}" style="max-width: 100%; border-radius: 10px; margin-bottom: 1rem;">` : ''}
                    <div class="berita-isi" style="line-height: 1.8;">
                        ${data.isi.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
            document.getElementById('viewContent').innerHTML = viewHTML;
            document.getElementById('viewModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data berita');
        });
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
document.querySelector('input[name="tanggal"]').valueAsDate = new Date();

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

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
</body>
</html>