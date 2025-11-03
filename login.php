<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin/dashboard.php');
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Desa Pinabetengan Selatan</title>

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
        overflow-x: hidden;
        min-height: 100vh;
        transition: background-color 0.4s ease, color 0.4s ease;
        position: relative;
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

    body.dark-mode {
        background: linear-gradient(135deg, #1a1a1a 0%, #151515 50%, #1a1a1a 100%);
    }

    /* ========== LOGIN CONTAINER ========== */
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
        position: relative;
        z-index: 2;
    }

    .login-card {
        background: white;
        padding: 3rem;
        border-radius: 30px;
        box-shadow: var(--shadow-strong);
        width: 100%;
        max-width: 450px;
        border: 2px solid transparent;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--red), var(--yellow));
        transform: scaleX(0);
        transform-origin: left;
        transition: var(--transition);
    }

    .login-card:hover::before {
        transform: scaleX(1);
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 70px rgba(44, 44, 44, 0.18);
        border-color: var(--yellow);
    }

    /* Dark mode login card */
    body.dark-mode .login-card {
        background: #222222;
        border: 1px solid rgba(255, 213, 79, 0.15);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    body.dark-mode .login-card:hover {
        background: #282828;
        border-color: var(--yellow);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
    }

    /* ========== LOGIN HEADER ========== */
    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .login-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: linear-gradient(135deg, var(--yellow-light), white);
        color: var(--black);
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        box-shadow: var(--shadow-soft);
        border: 2px solid var(--yellow);
    }

    body.dark-mode .login-badge {
        background: rgba(30, 30, 30, 0.9);
        border-color: var(--yellow);
        color: var(--yellow);
        box-shadow: 0 4px 20px rgba(255, 213, 79, 0.2);
    }

    .login-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }

    body.dark-mode .login-title {
        color: #FFFFFF;
    }

    .login-title .text-highlight {
        color: var(--red);
        position: relative;
    }

    body.dark-mode .login-title .text-highlight {
        color: var(--yellow);
    }

    .login-subtitle {
        color: var(--grey);
        font-size: 1rem;
    }

    body.dark-mode .login-subtitle {
        color: #B0B0B0;
    }

    /* ========== FORM STYLES ========== */
    .form-group {
        margin-bottom: 1.8rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.8rem;
        color: var(--black);
        font-weight: 500;
        font-size: 1rem;
    }

    body.dark-mode .form-label {
        color: #E0E0E0;
    }

    .form-input {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px solid var(--ivory-dark);
        border-radius: 15px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
        color: var(--black);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.2);
        transform: translateY(-2px);
    }

    body.dark-mode .form-input {
        background: #282828;
        border-color: rgba(255, 213, 79, 0.2);
        color: #E0E0E0;
    }

    body.dark-mode .form-input:focus {
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.3);
    }

    /* ========== BUTTON STYLES ========== */
    .btn-login {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--ivory);
        padding: 1.2rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        box-shadow: 0 8px 30px rgba(198, 40, 40, 0.35);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        font-size: 1.1rem;
        width: 100%;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 40px rgba(198, 40, 40, 0.45);
    }

    body.dark-mode .btn-login {
        box-shadow: 0 8px 30px rgba(198, 40, 40, 0.4);
    }

    body.dark-mode .btn-login:hover {
        box-shadow: 0 12px 40px rgba(198, 40, 40, 0.6);
    }

    /* ========== ERROR MESSAGE ========== */
    .error-message {
        background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(255, 213, 79, 0.05));
        color: var(--red);
        padding: 1rem 1.5rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(198, 40, 40, 0.2);
        font-weight: 500;
    }

    body.dark-mode .error-message {
        background: rgba(198, 40, 40, 0.15);
        border-color: rgba(198, 40, 40, 0.3);
        color: #ff6b6b;
    }

    /* ========== THEME TOGGLE ========== */
    .theme-toggle-container {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 1000;
    }

    .btn-theme-toggle {
        width: 50px;
        height: 50px;
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
        position: relative;
        overflow: hidden;
    }

    .btn-theme-toggle::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
        opacity: 0;
        transition: var(--transition);
    }

    .btn-theme-toggle:hover::before {
        opacity: 1;
    }

    .btn-theme-toggle:hover {
        background: var(--yellow-dark);
        transform: scale(1.15) rotate(180deg);
        box-shadow: 0 6px 25px rgba(255, 213, 79, 0.4);
    }

    .btn-theme-toggle i {
        font-size: 1.3rem;
        transition: var(--transition);
    }

    /* Dark mode toggle icon */
    body.dark-mode .btn-theme-toggle {
        background: var(--yellow-dark);
        color: var(--ivory);
    }

    body.dark-mode .btn-theme-toggle:hover {
        background: var(--yellow);
        color: var(--black);
    }

    /* ========== DECORATIVE ELEMENTS ========== */
    .deco-leaf {
        position: absolute;
        opacity: 0.08;
        color: var(--green-leaf);
        animation: floatLeaf 25s ease-in-out infinite;
        z-index: 1;
    }

    .deco-leaf-1 {
        top: 10%;
        left: 10%;
        font-size: 4rem;
        animation-delay: 0s;
    }

    .deco-leaf-2 {
        top: 70%;
        right: 15%;
        font-size: 3rem;
        animation-delay: 5s;
    }

    .deco-leaf-3 {
        bottom: 20%;
        left: 20%;
        font-size: 3.5rem;
        animation-delay: 10s;
    }

    body.dark-mode .deco-leaf {
        opacity: 0.03;
        color: var(--yellow);
    }

    @keyframes floatLeaf {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        25% {
            transform: translateY(-30px) rotate(10deg);
        }
        50% {
            transform: translateY(-60px) rotate(-5deg);
        }
        75% {
            transform: translateY(-30px) rotate(5deg);
        }
    }

    /* ========== BACKGROUND EFFECTS ========== */
    body::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 213, 79, 0.1) 0%, rgba(255, 213, 79, 0.02) 50%, transparent 70%);
        border-radius: 50%;
        animation: float 15s ease-in-out infinite;
    }

    body::after {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -150px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(124, 179, 66, 0.08) 0%, rgba(124, 179, 66, 0.02) 50%, transparent 70%);
        border-radius: 50%;
        animation: float 20s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 768px) {
        .login-container {
            padding: 1rem;
        }
        
        .login-card {
            padding: 2rem;
        }
        
        .login-title {
            font-size: 1.8rem;
        }
        
        .theme-toggle-container {
            top: 1rem;
            right: 1rem;
        }
        
        .btn-theme-toggle {
            width: 45px;
            height: 45px;
        }
        
        .deco-leaf {
            display: none;
        }
        
        body::before,
        body::after {
            display: none;
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
    .login-card,
    .form-input,
    .btn-login,
    .btn-theme-toggle,
    .error-message,
    .login-badge,
    .login-title,
    .login-subtitle,
    .form-label {
        transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease, opacity 0.4s ease;
    }
    </style>
</head>
<body>

<!-- ========== DECORATIVE ELEMENTS ========== -->
<i class="fas fa-leaf deco-leaf deco-leaf-1"></i>
<i class="fas fa-seedling deco-leaf deco-leaf-2"></i>
<i class="fas fa-spa deco-leaf deco-leaf-3"></i>

<!-- ========== THEME TOGGLE ========== -->
<div class="theme-toggle-container">
    <button class="btn-theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </button>
</div>

<!-- ========== LOGIN CONTAINER ========== -->
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-badge">
                <i class="fas fa-lock"></i>
                <span>Akses Admin</span>
            </div>
            <h1 class="login-title">
                <span class="text-highlight">Login</span> Admin
            </h1>
            <p class="login-subtitle">Desa Pinabetengan Selatan</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user me-2"></i>Username
                </label>
                <input type="text" name="username" class="form-input" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-key me-2"></i>Password
                </label>
                <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">
                <span>Masuk ke Dashboard</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>
</div>

<!-- ========== THEME TOGGLE SCRIPT ========== -->
<script>
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle.querySelector('i');
const body = document.body;

// Function to apply theme
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

// Check saved theme or system preference
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
    applyTheme(savedTheme);
} else if (systemPrefersDark) {
    applyTheme('dark');
} else {
    applyTheme('light');
}

// Toggle theme on button click
themeToggle.addEventListener('click', () => {
    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
    
    // Add a little animation feedback
    themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
        themeToggle.style.transform = '';
    }, 300);
});

// Listen to system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
        applyTheme(e.matches ? 'dark' : 'light');
    }
});

// Add focus effects to form inputs
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});

// Console welcome message
console.log('%c🔐 Login Admin - Desa Pinabetengan Selatan 🔐', 'color: #7CB342; font-size: 18px; font-weight: bold;');
console.log('%c💡 Tekan tombol bulan/matahari untuk toggle dark mode', 'color: #FFD54F; font-size: 12px;');
</script>

</body>
</html>