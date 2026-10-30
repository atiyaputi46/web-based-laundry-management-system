<?php
// transaksi/tambah.php - Halaman Tambah Transaksi Laundry
include '../config/koneksi.php';
include '../config/validasi.php';

if(isset($_POST['simpan'])){
    // 1. Simpan Header Transaksi (Total simpan 0 terlebih dahulu)
    $id_pelanggan = (int)$_POST['pelanggan'];
    $tanggal = bersih($_POST['tanggal']);
    mysqli_query($koneksi, "INSERT INTO transaksi (id_pelanggan, tanggal, total, status, status_bayar) VALUES ($id_pelanggan, '$tanggal', 0, 'diproses', 'Belum Lunas')");
    
    // Ambil ID Transaksi yang baru di-generate
    $id_trans = mysqli_insert_id($koneksi);
    
    // 2. Simpan Detail Layanan (Looping item dinamis)
    $total = 0;
    if(isset($_POST['layanan']) && is_array($_POST['layanan'])){
        foreach($_POST['layanan'] as $idx => $id_layanan){
            $id_layanan = (int)$id_layanan;
            $berat = (float)($_POST['berat'][$idx] ?? 0);
            $harga = (int)($_POST['harga'][$idx] ?? 0);
            $subtotal = (int)round($berat * $harga);
            $total += $subtotal;
            
            // Insert ke transaksi_detail
            mysqli_query($koneksi, "INSERT INTO transaksi_detail (id_transaksi, id_layanan, berat, subtotal) VALUES ($id_trans, $id_layanan, $berat, $subtotal)");
        }
    }
    
    // 3. Update Total Transaksi
    mysqli_query($koneksi, "UPDATE transaksi SET total=$total WHERE id_transaksi=$id_trans");
    
    header('Location: index.php');
    exit;
}

include '../layout/header.php';

$pel = mysqli_query($koneksi, 'SELECT * FROM pelanggan ORDER BY nama ASC');
$lay_options = [];
$lay_res = mysqli_query($koneksi, 'SELECT * FROM layanan ORDER BY nama_layanan ASC');
while($row = mysqli_fetch_assoc($lay_res)) {
    $lay_options[] = $row;
}
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-cart-plus-fill text-primary"></i> Buat Transaksi Baru
    </h2>
    <p class="page-subtitle">Input data penerimaan laundry, pilih jenis layanan, dan hitung biaya otomatis.</p>
  </div>
  <div>
    <a href="index.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
  </div>
</div>

<form method="post" id="formTransaksi">
  <div class="row g-4">
    
    <!-- Kolom Kiri: Data Utama & Detail Item -->
    <div class="col-12 col-lg-8">
      
      <!-- Card Data Pelanggan & Tanggal -->
      <div class="card-modern mb-4">
        <div class="card-modern-header">
          <h5 class="card-modern-title">
            <i class="bi bi-person-lines-fill text-primary"></i> Informasi Penerimaan
          </h5>
        </div>
        <div class="card-modern-body">
          <div class="row g-3">
            <div class="col-12 col-md-7">
              <label class="form-label">Pilih Pelanggan <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <select name="pelanggan" class="form-select" required>
                  <option value="">-- Pilih Nama Pelanggan --</option>
                  <?php while($p = mysqli_fetch_assoc($pel)): ?>
                    <option value="<?= $p['id_pelanggan'] ?>">
                      <?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['no_hp'] ?? 'No HP -') ?>)
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-text">
                Belum terdaftar? <a href="../pelanggan/tambah.php" target="_blank" class="fw-semibold">+ Tambah Pelanggan Baru</a>
              </div>
            </div>

            <div class="col-12 col-md-5">
              <label class="form-label">Tanggal Masuk Cucian <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                <input class="form-control" type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Rincian Layanan Dinamis -->
      <div class="card-modern">
        <div class="card-modern-header">
          <h5 class="card-modern-title">
            <i class="bi bi-basket text-primary"></i> Rincian Paket & Timbangan Cucian
          </h5>
          <button class="btn btn-sm btn-outline-primary" id="add" type="button">
            <i class="bi bi-plus-circle"></i> Tambah Item Lain
          </button>
        </div>
        <div class="card-modern-body">
          <div id="items" class="d-flex flex-column gap-3">
            
            <!-- Template Baris Item Pertama -->
            <div class="p-3 rounded-3 border bg-light item position-relative">
              <div class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                  <label class="form-label small mb-1">Jenis Layanan</label>
                  <select class="form-select layanan_select" name="layanan[]" required>
                    <?php foreach($lay_options as $l): ?>
                      <option value="<?= $l['id_layanan'] ?>" data-harga="<?= $l['harga'] ?>">
                        <?= htmlspecialchars($l['nama_layanan']) ?> (Rp <?= number_format($l['harga'], 0, ',', '.') ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-6 col-md-2">
                  <label class="form-label small mb-1">Berat (Kg/Pcs)</label>
                  <input type="number" step="0.1" min="0.1" class="form-control berat" name="berat[]" placeholder="0.0" value="1" required>
                </div>

                <div class="col-6 col-md-2">
                  <label class="form-label small mb-1">Tarif (Rp)</label>
                  <input class="form-control harga bg-white" name="harga[]" placeholder="0" readonly>
                </div>

                <div class="col-10 col-md-2">
                  <label class="form-label small mb-1">Subtotal (Rp)</label>
                  <input class="form-control subtotal bg-white fw-bold text-dark" name="subtotal[]" placeholder="0" readonly>
                </div>

                <div class="col-2 col-md-1 text-end pt-md-4">
                  <button class="btn btn-outline-danger btn-sm remove w-100" type="button" title="Hapus Item">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

    <!-- Kolom Kanan: Ringkasan Total & Submit -->
    <div class="col-12 col-lg-4">
      <div class="card-modern sticky-top" style="top: 90px;">
        <div class="card-modern-header">
          <h5 class="card-modern-title">
            <i class="bi bi-cash-stack text-success"></i> Ringkasan Order
          </h5>
        </div>
        <div class="card-modern-body">
          
          <div class="p-3 rounded-3 bg-light border mb-3">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Status Awal:</span>
              <span class="badge-status diproses"><i class="bi bi-arrow-repeat"></i> Diproses</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Status Bayar:</span>
              <span class="badge-status belum-lunas"><i class="bi bi-clock"></i> Belum Lunas</span>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label text-uppercase small fw-bold text-muted">Grand Total Biaya</label>
            <div class="input-group">
              <span class="input-group-text fw-bold fs-5 bg-primary text-white">Rp</span>
              <input class="form-control form-control-lg fw-bold text-primary fs-3 bg-white" id="total" name="total_display" value="0" readonly>
            </div>
          </div>

          <button class="btn btn-primary w-100 py-3 fs-6 mb-2" name="simpan" type="submit">
            <i class="bi bi-check2-circle fs-5"></i> Simpan Transaksi Laundry
          </button>

          <a href="index.php" class="btn btn-secondary w-100">
            <i class="bi bi-x"></i> Batalkan
          </a>

        </div>
      </div>
    </div>

  </div>
</form>

<script>
// Script Kalkulasi Dinamis Layanan Transaksi
function updateRow(row){
  const select = row.querySelector('.layanan_select');
  const hargaInput = row.querySelector('.harga');
  const beratInput = row.querySelector('.berat');
  const subtotalInput = row.querySelector('.subtotal');
  
  if(!select || !select.selectedOptions[0]) return;
  const harga = parseInt(select.selectedOptions[0].dataset.harga || 0);
  hargaInput.value = harga;
  
  const berat = parseFloat(beratInput.value || 0);
  subtotalInput.value = Math.round(harga * berat);
  
  updateTotal();
}

function updateTotal(){
  let total = 0;
  document.querySelectorAll('.subtotal').forEach(s => {
    total += parseInt(s.value || 0);
  });
  document.getElementById('total').value = Number(total).toLocaleString('id-ID');
}

// Event Delegation
document.getElementById('items').addEventListener('input', function(e){
  if(e.target.classList.contains('berat') || e.target.classList.contains('harga')){
    const row = e.target.closest('.item');
    updateRow(row);
  }
});

document.getElementById('items').addEventListener('change', function(e){
  if(e.target.classList.contains('layanan_select')){
    const row = e.target.closest('.item');
    updateRow(row);
  }
});

// Tombol Tambah Item
document.getElementById('add').addEventListener('click', function(){
  const container = document.getElementById('items');
  const firstItem = container.querySelector('.item');
  if(!firstItem) return;
  
  const clone = firstItem.cloneNode(true);
  // Reset input value pada clone
  clone.querySelector('.berat').value = "1";
  container.appendChild(clone);
  updateRow(clone);
});

// Tombol Hapus Item
document.getElementById('items').addEventListener('click', function(e){
  const removeBtn = e.target.closest('.remove');
  if(removeBtn) {
    const items = document.querySelectorAll('.item');
    if(items.length > 1) {
      removeBtn.closest('.item').remove();
      updateTotal();
    } else {
      alert('Transaksi harus memiliki minimal 1 item layanan.');
    }
  }
});

// Inisialisasi awal
document.querySelectorAll('.item').forEach(r => updateRow(r));
</script>

<?php include '../layout/footer.php'; ?>
