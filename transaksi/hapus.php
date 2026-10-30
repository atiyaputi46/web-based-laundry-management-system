<?php
// transaksi/hapus.php - Script Hapus Transaksi
// Menghapus data transaksi (detail akan terhapus otomatis by CASCADE jika diset di DB).
include '../config/koneksi.php';
$id = (int)$_GET['id'];
mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_transaksi=$id");
header('Location: index.php');
exit;
