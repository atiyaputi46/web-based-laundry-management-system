<?php
// pelanggan/edit.php - Halaman Edit Pelanggan
include '../config/koneksi.php';
include '../config/validasi.php';

// Mengambil ID dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* Ambil data pelanggan berdasarkan ID untuk ditampilkan di form */
$data = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan=$id")
);

// Validasi jika data tidak ditemukan
if(!$data){
    include '../layout/header.php';
    echo '<div class="alert alert-danger">Data pelanggan tidak ditemukan. <a href="index.php">Kembali</a></div>';
    include '../layout/footer.php';
    exit;
}

// Proses update saat form disubmit
if(isset($_POST['update'])){
    $nama   = bersih($_POST['nama']);
    $hp     = bersih($_POST['hp']);
    $alamat = bersih($_POST['alamat']);

    // Query UPDATE data pelanggan
    mysqli_query($koneksi, "
        UPDATE pelanggan SET
            nama='$nama',
            no_hp='$hp',
            alamat='$alamat'
        WHERE id_pelanggan=$id
    ");

    header('Location: index.php');
    exit;
}

include '../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-pencil-square text-primary"></i> Edit Data Pelanggan
    </h2>
    <p class="page-subtitle">Perbarui rincian informasi kontak dan alamat pelanggan #<?= $id ?>.</p>
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
          <i class="bi bi-person-gear text-primary"></i> Formulir Perubahan Data Pelanggan
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post">

          <div class="mb-3">
            <label class="form-label">Nama Lengkap Pelanggan <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-telephone"></i></span>
              <input type="text" name="hp" class="form-control" value="<?= htmlspecialchars($data['no_hp']) ?>" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text align-items-start pt-2"><i class="bi bi-geo-alt"></i></span>
              <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" name="update" class="btn btn-primary px-4">
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
