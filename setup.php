<?php
/**
 * Script Setup Database
 * Digunakan untuk inisialisasi awal database dan tabel-tabel yang dibutuhkan sistem.
 * Jalankan file ini satu kali saat pertama kali deploy project.
 */

$host = 'localhost'; $user = 'root'; $pass = '';
$db = 'laundry_db';

// Membuat koneksi ke server MySQL
$conn = mysqli_connect($host, $user, $pass);
if(!$conn) { die('Koneksi ke MySQL gagal: '.mysqli_connect_error()); }

// Membuat database jika belum ada
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
mysqli_select_db($conn, $db);

$queries = [];

$queries[] = "CREATE TABLE IF NOT EXISTS user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','kasir') DEFAULT 'kasir'
) ENGINE=InnoDB";

$queries[] = "CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    no_hp VARCHAR(20),
    alamat TEXT
) ENGINE=InnoDB";

$queries[] = "CREATE TABLE IF NOT EXISTS layanan (
    id_layanan INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(100),
    harga INT
) ENGINE=InnoDB";

$queries[] = "CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    tanggal DATE,
    total INT,
    status ENUM('diproses','dicuci','disetrika','selesai','diambil') DEFAULT 'diproses',
    status_bayar ENUM('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE SET NULL
) ENGINE=InnoDB";

$queries[] = "CREATE TABLE IF NOT EXISTS transaksi_detail (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT,
    id_layanan INT,
    berat DECIMAL(10,2),
    subtotal INT,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_layanan) REFERENCES layanan(id_layanan) ON DELETE SET NULL
) ENGINE=InnoDB";

$queries[] = "CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT,
    bukti VARCHAR(255),
    metode VARCHAR(50),
    tanggal_bayar DATE,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE
) ENGINE=InnoDB";

// Menjalankan semua query pembuatan tabel
foreach($queries as $q){
    mysqli_query($conn, $q) or die('Gagal buat tabel: '.mysqli_error($conn));
}


// Pastikan kolom status_bayar ada jika tabel transaksi sudah dibuat sebelumnya
$checkCol = mysqli_query($conn, "SHOW COLUMNS FROM transaksi LIKE 'status_bayar'");
if(mysqli_num_rows($checkCol) == 0){
    mysqli_query($conn, "ALTER TABLE transaksi ADD COLUMN status_bayar ENUM('Belum Lunas','Lunas') DEFAULT 'Belum Lunas'");
}

// Data awal Layanan
$checkLay = mysqli_query($conn, "SELECT COUNT(*) as c FROM layanan");
if(mysqli_fetch_assoc($checkLay)['c'] == 0){
    mysqli_query($conn, "INSERT INTO layanan (nama_layanan, harga) VALUES ('Kiloan', 8000), ('Express', 12000), ('Satuan - Sepatu', 15000)");
}

// Data awal Pelanggan
$checkPel = mysqli_query($conn, "SELECT COUNT(*) as c FROM pelanggan");
if(mysqli_fetch_assoc($checkPel)['c'] == 0){
    mysqli_query($conn, "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES ('Budi', '08123456789', 'Padang'), ('Siti', '08234567890', 'Padang')");
}

// Membuat akun Admin Default jika belum ada
$username = 'admin';
$check = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
if(mysqli_num_rows($check) == 0){
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO user (nama, username, password, role) VALUES ('Admin','admin','$password_hash','admin')");
}

// Data awal Transaksi
$checkTr = mysqli_query($conn, "SELECT COUNT(*) as c FROM transaksi");
if(mysqli_fetch_assoc($checkTr)['c'] == 0){
    mysqli_query($conn, "INSERT INTO transaksi (id_pelanggan,tanggal,total,status,status_bayar) VALUES (1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 24000, 'selesai', 'Lunas'), (2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 12000, 'selesai', 'Belum Lunas')");
    mysqli_query($conn, "INSERT INTO transaksi_detail (id_transaksi,id_layanan,berat,subtotal) VALUES (1,1,3,24000),(2,2,1,12000)");
    mysqli_query($conn, "INSERT INTO pembayaran (id_transaksi,tanggal_bayar,metode,bukti) VALUES (1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Cash', '')");
}

echo "Setup selesai. Admin dibuat (username: admin, password: admin123). Hapus setup.php setelah selesai untuk keamanan.";
?>
