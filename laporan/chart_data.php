<?php
// laporan/chart_data.php - API Data Grafik
// Mengembalikan data JSON omzet 7 hari terakhir untuk konsumsi Chart.js.
include '../config/koneksi.php';

$labels = [];
$data = [];

$query = mysqli_query($koneksi, "
  SELECT DATE(tanggal) as tgl, SUM(total) as omzet
  FROM transaksi
  WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
  GROUP BY DATE(tanggal)
  ORDER BY tgl ASC
");

while ($row = mysqli_fetch_assoc($query)) {
  $labels[] = $row['tgl'];
  $data[] = $row['omzet'];
}

echo json_encode([
  'labels' => $labels,
  'data' => $data
]);
