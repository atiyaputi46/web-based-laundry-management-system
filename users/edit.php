<?php
// users/edit.php - Edit Data User
include '../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if($_SESSION['user']['role'] !== 'admin') { 
    die('Akses ditolak'); 
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$q = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id'");
$data = mysqli_fetch_assoc($q);

if(!$data){
    include '../layout/header.php';
    echo '<div class="alert alert-danger m-4">User tidak ditemukan. <a href="index.php">Kembali</a></div>';
    include '../layout/footer.php';
    exit;
}

if(isset($_POST['simpan'])){
    $nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $role     = mysqli_real_escape_string($koneksi, trim($_POST['role']));

    if(!empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($koneksi, "
            UPDATE user SET 
                nama='$nama',
                username='$username',
                password='$password',
                role='$role'
            WHERE id_user='$id'
        ");
    } else {
        mysqli_query($koneksi, "
            UPDATE user SET 
                nama='$nama',
                username='$username',
                role='$role'
            WHERE id_user='$id'
        ");
    }

    // Jika yang diedit adalah akun sendiri, perbarui data sesi
    if($_SESSION['user']['id_user'] == $id){
        $_SESSION['user']['nama'] = $nama;
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['role'] = $role;
    }

    header('Location: index.php');
    exit;
}

include '../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-pencil-square text-primary"></i> Edit Data User #<?= $id ?>
    </h2>
    <p class="page-subtitle">Perbarui profil pengguna, username, role, atau reset password.</p>
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
          <i class="bi bi-person-gear text-primary"></i> Informasi Akun Pengguna
        </h5>
      </div>
      <div class="card-modern-body">
        <form method="post">

          <div class="mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Username Login <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-at"></i></span>
              <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required autocomplete="off">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Password Baru <span class="text-muted fw-normal">(Opsional)</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" id="inputPass" class="form-control" placeholder="Kosongkan jika password tidak ingin diubah">
              <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
                <i class="bi bi-eye" id="iconPass"></i>
              </button>
            </div>
            <div class="form-text">Isi hanya jika ingin mengganti password pengguna ini.</div>
          </div>

          <div class="mb-4">
            <label class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
              <select name="role" class="form-select" required>
                <option value="kasir" <?= $data['role']=='kasir'?'selected':'' ?>>Kasir (Kelola Transaksi & Pelanggan)</option>
                <option value="admin" <?= $data['role']=='admin'?'selected':'' ?>>Administrator (Akses Penuh Seluruh Sistem)</option>
              </select>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap gap-2 pt-2 border-top">
            <button type="submit" class="btn btn-primary px-4" name="simpan">
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
