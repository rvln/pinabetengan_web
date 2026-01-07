<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data layanan dengan error handling
try {
    $layanan = $pdo->query("SELECT * FROM layanan WHERE status = 'active' ORDER BY urutan")->fetchAll();
} catch (PDOException $e) {
    $layanan = [];
}

// Ambil data APBDes terbaru
try {
    $apbdes = $pdo->query("SELECT * FROM apbdes ORDER BY tahun DESC, created_at DESC LIMIT 1")->fetch();
} catch (PDOException $e) {
    $apbdes = null;
}

// Ambil data Rencana Kerja
try {
    $rencana_kerja = $pdo->query("SELECT * FROM rencana_kerja WHERE status = 'active' ORDER BY tahun DESC, created_at DESC")->fetchAll();
} catch (PDOException $e) {
    $rencana_kerja = [];
}

// Data fallback
if (empty($layanan)) {
    $layanan = [
        [
            'id' => 1,
            'nama' => 'Surat Keterangan Domisili',
            'deskripsi' => 'Pengurusan surat keterangan domisili untuk keperluan administrasi',
            'icon' => 'file-alt',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Foto 3x4', 'Surat Pengantar RT'],
            'waktu_proses' => '1-2 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 2,
            'nama' => 'Surat Keterangan Tidak Mampu',
            'deskripsi' => 'Surat keterangan tidak mampu untuk berbagai keperluan bantuan',
            'icon' => 'file-contract',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Surat Pengantar RT', 'Foto 3x4'],
            'waktu_proses' => '1-2 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 3,
            'nama' => 'Surat Keterangan Usaha',
            'deskripsi' => 'Pengurusan surat keterangan usaha untuk UMKM dan pedagang',
            'icon' => 'store',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Foto Usaha', 'Surat Pengantar RT'],
            'waktu_proses' => '2-3 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 4,
            'nama' => 'Surat Keterangan Kelahiran',
            'deskripsi' => 'Pengurusan surat keterangan kelahiran untuk administrasi kependudukan',
            'icon' => 'baby',
            'persyaratan' => ['Surat Keterangan Lahir dari Bidan/Rumah Sakit', 'KTP Orang Tua', 'Kartu Keluarga', 'Surat Nikah'],
            'waktu_proses' => '1 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 5,
            'nama' => 'Surat Keterangan Kematian',
            'deskripsi' => 'Pengurusan surat keterangan kematian untuk keperluan administrasi',
            'icon' => 'heartbeat',
            'persyaratan' => ['Surat Keterangan Kematian dari Dokter', 'KTP Alm', 'Kartu Keluarga', 'KTP Pelapor'],
            'waktu_proses' => '1 Hari Kerja',
            'biaya' => 'Gratis'
        ],
        [
            'id' => 6,
            'nama' => 'Bantuan Hukum',
            'deskripsi' => 'Konsultasi dan pendampingan hukum bagi masyarakat desa',
            'icon' => 'balance-scale',
            'persyaratan' => ['KTP Asli', 'Kartu Keluarga', 'Dokumen terkait permasalahan'],
            'waktu_proses' => 'Sesuai Jadwal',
            'biaya' => 'Gratis'
        ]
    ];
}

if (!$apbdes) {
    $apbdes = [
        'tahun' => '2024',
        'anggaran_pendapatan' => 2500000000,
        'anggaran_belanja' => 2450000000,
        'saldo_awal' => 500000000,
        'file_url' => '#'
    ];
}

if (empty($rencana_kerja)) {
    $rencana_kerja = [
        [
            'id' => 1,
            'judul' => 'Pembangunan Jalan Desa',
            'tahun' => '2024',
            'deskripsi' => 'Peningkatan dan perbaikan infrastruktur jalan desa sepanjang 2 km',
            'lokasi' => 'Seluruh Desa',
            'anggaran' => 500000000,
            'status' => 'Dalam Pengerjaan'
        ],
        [
            'id' => 2,
            'judul' => 'Program Pemberdayaan UMKM',
            'tahun' => '2024',
            'deskripsi' => 'Pelatihan dan pendampingan untuk pengusaha mikro dan kecil',
            'lokasi' => 'Balai Desa',
            'anggaran' => 150000000,
            'status' => 'Perencanaan'
        ],
        [
            'id' => 3,
            'judul' => 'Rehabilitasi Saluran Irigasi',
            'tahun' => '2024',
            'deskripsi' => 'Perbaikan saluran irigasi untuk mendukung pertanian',
            'lokasi' => 'Area Persawahan',
            'anggaran' => 300000000,
            'status' => 'Selesai'
        ]
    ];
}
?>
