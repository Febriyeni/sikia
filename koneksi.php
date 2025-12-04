<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname   = "yeni_mhs";

$koneksi = mysqli_connect($servername, $username, $password, $dbname);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set connection charset
mysqli_set_charset($koneksi, 'utf8mb4');
?>
