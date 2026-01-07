<?php
session_start();
require_once 'config/db.php';

// Ambil data statistik
try {
    $stats = $pdo->query("SELECT * FROM statistik WHERE id = 1")->fetch();
} catch (Exception $e) {
    $stats = ['penduduk' => 2500, 'rukun_tetangga' => 8, 'umkm_aktif' => 15, 'destinasi_wisata' => 5];
}

// Ambil data berita terbaru
try {
    $berita_terbaru = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3")->fetchAll();
} catch (Exception $e) {
    $berita_terbaru = [];
}

// Ambil data potensi
try {
    $potensi_data = $pdo->query("SELECT * FROM potensi WHERE status = 'active' ORDER BY urutan ASC")->fetchAll();
} catch (Exception $e) {
    $potensi_data = [];
}
?>
