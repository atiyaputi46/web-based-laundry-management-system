<?php
// transaksi/lihat.php - Halaman Detail Invoice Transaksi
include '../layout/header.php';
include '../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$t = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT t.*, p.nama as pelanggan, p.no_hp, p.alamat 
    FROM transaksi t 
    LEFT JOIN pelanggan p ON t.id_pelanggan=p.id_pelanggan 
    WHERE t.id_transaksi=$id
"));

if(!$t) { 
    echo '<div class="alert alert-danger">Transaksi tidak ditemukan. <a href="index.php">Kembali</a></div>'; 
    include '../layout/footer.php'; 
    exit; 
}

$details = mysqli_query($koneksi, "
    SELECT d.*, l.nama_layanan 
    FROM transaksi_detail d 
    LEFT JOIN layanan l ON d.id_layanan=l.id_layanan 
    WHERE d.id_transaksi=$id
");

$status_bayar = $t['status_bayar'] ?? 'Belum Lunas';
$status_lower = strtolower(trim($t['status']));
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-file-earmark-text text-primary"></i> Detail Transaksi #<?= $t['id_transaksi'] ?>
    </h2>
    <p class="page-subtitle">Rincian layanan, status pengerjaan, dan bukti pembayaran transaksi.</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-secondary" href="index.php">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <button class="btn btn-primary" id="btnPrint">
      <i class="bi bi-printer"></i> Cetak Nota
    </button>
    <button class="btn btn-success" id="btnPdf">
      <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
    </button>
  </div>
</div>

<div class="row g-4">

  <!-- Left: Invoice / Nota Presentation -->
  <div class="col-12 col-lg-8">
    <div class="card-modern" id="nota">
      <div class="card-modern-body p-4">

        <!-- Invoice Brand & Meta Header -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4 flex-wrap gap-3">
          <div>
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="p-2 rounded-2 bg-primary text-white fs-5">
                <i class="bi bi-droplet-half"></i>
              </div>
              <h4 class="fw-bold text-primary mb-0">Kinclong Laundry</h4>
            </div>
            <p class="text-muted small mb-0">
              Layanan Cuci Bersih, Higienis & Terpercaya<br>
              Padang, Sumatera Barat
            </p>
          </div>
          <div class="text-lg-end">
            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fs-6 mb-2 d-inline-block">
              INVOICE #<?= $t['id_transaksi'] ?>
            </span>
            <div class="text-muted small">
              Tanggal: <strong><?= date('d M Y', strtotime($t['tanggal'])) ?></strong>
            </div>
          </div>
        </div>

        <!-- Customer & Status Info Grid -->
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <div class="p-3 rounded-3 bg-light border">
              <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Informasi Pelanggan</small>
              <div class="fw-bold text-dark fs-6 mt-1"><?= htmlspecialchars($t['pelanggan'] ?? 'Pelanggan Umum') ?></div>
              <div class="text-muted small mt-1">
                <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($t['no_hp'] ?? '-') ?><br>
                <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($t['alamat'] ?? '-') ?>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="p-3 rounded-3 bg-light border">
              <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Status Transaksi</small>
              <div class="d-flex align-items-center gap-2 mt-2">
                <span class="text-muted small">Cucian:</span>
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
              </div>
              <div class="d-flex align-items-center gap-2 mt-2">
                <span class="text-muted small">Pembayaran:</span>
                <?php if($status_bayar == 'Lunas'): ?>
                  <span class="badge-status lunas"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                <?php else: ?>
                  <span class="badge-status belum-lunas"><i class="bi bi-clock"></i> Belum Lunas</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Detail Layanan Table -->
        <div class="table-responsive mb-4">
          <table class="table-modern">
            <thead>
              <tr>
                <th>Layanan</th>
                <th class="text-center">Berat / Qty</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $total_calc = 0;
              while($d = mysqli_fetch_assoc($details)): 
                $total_calc += $d['subtotal'];
              ?>
                <tr>
                  <td>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($d['nama_layanan']) ?></span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-light text-dark border"><?= $d['berat'] ?> Kg</span>
                  </td>
                  <td class="text-end fw-semibold text-dark">
                    Rp <?= number_format($d['subtotal'], 0, ',', '.') ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Grand Total Banner -->
        <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary-subtle d-flex justify-content-between align-items-center">
          <span class="fw-bold text-primary fs-6">TOTAL TAGIHAN</span>
          <span class="fw-bold text-primary fs-4">Rp <?= number_format($t['total'], 0, ',', '.') ?></span>
        </div>

      </div>
    </div>
  </div>

  <!-- Right: Control Panel (Update Status & Quick Action) -->
  <div class="col-12 col-lg-4">
    <!-- Panel Update Status -->
    <div class="card-modern mb-4">
      <div class="card-modern-header">
        <h5 class="card-modern-title">
          <i class="bi bi-sliders text-primary"></i> Kelola Status Cucian
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post" action="update_status.php" id="formUpdateStatus">
          <input type="hidden" name="id" value="<?= $t['id_transaksi'] ?>">
          
          <label class="form-label">Status Pengerjaan Saat Ini</label>
          <select name="status" class="form-select mb-3">
            <option value="diproses" <?= $status_lower=='diproses'?'selected':'' ?>>1. Diproses</option>
            <option value="dicuci" <?= $status_lower=='dicuci'?'selected':'' ?>>2. Dicuci</option>
            <option value="disetrika" <?= $status_lower=='disetrika'?'selected':'' ?>>3. Disetrika</option>
            <option value="selesai" <?= $status_lower=='selesai'?'selected':'' ?>>4. Selesai (Siap Diambil)</option>
            <option value="diambil" <?= $status_lower=='diambil'?'selected':'' ?>>5. Diambil (Selesai Diserahkan)</option>
          </select>

          <button type="submit" class="btn btn-primary w-100 mb-2">
            <i class="bi bi-arrow-repeat"></i> Update Status Cucian
          </button>
          
          <small class="text-muted d-block text-center" style="font-size: 0.78rem;">
            Status "Diambil" mewajibkan status pembayaran sudah Lunas.
          </small>
        </form>
      </div>
    </div>

    <!-- Panel Status Pembayaran -->
    <div class="card-modern">
      <div class="card-modern-header">
        <h5 class="card-modern-title">
          <i class="bi bi-wallet2 text-success"></i> Status Pembayaran
        </h5>
      </div>
      <div class="card-modern-body text-center">
        <?php if($status_bayar == 'Lunas'): ?>
          <div class="p-3 mb-3 rounded-3 bg-success bg-opacity-10 text-success border border-success-subtle">
            <i class="bi bi-check-circle-fill fs-2 d-block mb-1"></i>
            <div class="fw-bold">Tagihan Telah Lunas</div>
            <small>Pembayaran sudah terverifikasi di sistem.</small>
          </div>
        <?php else: ?>
          <div class="p-3 mb-3 rounded-3 bg-danger bg-opacity-10 text-danger border border-danger-subtle">
            <i class="bi bi-exclamation-circle-fill fs-2 d-block mb-1"></i>
            <div class="fw-bold">Menunggu Pembayaran</div>
            <small>Total tagihan sebesar Rp <?= number_format($t['total'], 0, ',', '.') ?> belum dibayar.</small>
          </div>
        <?php endif; ?>

        <a href="../pembayaran/tambah.php?id_transaksi=<?= $t['id_transaksi'] ?>" class="btn btn-outline-primary w-100">
          <i class="bi bi-pencil-square"></i> Kelola Pembayaran
        </a>
      </div>
    </div>
  </div>

</div>

<!-- Scripts html2canvas & jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// Print (Membuka jendela cetak.php)
document.getElementById('btnPrint').addEventListener('click', function(){
  const id = "<?= $t['id_transaksi'] ?>";
  window.open('../cetak.php?id='+id, '_blank', `width=${screen.width},height=${screen.height},top=0,left=0,resizable=yes,scrollbars=yes`);
});

// Validasi Status Laundry 'diambil'
document.getElementById('formUpdateStatus').addEventListener('submit', function(e){
  const statusSelect = document.querySelector('select[name="status"]');
  const selectedStatus = statusSelect.value;
  const statusBayar = "<?= $status_bayar ?>"; 

  if(selectedStatus === 'diambil' && statusBayar !== 'Lunas'){
      e.preventDefault();
      alert('Status Laundry tidak dapat diubah menjadi "Diambil" karena Status Pembayaran belum Lunas! Silakan update status pembayaran terlebih dahulu.');
  }
});

// Download PDF
document.getElementById('btnPdf').addEventListener('click', async function(){
  const nota = document.getElementById('nota');
  const canvas = await html2canvas(nota, { scale: 2 });
  const imgData = canvas.toDataURL('image/png');
  
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF('p','pt','a4');
  const imgProps = pdf.getImageProperties(imgData);
  const pdfWidth = pdf.internal.pageSize.getWidth();
  const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
  
  pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
  pdf.save('nota_laundry_<?= $t['id_transaksi'] ?>.pdf');
});
</script>

<?php include '../layout/footer.php'; ?>
