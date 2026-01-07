<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data profil desa
try {
    $profil = $pdo->query("SELECT * FROM profil WHERE id = 1")->fetch();
} catch (PDOException $e) {
    $profil = null;
}

// Data fallback
$profil_data = $profil ?: [
    'nama_desa' => 'Desa Pinabetengan Selatan',
    'sejarah' => 'Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan". Nama Pinabetengan berasal dari bahasa Minahasa kuno yang berarti "tempat bermusyawarah".

Desa ini didirikan pada awal abad ke-19 oleh sekelompok masyarakat Minahasa yang mencari tempat tinggal baru. Mereka adalah keturunan dari para leluhur yang bermukim di sekitar situs Watu Pinawetengan, sebuah batu besar yang menjadi tempat musyawarah penting dalam sejarah Minahasa.

Pada tahun 1920-an, desa ini mulai berkembang pesat dengan dibukanya lahan pertanian baru dan didirikannya sekolah pertama. Masyarakat desa hidup dari bertani jagung, ubi, dan sayuran, serta beternak ayam dan babi.

Selama masa penjajahan Belanda dan Jepang, masyarakat Pinabetengan Selatan dikenal dengan semangat perlawanannya. Banyak pemuda desa yang bergabung dengan gerakan perlawanan untuk mempertahankan tanah air.

Setelah Indonesia merdeka, desa ini terus berkembang dengan pembangunan infrastruktur seperti jalan, jembatan, dan fasilitas umum. Pada tahun 1985, dibangunlah balai desa yang menjadi pusat kegiatan masyarakat.

Di era modern, Pinabetengan Selatan terus melestarikan budaya dan tradisi Minahasa sambil mengembangkan potensi ekonomi melalui pertanian organik dan pariwisata budaya. Situs Watu Pinawetengan yang terletak tidak jauh dari desa menjadi daya tarik wisata yang penting.

Hingga kini, Desa Pinabetengan Selatan tetap mempertahankan nilai-nilai kearifan lokal dan semangat gotong royong yang menjadi ciri khas masyarakat Minahasa.',
    'gambar_sejarah' => ''
];
?>
