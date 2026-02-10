<?php
// Konfigurasi Databasee
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir";

// Buat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Set charset ke UTF-8
$koneksi->set_charset("utf8");
?>
