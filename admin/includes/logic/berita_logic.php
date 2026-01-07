<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/db.php';

// Cek sesi login
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Ambil parameter kategori
$kategori = $_GET['kategori'] ?? 'kegiatan';
$page_title = $kategori === 'pengumuman' ? 'Pengumuman Resmi' : 'Kegiatan & Program';

// Handle Tambah Berita
if (isset($_POST['tambah_berita'])) {
    $judul = htmlspecialchars($_POST['judul'] ?? '');
    $isi = htmlspecialchars($_POST['isi'] ?? '');
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $kategori_input = $_POST['kategori'] ?? 'kegiatan';
    $gambar = '';

    // Upload Gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/berita/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['gambar']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            $gambar = $fileName;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, tanggal, kategori, gambar, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$judul, $isi, $tanggal, $kategori_input, $gambar]);
        $success = "Berita berhasil ditambahkan!";
    } catch (PDOException $e) {
        $error = "Gagal menambahkan berita: " . $e->getMessage();
    }
}

// Handle Hapus Berita
if (isset($_POST['hapus_berita'])) {
    $id = $_POST['id'] ?? 0;
    try {
        // Ambil info gambar lama untuk dihapus
        $stmt = $pdo->prepare("SELECT gambar FROM berita WHERE id = ?");
        $stmt->execute([$id]);
        $berita_lama = $stmt->fetch();

        if ($berita_lama && $berita_lama['gambar']) {
            $path = '../uploads/berita/' . $berita_lama['gambar'];
            if (file_exists($path)) unlink($path);
        }

        $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Berita berhasil dihapus!";
    } catch (PDOException $e) {
        $error = "Gagal menghapus berita: " . $e->getMessage();
    }
}

// Ambil data berita sesuai kategori
try {
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE kategori = ? ORDER BY tanggal DESC, id DESC");
    $stmt->execute([$kategori]);
    $berita = $stmt->fetchAll();

    // Ambil statistik untuk dashboard mini
    $total_kegiatan = $pdo->query("SELECT COUNT(*) FROM berita WHERE kategori = 'kegiatan'")->fetchColumn();
    $total_pengumuman = $pdo->query("SELECT COUNT(*) FROM berita WHERE kategori = 'pengumuman'")->fetchColumn();
    $total_berita = $total_kegiatan + $total_pengumuman;

    // Berita terbaru untuk widget
    $stmt_latest = $pdo->query("SELECT judul, tanggal FROM berita ORDER BY created_at DESC LIMIT 1");
    $berita_terbaru = $stmt_latest->fetch();

} catch (PDOException $e) {
    $berita = [];
    $total_kegiatan = 0;
    $total_pengumuman = 0;
    $total_berita = 0;
    $berita_terbaru = null;
}
?>
