<?php
// pelanggan/index.php - Halaman Utama Manajemen Pelanggan
include '../layout/header.php';
include '../config/koneksi.php';

// Logika Pencarian
$search = "";
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search = mysqli_real_escape_string($koneksi, trim($_GET['search']));
    $q = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE nama LIKE '%$search%' OR no_hp LIKE '%$search%' OR alamat LIKE '%$search%' ORDER BY id_pelanggan DESC");
} else {
    $q = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
}

$total_pelanggan = mysqli_num_rows($q);
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-people-fill text-primary"></i> Data Pelanggan
    </h2>
    <p class="page-subtitle">Kelola informasi data pelanggan, nomor telepon, dan riwayat alamat.</p>
  </div>
  <div>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-person-plus-fill"></i> Tambah Pelanggan
    </a>
  </div>
</div>

<!-- Search & Filter Card -->
<div class="card-modern mb-4">
  <div class="card-modern-body p-3">
    <form action="" method="get">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-5">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" placeholder="Cari nama, no hp, atau alamat..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
            <?php if(isset($_GET['search']) && $_GET['search'] !== ''): ?>
              <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-7 text-md-end text-muted small">
          Menampilkan <strong><?= $total_pelanggan ?></strong> pelanggan
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Customer Table Card -->
<div class="card-modern">
  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th width="80">ID</th>
          <th>Nama Pelanggan</th>
          <th>Nomor WhatsApp / HP</th>
          <th>Alamat Lengkap</th>
          <th width="160" class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if($total_pelanggan > 0): ?>
          <?php while($r = mysqli_fetch_assoc($q)): 
            $initial = strtoupper(substr($r['nama'], 0, 1));
            $clean_phone = preg_replace('/[^0-9]/', '', $r['no_hp'] ?? '');
            $wa_link = '';
            if(!empty($clean_phone)) {
              $wa_number = (substr($clean_phone, 0, 1) === '0') ? '62' . substr($clean_phone, 1) : $clean_phone;
              $wa_link = "https://wa.me/{$wa_number}";
            }
          ?>
            <tr>
              <td>
                <span class="fw-bold text-muted">#<?= $r['id_pelanggan'] ?></span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <span class="table-avatar"><?= $initial ?></span>
                  <div class="fw-semibold text-dark"><?= htmlspecialchars($r['nama']) ?></div>
                </div>
              </td>
              <td>
                <?php if(!empty($r['no_hp'])): ?>
                  <div class="d-flex align-items-center gap-2">
                    <span class="text-dark fw-medium"><?= htmlspecialchars($r['no_hp']) ?></span>
                    <?php if($wa_link): ?>
                      <a href="<?= $wa_link ?>" target="_blank" class="btn btn-sm btn-success p-1 px-2" title="Chat via WhatsApp" style="font-size: 0.72rem;">
                        <i class="bi bi-whatsapp"></i> Chat
                      </a>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="text-secondary"><?= htmlspecialchars($r['alamat']) ?></span>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <a class="btn btn-sm btn-warning" 
                     href="edit.php?id=<?= $r['id_pelanggan'] ?>"
                     title="Edit Data">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>

                  <a class="btn btn-sm btn-danger" 
                     href="hapus.php?id=<?= $r['id_pelanggan'] ?>"
                     onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')"
                     title="Hapus Data">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="bi bi-people"></i>
                </div>
                <div class="empty-state-title">Pelanggan Tidak Ditemukan</div>
                <div class="empty-state-text">
                  <?= isset($_GET['search']) ? 'Tidak ada hasil untuk kata kunci "'.htmlspecialchars($_GET['search']).'".' : 'Belum ada data pelanggan yang ditambahkan ke sistem.' ?>
                </div>
                <a href="tambah.php" class="btn btn-primary">
                  <i class="bi bi-person-plus-fill"></i> Tambah Pelanggan Baru
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
