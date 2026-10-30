<?php
session_start();
// Cek jika user sudah login, langsung alihkan ke dashboard admin
if (isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

include '../config/koneksi.php';

$err = null;

// Proses Login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = $_POST['password'];

    // Cek username di database
    $q = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' LIMIT 1");

    if ($r = mysqli_fetch_assoc($q)) {
        // Verifikasi password hash
        if (password_verify($password, $r['password'])) {
            // Set session jika verifikasi sukses
            $_SESSION['login'] = true;
            $_SESSION['user']  = $r;
            header('Location: ../index.php');
            exit;
        }
    }

    // Jika gagal
    $err = "Username atau password yang Anda masukkan tidak sesuai.";
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Staf | Kinclong Laundry Management</title>
  
  <!-- Favicon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💧</text></svg>">

  <!-- Google Fonts: Plus Jakarta Sans & Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-navy: #17243A;
      --accent-blue: #3478F6;
      --accent-hover: #2563EB;
      --bg-soft: #F4F6FB;
      --border-color: #E2E8F0;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background: linear-gradient(135deg, #17243A 0%, #111a2b 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      margin: 0;
      position: relative;
      overflow-x: hidden;
    }

    /* Background decorative ambient glow */
    body::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(52, 120, 246, 0.25) 0%, rgba(52, 120, 246, 0) 70%);
      top: -100px;
      left: -100px;
      border-radius: 50%;
      z-index: 0;
    }

    body::after {
      content: '';
      position: absolute;
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(30, 64, 175, 0.2) 0%, rgba(30, 64, 175, 0) 70%);
      bottom: -100px;
      right: -100px;
      border-radius: 50%;
      z-index: 0;
    }

    .login-container {
      width: 100%;
      max-width: 460px;
      position: relative;
      z-index: 1;
    }

    .login-card {
      background: #FFFFFF;
      border-radius: 20px;
      padding: 40px 36px;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
      border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .brand-logo-badge {
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, #3478F6 0%, #1d4ed8 100%);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #FFFFFF;
      font-size: 1.75rem;
      box-shadow: 0 8px 20px rgba(52, 120, 246, 0.35);
      margin: 0 auto 16px;
    }

    .login-title {
      font-size: 1.45rem;
      font-weight: 800;
      color: var(--primary-navy);
      letter-spacing: -0.02em;
      margin-bottom: 4px;
      text-align: center;
    }

    .login-subtitle {
      font-size: 0.88rem;
      color: #64748B;
      text-align: center;
      margin-bottom: 28px;
    }

    .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }

    .input-group-text {
      background-color: #F8FAFC;
      border: 1.5px solid var(--border-color);
      color: #64748B;
      border-right: none;
      border-radius: 10px 0 0 10px;
      padding: 10px 14px;
    }

    .form-control {
      border: 1.5px solid var(--border-color);
      border-left: none;
      border-radius: 0 10px 10px 0;
      padding: 11px 14px;
      font-size: 0.92rem;
      color: #0F172A;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: var(--accent-blue);
      box-shadow: none;
    }

    .input-group:focus-within .input-group-text {
      border-color: var(--accent-blue);
      color: var(--accent-blue);
    }

    .input-group:focus-within .btn-toggle-pass {
      border-color: var(--accent-blue);
    }

    .btn-toggle-pass {
      background-color: transparent;
      border: 1.5px solid var(--border-color);
      border-left: none;
      border-radius: 0 10px 10px 0;
      color: #64748B;
      padding: 0 14px;
    }

    .btn-login {
      background: linear-gradient(135deg, #3478F6 0%, #2563EB 100%);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 700;
      font-size: 0.95rem;
      width: 100%;
      color: #FFFFFF;
      box-shadow: 0 4px 16px rgba(52, 120, 246, 0.35);
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-login:hover {
      background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(52, 120, 246, 0.45);
      color: #FFFFFF;
    }

    .alert-error {
      background-color: #FEF2F2;
      border: 1px solid #FECACA;
      color: #DC2626;
      border-radius: 10px;
      font-size: 0.85rem;
      padding: 12px 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .footer-portal-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #94A3B8;
      font-size: 0.85rem;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .footer-portal-link:hover {
      color: #FFFFFF;
    }
  </style>
</head>

<body>

  <div class="login-container">
    <div class="login-card">
      
      <!-- Brand Logo & Header -->
      <div class="brand-logo-badge">
        <i class="bi bi-droplet-half"></i>
      </div>
      <h3 class="login-title">Kinclong Laundry</h3>
      <p class="login-subtitle">Sistem Manajemen Bisnis & Operasional</p>

      <!-- Alert Pesan Error -->
      <?php if($err): ?>
        <div class="alert-error">
          <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
          <div><?= htmlspecialchars($err) ?></div>
        </div>
      <?php endif; ?>

      <!-- Form Login -->
      <form method="post">
        
        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda" required autofocus autocomplete="username">
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
            <input type="password" name="password" id="inputPass" class="form-control" style="border-right: none;" placeholder="Masukkan password Anda" required autocomplete="current-password">
            <button class="btn btn-toggle-pass" type="button" onclick="togglePass()">
              <i class="bi bi-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-login" name="login">
          <span>Masuk ke Dashboard</span>
          <i class="bi bi-arrow-right"></i>
        </button>

      </form>

    </div>

    <!-- Back to Public Portal Link -->
    <div class="text-center mt-4">
      <a href="../cust/index.php" class="footer-portal-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama Customer
      </a>
      <div class="text-muted small mt-2" style="font-size: 0.75rem; color: #64748B !important;">
        &copy; <?= date('Y') ?> Kinclong Laundry Management System
      </div>
    </div>
  </div>

  <script>
    function togglePass() {
      const p = document.getElementById('inputPass');
      const icon = document.getElementById('toggleIcon');
      if (p.type === 'password') {
        p.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        p.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }
  </script>

</body>
</html>
