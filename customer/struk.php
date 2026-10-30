<?php
include '../config/koneksi.php';
$id = intval($_GET['id']);

$q = mysqli_query($koneksi,"
SELECT t.*, p.nama
FROM transaksi t
JOIN pelanggan p ON t.id_pelanggan=p.id_pelanggan
WHERE t.id_transaksi='$id'
");
$d = mysqli_fetch_assoc($q);
?>
<!doctype html>
<html>
<head>
<title>Struk Laundry</title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');

    body {
        font-family: "Inter", sans-serif;
        background: #f0f2f5;
        padding: 20px;
    }

    .struk-container {
        width: 320px;
        margin: auto;
        background: white;
        padding: 22px;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        animation: fadeIn .4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    h3 {
        text-align: center;
        margin-bottom: 5px;
        letter-spacing: .8px;
        font-weight: 600;
    }

    .line {
        border-bottom: 1px dashed #b8b8b8;
        margin: 12px 0;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 7px;
        font-size: 14px;
    }

    .item-title {
        font-weight: 600;
        color: #333;
    }

    .footer {
        text-align: center;
        margin-top: 18px;
        font-size: 13px;
        font-weight: 300;
        color: #666;
        line-height: 1.4;
    }

    @media print {
        body {
            background: white;
        }
        .struk-container {
            box-shadow: none;
            margin: 0;
            width: 100%;
            border-radius: 0;
        }
    }
</style>

</head>
<body onload="window.print()">

<div class="struk-container">

    <h3>🧺 LaundryApp</h3>
    <small style="display:block;text-align:center;color:#777;margin-bottom:6px;font-size:13px;">
        Bukti Pembayaran / Transaksi
    </small>

    <div class="line"></div>

    <div class="item-row"><span class="item-title">Nama</span><span><?= $d['nama'] ?></span></div>
    <div class="item-row"><span class="item-title">Tanggal</span><span><?= $d['tanggal'] ?></span></div>
    <div class="item-row"><span class="item-title">Status</span><span><?= ucfirst($d['status']) ?></span></div>

    <div class="line"></div>

    <div class="item-row" style="font-weight:bold;font-size:16px;">
        <span>Total</span>
        <span>Rp <?= number_format($d['total'],0,',','.') ?></span>
    </div>

    <div class="line"></div>

    <div class="footer">
        Terima kasih telah memakai layanan LaundryApp 🙏<br>
        Semoga harimu menyenangkan!
    </div>

</div>

<div class="text-center mt-3">
    <a href="portal.php" class="btn btn-secondary">← Kembali ke Portal</a>
</div>

</body>
</html>
