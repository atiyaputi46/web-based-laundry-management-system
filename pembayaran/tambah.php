<?php
// pembayaran/tambah.php - Halaman Edit/Update Pembayaran Transaksi
require_once '../layout/header.php';
require_once '../config/koneksi.php';

$id_transaksi = isset($_GET['id_transaksi']) ? (int) preg_replace('/[^0-9]/', '', $_GET['id_transaksi']) : 0;
$data_bayar = [];
$transaksi_info = null;

if($id_transaksi > 0) {
    // Ambil data transaksi & pelanggan
    $qt = mysqli_query($koneksi, "
        SELECT t.*, p.nama as pelanggan, p.no_hp 
        FROM transaksi t 
        LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
        WHERE t.id_transaksi = '$id_transaksi'
    ");
    $transaksi_info = mysqli_fetch_assoc($qt);
    $status_bayar_sekarang = $transaksi_info['status_bayar'] ?? 'Belum Lunas';

    // Cek apakah sudah ada data pembayaran sebelumnya di tabel pembayaran
    $q = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id_transaksi = '$id_transaksi'");
    $data_bayar = mysqli_fetch_assoc($q);
}

// Ambil daftar transaksi untuk opsi dropdown jika user masuk tanpa memilih order
$all_transaksi = mysqli_query($koneksi, "
    SELECT t.id_transaksi, t.total, t.tanggal, t.status_bayar, p.nama as pelanggan 
    FROM transaksi t 
    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
    ORDER BY t.id_transaksi DESC
");

$tanggal_bayar = $data_bayar['tanggal_bayar'] ?? date('Y-m-d');
$metode = $data_bayar['metode'] ?? 'Cash';
$status_bayar = $status_bayar_sekarang ?? 'Belum Lunas';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-wallet2 text-primary"></i> Kelola Pembayaran Transaksi
    </h2>
    <p class="page-subtitle">
      <?= $id_transaksi > 0 ? "Pembaruan status pelunasan dan metode pembayaran untuk invoice #{$id_transaksi}." : "Pilih nomor transaksi untuk memperbarui status pelunasan dan metode pembayaran." ?>
    </p>
  </div>
  <div>
    <a href="index.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
  </div>
</div>

<div class="row">
  <div class="col-12 col-lg-8 col-xl-6">
    <div class="card-modern">
      <div class="card-modern-header">
        <h5 class="card-modern-title">
          <i class="bi bi-credit-card-2-front text-primary"></i> Formulir Verifikasi Pembayaran
        </h5>
      </div>
      <div class="card-modern-body">
        
        <?php if($transaksi_info): ?>
          <div class="p-3 mb-4 rounded-3 bg-light border">
            <div class="row g-2">
              <div class="col-6">
                <small class="text-muted d-block" style="font-size: 0.72rem;">PELANGGAN</small>
                <div class="fw-bold text-dark"><?= htmlspecialchars($transaksi_info['pelanggan'] ?? 'Pelanggan Umum') ?></div>
                <small class="text-muted"><?= htmlspecialchars($transaksi_info['no_hp'] ?? '-') ?></small>
              </div>
              <div class="col-6 text-end">
                <small class="text-muted d-block" style="font-size: 0.72rem;">TOTAL TAGIHAN</small>
                <div class="fw-bold text-primary fs-5">Rp <?= number_format($transaksi_info['total'], 0, ',', '.') ?></div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <form action="simpan.php" method="POST">
          
          <div class="mb-3">
            <label class="form-label">Nomor ID Transaksi <span class="text-danger">*</span></label>
            <?php if($id_transaksi > 0): ?>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                <input type="text" name="id_transaksi" class="form-control bg-light" value="<?= htmlspecialchars($id_transaksi) ?>" readonly required>
              </div>
            <?php else: ?>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                <select name="id_transaksi" class="form-select" required onchange="if(this.value) window.location.href='tambah.php?id_transaksi='+this.value">
                  <option value="">-- Pilih Transaksi yang Ingin Dibayar --</option>
                  <?php while($at = mysqli_fetch_assoc($all_transaksi)): ?>
                    <option value="<?= $at['id_transaksi'] ?>">
                      #<?= $at['id_transaksi'] ?> - <?= htmlspecialchars($at['pelanggan'] ?? 'Pelanggan') ?> (Rp <?= number_format($at['total'], 0, ',', '.') ?>) [<?= $at['status_bayar'] ?? 'Belum Lunas' ?>]
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-text">Pilih transaksi dari daftar di atas untuk memuat rincian tagihan.</div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pelunasan / Pembayaran <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
              <input type="date" name="tanggal_bayar" class="form-control" value="<?= $tanggal_bayar ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
              <select name="metode" class="form-select" required>
                <option value="">-- Pilih Metode Pembayaran --</option>
                <option value="Cash" <?= $metode == 'Cash' ? 'selected' : '' ?>>Tunai / Cash</option>
                <option value="Transfer" <?= $metode == 'Transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                <option value="QRIS" <?= $metode == 'QRIS' ? 'selected' : '' ?>>QRIS / E-Wallet</option>
              </select>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
            <select name="status_bayar" class="form-select" required>
              <option value="Lunas" <?= $status_bayar == 'Lunas' ? 'selected' : '' ?>>Lunas (Sudah Dibayar)</option>
              <option value="Belum Lunas" <?= $status_bayar == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas (Menunggu Pembayaran)</option>
            </select>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-check2-circle"></i> Simpan Status Pembayaran
            </button>
            <a href="index.php" class="btn btn-secondary px-3">
              <i class="bi bi-x-circle"></i> Batal
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layout/footer.php'; ?>
