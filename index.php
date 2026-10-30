<?php
/**
 * Halaman Dashboard Admin - Modern Premium
 * Menampilkan statistik ringkasan (total pelanggan, transaksi, omzet hari ini, cucian aktif)
 * grafik omzet 7 hari terakhir, dan daftar transaksi terakhir.
 */
include 'layout/header.php';
include 'config/koneksi.php';

/* ================= STATISTIK ================= */
// Total Pelanggan
$pelanggan = (int) mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS c FROM pelanggan")
)['c'];

// Total Seluruh Transaksi
$transaksi = (int) mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS c FROM transaksi")
)['c'];

// Omzet Hari Ini (Transaksi Selesai & Hari Ini)
$omzet_hari_ini = (int) mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT IFNULL(SUM(total),0) AS total 
         FROM transaksi 
         WHERE (LOWER(status)='selesai' OR status='Selesai') 
         AND DATE(tanggal)=CURDATE()"
    )
)['total'];

// Pesanan Aktif (Dalam Proses / Belum Diambil)
$pesanan_aktif = (int) mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS c 
         FROM transaksi 
         WHERE LOWER(status) NOT IN ('diambil')"
    )
)['c'];

/* ================= TRANSAKSI TERAKHIR ================= */
$recent = mysqli_query($koneksi, "
    SELECT t.*, p.nama AS pelanggan, p.no_hp
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
      <i class="bi bi-grid-1x2-fill text-primary"></i> Dashboard Ringkasan
    </h2>
    <p class="page-subtitle">Pantau performa harian, omzet, dan aktivitas operasional laundry secara real-time.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="transaksi/tambah.php" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Tambah Transaksi
    </a>
    <a href="pelanggan/tambah.php" class="btn btn-secondary">
      <i class="bi bi-person-plus"></i> Pelanggan Baru
    </a>
  </div>
</div>

<!-- ================= STATISTIK CARDS ================= -->
<div class="row g-4 mb-4">

  <!-- Card 1: Total Pelanggan -->
  <div class="col-12 col-sm-6 col-xl-3">
    <a href="pelanggan/index.php" class="text-decoration-none">
      <div class="card-stat">
        <div class="card-stat-header">
          <span class="card-stat-title">Total Pelanggan</span>
          <div class="card-stat-icon primary">
            <i class="bi bi-people-fill"></i>
          </div>
        </div>
        <div class="card-stat-value"><?= number_format($pelanggan, 0, ',', '.') ?></div>
        <div class="card-stat-desc">
          <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Terdaftar</span>
          <span>dalam database</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 2: Total Transaksi -->
  <div class="col-12 col-sm-6 col-xl-3">
    <a href="transaksi/index.php" class="text-decoration-none">
      <div class="card-stat">
        <div class="card-stat-header">
          <span class="card-stat-title">Total Transaksi</span>
          <div class="card-stat-icon success">
            <i class="bi bi-receipt-cutoff"></i>
          </div>
        </div>
        <div class="card-stat-value"><?= number_format($transaksi, 0, ',', '.') ?></div>
        <div class="card-stat-desc">
          <span class="text-primary fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>Keseluruhan</span>
          <span>order masuk</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 3: Omzet Hari Ini -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card-stat">
      <div class="card-stat-header">
        <span class="card-stat-title">Omzet Hari Ini</span>
        <div class="card-stat-icon warning">
          <i class="bi bi-wallet2"></i>
        </div>
      </div>
      <div class="card-stat-value text-primary">
        Rp <?= number_format($omzet_hari_ini, 0, ',', '.') ?>
      </div>
      <div class="card-stat-desc">
        <span class="text-warning fw-semibold"><i class="bi bi-calendar-event me-1"></i>Hari Ini</span>
        <span>transaksi selesai</span>
      </div>
    </div>
  </div>

  <!-- Card 4: Pesanan Aktif -->
  <div class="col-12 col-sm-6 col-xl-3">
    <a href="transaksi/index.php" class="text-decoration-none">
      <div class="card-stat">
        <div class="card-stat-header">
          <span class="card-stat-title">Pesanan Aktif</span>
          <div class="card-stat-icon navy">
            <i class="bi bi-hourglass-split"></i>
          </div>
        </div>
        <div class="card-stat-value text-dark"><?= number_format($pesanan_aktif, 0, ',', '.') ?></div>
        <div class="card-stat-desc">
          <span class="text-info fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>Proses</span>
          <span>perlu dikerjakan/diambil</span>
        </div>
      </div>
    </a>
  </div>

</div>

<!-- ================= GRAFIK OMZET & QUICK STATS ================= -->
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-8">
    <div class="card-modern h-100">
      <div class="card-modern-header">
        <h5 class="card-modern-title">
          <i class="bi bi-graph-up-arrow text-primary"></i> Tren Omzet Harian (7 Hari Terakhir)
        </h5>
        <a href="laporan/chart.php" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-arrows-fullscreen"></i> Mode Penuh
        </a>
      </div>
      <div class="card-modern-body">
        <div style="position: relative; height: 280px; width: 100%;">
          <canvas id="dashboardChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card-modern h-100">
      <div class="card-modern-header">
        <h5 class="card-modern-title">
          <i class="bi bi-lightning-charge-fill text-warning"></i> Akses Cepat
        </h5>
      </div>
      <div class="card-modern-body d-flex flex-column gap-3 justify-content-center">
        <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
          <div class="p-2 rounded-2 bg-primary text-white fs-4">
            <i class="bi bi-receipt"></i>
          </div>
          <div>
            <div class="fw-bold">Input Order Baru</div>
            <small class="text-muted">Buat invoice & timbang cucian</small>
          </div>
          <a href="transaksi/tambah.php" class="btn btn-sm btn-primary ms-auto">Buka</a>
        </div>

        <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
          <div class="p-2 rounded-2 bg-success text-white fs-4">
            <i class="bi bi-cash-stack"></i>
          </div>
          <div>
            <div class="fw-bold">Konfirmasi Bayar</div>
            <small class="text-muted">Cek tagihan & pelunasan</small>
          </div>
          <a href="pembayaran/index.php" class="btn btn-sm btn-success ms-auto">Buka</a>
        </div>

        <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
          <div class="p-2 rounded-2 bg-info text-white fs-4">
            <i class="bi bi-file-earmark-spreadsheet"></i>
          </div>
          <div>
            <div class="fw-bold">Export Excel</div>
            <small class="text-muted">Unduh rekap data transaksi</small>
          </div>
          <a href="laporan/export.php" class="btn btn-sm btn-secondary ms-auto">Unduh</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ================= TRANSAKSI TERAKHIR ================= -->
<div class="card-modern">
  <div class="card-modern-header">
    <h5 class="card-modern-title">
      <i class="bi bi-clock-history text-primary"></i> Transaksi Terakhir
    </h5>
    <a href="transaksi/index.php" class="btn btn-sm btn-outline-primary">
      Lihat Semua Transaksi <i class="bi bi-arrow-right"></i>
    </a>
  </div>

  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th>ID Order</th>
          <th>Pelanggan</th>
          <th>Tanggal</th>
          <th>Total Biaya</th>
          <th>Status Cucian</th>
          <th>Pembayaran</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(mysqli_num_rows($recent) > 0): ?>
          <?php while($r = mysqli_fetch_assoc($recent)): 
            $status_lower = strtolower(trim($r['status']));
            $status_bayar_lower = strtolower(trim($r['status_bayar'] ?? 'belum lunas'));
            $cust_name = $r['pelanggan'] ?? 'Pelanggan Umum';
            $cust_initial = strtoupper(substr($cust_name, 0, 1));
          ?>
            <tr>
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
                <div class="fw-medium text-dark"><?= date('d M Y', strtotime($r['tanggal'])) ?></div>
                <small class="text-muted"><?= date('H:i', strtotime($r['tanggal'])) != '00:00' ? date('H:i', strtotime($r['tanggal'])) : 'Hari ini' ?></small>
              </td>
              <td>
                <span class="fw-bold text-dark">Rp <?= number_format($r['total'], 0, ',', '.') ?></span>
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
                <a href="transaksi/lihat.php?id=<?= $r['id_transaksi'] ?>" class="btn btn-sm btn-secondary" title="Lihat Detail Transaksi">
                  <i class="bi bi-eye"></i> Detail
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="bi bi-inbox"></i>
                </div>
                <div class="empty-state-title">Belum Ada Transaksi</div>
                <div class="empty-state-text">Saat ini belum ada data transaksi yang tercatat dalam sistem.</div>
                <a href="transaksi/tambah.php" class="btn btn-primary">
                  <i class="bi bi-plus-circle"></i> Buat Transaksi Pertama
                </a>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('/laundry_project/laporan/chart_data.php')
    .then(res => res.json())
    .then(data => {
      const ctx = document.getElementById('dashboardChart').getContext('2d');
      
      // Buat gradient background untuk area chart
      const gradient = ctx.createLinearGradient(0, 0, 0, 260);
      gradient.addColorStop(0, 'rgba(52, 120, 246, 0.35)');
      gradient.addColorStop(1, 'rgba(52, 120, 246, 0.00)');

      // Format format tanggal label ke format yang lebih cantik
      const formattedLabels = (data.labels || []).map(label => {
        const d = new Date(label);
        if (isNaN(d.getTime())) return label;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
      });

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: formattedLabels.length > 0 ? formattedLabels : ['H-6', 'H-5', 'H-4', 'H-3', 'H-2', 'Kemarin', 'Hari Ini'],
          datasets: [{
            label: 'Omzet (Rp)',
            data: (data.data && data.data.length > 0) ? data.data : [0, 0, 0, 0, 0, 0, 0],
            borderColor: '#3478F6',
            backgroundColor: gradient,
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#FFFFFF',
            pointBorderColor: '#3478F6',
            pointBorderWidth: 2.5,
            pointRadius: 4.5,
            pointHoverRadius: 6.5,
            pointHoverBackgroundColor: '#3478F6',
            pointHoverBorderColor: '#FFFFFF'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: '#17243A',
              titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
              bodyFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
              padding: 12,
              cornerRadius: 8,
              displayColors: false,
              callbacks: {
                label: function(context) {
                  return 'Omzet: Rp ' + Number(context.raw || 0).toLocaleString('id-ID');
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                font: { family: 'Plus Jakarta Sans', size: 11 },
                color: '#64748B'
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                color: '#F1F5F9'
              },
              ticks: {
                font: { family: 'Plus Jakarta Sans', size: 11 },
                color: '#64748B',
                callback: function(value) {
                  if (value >= 1000000) return (value / 1000000) + ' Jt';
                  if (value >= 1000) return (value / 1000) + ' Rb';
                  return value;
                }
              }
            }
          }
        }
      });
    })
    .catch(err => {
      console.warn('Gagal memuat data grafik harian:', err);
    });
});
</script>

<?php include 'layout/footer.php'; ?>