<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Handle change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM admin WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($current_password, $admin['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $_SESSION['admin_id']]);
            $success = "Password berhasil diubah!";
        } else {
            $error = "Password baru tidak cocok!";
        }
    } else {
        $error = "Password saat ini salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --ivory: #F5F3EF; --ivory-light: #FAFAF8; --ivory-dark: #E8E4DC;
            --red: #C62828; --red-dark: #B71C1C; --red-light: #E53935;
            --yellow: #FFD54F; --yellow-dark: #FFC107; --yellow-light: #FFECB3;
            --black: #2C2C2C; --black-light: #4A4A4A; --grey: #6B6B6B;
            --shadow-soft: 0 4px 20px rgba(44, 44, 44, 0.08);
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body.dark-mode { --ivory: #1a1a1a; --black: #FFFFFF; --grey: #B0B0B0; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--ivory); color: var(--black); transition: var(--transition); }
        
        .admin-header { background: rgba(245, 243, 239, 0.95); padding: 1rem 0; border-bottom: 2px solid var(--yellow); }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 2rem; display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 600; transition: var(--transition); }
        .btn-primary { background: var(--red); color: white; }
        .btn-primary:hover { background: var(--red-dark); transform: translateY(-2px); }
        
        .admin-main { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .card { background: white; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-soft); margin-bottom: 2rem; }
        body.dark-mode .card { background: #222222; }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-input { width: 100%; padding: 1rem; border: 2px solid var(--ivory-dark); border-radius: 10px; font-size: 1rem; transition: var(--transition); }
        .form-input:focus { outline: none; border-color: var(--yellow); }
        body.dark-mode .form-input { background: #282828; border-color: #333; color: white; }
        
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .info-item { background: var(--yellow-light); padding: 1.5rem; border-radius: 15px; }
        body.dark-mode .info-item { background: #333; }
        
        .success { background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; }
        .error { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="header-content">
            <h1><i class="fas fa-cog"></i> Pengaturan Sistem</h1>
            <div>
                <a href="dashboard.php" class="btn"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="logout.php" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </header>

    <main class="admin-main">
        <?php if (isset($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <!-- Ubah Password -->
        <div class="card">
            <h2><i class="fas fa-key"></i> Ubah Password</h2>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="form-input" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">
                    <i class="fas fa-save"></i> Ubah Password
                </button>
            </form>
        </div>

        <!-- Informasi Sistem -->
        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Informasi Sistem</h2>
            <div class="info-grid">
                <div class="info-item">
                    <h3><i class="fas fa-user"></i> Informasi Admin</h3>
                    <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></p>
                    <p><strong>Login Terakhir:</strong> <?= date('d/m/Y H:i:s') ?></p>
                </div>
                <div class="info-item">
                    <h3><i class="fas fa-database"></i> Statistik Database</h3>
                    <p><strong>PHP Version:</strong> <?= phpversion() ?></p>
                    <p><strong>MySQL Version:</strong> <?= $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) ?></p>
                </div>
                <div class="info-item">
                    <h3><i class="fas fa-server"></i> Server Info</h3>
                    <p><strong>Software:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?></p>
                    <p><strong>PHP Memory Limit:</strong> <?= ini_get('memory_limit') ?></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>