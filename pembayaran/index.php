<?php
// pembayaran/index.php - Halaman Daftar Pembayaran Transaksi
include '../layout/header.php';
include '../config/koneksi.php';

// Mengambil data transaksi digabungkan dengan nama pelanggan
$q = mysqli_query($koneksi, "
    SELECT t.*, p.nama as nama_pelanggan, p.no_hp
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.id_transaksi DESC
");

$total_data = mysqli_num_rows($q);
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-credit-card-2-front-fill text-primary"></i> Data Pembayaran Transaksi
    </h2>
    <p class="page-subtitle">Pantau status pelunasan, riwayat metode bayar, dan verifikasi tagihan pelanggan.</p>
  </div>
</div>

<!-- Table Card -->
<div class="card-modern">
  <div class="card-modern-header">
    <h5 class="card-modern-title">
      <i class="bi bi-wallet2 text-primary"></i> Tagihan & Status Pembayaran (<?= $total_data ?> Transaksi)
    </h5>
  </div>

  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th width="80">No</th>
          <th>ID Transaksi</th>
          <th>Nama Pelanggan</th>
          <th>Total Tagihan</th>
          <th>Status Pembayaran</th>
          <th width="180" class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if($total_data > 0): 
          $no = 1;
          while($r = mysqli_fetch_assoc($q)): 
            $status_bayar_lower = strtolower(trim($r['status_bayar'] ?? 'belum lunas'));
            $cust_name = $r['nama_pelanggan'] ?? 'Pelanggan Umum';
            $cust_initial = strtoupper(substr($cust_name, 0, 1));
          ?>
            <tr>
              <td>
                <span class="text-muted fw-bold"><?= $no++ ?></span>
              </td>
              <td>
                <span class="fw-bold text-primary">#<?= $r['id_transaksi'] ?></span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <span class="table-avatar"><?= $cust_initial ?></span>
                  <div>
                    <div class="fw-semibold text-dark"><?= htmlspecialchars($cust_name) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($r['no_hp'] ?? '-') ?></small>
                  </div>
                </div>
              </td>
              <td>
                <span class="fw-bold text-dark fs-6">
                  Rp <?= number_format($r['total'], 0, ',', '.') ?>
                </span>
              </td>
              <td>
                <?php if($status_bayar_lower == 'lunas'): ?>
                  <span class="badge-status lunas"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                <?php else: ?>
                  <span class="badge-status belum-lunas"><i class="bi bi-clock"></i> Belum Lunas</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <a href="tambah.php?id_transaksi=<?= $r['id_transaksi'] ?>" class="btn btn-sm btn-outline-primary" title="Kelola Pembayaran">
                  <i class="bi bi-pencil-square"></i> Kelola Bayar
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="bi bi-credit-card"></i>
                </div>
                <div class="empty-state-title">Belum Ada Tagihan</div>
                <div class="empty-state-text">Saat ini belum ada data transaksi yang membutuhkan pencatatan pembayaran.</div>
                <a href="../transaksi/tambah.php" class="btn btn-primary">
                  <i class="bi bi-plus-circle"></i> Buat Transaksi Baru
                </a>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
