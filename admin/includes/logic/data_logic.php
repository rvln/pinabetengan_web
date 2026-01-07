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
