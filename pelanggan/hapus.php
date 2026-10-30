<?php
// pelanggan/hapus.php - Script Hapus Pelanggan
// Menghapus data pelanggan berdasarkan ID yang dikirim.
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Eksekusi query DELETE
$hapus = mysqli_query(
    $koneksi,
    "DELETE FROM pelanggan WHERE id_pelanggan='$id'"
);

if ($hapus) {
    header('Location: index.php');
    exit;
} else {
    echo "Gagal hapus data";
}
?>
