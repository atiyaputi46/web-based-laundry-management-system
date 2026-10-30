<?php
// cust/partials/header.php - Header Template untuk sisi Customer
// Berisi navigasi bar (navbar) dan pembukaan tag HTML

$halaman = basename($_SERVER['PHP_SELF']);

// Fungsi Helper untuk menandai menu aktif
function aktif($page) {
  global $halaman;
  return $halaman == $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kinclong Laundry | Layanan Laundry Profesional</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Layanan laundry terbaik, cepat, dan higienis. Kualitas premium dengan harga terjangkau.">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link href="/laundry_project/cust/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-floating">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-primary" href="/laundry_project/cust/index.php">
      <i class="bi bi-droplet-fill"></i> Kinclong
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
        <li class="nav-item"><a class="nav-link <?=aktif('index.php')?>" href="/laundry_project/cust/index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link <?=aktif('tentang.php')?>" href="/laundry_project/cust/tentang.php">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link <?=aktif('layanan.php')?>" href="/laundry_project/cust/layanan.php">Layanan</a></li>
        <li class="nav-item"><a class="nav-link <?=aktif('portal.php')?>" href="/laundry_project/customer/portal.php">Cek Pesanan</a></li>
        <li class="nav-item"><a class="nav-link <?=aktif('kontak.php')?>" href="/laundry_project/cust/kontak.php">Kontak</a></li>
      </ul>

      <div class="d-flex gap-2">
        <a href="/laundry_project/auth/login.php" class="btn btn-outline-primary px-4">
          Login
        </a>
      </div>
    </div>
  </div>
</nav>

<main>
