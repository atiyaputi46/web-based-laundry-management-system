<?php
// laporan/export.php - Export Data Transaksi ke Excel (.xls)
session_start();
if(!isset($_SESSION['login'])){
    header('Location: /laundry_project/auth/login.php');
    exit;
}

include '../config/koneksi.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_transaksi_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

$q = mysqli_query($koneksi, "
    SELECT t.*, p.nama AS nama_pelanggan, p.no_hp
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.id_transaksi DESC
");
?>
<table border="1">
    <thead>
        <tr style="background-color: #6366f1; color: white;">
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>No. HP</th>
            <th>Tanggal</th>
            <th>Total (Rp)</th>
            <th>Status Pengerjaan</th>
            <th>Status Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $total_omzet = 0;
        while($r = mysqli_fetch_assoc($q)): 
            $total_omzet += $r['total'];
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td>#<?= $r['id_transaksi'] ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan'] ?? '-') ?></td>
            <td><?= htmlspecialchars($r['no_hp'] ?? '-') ?></td>
            <td><?= $r['tanggal'] ?></td>
            <td><?= $r['total'] ?></td>
            <td><?= ucfirst($r['status']) ?></td>
            <td><?= $r['status_bayar'] ?? 'Belum Lunas' ?></td>
        </tr>
        <?php endwhile; ?>
        <tr style="font-weight: bold; background-color: #f1f5f9;">
            <td colspan="5" align="right">Total Keseluruhan:</td>
            <td><?= $total_omzet ?></td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>
