<?php
// layanan/tambah.php - Tambah Layanan Baru
include '../config/koneksi.php';
include '../config/validasi.php';

if(isset($_POST['simpan'])){
    $nama = bersih($_POST['nama']);
    $harga = (int)$_POST['harga'];
    mysqli_query($koneksi, "INSERT INTO layanan (nama_layanan, harga) VALUES ('$nama', $harga)");
    header('Location: index.php');
    exit;
}

include '../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-tag-fill text-primary"></i> Tambah Layanan Baru
    </h2>
    <p class="page-subtitle">Tentukan nama jenis cucian dan tarif per kilogram atau satuan.</p>
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
          <i class="bi bi-plus-square text-primary"></i> Informasi Paket & Tarif
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post">

          <div class="mb-3">
            <label class="form-label">Nama Layanan / Paket <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-basket"></i></span>
              <input type="text" class="form-control" name="nama" placeholder="Contoh: Cuci Kering Setrika / Selimut" required autofocus>
            </div>
            <div class="form-text">Contoh: Cuci Komplit, Setrika Saja, Bed Cover, dsb.</div>
          </div>

          <div class="mb-4">
            <label class="form-label">Tarif / Harga (Rupiah) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text fw-bold">Rp</span>
              <input type="number" class="form-control" name="harga" placeholder="Contoh: 8000" min="0" required>
            </div>
            <div class="form-text">Masukkan nominal angka tanpa titik atau koma.</div>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" class="btn btn-primary px-4" name="simpan">
              <i class="bi bi-check2-circle"></i> Simpan Layanan
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

<?php include '../layout/footer.php'; ?>
