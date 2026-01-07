<?php
// fix_admin_hash.php
require_once 'config/db.php';

// Password yang ingin dihash
$password = "pinseldesacantik"; // Ganti dengan password admin Anda

// Generate hash yang benar
$correctHash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash yang benar: " . $correctHash . "\n\n";

// Update database
$stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = 'admin'");
$stmt->execute([$correctHash]);

if ($stmt->rowCount() > 0) {
    echo "✅ Hash berhasil diperbaiki di database!\n";
    
    // Verifikasi
    $stmt = $pdo->prepare("SELECT password FROM admin WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    echo "Hash di database sekarang: " . $admin['password'] . "\n";
    echo "Verifikasi: " . (password_verify($password, $admin['password']) ? "SUKSES" : "GAGAL") . "\n";
} else {
    echo "❌ Gagal memperbarui database\n";
}
?>


