<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';

// Ambil data profil desa dengan error handling
try {
    $profil = $pdo->query("SELECT * FROM profil WHERE id = 1")->fetch();
} catch (PDOException $e) {
    $profil = null;
}

// Ambil data kepala desa / pejabat dengan error handling
try {
    $pejabat = $pdo->query("SELECT * FROM pejabat ORDER BY periode_mulai DESC")->fetchAll();
} catch (PDOException $e) {
    $pejabat = [];
}

// Data fallback - HANYA JIKA data tidak ada di database
$profil_data = $profil ?: [
    'nama_desa' => 'Desa Pinabetengan Selatan',
    'tentang' => 'Desa Pinabetengan Selatan adalah sebuah desa yang terletak di Kecamatan Tompaso Baru, Kabupaten Minahasa Selatan, Provinsi Sulawesi Utara. Desa ini memiliki luas wilayah sekitar 450 hektar dengan topografi yang beragam, mulai dari dataran rendah hingga perbukitan.',
    'jumlah_penduduk' => 2500,
    'visi' => 'Terwujudnya Desa Pinabetengan Selatan yang Maju, Mandiri, Sejahtera, dan Berbudaya yang berlandaskan pada nilai-nilai keagamaan dan kearifan lokal.',
    'misi' => 'Meningkatkan kualitas SDM melalui pendidikan dan pelatihan, mengembangkan potensi ekonomi lokal, melestarikan budaya dan tradisi, meningkatkan infrastruktur desa, dan mewujudkan pemerintahan yang bersih dan transparan.',
    'gambar_desa' => '',
    'sejarah' => 'Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan".'
];

// Data fallback untuk pejabat - HANYA JIKA tidak ada data di database
if (empty($pejabat)) {
    $pejabat = [
        [
            'nama' => 'Johanis Tumimomor, S.Sos',
            'jabatan' => 'Kepala Desa',
            'periode_mulai' => '2021-01-01',
            'periode_selesai' => null,
            'foto' => ''
        ],
        [
            'nama' => 'Drs. Markus Kansil',
            'jabatan' => 'Kepala Desa',
            'periode_mulai' => '2015-01-01',
            'periode_selesai' => '2020-12-31',
            'foto' => ''
        ],
        [
            'nama' => 'Alexander Rondonuwu',
            'jabatan' => 'Kepala Desa',
            'periode_mulai' => '2009-01-01',
            'periode_selesai' => '2014-12-31',
            'foto' => ''
        ]
    ];
}
?>
