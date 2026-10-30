<?php
// pelanggan/tambah.php - Halaman Tambah Pelanggan
include '../config/koneksi.php';

// Proses simpan data saat tombol 'simpan' ditekan
if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $no_hp  = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));

    mysqli_query($koneksi, "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES ('$nama','$no_hp','$alamat')");
    header('Location: index.php');
    exit;
}

include '../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-person-plus-fill text-primary"></i> Tambah Pelanggan Baru
    </h2>
    <p class="page-subtitle">Daftarkan pelanggan baru ke dalam sistem untuk mempermudah transaksi laundry.</p>
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
          <i class="bi bi-card-text text-primary"></i> Formulir Informasi Pelanggan
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post">

          <div class="mb-3">
            <label class="form-label">Nama Lengkap Pelanggan <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="nama" class="form-control" placeholder="Contoh: Ibu Rina Melati" required autofocus>
            </div>
            <div class="form-text">Masukkan nama lengkap atau nama panggilan yang mudah dikenali.</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-telephone"></i></span>
              <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required>
            </div>
            <div class="form-text">Digunakan untuk kirim notifikasi otomatis saat laundry selesai.</div>
          </div>

          <div class="mb-4">
            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text align-items-start pt-2"><i class="bi bi-geo-alt"></i></span>
              <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat jalan, nomor rumah, atau patokan lokasi..." required></textarea>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" name="simpan" class="btn btn-primary px-4">
              <i class="bi bi-check2-circle"></i> Simpan Pelanggan
            </button>
            <a href="index.php" class="btn btn-secondary px-3">
              <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="reset" class="btn btn-outline-danger ms-auto">
              <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
