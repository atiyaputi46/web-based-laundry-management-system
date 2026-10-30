<?php
// transaksi/update_status.php - Script Update Status Laundry
// Mengubah status transaksi (Proses/Dicuci/Selesai/Diambil).

include '../config/koneksi.php';
$id = (int)$_POST['id'];
$status = mysqli_real_escape_string($koneksi, $_POST['status']);

// Validasi Status: Cek status pembayaran jika ingin set status 'diambil'
if($status == 'diambil') {
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT status_bayar FROM transaksi WHERE id_transaksi=$id"));
    // Handle case where status_bayar might be null or empty
    $status_bayar = $cek['status_bayar'] ?? 'Belum Lunas';
    
    if($status_bayar != 'Lunas'){
         echo "<script>alert('Status Laundry tidak dapat diubah menjadi Diambil karena Status Pembayaran belum Lunas!'); window.location='lihat.php?id=$id';</script>";
         exit;
    }
}

mysqli_query($koneksi, "UPDATE transaksi SET status='$status' WHERE id_transaksi=$id");
header("Location: lihat.php?id=$id");
exit;
