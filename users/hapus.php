<?php
// users/hapus.php - Hapus User
// Menghapus user, dengan proteksi tidak bisa menghapus diri sendiri.
session_start();
include '../config/koneksi.php';

if($_SESSION['user']['role'] !== 'admin') { 
    die('Akses ditolak'); 
}

$id = intval($_GET['id']);

if($id == $_SESSION['user']['id_user']){
    echo "<script>alert('Anda tidak bisa menghapus akun Anda sendiri!');location='index.php';</script>";
    exit;
}


$q = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id'");
$data = mysqli_fetch_assoc($q);

if(!$data){
    echo "<script>alert('User tidak ditemukan');location='index.php';</script>";
    exit;
}

mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");

echo "<script>alert('User berhasil dihapus');location='index.php';</script>";
?>
