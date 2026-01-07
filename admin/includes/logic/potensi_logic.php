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
