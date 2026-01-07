<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data statistik
try {
    $penduduk = $pdo->query("SELECT * FROM data_penduduk ORDER BY tahun DESC LIMIT 1")->fetch();
} catch (PDOException $e) {
    $penduduk = null;
}

try {
    $pendidikan = $pdo->query("SELECT * FROM data_pendidikan")->fetchAll();
} catch (PDOException $e) {
    $pendidikan = [];
}

try {
    $pekerjaan = $pdo->query("SELECT * FROM data_pekerjaan")->fetchAll();
} catch (PDOException $e) {
    $pekerjaan = [];
}

// Data fallback
$data_penduduk = $penduduk ?: [
    'total_penduduk' => 5247,
    'laki_laki' => 2621,
    'perempuan' => 2626,
    'kepala_keluarga' => 1458,
    'kepadatan_penduduk' => 245,
    'tahun' => 2024
];

// Hitung persentase untuk pendidikan
if (!empty($pendidikan)) {
    $total_pendidikan = array_sum(array_column($pendidikan, 'jumlah'));
    foreach ($pendidikan as &$edu) {
        $edu['persentase'] = $total_pendidikan > 0 ? ($edu['jumlah'] / $total_pendidikan) * 100 : 0;
    }
    unset($edu); // Unset reference
} else {
    $pendidikan = [
        ['tingkat' => 'Tidak Sekolah', 'jumlah' => 187, 'persentase' => 3.6],
        ['tingkat' => 'SD/Sederajat', 'jumlah' => 1458, 'persentase' => 27.8],
        ['tingkat' => 'SMP/Sederajat', 'jumlah' => 1362, 'persentase' => 26.0],
        ['tingkat' => 'SMA/Sederajat', 'jumlah' => 1524, 'persentase' => 29.0],
        ['tingkat' => 'Diploma/Sarjana', 'jumlah' => 716, 'persentase' => 13.6]
    ];
}

// Hitung persentase untuk pekerjaan
if (!empty($pekerjaan)) {
    $total_pekerjaan = array_sum(array_column($pekerjaan, 'jumlah'));
    foreach ($pekerjaan as &$job) {
        $job['persentase'] = $total_pekerjaan > 0 ? ($job['jumlah'] / $total_pekerjaan) * 100 : 0;
    }
    unset($job); // Unset reference
} else {
    $pekerjaan = [
        ['jenis' => 'Petani', 'jumlah' => 1245, 'persentase' => 23.7],
        ['jenis' => 'Pedagang', 'jumlah' => 856, 'persentase' => 16.3],
        ['jenis' => 'PNS/TNI/Polri', 'jumlah' => 324, 'persentase' => 6.2],
        ['jenis' => 'Karyawan Swasta', 'jumlah' => 1087, 'persentase' => 20.7],
        ['jenis' => 'Wiraswasta', 'jumlah' => 892, 'persentase' => 17.0],
        ['jenis' => 'Lainnya', 'jumlah' => 843, 'persentase' => 16.1]
    ];
}

// Siapkan data untuk grafik
$penduduk_labels = ['Laki-laki', 'Perempuan'];
$penduduk_data = [$data_penduduk['laki_laki'], $data_penduduk['perempuan']];
$penduduk_colors = ['#C62828', '#FFD54F'];

$pendidikan_labels = array_column($pendidikan, 'tingkat');
$pendidikan_data = array_column($pendidikan, 'jumlah');
$pendidikan_colors = ['#C62828', '#FF9800', '#4CAF50', '#2196F3', '#9C27B0'];

$pekerjaan_labels = array_column($pekerjaan, 'jenis');
$pekerjaan_data = array_column($pekerjaan, 'jumlah');
$pekerjaan_colors = ['#C62828', '#FF9800', '#4CAF50', '#2196F3', '#9C27B0', '#607D8B'];
?>
