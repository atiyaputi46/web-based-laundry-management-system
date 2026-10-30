<?php
// layanan/hapus.php - Hapus Layanan
// Menghapus data layanan dari database.
include '../config/koneksi.php';
$id = (int)$_GET['id'];
mysqli_query($koneksi, "DELETE FROM layanan WHERE id_layanan=$id");
header('Location: index.php');
exit;
