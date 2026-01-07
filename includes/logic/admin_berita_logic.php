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
