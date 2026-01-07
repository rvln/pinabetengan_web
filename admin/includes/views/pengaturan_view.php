<?php
require_once 'includes/header.php';
?>

<main class="dashboard-main">
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Ubah Password -->
    <div class="card">
        <h2 class="card-title"><i class="fas fa-key"></i> Ubah Password</h2>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="change_password" class="btn-primary">
                <i class="fas fa-save"></i> Ubah Password
            </button>
        </form>
    </div>

    <!-- Informasi Sistem -->
    <div class="card">
        <h2 class="card-title"><i class="fas fa-info-circle"></i> Informasi Sistem</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-label">Username</div>
                <div class="stat-number" style="font-size: 1.5rem;"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-label">Waktu Server</div>
                <div class="stat-number" style="font-size: 1.5rem;"><?= date('H:i') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stat-label">PHP Version</div>
                <div class="stat-number" style="font-size: 1.5rem;"><?= phpversion() ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-server"></i>
                </div>
                <div class="stat-label">Server Software</div>
                <div class="stat-number" style="font-size: 1rem; margin-top: 0.5rem;"><?= $_SERVER['SERVER_SOFTWARE'] ?></div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
