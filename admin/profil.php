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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil Desa - Admin</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    /* ========== NATURE COLOR PALETTE ========== */
    :root {
        /* Primary - Putih Gading (Off-white/Ivory) */
        --ivory: #F5F3EF;
        --ivory-light: #FAFAF8;
        --ivory-dark: #E8E4DC;
        
        /* Secondary - Merah */
        --red: #C62828;
        --red-dark: #B71C1C;
        --red-light: #E53935;
        
        /* Accent - Kuning Jagung */
        --yellow: #FFD54F;
        --yellow-dark: #FFC107;
        --yellow-light: #FFECB3;
        
        /* Text - Hitam (ALL TEXT) */
        --black: #2C2C2C;
        --black-light: #4A4A4A;
        --grey: #6B6B6B;
        
        /* Nature accents */
        --green-leaf: #7CB342;
        --green-dark: #558B2F;
        --brown-earth: #8D6E63;
        --sky-blue: #64B5F6;
        --cream: #FFF9E6;
        
        /* Effects */
        --shadow-soft: 0 4px 20px rgba(44, 44, 44, 0.08);
        --shadow-medium: 0 8px 32px rgba(44, 44, 44, 0.12);
        --shadow-strong: 0 12px 48px rgba(44, 44, 44, 0.15);
        --shadow-glow: 0 0 40px rgba(255, 213, 79, 0.3);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ========== DARK MODE ========== */
    body.dark-mode {
        --ivory: #1a1a1a;
        --ivory-light: #1a1a1a;
        --ivory-dark: #151515;
        --black: #FFFFFF;
        --black-light: #E0E0E0;
        --grey: #B0B0B0;
        --cream: #1a1a1a;
        --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.5);
        --shadow-medium: 0 8px 32px rgba(0, 0, 0, 0.6);
        --shadow-strong: 0 12px 48px rgba(0, 0, 0, 0.7);
    }

    /* ========== GLOBAL STYLES ========== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--ivory) 0%, var(--cream) 50%, var(--ivory-light) 100%);
        color: var(--black);
        line-height: 1.7;
        min-height: 100vh;
        transition: background-color 0.4s ease, color 0.4s ease;
    }

    body.dark-mode {
        background: linear-gradient(135deg, #1a1a1a 0%, #151515 50%, #1a1a1a 100%);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        color: var(--black);
        font-weight: 700;
    }

    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode h3,
    body.dark-mode h4,
    body.dark-mode h5,
    body.dark-mode h6 {
        color: #FFFFFF;
    }

    /* ========== HEADER STYLES ========== */
    .dashboard-header {
        background: rgba(245, 243, 239, 0.95);
        backdrop-filter: blur(15px);
        padding: 1.5rem 0;
        box-shadow: var(--shadow-soft);
        border-bottom: 2px solid var(--yellow);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    body.dark-mode .dashboard-header {
        background: rgba(20, 20, 20, 0.95);
        border-bottom-color: rgba(255, 213, 79, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .brand-icon {
        font-size: 2rem;
        color: var(--green-leaf);
    }

    body.dark-mode .brand-icon {
        color: var(--yellow);
    }

    .brand-text h1 {
        font-size: 1.5rem;
        margin-bottom: 0.2rem;
    }

    .brand-text p {
        color: var(--grey);
        font-size: 0.9rem;
    }

    body.dark-mode .brand-text p {
        color: #B0B0B0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    /* ========== MAIN CONTENT ========== */
    .dashboard-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* ========== CARD STYLES ========== */
    .card {
        background: white;
        padding: 2.5rem;
        border-radius: 25px;
        box-shadow: var(--shadow-soft);
        border: 2px solid transparent;
        transition: var(--transition);
        margin-bottom: 2rem;
    }

    body.dark-mode .card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
        border-color: var(--yellow);
    }

    .card-title {
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: var(--black);
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--yellow);
    }

    body.dark-mode .card-title {
        color: #FFFFFF;
    }

    /* ========== BUTTON STYLES ========== */
    .btn-logout {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-logout:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(198, 40, 40, 0.4);
        color: var(--ivory);
    }

    .btn-secondary {
        background: transparent;
        color: var(--red);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        border: 2px solid var(--red);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: var(--red);
        color: var(--ivory);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 500;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333, #dc3545);
        transform: translateY(-2px);
    }

    .btn-theme-toggle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--yellow);
        border: none;
        color: var(--black);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        cursor: pointer;
    }

    .btn-theme-toggle:hover {
        background: var(--yellow-dark);
        transform: scale(1.1) rotate(180deg);
        box-shadow: 0 6px 20px rgba(255, 213, 79, 0.4);
    }

    body.dark-mode .btn-theme-toggle {
        background: var(--yellow-dark);
        color: var(--ivory);
    }

    body.dark-mode .btn-theme-toggle:hover {
        background: var(--yellow);
        color: var(--black);
    }

    /* ========== FORM STYLES ========== */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.8rem;
        color: var(--black);
        font-weight: 600;
        font-size: 1rem;
    }

    body.dark-mode .form-label {
        color: #E0E0E0;
    }

    .form-control {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px solid var(--ivory-dark);
        border-radius: 15px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
        color: var(--black);
        font-family: 'Inter', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.2);
        transform: translateY(-2px);
    }

    body.dark-mode .form-control {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.2);
        color: #E0E0E0;
    }

    body.dark-mode .form-control:focus {
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.3);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
        line-height: 1.6;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px dashed var(--ivory-dark);
        border-radius: 15px;
        background: var(--ivory-light);
        cursor: pointer;
        transition: var(--transition);
    }

    .file-input:hover {
        border-color: var(--yellow);
        background: var(--yellow-light);
    }

    .file-input::file-selector-button {
        background: var(--red);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        margin-right: 1rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .file-input::file-selector-button:hover {
        background: var(--red-dark);
    }

    .file-info {
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: var(--grey);
    }

    .current-image {
        margin-top: 1rem;
        text-align: center;
    }

    .current-image img {
        max-width: 300px;
        max-height: 200px;
        border-radius: 10px;
        box-shadow: var(--shadow-soft);
        border: 2px solid var(--yellow);
    }

    /* ========== TABLE STYLES ========== */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .data-table th,
    .data-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--ivory-dark);
        vertical-align: middle;
    }

    .data-table th {
        background: var(--yellow-light);
        color: var(--black);
        font-weight: 600;
    }

    body.dark-mode .data-table th {
        background: rgba(255, 213, 79, 0.2);
        color: var(--yellow);
    }

    .data-table tr:hover {
        background: var(--ivory-light);
    }

    body.dark-mode .data-table tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .photo-thumbnail {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--yellow);
    }

    /* ========== ALERT STYLES ========== */
    .alert {
        border-radius: 15px;
        border: none;
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    body.dark-mode .alert-success {
        background: rgba(21, 87, 36, 0.2);
        color: #d4edda;
        border-left-color: #28a745;
    }

    body.dark-mode .alert-error {
        background: rgba(114, 28, 36, 0.2);
        color: #f8d7da;
        border-left-color: #dc3545;
    }

    /* ========== PREVIEW SECTION ========== */
    .preview-section {
        background: var(--ivory-light);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        border: 2px dashed var(--yellow);
        transition: var(--transition);
    }

    body.dark-mode .preview-section {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.3);
    }

    .preview-title {
        font-size: 1.2rem;
        color: var(--black);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--yellow);
    }

    body.dark-mode .preview-title {
        color: #FFFFFF;
    }

    .preview-content {
        line-height: 1.8;
    }

    .preview-item {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        border-left: 4px solid var(--red);
    }

    body.dark-mode .preview-item {
        background: rgba(255, 255, 255, 0.05);
        border-left-color: var(--yellow);
    }

    .preview-item h4 {
        color: var(--red);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    body.dark-mode .preview-item h4 {
        color: var(--yellow);
    }

    .preview-item p {
        color: var(--black-light);
        margin: 0;
    }

    body.dark-mode .preview-item p {
        color: #B0B0B0;
    }

    /* ========== GRID SYSTEM ========== */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -1rem;
    }

    .col-12, .col-md-6, .col-lg-4, .col-lg-3 {
        padding: 0 1rem;
    }

    .col-12 { width: 100%; }
    .col-md-6 { width: 50%; }
    .col-lg-4 { width: 33.333%; }
    .col-lg-3 { width: 25%; }

    /* ========== IMAGE PREVIEW STYLES ========== */
    .image-preview {
        margin-top: 1rem;
        text-align: center;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 15px;
        box-shadow: var(--shadow-soft);
        border: 3px solid var(--yellow);
        transition: var(--transition);
    }

    .image-preview img:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-medium);
    }

    /* ========== OFFICIAL CARD STYLES ========== */
    .official-card-preview {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        margin-bottom: 1rem;
    }

    .official-card-preview:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }

    .official-photo-preview {
        height: 200px;
        background: linear-gradient(135deg, var(--red), var(--yellow));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        overflow: hidden;
    }

    .official-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .official-photo-preview:hover img {
        transform: scale(1.05);
    }

    .official-info-preview {
        padding: 1.5rem;
        text-align: center;
    }

    .official-name-preview {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--black);
    }

    .official-position-preview {
        color: var(--red);
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .official-period-preview {
        color: var(--grey);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .dashboard-main {
            padding: 1rem;
        }

        .card {
            padding: 2rem;
        }

        .col-md-6, .col-lg-4, .col-lg-3 {
            width: 100%;
        }

        .header-actions {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .current-image img {
            max-width: 100%;
        }
    }

    /* ========== SCROLLBAR ========== */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--ivory);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--red);
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--red-dark);
    }

    body.dark-mode::-webkit-scrollbar-track {
        background: var(--ivory-dark);
    }

    body.dark-mode::-webkit-scrollbar-thumb {
        background: var(--yellow);
    }

    body.dark-mode::-webkit-scrollbar-thumb:hover {
        background: var(--yellow-dark);
    }

    /* ========== THEME TRANSITION ========== */
    body, 
    .dashboard-header,
    .card,
    .btn-logout,
    .btn-theme-toggle {
        transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease;
    }
    </style>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="dashboard-header">
    <div class="header-content">
        <div class="brand">
            <i class="fas fa-landmark brand-icon"></i>
            <div class="brand-text">
                <h1>Kelola Profil Desa</h1>
                <p>Admin <?= htmlspecialchars($profil_data['nama_desa']) ?></p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
            <a href="dashboard.php" class="btn-logout">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
</header>

<!-- ========== MAIN CONTENT ========== -->
<main class="dashboard-main">
    <div class="container-fluid">
        <!-- Profil Desa Section -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-edit"></i>
                Edit Profil Desa
            </h2>
            
            <?php if (!empty($success_profil)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success_profil) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_profil)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error_profil) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Desa</label>
                            <input type="text" name="nama_desa" class="form-control" 
                                   value="<?= htmlspecialchars($profil_data['nama_desa']) ?>" 
                                   placeholder="Masukkan nama desa" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Jumlah Penduduk</label>
                            <input type="number" name="jumlah_penduduk" class="form-control" 
                                   value="<?= htmlspecialchars($profil_data['jumlah_penduduk']) ?>" 
                                   placeholder="Masukkan jumlah penduduk" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Gambar Desa</label>
                    <input type="file" name="gambar_desa" class="file-input" 
                           accept=".jpg,.jpeg,.png,.gif,.webp">
                    <div class="file-info">
                        <small>Format: JPG, PNG, GIF, WebP (Max: 2MB) - Direkomendasikan: WebP untuk ukuran lebih kecil</small>
                    </div>
                    
                    <?php if (!empty($profil_data['gambar_desa'])): ?>
                        <div class="current-image">
                            <p><strong>Gambar Saat Ini:</strong></p>
                            <img src="../<?= htmlspecialchars($profil_data['gambar_desa']) ?>" 
                                 alt="Gambar Desa <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                 onerror="this.style.display='none'">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tentang Desa</label>
                    <textarea name="tentang" class="form-control form-textarea" 
                              placeholder="Tuliskan deskripsi tentang desa..." 
                              rows="6" required><?= htmlspecialchars($profil_data['tentang']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Visi Desa</label>
                    <textarea name="visi" class="form-control form-textarea" 
                              placeholder="Tuliskan visi desa..." 
                              rows="4" required><?= htmlspecialchars($profil_data['visi']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Misi Desa</label>
                    <textarea name="misi" class="form-control form-textarea" 
                              placeholder="Tuliskan misi desa..." 
                              rows="4" required><?= htmlspecialchars($profil_data['misi']) ?></textarea>
                </div>

                <!-- Field Sejarah dengan Upload Foto -->
                <div class="form-group">
                    <label class="form-label">Sejarah Desa</label>
                    
                    <!-- Input Upload Foto Sejarah -->
                    <div class="mb-3">
                        <label class="form-label">Foto Sejarah Desa</label>
                        <input type="file" name="gambar_sejarah" class="file-input" 
                               accept=".jpg,.jpeg,.png,.gif,.webp">
                        <div class="file-info">
                            <small>Format: JPG, PNG, GIF, WebP (Max: 2MB) - Gambar yang mendukung konten sejarah</small>
                        </div>
                        
                        <?php if (!empty($profil_data['gambar_sejarah'])): ?>
                            <div class="current-image mt-2">
                                <p><strong>Foto Sejarah Saat Ini:</strong></p>
                                <img src="../<?= htmlspecialchars($profil_data['gambar_sejarah']) ?>" 
                                     alt="Foto Sejarah <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                     style="max-width: 300px; max-height: 200px; border-radius: 10px; border: 2px solid var(--yellow);"
                                     onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <textarea name="sejarah" class="form-control form-textarea" 
                              placeholder="Tuliskan sejarah lengkap desa..." 
                              rows="8"><?= htmlspecialchars($profil_data['sejarah']) ?></textarea>
                    <div class="file-info">
                        <small>Sejarah ini akan ditampilkan di halaman "Sejarah Desa" dan ringkasan di halaman profil utama.</small>
                    </div>
                </div>
                
                <button type="submit" name="update_profil_desa" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Update Profil Desa
                </button>
            </form>

            <!-- Preview Profil Desa -->
            <div class="preview-section">
                <h3 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview Profil Desa
                </h3>
                
                <div class="preview-content">
                    <?php if (!empty($profil_data['gambar_desa'])): ?>
                        <div class="image-preview">
                            <img src="../<?= htmlspecialchars($profil_data['gambar_desa']) ?>" 
                                 alt="Gambar Desa <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                 onerror="this.style.display='none'">
                        </div>
                    <?php endif; ?>
                    
                    <div class="preview-item">
                        <h4><i class="fas fa-building"></i> Tentang Desa</h4>
                        <p><?= nl2br(htmlspecialchars($profil_data['tentang'])) ?></p>
                    </div>
                    
                    <div class="preview-item">
                        <h4><i class="fas fa-bullseye"></i> Visi Desa</h4>
                        <p><?= nl2br(htmlspecialchars($profil_data['visi'])) ?></p>
                    </div>
                    
                    <div class="preview-item">
                        <h4><i class="fas fa-tasks"></i> Misi Desa</h4>
                        <p><?= nl2br(htmlspecialchars($profil_data['misi'])) ?></p>
                    </div>

                    <!-- Preview Sejarah dengan Foto -->
                    <div class="preview-item">
                        <h4><i class="fas fa-history"></i> Sejarah Desa</h4>
                        
                        <?php if (!empty($profil_data['gambar_sejarah'])): ?>
                            <div class="image-preview mb-3">
                                <img src="../<?= htmlspecialchars($profil_data['gambar_sejarah']) ?>" 
                                     alt="Foto Sejarah <?= htmlspecialchars($profil_data['nama_desa']) ?>"
                                     style="max-width: 100%; max-height: 300px; border-radius: 10px; border: 2px solid var(--yellow);"
                                     onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>
                        
                        <p><?= nl2br(htmlspecialchars($profil_data['sejarah'])) ?></p>
                        <div class="file-info mt-2">
                            <small><i class="fas fa-info-circle"></i> Ini adalah preview ringkasan. Untuk melihat sejarah lengkap, buka halaman <strong>detail_sejarah.php</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelola Kepala Desa Section -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-user-tie"></i>
                Kelola Kepala Desa
            </h2>
            
            <?php if (!empty($success_kepala)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success_kepala) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_kepala)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error_kepala) ?>
                </div>
            <?php endif; ?>

            <!-- Form Tambah Kepala Desa -->
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" 
                                   placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" 
                                   value="Kepala Desa" 
                                   placeholder="Masukkan jabatan" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Periode Mulai</label>
                            <input type="date" name="periode_mulai" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Periode Selesai</label>
                            <input type="date" name="periode_selesai" class="form-control" 
                                   placeholder="Kosongkan jika masih menjabat">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="file-input" 
                                   accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="file-info">
                                <small>Format: JPG, PNG, GIF, WebP (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="update_kepala_desa" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Kepala Desa
                </button>
            </form>

            <!-- Daftar Kepala Desa -->
            <h3 class="preview-title">
                <i class="fas fa-list"></i>
                Daftar Kepala Desa
            </h3>
            
            <?php if (!empty($pejabat)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pejabat as $p): ?>
                            <tr>
                                <td>
                                    <?php if(!empty($p['foto_thumb'])): ?>
                                        <img src="../<?= htmlspecialchars($p['foto_thumb']) ?>" 
                                             alt="<?= htmlspecialchars($p['nama']) ?>" 
                                             class="photo-thumbnail"
                                             onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; border-radius: 50%; background: #f2f2f2; display: flex; align-items: center; justify-content: center; color: #999;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td><?= htmlspecialchars($p['jabatan']) ?></td>
                                <td>
                                    <?= date('Y', strtotime($p['periode_mulai'])) ?> - 
                                    <?= $p['periode_selesai'] ? date('Y', strtotime($p['periode_selesai'])) : 'Sekarang' ?>
                                </td>
                                <td>
                                    <span style="color: <?= $p['periode_selesai'] ? '#6c757d' : '#28a745' ?>; font-weight: 500;">
                                        <?= $p['periode_selesai'] ? 'Selesai' : 'Aktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" name="delete_kepala_desa" class="btn-danger" 
                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="preview-section text-center">
                    <i class="fas fa-user-tie" style="font-size: 3rem; color: var(--grey); margin-bottom: 1rem;"></i>
                    <p style="color: var(--grey);">Belum ada data kepala desa.</p>
                </div>
            <?php endif; ?>

            <!-- Preview Kepala Desa -->
            <div class="preview-section">
                <h3 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Preview di Halaman Publik
                </h3>
                
                <div class="row">
                    <?php foreach(array_slice($pejabat, 0, 4) as $p): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="official-card-preview">
                                <div class="official-photo-preview">
                                    <?php if(!empty($p['foto'])): ?>
                                        <img src="../<?= htmlspecialchars($p['foto']) ?>" 
                                             alt="<?= htmlspecialchars($p['nama']) ?>"
                                             loading="lazy"
                                             onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <i class="fas fa-user-tie"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="official-info-preview">
                                    <h4 class="official-name-preview"><?= htmlspecialchars($p['nama']) ?></h4>
                                    <p class="official-position-preview"><?= htmlspecialchars($p['jabatan']) ?></p>
                                    <p class="official-period-preview">
                                        <i class="fas fa-calendar"></i>
                                        <?= date('Y', strtotime($p['periode_mulai'])) ?> - <?= $p['periode_selesai'] ? date('Y', strtotime($p['periode_selesai'])) : 'Sekarang' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ========== JAVASCRIPT ========== -->
<script>
// Theme Toggle Functionality
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle.querySelector('i');
const body = document.body;

function applyTheme(theme) {
    if (theme === 'dark') {
        body.classList.add('dark-mode');
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
    } else {
        body.classList.remove('dark-mode');
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
    }
    localStorage.setItem('theme', theme);
}

// Check saved theme
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
    applyTheme(savedTheme);
} else if (systemPrefersDark) {
    applyTheme('dark');
} else {
    applyTheme('light');
}

// Theme toggle event
themeToggle.addEventListener('click', () => {
    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
    
    themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
        themeToggle.style.transform = '';
    }, 300);
});

// Listen to system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
        const newTheme = e.matches ? 'dark' : 'light';
        applyTheme(newTheme);
    }
});

// Auto-fill current year for period end if empty
document.addEventListener('DOMContentLoaded', function() {
    const periodeMulaiInput = document.querySelector('input[name="periode_mulai"]');
    const periodeSelesaiInput = document.querySelector('input[name="periode_selesai"]');
    
    if (periodeMulaiInput) {
        periodeMulaiInput.addEventListener('change', function() {
            if (!periodeSelesaiInput.value) {
                const startYear = new Date(this.value).getFullYear();
                const endDate = new Date(startYear + 5, 11, 31); // 5 years later
                periodeSelesaiInput.value = endDate.toISOString().split('T')[0];
            }
        });
    }
});

// File input preview
function setupFileInput(fileInput) {
    fileInput.addEventListener('change', function() {
        const fileInfo = this.nextElementSibling;
        if (this.files.length > 0) {
            const file = this.files[0];
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
            fileInfo.innerHTML = `<small>File: ${file.name} (${fileSize} MB)</small>`;
            
            // Preview image
            const preview = document.createElement('div');
            preview.className = 'image-preview';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = 'Preview';
            img.onload = () => URL.revokeObjectURL(img.src);
            preview.appendChild(img);
            
            // Remove existing preview
            const existingPreview = this.parentNode.querySelector('.image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            this.parentNode.appendChild(preview);
        } else {
            fileInfo.innerHTML = '<small>Format: JPG, PNG, GIF, WebP (Max: 2MB)</small>';
        }
    });
}

// Setup all file inputs
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(setupFileInput);
});
</script>
</body>
</html>