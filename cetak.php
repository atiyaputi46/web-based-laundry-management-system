<?php
// cetak.php - Halaman untuk mencetak nota transaksi laundry
// File ini akan menampilkan detail transaksi dalam format siap cetak (print-friendly)
include 'config/koneksi.php';

//PENGAMANAN & VALIDASI INPUT ID
//Pengecekan apakah 'id' ada dan merupakan bilangan bulat positif
if (!isset($_GET['id']) || (int)$_GET['id'] <= 0) {
    die("Error: Parameter ID transaksi tidak valid atau tidak ditemukan. Harap akses melalui detail transaksi.");
}

//Mengambil dan membersihkan ID
$id = (int)$_GET['id'];
$k = $koneksi;

//INFORMASI LAUNDRY (Dapat disesuaikan atau diambil dari tabel konfigurasi)
$nama_laundry = "Kinclong Laundry";
$alamat_laundry = "Jl. Raya Cupak Tangah Kec. Pauh, Kota Padang, Sumatera Barat";
$kontak_laundry = "Telp: 083189252389";


//PROSES DATA MENGGUNAKAN PREPARED STATEMENTS (KEAMANAN SQL INJECTION)

// Ambil data Transaksi Utama berdasarkan ID
// Menggunakan LEFT JOIN untuk mengambil data pelanggan sekaligus
$stmt_t = mysqli_prepare($k, "SELECT t.*, p.nama as pelanggan, p.alamat as alamat_pelanggan FROM transaksi t LEFT JOIN pelanggan p ON t.id_pelanggan=p.id_pelanggan WHERE t.id_transaksi=?");
mysqli_stmt_bind_param($stmt_t, 'i', $id);
mysqli_stmt_execute($stmt_t);
$result_t = mysqli_stmt_get_result($stmt_t);
$t = mysqli_fetch_assoc($result_t);
mysqli_stmt_close($stmt_t);

//Validasi: Cek apakah transaksi ditemukan setelah pencarian
if (!$t) {
    die("Transaksi dengan ID #$id tidak ditemukan.");
}

// Ambil data Detail Transaksi (Layanan yang dipilih)
// Menggunakan LEFT JOIN untuk mengambil nama layanan
$stmt_details = mysqli_prepare($k, "SELECT d.*, l.nama_layanan FROM transaksi_detail d LEFT JOIN layanan l ON d.id_layanan=l.id_layanan WHERE d.id_transaksi=?");
mysqli_stmt_bind_param($stmt_details, 'i', $id);
mysqli_stmt_execute($stmt_details);
$details = mysqli_stmt_get_result($stmt_details);
mysqli_stmt_close($stmt_details);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Transaksi #<?= $t['id_transaksi'] ?></title>
    <style>
        body{font-family: 'Courier New', Courier, monospace; font-size: 14px; margin: 0; padding: 20px;}
        .container { width: 100%; max-width: 800px; margin: 0 auto; }
        .page-title { text-align: center; margin-bottom: 20px; font-weight: normal; font-size: 18px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h3 { margin: 0; text-transform: uppercase; font-weight: bold; font-size: 20px; }
        .header p { margin: 4px 0; font-size: 14px; }
        .separator { border-top: 2px dashed #000; margin: 15px 0; }
        .info { margin-bottom: 15px; }
        .info div { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-weight: bold; padding: 5px 0; }
        td { vertical-align: top; padding: 5px 0; }
        .text-right { text-align: right; }
        .totals { margin-top: 15px; text-align: right; }
        .totals div { margin-bottom: 5px; }
        .footer { text-align: center; margin-top: 30px; font-size: 14px; }
        
        @media print {
            body { padding: 0; margin: 0; font-size: 16px; }
            .container { max-width: 100%; width: 100%; }
            .page-title { font-size: 20px; }
            .header h3 { font-size: 24px; }
            .header p { font-size: 16px; }
            @page { margin: 1cm; size: auto; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container">
        <div class="page-title">
            Nota Transaksi #<?= $t['id_transaksi'] ?>
        </div>

        <div class="header">
            <h3><?= $nama_laundry ?></h3>
            <p><?= $alamat_laundry ?> <?= $kontak_laundry ?></p>
        </div>

        <div class="separator"></div>

        <div class="info">
            <div>Nota ID: #<?= $t['id_transaksi'] ?></div>
            <div>Pelanggan: **<?= htmlspecialchars($t['pelanggan']) ?>**</div>
            <div>Tanggal Masuk: <?= date('d M Y H:i', strtotime($t['tanggal'])) ?></div>
        </div>

        <div class="separator"></div>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-right">Berat</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php $total_berat = 0; while($d = mysqli_fetch_assoc($details)): $total_berat += $d['berat']; ?>
                <tr>
                    <td><?= htmlspecialchars($d['nama_layanan']) ?></td>
                    <td class="text-right"><?= number_format($d['berat'], 2, ',', '.') ?> Kg</td>
                    <td class="text-right">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="separator"></div>

        <div class="totals">
            <div>Total Berat: **<?= number_format($total_berat, 2, ',', '.') ?> Kg**</div>
            <div><strong>TOTAL: **Rp <?= number_format($t['total'], 0, ',', '.') ?>**</strong></div>
        </div>

        <div class="separator"></div>

        <div class="footer">
            Terima kasih atas kunjungan Anda.<br>
            Barang diambil dengan nota ini.
        </div>
    </div>

</body>
</html>