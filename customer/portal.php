<?php
include '../config/koneksi.php';
include '../cust/partials/header.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$results = [];
$search_attempted = false;

if ($keyword) {
    $search_attempted = true;
    $keyword_esc = mysqli_real_escape_string($koneksi, $keyword);

    // 1. Cari berdasarkan ID Transaksi (Exact Match)
    $q_trans = mysqli_query($koneksi, "SELECT t.*, p.nama as nama_pelanggan, p.no_hp FROM transaksi t JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan WHERE t.id_transaksi = '$keyword_esc'");
    while($r = mysqli_fetch_assoc($q_trans)) {
        $r['type'] = 'ID Pesanan';
        $results[] = $r;
    }

    // 2. Cari berdasarkan 4 Digit Terakhir No HP
    // Hanya jika keyword berupa angka dan panjang <= 4 (atau sesuai kebutuhan)
    if(is_numeric($keyword)) {
       $q_cust = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE RIGHT(no_hp, 4) = '$keyword_esc'");
       while($c = mysqli_fetch_assoc($q_cust)) {
           $pid = $c['id_pelanggan'];
           // Ambil 5 transaksi terakhir
           $qt = mysqli_query($koneksi, "SELECT t.*, p.nama as nama_pelanggan, p.no_hp FROM transaksi t JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan WHERE t.id_pelanggan = '$pid' ORDER BY t.id_transaksi DESC LIMIT 5");
           while($rt = mysqli_fetch_assoc($qt)) {
               $rt['type'] = 'No HP (...' . substr($c['no_hp'], -4) . ')';
               // Hindari duplikasi jika sudah ditemukan by ID
               $exists = false;
               foreach($results as $res) {
                   if($res['id_transaksi'] == $rt['id_transaksi']) $exists = true;
               }
               if(!$exists) $results[] = $rt;
           }
       }
    }
}
?>

<div class="bg-primary bg-opacity-10 py-4">
    <div class="container text-center py-3">
        <h1 class="fw-bold mb-3">Cek Status Laundry Anda</h1>
        <p class="text-muted mb-4">
            Masukkan <strong>ID Pesanan</strong> atau <strong>4 Digit Terakhir Nomor HP</strong> Anda.
        </p>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="GET" class="card p-2 rounded-pill shadow-sm border-0">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control border-0 bg-transparent ps-4" 
                               placeholder="Contoh: 1001 atau 4567" 
                               value="<?= htmlspecialchars($keyword) ?>" required>
                        <button class="btn btn-primary rounded-pill px-4" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container py-2">
    <?php if($search_attempted): ?>
        <?php if(count($results) > 0): ?>
            <!-- Results loop... (unchanged) -->
            <h4 class="fw-bold mb-4">Hasil Pencarian "<?= htmlspecialchars($keyword) ?>"</h4>
            
            <div class="row g-4">
                <?php foreach($results as $r): ?>
                    <!-- ... card content ... -->
                    <!-- (Using original content for context matching if needed, but here I can just keep the structure around the else) -->
                    <!-- Wait, replace_file_content works on chunks. I should confirm the lines. -->

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Order #<?= $r['id_transaksi'] ?></span>
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($r['nama_pelanggan']) ?></h5>
                                        <small class="text-muted"><?= date('d F Y', strtotime($r['tanggal'])) ?></small>
                                    </div>
                                    <?php
                                    $status_class = 'bg-secondary';
                                    if(strtolower($r['status']) == 'selesai') $status_class = 'bg-success';
                                    elseif(strtolower($r['status']) == 'proses') $status_class = 'bg-warning text-dark';
                                    elseif(strtolower($r['status']) == 'baru') $status_class = 'bg-info text-dark';
                                    ?>
                                    <span class="badge <?= $status_class ?> rounded-pill px-3 py-2">
                                        <?= htmlspecialchars($r['status']) ?>
                                    </span>
                                </div>
                                
                                <div class="alert alert-light border-0 small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Total Biaya:</span>
                                        <strong>Rp <?= number_format($r['total'], 0, ',', '.') ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Status Pembayaran:</span>
                                        <?php if(($r['status_bayar'] ?? '') === 'Lunas'): ?>
                                            <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                        <?php else: ?>
                                            <span class="text-danger fw-bold">Belum Lunas</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <a href="detail.php?id=<?= $r['id_transaksi'] ?>" class="btn btn-outline-primary btn-sm rounded-pill flex-fill">Lihat Detail</a>
                                    <!-- Struk button removed as per request -->
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                <h4 class="fw-bold">Pesanan Tidak Ditemukan</h4>
                <p class="text-muted">Coba periksa ID Pesanan atau Nomor HP yang Anda masukkan.</p>
                <a href="portal.php" class="btn btn-outline-primary rounded-pill mt-3">Reset Pencarian</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center text-muted py-2">
            <small class="fw-normal">Silakan masukkan data pencarian di atas</small>
        </div>
    <?php endif; ?>
</div>

<!-- Simple Footer for Portal Context (Or include main footer) -->
<?php include '../cust/partials/footer.php'; ?>