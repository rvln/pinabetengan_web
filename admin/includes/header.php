<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Admin Desa Pinabetengan Selatan</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">

</head>
<body>

<!-- ========== HEADER ========== -->
<header class="dashboard-header">
    <div class="header-content">
        <div class="brand">
            <i class="fas fa-leaf brand-icon"></i>
            <div class="brand-text">
                <h1>Dashboard Admin</h1>
                <p>Desa Pinabetengan Selatan</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
            <?php if(basename($_SERVER['PHP_SELF']) !== 'dashboard.php'): ?>
                <a href="dashboard.php" class="btn-logout">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            <?php endif; ?>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
</header>
