<?php
// users/tambah.php - Tambah User Baru
include '../config/koneksi.php';

// Proteksi role admin
if (session_status() === PHP_SESSION_NONE) session_start();
if($_SESSION['user']['role'] !== 'admin') { 
    die('Akses ditolak'); 
}

if(isset($_POST['simpan'])){
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($koneksi, trim($_POST['role']));
    
    mysqli_query($koneksi, "INSERT INTO user (nama, username, password, role) VALUES ('$nama', '$username', '$pass', '$role')");
    header('Location: index.php');
    exit;
}

include '../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-person-plus-fill text-primary"></i> Tambah User Baru
    </h2>
    <p class="page-subtitle">Daftarkan akun kasir atau staf baru ke dalam sistem.</p>
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
          <i class="bi bi-person-vcard text-primary"></i> Formulir Akun Pengguna
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post">

          <div class="mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" name="nama" placeholder="Contoh: Rian Pratama" required autofocus>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Username Login <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-at"></i></span>
              <input type="text" class="form-control" name="username" placeholder="Contoh: rian_kasir" required autocomplete="off">
            </div>
            <div class="form-text">Gunakan huruf kecil atau angka tanpa spasi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" name="password" id="inputPass" placeholder="Minimal 6 karakter" required>
              <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
                <i class="bi bi-eye" id="iconPass"></i>
              </button>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
              <select name="role" class="form-select" required>
                <option value="kasir">Kasir (Kelola Transaksi & Pelanggan)</option>
                <option value="admin">Administrator (Akses Penuh Seluruh Sistem)</option>
              </select>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" class="btn btn-primary px-4" name="simpan">
              <i class="bi bi-check2-circle"></i> Buat Akun Pengguna
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

<script>
function togglePass() {
  const p = document.getElementById('inputPass');
  const icon = document.getElementById('iconPass');
  if(p.type === 'password'){
    p.type = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    p.type = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>

<?php include '../layout/footer.php'; ?>
