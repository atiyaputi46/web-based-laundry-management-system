<?php
// Script Logout
// Menghapus sesi login dan mengarahkan kembali ke halaman login
session_start();
session_destroy();
header('Location: login.php');
exit;
