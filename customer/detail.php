<?php
include '../config/koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil info transaksi
$qt = mysqli_query($koneksi, "
    SELECT t.*, p.nama AS nama_pelanggan, p.no_hp 
    FROM transaksi t 
    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
    WHERE t.id_transaksi = '$id'
");
$trans = mysqli_fetch_assoc($qt);

// Ambil detail layanan
$q = mysqli_query($koneksi, "
    SELECT d.*, l.nama_layanan 
    FROM transaksi_detail d
    LEFT JOIN layanan l ON d.id_layanan = l.id_layanan
    WHERE d.id_transaksi = '$id'
");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Detail Pesanan #<?= $id ?> | Kinclong Laundry</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: #F4F6FB;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #17243A;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .card-detail {
      background: #FFFFFF;
      border-radius: 18px;
      border: 1px solid #E2E8F0;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }
    .badge-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 30px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    .badge-status.selesai { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
    .badge-status.proses { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
    .badge-status.diambil { background: #FAF5FF; color: #7C3AED; border: 1px solid #E9D5FF; }
    .badge-status.default { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }
  </style>
</head>

<body>

<div class="container py-5 my-auto">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-7">

      <div class="card-detail p-4 p-md-5">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-3 flex-wrap gap-2">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <i class="bi bi-droplet-half text-primary fs-4"></i>
              <h4 class="fw-bold mb-0 text-primary">Kinclong Laundry</h4>
            </div>
            <p class="text-muted small mb-0">Rincian Cucian Pelanggan</p>
          </div>
          <div class="text-md-end">
            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fs-6">
              Order #<?= $id ?>
            </span>
          </div>
        </div>

        <?php if($trans): 
          $st = strtolower(trim($trans['status']));
          $badge_cls = 'default';
          if($st == 'selesai') $badge_cls = 'selesai';
          elseif(in_array($st, ['proses','diproses','dicuci','disetrika'])) $badge_cls = 'proses';
          elseif($st == 'diambil') $badge_cls = 'diambil';
        ?>
          <!-- Status Grid -->
          <div class="p-3 mb-4 rounded-3 bg-light border">
            <div class="row g-2 align-items-center">
              <div class="col-sm-6">
                <small class="text-muted d-block" style="font-size: 0.75rem;">NAMA PELANGGAN</small>
                <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($trans['nama_pelanggan'] ?? 'Pelanggan') ?></div>
                <small class="text-muted"><?= date('d F Y', strtotime($trans['tanggal'])) ?></small>
              </div>
              <div class="col-sm-6 text-sm-end">
                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">STATUS PESANAN</small>
                <span class="badge-status <?= $badge_cls ?>">
                  <?= ucfirst($trans['status']) ?>
                </span>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Table of Services -->
        <div class="table-responsive mb-4">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Layanan</th>
                <th class="text-center">Berat</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $calc_total = 0;
              while($r = mysqli_fetch_assoc($q)): 
                $calc_total += $r['subtotal'];
              ?>
              <tr>
                <td class="fw-semibold text-dark"><?= htmlspecialchars($r['nama_layanan']) ?></td>
                <td class="text-center"><span class="badge bg-light text-dark border"><?= $r['berat'] ?> Kg</span></td>
                <td class="text-end fw-bold text-dark">Rp <?= number_format($r['subtotal'], 0, ',', '.') ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Total Box -->
        <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary-subtle d-flex justify-content-between align-items-center mb-4">
          <span class="fw-bold text-primary">TOTAL BIAYA:</span>
          <span class="fw-bold text-primary fs-4">Rp <?= number_format($trans['total'] ?? $calc_total, 0, ',', '.') ?></span>
        </div>

        <!-- Back Button -->
        <a href="portal.php" class="btn btn-secondary w-100 py-2 fw-semibold">
          <i class="bi bi-arrow-left"></i> Kembali ke Pencarian Pesanan
        </a>

      </div>

    </div>
  </div>
</div>

</body>
</html>
