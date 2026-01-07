<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data potensi dengan error handling
try {
    $potensi = $pdo->query("SELECT * FROM potensi WHERE status = 'active' ORDER BY urutan")->fetchAll();
} catch (PDOException $e) {
    $potensi = [];
}

// Data fallback jika tidak ada data dari database
if (empty($potensi)) {
    $potensi = [
        [
            'id' => 1,
            'nama' => 'Watu Pinawetengan',
            'jenis' => 'Wisata Budaya & Sejarah',
            'deskripsi' => 'Situs megalitikum bersejarah yang menjadi cikal bakal peradaban Minahasa.',
            'icon' => 'monument',
            'fasilitas' => ['Area Parkir', 'Mushola', 'Warung Makan', 'Guide Lokal'],
            'aktivitas' => ['Wisata Sejarah', 'Foto Budaya', 'Studi Arkeologi'],
            'lokasi' => 'Desa Pinabetengan Selatan',
            'jam_operasional' => '08:00 - 17:00 WITA',
            'tiket' => 'Rp 10.000',
            'kontak' => '6281234567890',
            'rating' => 4.8,
            'highlight' => true
        ],
        [
            'id' => 2,
            'nama' => 'Produk Pangsit Jagung UMKM',
            'jenis' => 'Kuliner & Kerajinan',
            'deskripsi' => 'Pangsit jagung khas desa yang dibuat dari jagung lokal dengan resep turun-temurun.',
            'icon' => 'cookie-bite',
            'fasilitas' => ['Showroom Produk', 'Area Produksi', 'Packaging Higienis'],
            'aktivitas' => ['Beli Produk', 'Workshop Pembuatan', 'Foto Produk'],
            'lokasi' => 'Sentra UMKM Desa',
            'jam_operasional' => '07:00 - 20:00 WITA',
            'harga' => 'Rp 25.000 - Rp 50.000',
            'kontak' => '6281345678901',
            'rating' => 4.9,
            'highlight' => true
        ],
        [
            'id' => 3,
            'nama' => 'Bendang Stable',
            'jenis' => 'Wisata Edukasi & Peternakan',
            'deskripsi' => 'Peternakan kuda tradisional yang menawarkan pengalaman wisata edukasi.',
            'icon' => 'horse',
            'fasilitas' => ['Area Berkuda', 'Kandang Kuda', 'Pemandu Wisata', 'Kafe'],
            'aktivitas' => ['Berkuda', 'Edukasi Peternakan', 'Foto dengan Kuda'],
            'lokasi' => 'Dusun Bendang',
            'jam_operasional' => '06:00 - 18:00 WITA',
            'tiket' => 'Rp 75.000',
            'kontak' => '6281456789012',
            'rating' => 4.7,
            'highlight' => true
        ]
    ];
} else {
    // Format data dari database untuk match dengan struktur yang diharapkan frontend
    $formatted_potensi = [];
    foreach ($potensi as $item) {
        $formatted_potensi[] = [
            'id' => $item['id'],
            'nama' => $item['nama_potensi'],
            'jenis' => 'Wisata Desa', // Default value
            'deskripsi' => $item['deskripsi'],
            'icon' => str_replace('fas fa-', '', $item['icon']), // Remove fas fa- prefix
            'fasilitas' => ['Fasilitas Tersedia'], // Default value
            'aktivitas' => ['Aktivitas Tersedia'], // Default value
            'lokasi' => 'Desa Pinabetengan Selatan', // Default value
            'jam_operasional' => '08:00 - 17:00 WITA', // Default value
            'tiket' => 'Hubungi untuk info', // Default value
            'kontak' => '6281234567890', // Default value
            'rating' => 4.5, // Default value
            'highlight' => true // Default value
        ];
    }
    $potensi = $formatted_potensi;
}
?>
