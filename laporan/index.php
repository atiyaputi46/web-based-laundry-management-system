<?php
// laporan/index.php - Pusat Laporan & Analitik Bisnis Laundry
include '../layout/header.php';
include '../config/koneksi.php';

// Total Transaksi & Omzet Keseluruhan
$stat_omzet = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT 
        COUNT(*) as total_transaksi,
        IFNULL(SUM(total), 0) as total_omzet,
        SUM(CASE WHEN status_bayar = 'Lunas' THEN total ELSE 0 END) as omzet_lunas,
        SUM(CASE WHEN status_bayar != 'Lunas' OR status_bayar IS NULL THEN total ELSE 0 END) as piutang
    FROM transaksi
"));

// 10 Data Transaksi Terakhir untuk Preview Laporan
$preview = mysqli_query($koneksi, "
    SELECT t.*, p.nama AS nama_pelanggan, p.no_hp
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.id_transaksi DESC
    LIMIT 10
");
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-bar-chart-fill text-primary"></i> Laporan & Rekap Finansial
    </h2>
    <p class="page-subtitle">Pusat dokumentasi rekapitulasi omzet, status tagihan, dan ekspor data ke Excel.</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-success" href="export.php">
      <i class="bi bi-file-earmark-excel"></i> Unduh File Excel (.xls)
    </a>
    <a class="btn btn-outline-primary" href="chart.php">
      <i class="bi bi-graph-up"></i> Visualisasi Grafik
    </a>
  </div>
</div>

<!-- Statistik Laporan -->
<div class="row g-4 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card-stat">
      <div class="card-stat-header">
        <span class="card-stat-title">Total Omzet Keseluruhan</span>
        <div class="card-stat-icon primary">
          <i class="bi bi-cash-stack"></i>
        </div>
      </div>
      <div class="card-stat-value text-primary">
        Rp <?= number_format($stat_omzet['total_omzet'] ?? 0, 0, ',', '.') ?>
      </div>
      <div class="card-stat-desc">
        <span class="text-success fw-semibold"><i class="bi bi-check-all me-1"></i>Akumulasi</span>
        <span>semua transaksi</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card-stat">
      <div class="card-stat-header">
        <span class="card-stat-title">Dana Sudah Lunas</span>
        <div class="card-stat-icon success">
          <i class="bi bi-check-circle-fill"></i>
        </div>
      </div>
      <div class="card-stat-value text-success">
        Rp <?= number_format($stat_omzet['omzet_lunas'] ?? 0, 0, ',', '.') ?>
      </div>
      <div class="card-stat-desc">
        <span class="text-success fw-semibold">Kas masuk terkonfirmasi</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card-stat">
      <div class="card-stat-header">
        <span class="card-stat-title">Tagihan Belum Lunas</span>
        <div class="card-stat-icon warning">
          <i class="bi bi-clock-history"></i>
        </div>
      </div>
      <div class="card-stat-value text-danger">
        Rp <?= number_format($stat_omzet['piutang'] ?? 0, 0, ',', '.') ?>
      </div>
      <div class="card-stat-desc">
        <span class="text-danger fw-semibold">Menunggu pelunasan</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card-stat">
      <div class="card-stat-header">
        <span class="card-stat-title">Volume Transaksi</span>
        <div class="card-stat-icon navy">
          <i class="bi bi-receipt"></i>
        </div>
      </div>
      <div class="card-stat-value text-dark">
        <?= number_format($stat_omzet['total_transaksi'] ?? 0, 0, ',', '.') ?>
      </div>
      <div class="card-stat-desc">
        <span class="text-primary fw-semibold">Total nota diterbitkan</span>
      </div>
    </div>
  </div>
</div>

<!-- Preview Tabel Laporan -->
<div class="card-modern">
  <div class="card-modern-header">
    <h5 class="card-modern-title">
      <i class="bi bi-table text-primary"></i> Pratinjau Rekapitulasi Data Transaksi
    </h5>
    <a href="export.php" class="btn btn-sm btn-success">
      <i class="bi bi-file-earmark-arrow-down"></i> Export Seluruh Data (.xls)
    </a>
  </div>

  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th>No</th>
          <th>ID Nota</th>
          <th>Nama Pelanggan</th>
          <th>Nomor HP</th>
          <th>Tanggal Transaksi</th>
          <th>Total Biaya</th>
          <th>Status Cucian</th>
          <th>Status Pembayaran</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        if(mysqli_num_rows($preview) > 0): 
          while($r = mysqli_fetch_assoc($preview)): 
            $status_lower = strtolower(trim($r['status']));
            $status_bayar_lower = strtolower(trim($r['status_bayar'] ?? 'belum lunas'));
        ?>
          <tr>
            <td><span class="text-muted fw-bold"><?= $no++ ?></span></td>
            <td><span class="fw-bold text-primary">#<?= $r['id_transaksi'] ?></span></td>
            <td><div class="fw-semibold text-dark"><?= htmlspecialchars($r['nama_pelanggan'] ?? 'Pelanggan Umum') ?></div></td>
            <td><span class="text-muted"><?= htmlspecialchars($r['no_hp'] ?? '-') ?></span></td>
            <td><span class="text-dark"><?= date('d/m/Y', strtotime($r['tanggal'])) ?></span></td>
            <td><span class="fw-bold text-dark">Rp <?= number_format($r['total'], 0, ',', '.') ?></span></td>
            <td>
              <?php if($status_lower == 'selesai'): ?>
                <span class="badge-status selesai">Selesai</span>
              <?php elseif($status_lower == 'diambil'): ?>
                <span class="badge-status diambil">Diambil</span>
              <?php else: ?>
                <span class="badge-status diproses"><?= ucfirst($status_lower) ?></span>
              <?php endif; ?>
            </td>
            <td>
              <?php if($status_bayar_lower == 'lunas'): ?>
                <span class="badge-status lunas">Lunas</span>
              <?php else: ?>
                <span class="badge-status belum-lunas">Belum Lunas</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
                <div class="empty-state-title">Data Belum Tersedia</div>
                <div class="empty-state-text">Belum ada transaksi untuk direkap ke dalam laporan.</div>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>