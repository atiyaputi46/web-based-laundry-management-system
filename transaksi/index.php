<?php
// transaksi/index.php - Halaman Utama Transaksi Laundry
include '../config/koneksi.php';
include '../layout/header.php';

$q = mysqli_query($koneksi, "
    SELECT 
        t.*, 
        p.nama AS pelanggan,
        p.no_hp
    FROM transaksi t
    LEFT JOIN pelanggan p 
        ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.id_transaksi DESC
");

$total_trans = mysqli_num_rows($q);
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-receipt-cutoff text-primary"></i> Data Transaksi Laundry
    </h2>
    <p class="page-subtitle">Daftar order cucian masuk, progres pengerjaan, dan pengiriman notifikasi WhatsApp.</p>
  </div>
  <div>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Tambah Transaksi Baru
    </a>
  </div>
</div>

<!-- Table Card -->
<div class="card-modern">
  <div class="card-modern-header">
    <h5 class="card-modern-title">
      <i class="bi bi-list-check text-primary"></i> Riwayat Transaksi (<?= $total_trans ?> Order)
    </h5>
  </div>

  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th width="90">ID Order</th>
          <th>Pelanggan</th>
          <th>Tanggal Masuk</th>
          <th>Total Biaya</th>
          <th>Status Cucian</th>
          <th>Pembayaran</th>
          <th width="220" class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if($total_trans > 0): ?>
          <?php while($r = mysqli_fetch_assoc($q)): 
            $status_lower = strtolower(trim($r['status']));
            $status_bayar_lower = strtolower(trim($r['status_bayar'] ?? 'belum lunas'));
            $cust_name = $r['pelanggan'] ?? 'Pelanggan Umum';
            $cust_initial = strtoupper(substr($cust_name, 0, 1));
          ?>
            <tr>
              <td>
                <span class="fw-bold text-primary">#<?= $r['id_transaksi']; ?></span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <span class="table-avatar"><?= $cust_initial ?></span>
                  <div>
                    <div class="fw-semibold text-dark"><?= htmlspecialchars($cust_name); ?></div>
                    <small class="text-muted"><?= htmlspecialchars($r['no_hp'] ?? '-'); ?></small>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-dark fw-medium"><?= date('d M Y', strtotime($r['tanggal'])); ?></div>
              </td>
              <td>
                <span class="fw-bold text-dark">Rp <?= number_format($r['total'], 0, ',', '.'); ?></span>
              </td>
              <td>
                <?php if($status_lower == 'selesai'): ?>
                  <span class="badge-status selesai"><i class="bi bi-check2-circle"></i> Selesai</span>
                <?php elseif($status_lower == 'diambil'): ?>
                  <span class="badge-status diambil"><i class="bi bi-bag-check"></i> Diambil</span>
                <?php elseif($status_lower == 'dicuci'): ?>
                  <span class="badge-status dicuci"><i class="bi bi-droplet"></i> Dicuci</span>
                <?php elseif($status_lower == 'disetrika'): ?>
                  <span class="badge-status disetrika"><i class="bi bi-fire"></i> Disetrika</span>
                <?php else: ?>
                  <span class="badge-status diproses"><i class="bi bi-arrow-repeat"></i> Diproses</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if($status_bayar_lower == 'lunas'): ?>
                  <span class="badge-status lunas"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                <?php else: ?>
                  <span class="badge-status belum-lunas"><i class="bi bi-clock"></i> Belum Lunas</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <!-- Lihat Detail -->
                  <a class="btn btn-sm btn-outline-primary"
                     href="lihat.php?id=<?= $r['id_transaksi']; ?>"
                     title="Lihat Detail Transaksi">
                     <i class="bi bi-eye"></i> Detail
                  </a>

                  <!-- WhatsApp Notifikasi jika Selesai -->
                  <?php if(
                      $status_lower == 'selesai' 
                      && !empty($r['no_hp'])
                  ): 
                    $clean_phone = preg_replace('/[^0-9]/', '', $r['no_hp']);
                    $noWa = (substr($clean_phone, 0, 1) === '0') ? '62' . substr($clean_phone, 1) : $clean_phone;
                    $harga = number_format($r['total'], 0, ',', '.');
                    $pesan = "Halo {$r['pelanggan']},\n\nOrder Cucian Anda (#{$r['id_transaksi']}) dengan Total Rp {$harga} telah SELESAI dan siap diambil.\n\nTerima kasih telah mempercayakan laundry Anda kepada Kinclong Laundry!";
                  ?>
                    <a class="btn btn-sm btn-success"
                       target="_blank"
                       href="https://wa.me/<?= $noWa ?>?text=<?= urlencode($pesan) ?>"
                       title="Kirim Notifikasi WhatsApp">
                       <i class="bi bi-whatsapp"></i>
                    </a>
                  <?php endif; ?>

                  <!-- Hapus -->
                  <a class="btn btn-sm btn-danger"
                     href="hapus.php?id=<?= $r['id_transaksi']; ?>"
                     onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi #<?= $r['id_transaksi'] ?>?')"
                     title="Hapus Transaksi">
                     <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="bi bi-receipt"></i>
                </div>
                <div class="empty-state-title">Belum Ada Transaksi</div>
                <div class="empty-state-text">Mulai catat transaksi laundry baru untuk pelanggan Anda.</div>
                <a href="tambah.php" class="btn btn-primary">
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
