<?php
// layanan/index.php - Manajemen Layanan & Tarif
include '../layout/header.php';
include '../config/koneksi.php';

$q = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan ASC");
$total_layanan = mysqli_num_rows($q);
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-tags-fill text-primary"></i> Paket & Layanan Laundry
    </h2>
    <p class="page-subtitle">Daftar harga satuan dan kiloan untuk berbagai tipe cucian.</p>
  </div>
  <div>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Tambah Layanan Baru
    </a>
  </div>
</div>

<!-- Table Card -->
<div class="card-modern">
  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th width="80">ID</th>
          <th>Nama Paket / Layanan</th>
          <th>Tarif / Harga</th>
          <th width="160" class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if($total_layanan > 0): ?>
          <?php while($r = mysqli_fetch_assoc($q)): ?>
            <tr>
              <td>
                <span class="fw-bold text-muted">#<?= $r['id_layanan'] ?></span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary me-3 fs-5">
                    <i class="bi bi-basket2"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-dark"><?= htmlspecialchars($r['nama_layanan']) ?></div>
                    <small class="text-muted">Layanan Reguler & Express</small>
                  </div>
                </div>
              </td>
              <td>
                <span class="fw-bold text-primary fs-6">
                  Rp <?= number_format($r['harga'], 0, ',', '.') ?>
                </span>
                <span class="text-muted small">/ satuan / kg</span>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <a class="btn btn-sm btn-warning" 
                     href="edit.php?id=<?= $r['id_layanan'] ?>"
                     title="Edit Layanan">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>

                  <a class="btn btn-sm btn-danger" 
                     href="hapus.php?id=<?= $r['id_layanan'] ?>" 
                     onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')"
                     title="Hapus Layanan">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="bi bi-tags"></i>
                </div>
                <div class="empty-state-title">Belum Ada Layanan</div>
                <div class="empty-state-text">Silakan tambahkan jenis layanan laundry dan tarifnya ke sistem.</div>
                <a href="tambah.php" class="btn btn-primary">
                  <i class="bi bi-plus-circle"></i> Tambah Layanan Pertama
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
