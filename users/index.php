<?php
// users/index.php - Manajemen Pengguna Sistem (Admin & Kasir)
include '../layout/header.php';
include '../config/koneksi.php';

// Proteksi role admin
if($_SESSION['user']['role'] !== 'admin'){
    echo '<div class="alert alert-danger m-4">Akses ditolak. Halaman ini hanya untuk Administrator.</div>';
    include '../layout/footer.php';
    exit;
}

$q = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id_user DESC");
$total_users = mysqli_num_rows($q);
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-shield-lock-fill text-primary"></i> Manajemen Pengguna
    </h2>
    <p class="page-subtitle">Kelola hak akses akun staf administrator dan kasir laundry.</p>
  </div>
  <div>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-person-plus-fill"></i> Tambah User Baru
    </a>
  </div>
</div>

<!-- Users Table Card -->
<div class="card-modern">
  <div class="card-modern-header">
    <h5 class="card-modern-title">
      <i class="bi bi-people text-primary"></i> Daftar Akun Pengguna (<?= $total_users ?> Akun)
    </h5>
  </div>

  <div class="table-responsive">
    <table class="table-modern">
      <thead>
        <tr>
          <th width="80">ID</th>
          <th>Nama Staf</th>
          <th>Username</th>
          <th>Hak Akses (Role)</th>
          <th width="160" class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if($total_users > 0): ?>
          <?php while($r = mysqli_fetch_assoc($q)): 
            $initial = strtoupper(substr($r['nama'], 0, 1));
            $is_current_user = ($r['id_user'] == $_SESSION['user']['id_user']);
          ?>
            <tr>
              <td>
                <span class="text-muted fw-bold">#<?= $r['id_user'] ?></span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <span class="table-avatar"><?= $initial ?></span>
                  <div>
                    <div class="fw-semibold text-dark">
                      <?= htmlspecialchars($r['nama']) ?>
                      <?php if($is_current_user): ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary ms-1" style="font-size: 0.65rem;">Anda</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <span class="text-muted fw-mono">@<?= htmlspecialchars($r['username']) ?></span>
              </td>
              <td>
                <?php if($r['role'] === 'admin'): ?>
                  <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                    <i class="bi bi-shield-shaded me-1"></i>Administrator
                  </span>
                <?php else: ?>
                  <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle px-2 py-1 rounded-pill">
                    <i class="bi bi-person me-1"></i>Kasir
                  </span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <a href="edit.php?id=<?= $r['id_user'] ?>" 
                     class="btn btn-sm btn-warning"
                     title="Edit Akun">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>

                  <?php if(!$is_current_user): ?>
                    <a href="hapus.php?id=<?= $r['id_user'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                       title="Hapus Akun">
                      <i class="bi bi-trash"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">
              <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-person-x"></i></div>
                <div class="empty-state-title">Belum Ada Pengguna</div>
                <div class="empty-state-text">Daftarkan akun kasir atau staf baru untuk mengoperasikan sistem.</div>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
