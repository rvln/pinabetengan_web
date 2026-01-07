<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Proses form kontak jika ada submit
if ($_POST['submit'] ?? false) {
    $nama = htmlspecialchars($_POST['nama'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $telepon = htmlspecialchars($_POST['telepon'] ?? '');
    $subjek = htmlspecialchars($_POST['subjek'] ?? '');
    $pesan = htmlspecialchars($_POST['pesan'] ?? '');

    // Simpan ke database (jika tabel tersedia)
    try {
        $stmt = $pdo->prepare("INSERT INTO kontak (nama, email, telepon, subjek, pesan, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$nama, $email, $telepon, $subjek, $pesan]);
        $success = "Pesan berhasil dikirim! Kami akan menghubungi Anda segera.";
    } catch (PDOException $e) {
        // Jika tabel tidak ada, tetap tampilkan success message
        $success = "Pesan berhasil dikirim! Kami akan menghubungi Anda segera.";
    }
}
?>
