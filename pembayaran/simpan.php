<?php
// pembayaran/simpan.php - Script Simpan Pembayaran
// Menyimpan data pembayaran ke tabel 'pembayaran' dan mengupdate status di tabel 'transaksi'.

include '../config/koneksi.php';

// Pastikan kolom status_bayar ada di tabel transaksi (sesuai spesifikasi di setup.php)
$checkCol = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'status_bayar'");
if ($checkCol && mysqli_num_rows($checkCol) == 0) {
    mysqli_query($koneksi, "ALTER TABLE transaksi ADD COLUMN status_bayar ENUM('Belum Lunas','Lunas') DEFAULT 'Belum Lunas'");
}

// Ambil dan sanitasi data dari form POST
$id_transaksi  = isset($_POST['id_transaksi']) ? (int) preg_replace('/[^0-9]/', '', $_POST['id_transaksi']) : 0;
$tanggal_bayar = !empty($_POST['tanggal_bayar']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_bayar']) : date('Y-m-d');
$metode        = !empty($_POST['metode']) ? mysqli_real_escape_string($koneksi, $_POST['metode']) : 'Cash';
$status_bayar  = isset($_POST['status_bayar']) ? mysqli_real_escape_string($koneksi, $_POST['status_bayar']) : 'Belum Lunas';

// Validasi ID Transaksi
if ($id_transaksi <= 0) {
    echo "<script>
            alert('ID Transaksi tidak valid atau belum dipilih!');
            window.location.href = 'index.php';
          </script>";
    exit;
}

// 1. Update atau Insert ke tabel pembayaran
// Cek dulu apakah sudah ada record pembayaran untuk ID transaksi ini
$cek = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id_transaksi = '$id_transaksi'");

if ($cek && mysqli_num_rows($cek) > 0) {
    // Update record pembayaran yang sudah ada
    $query_pembayaran = "UPDATE pembayaran SET 
                            tanggal_bayar = '$tanggal_bayar',
                            metode = '$metode'
                         WHERE id_transaksi = '$id_transaksi'";
} else {
    // Insert baru
    $query_pembayaran = "INSERT INTO pembayaran (id_transaksi, tanggal_bayar, metode, bukti) 
                         VALUES ('$id_transaksi', '$tanggal_bayar', '$metode', '')";
}

$exec_pembayaran = mysqli_query($koneksi, $query_pembayaran);

// 2. Update status bayar di tabel transaksi
$query_transaksi = "UPDATE transaksi SET status_bayar = '$status_bayar' WHERE id_transaksi = '$id_transaksi'";
$exec_transaksi = mysqli_query($koneksi, $query_transaksi);

// Cek keberhasilan kedua query
if ($exec_pembayaran && $exec_transaksi) {
    echo "<script>
            alert('Data pembayaran berhasil disimpan!');
            window.location.href = 'index.php';
          </script>";
} else {
    $err = mysqli_error($koneksi);
    echo "<script>
            alert('Gagal menyimpan data: " . addslashes($err) . "');
            history.go(-1);
          </script>";
}
?>
