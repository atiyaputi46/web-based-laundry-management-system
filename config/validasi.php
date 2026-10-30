<?php
// config/validasi.php - Helper Validasi Input
// Fungsi-fungsi bantuan untuk membersihkan dan memvalidasi input user.

// Fungsi untuk sanitasi string (mencegah XSS sederhana)
function bersih($data){
    return htmlspecialchars(trim($data));
}

// Fungsi helper untuk mengambil data POST dengan aman
function cek_post($key){
    return isset($_POST[$key]) ? $_POST[$key] : null;
}
?>
