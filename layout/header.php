<?php
// layout/header.php - Modern Premium Header Template untuk sisi Admin
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['login'])) {
    header('Location: /laundry_project/auth/login.php');
    exit;
}

$user = $_SESSION['user'] ?? ['nama' => 'Pengguna', 'role' => 'kasir'];
$current_page = $_SERVER['PHP_SELF'];

// Helper fungsi menandai menu aktif
function is_nav_active($keyword, $is_dashboard = false) {
    global $current_page;
    if ($is_dashboard) {
        $subfolders = ['pelanggan', 'layanan', 'transaksi', 'pembayaran', 'laporan', 'users'];
        foreach ($subfolders as $sf) {
            if (strpos($current_page, "/$sf/") !== false) return '';
        }
        return (basename($current_page) === 'index.php') ? 'active' : '';
    }
    return (strpos($current_page, "/$keyword/") !== false) ? 'active' : '';
}

// Format Tanggal Hari Ini (Indonesia)
$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$today_formatted = $days[(int)date('w')] . ', ' . date('j') . ' ' . $months[(int)date('n')] . ' ' . date('Y');

// Inisial Nama Pengguna untuk Avatar
$initials = strtoupper(substr($user['nama'], 0, 1));
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kinclong Laundry - Business Management System</title>
  
  <!-- Favicon / Icon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💧</text></svg>">
  
  <!-- Google Fonts: Plus Jakarta Sans & Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom Modern Theme Stylesheet -->
  <link rel="stylesheet" href="/laundry_project/assets/css/admin-theme.css">
</head>

<body>

<!-- Mobile Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<div class="app">

  <!-- ===================== SIDEBAR ===================== -->
  <aside class="sidebar" id="sidebar">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
      <a href="/laundry_project/index.php" class="brand-link">
        <div class="brand-icon-wrapper">
          <i class="bi bi-droplet-half"></i>
        </div>
        <div class="brand-text">
          <span class="brand-title">Kinclong</span>
          <span class="brand-subtitle">Laundry System</span>
        </div>
      </a>
      <button class="sidebar-toggle-btn d-none d-lg-flex" id="sidebarToggle" title="Toggle Sidebar">
        <i class="bi bi-chevron-left" id="toggleIcon"></i>
      </button>
    </div>

    <!-- Sidebar Menu Items -->
    <div class="sidebar-menu">
      <div class="nav-section-title">Menu Utama</div>
      
      <a href="/laundry_project/index.php" class="nav-item-link <?= is_nav_active('', true) ?>" title="Dashboard">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
      </a>

      <div class="nav-section-title">Manajemen Operasional</div>

      <a href="/laundry_project/pelanggan/index.php" class="nav-item-link <?= is_nav_active('pelanggan') ?>" title="Pelanggan">
        <i class="bi bi-people-fill"></i>
        <span>Data Pelanggan</span>
      </a>

      <a href="/laundry_project/layanan/index.php" class="nav-item-link <?= is_nav_active('layanan') ?>" title="Layanan">
        <i class="bi bi-tags-fill"></i>
        <span>Paket & Layanan</span>
      </a>

      <a href="/laundry_project/transaksi/index.php" class="nav-item-link <?= is_nav_active('transaksi') ?>" title="Transaksi">
        <i class="bi bi-receipt-cutoff"></i>
        <span>Transaksi Laundry</span>
      </a>

      <a href="/laundry_project/pembayaran/index.php" class="nav-item-link <?= is_nav_active('pembayaran') ?>" title="Pembayaran">
        <i class="bi bi-credit-card-2-front-fill"></i>
        <span>Pembayaran</span>
      </a>

      <div class="nav-section-title">Laporan & Pengaturan</div>

      <a href="/laundry_project/laporan/index.php" class="nav-item-link <?= is_nav_active('laporan') ?>" title="Laporan">
        <i class="bi bi-bar-chart-fill"></i>
        <span>Laporan & Analitik</span>
      </a>

      <?php if ($user['role'] === 'admin'): ?>
      <a href="/laundry_project/users/index.php" class="nav-item-link <?= is_nav_active('users') ?>" title="Users">
        <i class="bi bi-shield-lock-fill"></i>
        <span>Manajemen Users</span>
      </a>
      <?php endif; ?>

      <!-- Quick Link to Customer Website / Public Portal -->
      <a href="/laundry_project/cust/index.php" target="_blank" class="nav-item-link mt-2" title="Website Customer">
        <i class="bi bi-globe2 text-info"></i>
        <span>Portal Customer</span>
        <i class="bi bi-box-arrow-up-right ms-auto fs-6 text-muted"></i>
      </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
      <div class="sidebar-user-card">
        <div class="sidebar-user-avatar">
          <?= htmlspecialchars($initials) ?>
        </div>
        <div class="sidebar-user-info">
          <span class="sidebar-user-name" title="<?= htmlspecialchars($user['nama']) ?>"><?= htmlspecialchars($user['nama']) ?></span>
          <span class="sidebar-user-role"><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
        </div>
      </div>

      <a href="/laundry_project/auth/logout.php" onclick="return confirm('Apakah Anda yakin ingin logout dari sistem?');" class="btn-sidebar-logout" title="Keluar">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout Akun</span>
      </a>
    </div>
  </aside>

  <!-- ===================== MAIN WRAPPER ===================== -->
  <div class="main" id="mainContent">

    <!-- ===================== TOPBAR ===================== -->
    <header class="topbar">
      <div class="topbar-left">
        <!-- Mobile Toggle Button -->
        <button class="mobile-nav-toggle" id="mobileNavToggle" title="Buka Navigasi">
          <i class="bi bi-list"></i>
        </button>

        <!-- Sapaan Pengguna & Tanggal -->
        <div class="topbar-greeting">
          <div class="topbar-greeting-title">
            Halo, <?= htmlspecialchars($user['nama']) ?> <span class="d-none d-sm-inline">👋</span>
          </div>
          <div class="topbar-date">
            <i class="bi bi-calendar3 me-1"></i><?= $today_formatted ?>
          </div>
        </div>
      </div>

      <div class="topbar-right">
        <!-- Status Operasional System -->
        <div class="status-indicator-badge">
          <span class="status-dot"></span>
          <span>Sistem Online</span>
        </div>

        <!-- Akun Pengguna Pill -->
        <div class="user-profile-pill">
          <div class="user-avatar-sm">
            <?= htmlspecialchars($initials) ?>
          </div>
          <div class="user-details">
            <span class="user-details-name"><?= htmlspecialchars($user['nama']) ?></span>
            <span class="user-details-role"><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
          </div>
        </div>
      </div>
    </header>

    <!-- ===================== CONTENT AREA ===================== -->
    <main class="content">
