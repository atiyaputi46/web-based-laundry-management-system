<?php
// config/koneksi.php - Konfigurasi Database
// Mengatur parameter koneksi ke database laundry.

$host = "localhost";
$user = "root";
$pass = "";
$db   = "laundry_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if(!$koneksi){
    // don't die to allow setup.php to create the DB
    // use mysqli_connect for page queries with fallback
    // but many pages will reconnect explicitly using $koneksi anyway
}
?>
