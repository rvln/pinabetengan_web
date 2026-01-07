<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil parameter kategori dari URL
$kategori = $_GET['kategori'] ?? 'kegiatan';
$page_title = $kategori === 'pengumuman' ? 'Pengumuman Resmi' : 'Kegiatan & Program';

// Ambil data berita dari database berdasarkan kategori
try {
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE kategori = ? ORDER BY tanggal DESC, id DESC");
    $stmt->execute([$kategori]);
    $berita = $stmt->fetchAll();
} catch (PDOException $e) {
    $berita = [];
}

// Data fallback jika tidak ada berita di database
if (empty($berita)) {
    if ($kategori === 'kegiatan') {
        $berita = [
            [
                'id' => 1,
                'judul' => 'Festival Budaya Pinabetengan 2024 Sukses Digelar dengan Meriah',
                'isi' => 'Festival Budaya Pinabetengan 2024 berhasil diselenggarakan dengan meriah di lapangan desa. Acara yang dihadiri oleh ribuan pengunjung dari berbagai daerah ini menampilkan berbagai kesenian tradisional Minahasa, mulai dari tarian Maengket, musik Bambu, hingga pameran kuliner khas desa. Festival ini menjadi ajang pelestarian budaya sekaligus promosi wisata desa.',
                'tanggal' => '2024-03-15',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Admin Desa',
                'dibaca' => 1250
            ],
            [
                'id' => 2,
                'judul' => 'Pelatihan UMKM Tingkatkan Kualitas Produk Pangsit Jagung',
                'isi' => 'Pemerintah desa menyelenggarakan pelatihan peningkatan kualitas produk pangsit jagung bagi para pelaku UMKM. Pelatihan yang diikuti oleh 25 peserta dari berbagai dusun ini fokus pada teknik pengemasan, pemasaran digital, dan standar higienitas. Diharapkan produk pangsit jagung khas desa dapat bersaing di pasar yang lebih luas.',
                'tanggal' => '2024-03-10',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Bidang Ekonomi',
                'dibaca' => 890
            ],
            [
                'id' => 3,
                'judul' => 'Perbaikan Jalan Desa Selesai, Akses Transportasi Lebih Lancar',
                'isi' => 'Pekerjaan perbaikan jalan sepanjang 2 km di Desa Pinabetengan Selatan telah selesai dilaksanakan tepat waktu. Pembangunan menggunakan material berkualitas tinggi dengan sistem drainase yang baik. Hal ini akan memudahkan akses transportasi warga dan pengunjung menuju destinasi wisata desa.',
                'tanggal' => '2024-03-05',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Bidang PU',
                'dibaca' => 756
            ],
            [
                'id' => 4,
                'judul' => 'Bendang Stable Jadi Destinasi Wisata Edukasi Favorit',
                'isi' => 'Bendang Stable semakin populer sebagai destinasi wisata edukasi keluarga. Rata-rata dikunjungi 100 pengunjung per minggu yang ingin belajar tentang perawatan kuda tradisional Minahasa. Fasilitas terus ditingkatkan dengan penambahan area bermain anak dan kafe tradisional.',
                'tanggal' => '2024-02-28',
                'gambar' => '',
                'kategori' => 'kegiatan',
                'penulis' => 'Bidang Pariwisata',
                'dibaca' => 1120
            ]
        ];
    } else {
        $berita = [
            [
                'id' => 5,
                'judul' => 'Pengumuman Jadwal Pemadaman Listrik Bergilir',
                'isi' => 'Diberitahukan kepada seluruh warga Desa Pinabetengan Selatan bahwa akan dilakukan pemadaman listrik bergilir pada tanggal 20-22 Maret 2024 untuk perawatan jaringan listrik. Pemadaman akan dilakukan pukul 09.00-15.00 WITA secara bergilir sesuai zona.',
                'tanggal' => '2024-03-18',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'PLN Wilayah',
                'dibaca' => 1567
            ],
            [
                'id' => 6,
                'judul' => 'Pendaftaran Bantuan Sosial Tahap II Dibuka',
                'isi' => 'Pemerintah Desa membuka pendaftaran bantuan sosial tahap II untuk masyarakat kurang mampu. Pendaftaran dibuka mulai tanggal 25 Maret - 5 April 2024 di Kantor Desa. Syarat: Fotokopi KTP, KK, dan surat keterangan tidak mampu dari RT/RW.',
                'tanggal' => '2024-03-20',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'Bidang Sosial',
                'dibaca' => 934
            ],
            [
                'id' => 7,
                'judul' => 'Pengumuman Libur Nasional Hari Raya Nyepi',
                'isi' => 'Berdasarkan kalender nasional, Hari Raya Nyepi 2024 jatuh pada tanggal 11 Maret 2024. Seluruh aktivitas perkantoran desa akan diliburkan. Pelayanan darurat tetap dapat diakses melalui nomor hotline 24 jam.',
                'tanggal' => '2024-03-08',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'Sekretaris Desa',
                'dibaca' => 756
            ],
            [
                'id' => 8,
                'judul' => 'Pemberitahuan Pelaksanaan Kerja Bakti Bersama',
                'isi' => 'Dalam rangka menyambut Hari Kebersihan Nasional, akan diadakan kerja bakti bersama pada hari Minggu, 24 Maret 2024 pukul 07.00 WITA. Tempat kumpul: Lapangan Desa. Diharapkan partisipasi seluruh warga.',
                'tanggal' => '2024-03-22',
                'gambar' => '',
                'kategori' => 'pengumuman',
                'penulis' => 'Ketua RT',
                'dibaca' => 890
            ]
        ];
    }
}
?>
