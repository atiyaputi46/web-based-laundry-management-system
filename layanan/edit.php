<?php
// layanan/edit.php - Edit Layanan
include '../config/koneksi.php';
include '../config/validasi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM layanan WHERE id_layanan=$id"));

if(!$data) { 
    include '../layout/header.php';
    echo '<div class="alert alert-danger">Layanan tidak ditemukan. <a href="index.php">Kembali</a></div>';
    include '../layout/footer.php';
    exit; 
}

if(isset($_POST['update'])){
    $nama = bersih($_POST['nama']);
    $harga = (int)$_POST['harga'];
    mysqli_query($koneksi, "UPDATE layanan SET nama_layanan='$nama', harga=$harga WHERE id_layanan=$id");
    header('Location: index.php');
    exit;
}

include '../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-pencil-square text-primary"></i> Edit Paket & Layanan
    </h2>
    <p class="page-subtitle">Perbarui nama layanan atau sesuaikan tarif harga.</p>
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
          <i class="bi bi-tag text-primary"></i> Perubahan Data Layanan #<?= $id ?>
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post">

          <div class="mb-3">
            <label class="form-label">Nama Layanan / Paket <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-basket"></i></span>
              <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($data['nama_layanan']) ?>" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Tarif / Harga (Rupiah) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text fw-bold">Rp</span>
              <input type="number" class="form-control" name="harga" value="<?= htmlspecialchars($data['harga']) ?>" min="0" required>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" class="btn btn-primary px-4" name="update">
              <i class="bi bi-save"></i> Simpan Perubahan
            </button>
            <a href="index.php" class="btn btn-secondary px-3">
              <i class="bi bi-arrow-left"></i> Batal
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
