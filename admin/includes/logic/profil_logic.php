<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Konfigurasi upload
$upload_dir = '../uploads/';
$pejabat_dir = $upload_dir . 'pejabat/';
$desa_dir = $upload_dir . 'desa/';

// Buat folder jika belum ada
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
if (!is_dir($pejabat_dir)) mkdir($pejabat_dir, 0755, true);
if (!is_dir($desa_dir)) mkdir($desa_dir, 0755, true);

$allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$max_size = 2 * 1024 * 1024; // 2MB

// Fungsi optimasi gambar
function optimizeImage($source_path, $destination_path, $max_width = 1200, $quality = 80) {
    // Cek jika ekstensi GD terinstall
    if (!extension_loaded('gd')) {
        // Jika GD tidak tersedia, copy file asli
        return copy($source_path, $destination_path);
    }

    $info = getimagesize($source_path);
    if (!$info) return false;

    list($width, $height, $type) = $info;

    // Calculate new dimensions
    if ($width > $max_width) {
        $new_width = $max_width;
        $new_height = (int)($height * $max_width / $width);
    } else {
        $new_width = $width;
        $new_height = $height;
    }

    // Create image resource
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }

    if (!$source) return false;

    // Create new image
    $destination = imagecreatetruecolor($new_width, $new_height);

    // Preserve transparency for PNG and GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($destination, imagecolorallocatealpha($destination, 0, 0, 0, 127));
        imagealphablending($destination, false);
        imagesavealpha($destination, true);
    }

    // Resize image
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Save as WebP
    $result = imagewebp($destination, $destination_path, $quality);

    // Clean up
    imagedestroy($source);
    imagedestroy($destination);

    return $result;
}

// Fungsi buat thumbnail
function createThumbnail($source_path, $destination_path, $size = 300, $quality = 80) {
    return optimizeImage($source_path, $destination_path, $size, $quality);
}

// Initialize variables
$error_profil = '';
$error_kepala = '';
$success_profil = '';
$success_kepala = '';

// Handle update profil desa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profil_desa'])) {
        $nama_desa = trim($_POST['nama_desa']);
        $tentang = trim($_POST['tentang']);
        $jumlah_penduduk = intval($_POST['jumlah_penduduk']);
        $visi = trim($_POST['visi']);
        $misi = trim($_POST['misi']);
        $sejarah = trim($_POST['sejarah']);
        $gambar_desa_path = '';
        $gambar_sejarah_path = '';

        // Validasi input dasar
        if (empty($nama_desa) || empty($tentang) || empty($visi) || empty($misi)) {
            $error_profil = "Semua field wajib diisi!";
        } else {
            // Handle upload gambar desa
            if (isset($_FILES['gambar_desa']) && $_FILES['gambar_desa']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['gambar_desa'];
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_size = $file['size'];

                // Validasi file
                if (!in_array($file_extension, $allowed_types)) {
                    $error_profil = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau WebP.";
                } elseif ($file_size > $max_size) {
                    $error_profil = "Ukuran file terlalu besar. Maksimal 2MB.";
                } else {
                    // Generate unique filename
                    $base_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nama_desa);
                    $original_filename = $base_filename . '.' . $file_extension;
                    $webp_filename = $base_filename . '.webp';
                    $thumbnail_filename = $base_filename . '_thumb.webp';

                    $original_path = $desa_dir . $original_filename;
                    $optimized_path = $desa_dir . $webp_filename;
                    $thumbnail_path = $desa_dir . $thumbnail_filename;

                    if (move_uploaded_file($file['tmp_name'], $original_path)) {
                        // Optimize main image to WebP
                        if (optimizeImage($original_path, $optimized_path, 1200, 80)) {
                            $gambar_desa_path = 'uploads/desa/' . $webp_filename;

                            // Create thumbnail
                            createThumbnail($original_path, $thumbnail_path, 400, 75);

                            // Delete original if not WebP
                            if ($file_extension !== 'webp' && file_exists($original_path)) {
                                unlink($original_path);
                            }
                        } else {
                            // Jika optimasi gagal, gunakan file original
                            $gambar_desa_path = 'uploads/desa/' . $original_filename;
                        }
                    } else {
                        $error_profil = "Gagal mengupload file.";
                    }
                }
            }

            // Handle upload gambar sejarah
            if (isset($_FILES['gambar_sejarah']) && $_FILES['gambar_sejarah']['error'] === UPLOAD_ERR_OK && empty($error_profil)) {
                $file = $_FILES['gambar_sejarah'];
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_size = $file['size'];

                // Validasi file
                if (!in_array($file_extension, $allowed_types)) {
                    $error_profil = "Format file sejarah tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau WebP.";
                } elseif ($file_size > $max_size) {
                    $error_profil = "Ukuran file sejarah terlalu besar. Maksimal 2MB.";
                } else {
                    // Generate unique filename untuk sejarah
                    $base_filename = uniqid() . '_sejarah_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nama_desa);
                    $original_filename = $base_filename . '.' . $file_extension;
                    $webp_filename = $base_filename . '.webp';
                    $thumbnail_filename = $base_filename . '_thumb.webp';

                    $original_path = $desa_dir . $original_filename;
                    $optimized_path = $desa_dir . $webp_filename;
                    $thumbnail_path = $desa_dir . $thumbnail_filename;

                    if (move_uploaded_file($file['tmp_name'], $original_path)) {
                        // Optimize main image to WebP
                        if (optimizeImage($original_path, $optimized_path, 1200, 80)) {
                            $gambar_sejarah_path = 'uploads/desa/' . $webp_filename;

                            // Create thumbnail
                            createThumbnail($original_path, $thumbnail_path, 400, 75);

                            // Delete original if not WebP
                            if ($file_extension !== 'webp' && file_exists($original_path)) {
                                unlink($original_path);
                            }
                        } else {
                            // Jika optimasi gagal, gunakan file original
                            $gambar_sejarah_path = 'uploads/desa/' . $original_filename;
                        }
                    } else {
                        $error_profil = "Gagal mengupload file sejarah.";
                    }
                }
            }

            if (empty($error_profil)) {
                try {
                    // Buat tabel jika belum ada
                    $pdo->query("CREATE TABLE IF NOT EXISTS profil (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        nama_desa VARCHAR(255),
                        tentang TEXT,
                        jumlah_penduduk INT,
                        visi TEXT,
                        misi TEXT,
                        sejarah TEXT,
                        gambar_sejarah VARCHAR(255),
                        gambar_desa VARCHAR(255),
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )");

                    // Cek apakah ada data profil sebelumnya
                    $old_profil = $pdo->query("SELECT gambar_desa, gambar_sejarah FROM profil WHERE id = 1")->fetch();

                    // Jika ada gambar baru, hapus gambar lama
                    if ($gambar_desa_path && $old_profil && !empty($old_profil['gambar_desa'])) {
                        $old_path = '../' . $old_profil['gambar_desa'];
                        if (file_exists($old_path)) {
                            unlink($old_path);
                            // Juga hapus thumbnail jika ada
                            $thumb_path = str_replace('.webp', '_thumb.webp', $old_path);
                            if (file_exists($thumb_path)) {
                                unlink($thumb_path);
                            }
                        }
                    }

                    if ($gambar_sejarah_path && $old_profil && !empty($old_profil['gambar_sejarah'])) {
                        $old_sejarah_path = '../' . $old_profil['gambar_sejarah'];
                        if (file_exists($old_sejarah_path)) {
                            unlink($old_sejarah_path);
                            // Juga hapus thumbnail jika ada
                            $thumb_sejarah_path = str_replace('.webp', '_thumb.webp', $old_sejarah_path);
                            if (file_exists($thumb_sejarah_path)) {
                                unlink($thumb_sejarah_path);
                            }
                        }
                    }

                    // Build query berdasarkan gambar yang diupdate
                    if ($gambar_desa_path && $gambar_sejarah_path) {
                        $stmt = $pdo->prepare("INSERT INTO profil (id, nama_desa, tentang, jumlah_penduduk, visi, misi, sejarah, gambar_sejarah, gambar_desa)
                                             VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?)
                                             ON DUPLICATE KEY UPDATE
                                             nama_desa=?, tentang=?, jumlah_penduduk=?, visi=?, misi=?, sejarah=?, gambar_sejarah=?, gambar_desa=?");
                        $stmt->execute([
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah, $gambar_sejarah_path, $gambar_desa_path,
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah, $gambar_sejarah_path, $gambar_desa_path
                        ]);
                    } elseif ($gambar_desa_path) {
                        $stmt = $pdo->prepare("INSERT INTO profil (id, nama_desa, tentang, jumlah_penduduk, visi, misi, sejarah, gambar_desa)
                                             VALUES (1, ?, ?, ?, ?, ?, ?, ?)
                                             ON DUPLICATE KEY UPDATE
                                             nama_desa=?, tentang=?, jumlah_penduduk=?, visi=?, misi=?, sejarah=?, gambar_desa=?");
                        $stmt->execute([
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah, $gambar_desa_path,
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah, $gambar_desa_path
                        ]);
                    } elseif ($gambar_sejarah_path) {
                        $stmt = $pdo->prepare("INSERT INTO profil (id, nama_desa, tentang, jumlah_penduduk, visi, misi, sejarah, gambar_sejarah)
                                             VALUES (1, ?, ?, ?, ?, ?, ?, ?)
                                             ON DUPLICATE KEY UPDATE
                                             nama_desa=?, tentang=?, jumlah_penduduk=?, visi=?, misi=?, sejarah=?, gambar_sejarah=?");
                        $stmt->execute([
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah, $gambar_sejarah_path,
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah, $gambar_sejarah_path
                        ]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO profil (id, nama_desa, tentang, jumlah_penduduk, visi, misi, sejarah)
                                             VALUES (1, ?, ?, ?, ?, ?, ?)
                                             ON DUPLICATE KEY UPDATE
                                             nama_desa=?, tentang=?, jumlah_penduduk=?, visi=?, misi=?, sejarah=?");
                        $stmt->execute([
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah,
                            $nama_desa, $tentang, $jumlah_penduduk, $visi, $misi, $sejarah
                        ]);
                    }

                    $success_profil = "Profil desa berhasil diupdate!";

                } catch (Exception $e) {
                    $error_profil = "Error: " . $e->getMessage();
                    error_log("Profile update error: " . $e->getMessage());
                }
            }
        }
    }

    // Handle update kepala desa
    if (isset($_POST['update_kepala_desa'])) {
        $nama = trim($_POST['nama']);
        $jabatan = trim($_POST['jabatan']);
        $periode_mulai = $_POST['periode_mulai'];
        $periode_selesai = !empty($_POST['periode_selesai']) ? $_POST['periode_selesai'] : null;
        $foto_path = '';

        // Validasi input
        if (empty($nama) || empty($jabatan) || empty($periode_mulai)) {
            $error_kepala = "Nama, jabatan, dan periode mulai wajib diisi!";
        } else {
            // Handle file upload
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['foto'];
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_size = $file['size'];

                // Validasi file
                if (!in_array($file_extension, $allowed_types)) {
                    $error_kepala = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau WebP.";
                } elseif ($file_size > $max_size) {
                    $error_kepala = "Ukuran file terlalu besar. Maksimal 2MB.";
                } else {
                    // Generate unique filename
                    $base_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nama);
                    $original_filename = $base_filename . '.' . $file_extension;
                    $webp_filename = $base_filename . '.webp';
                    $thumbnail_filename = $base_filename . '_thumb.webp';

                    $original_path = $pejabat_dir . $original_filename;
                    $optimized_path = $pejabat_dir . $webp_filename;
                    $thumbnail_path = $pejabat_dir . $thumbnail_filename;

                    if (move_uploaded_file($file['tmp_name'], $original_path)) {
                        // Optimize to WebP
                        if (optimizeImage($original_path, $optimized_path, 800, 80)) {
                            $foto_path = 'uploads/pejabat/' . $webp_filename;

                            // Create thumbnail untuk tabel
                            createThumbnail($original_path, $thumbnail_path, 150, 75);

                            // Delete original if not WebP
                            if ($file_extension !== 'webp' && file_exists($original_path)) {
                                unlink($original_path);
                            }
                        } else {
                            $foto_path = 'uploads/pejabat/' . $original_filename;
                        }
                    } else {
                        $error_kepala = "Gagal mengupload file.";
                    }
                }
            }

            if (empty($error_kepala)) {
                try {
                    $pdo->query("CREATE TABLE IF NOT EXISTS pejabat (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        nama VARCHAR(255),
                        jabatan VARCHAR(100),
                        periode_mulai DATE,
                        periode_selesai DATE,
                        foto VARCHAR(255),
                        foto_thumb VARCHAR(255),
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )");

                    $foto_thumb = $foto_path ? str_replace('.webp', '_thumb.webp', $foto_path) : '';

                    $stmt = $pdo->prepare("INSERT INTO pejabat (nama, jabatan, periode_mulai, periode_selesai, foto, foto_thumb) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nama, $jabatan, $periode_mulai, $periode_selesai, $foto_path, $foto_thumb]);

                    $success_kepala = "Data kepala desa berhasil ditambahkan!";
                } catch (Exception $e) {
                    $error_kepala = "Error: " . $e->getMessage();
                    error_log("Pejabat insert error: " . $e->getMessage());
                }
            }
        }
    }

    // Handle delete kepala desa
    if (isset($_POST['delete_kepala_desa'])) {
        $id = intval($_POST['id']);

        try {
            // Hapus file foto jika ada
            $stmt = $pdo->prepare("SELECT foto, foto_thumb FROM pejabat WHERE id = ?");
            $stmt->execute([$id]);
            $pejabat = $stmt->fetch();

            if ($pejabat) {
                if (!empty($pejabat['foto'])) {
                    $file_path = '../' . $pejabat['foto'];
                    if (file_exists($file_path)) unlink($file_path);
                }
                if (!empty($pejabat['foto_thumb'])) {
                    $thumb_path = '../' . $pejabat['foto_thumb'];
                    if (file_exists($thumb_path)) unlink($thumb_path);
                }
            }

            $stmt = $pdo->prepare("DELETE FROM pejabat WHERE id = ?");
            $stmt->execute([$id]);

            $success_kepala = "Data kepala desa berhasil dihapus!";
        } catch (Exception $e) {
            $error_kepala = "Error: " . $e->getMessage();
            error_log("Pejabat delete error: " . $e->getMessage());
        }
    }
}

// Ambil data profil desa
try {
    $profil = $pdo->query("SELECT * FROM profil WHERE id = 1")->fetch();
} catch (Exception $e) {
    $profil = null;
    error_log("Profile fetch error: " . $e->getMessage());
}

// Ambil data kepala desa
try {
    $pejabat = $pdo->query("SELECT * FROM pejabat ORDER BY periode_mulai DESC")->fetchAll();
} catch (Exception $e) {
    $pejabat = [];
    error_log("Pejabat fetch error: " . $e->getMessage());
}

// Data fallback
$profil_data = $profil ?: [
    'nama_desa' => 'Desa Pinabetengan Selatan',
    'tentang' => 'Desa Pinabetengan Selatan adalah sebuah desa yang terletak di Kecamatan Tompaso Baru, Kabupaten Minahasa Selatan, Provinsi Sulawesi Utara. Desa ini memiliki luas wilayah sekitar 450 hektar dengan topografi yang beragam, mulai dari dataran rendah hingga perbukitan.',
    'jumlah_penduduk' => 2500,
    'visi' => 'Terwujudnya Desa Pinabetengan Selatan yang Maju, Mandiri, Sejahtera, dan Berbudaya yang berlandaskan pada nilai-nilai keagamaan dan kearifan lokal.',
    'misi' => 'Meningkatkan kualitas SDM melalui pendidikan dan pelatihan, mengembangkan potensi ekonomi lokal, melestarikan budaya dan tradisi, meningkatkan infrastruktur desa, dan mewujudkan pemerintahan yang bersih dan transparan.',
    'sejarah' => 'Desa Pinabetengan Selatan memiliki sejarah panjang yang berkaitan erat dengan peradaban Minahasa dan situs bersejarah "Watu Pinawetengan". Nama Pinabetengan berasal dari bahasa Minahasa kuno yang berarti "tempat bermusyawarah".',
    'gambar_sejarah' => '',
    'gambar_desa' => ''
];
?>
