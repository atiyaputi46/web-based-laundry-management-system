<?php 
// cust/layanan.php - Halaman Daftar Layanan
// Menampilkan semua layanan yang tersedia beserta harganya dari database.
include 'partials/header.php'; 
?>

<div class="container py-5">
  <?php include '../config/koneksi.php'; ?>

  <h3 class="fw-bold mb-4">Layanan Kami</h3>

  <div class="row g-4">
  <?php
  // Mengambil data layanan dari database tabel 'layanan'
  $q = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan ASC");
  while($r = mysqli_fetch_assoc($q)):
  ?>
    <div class="col-md-4">
      <div class="card p-4 rounded-4 text-center">
        <i class="bi bi-bag-check fs-1 text-primary mb-3"></i>
        <h6 class="fw-bold"><?= htmlspecialchars($r['nama_layanan']) ?></h6>
        <p class="small text-muted">Rp <?= number_format($r['harga'],0,',','.') ?></p>
      </div>
    </div>
  <?php endwhile; ?>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
