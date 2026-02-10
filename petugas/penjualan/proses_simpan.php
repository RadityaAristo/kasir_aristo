<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
date_default_timezone_set('Asia/Jakarta');

if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Ambil data dari form
$nama   = mysqli_real_escape_string($connect, $_POST['NamaPelanggan']);
$alamat = mysqli_real_escape_string($connect, $_POST['Alamat']);
$telp   = mysqli_real_escape_string($connect, $_POST['NomorTelepon']);

$tgl    = date('Y-m-d H:i:s'); 
$pids   = $_POST['ProdukID'];
$qtys   = $_POST['Jumlah'];

// 1. Simpan Pelanggan Lengkap
mysqli_query($connect, "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) VALUES ('$nama', '$alamat', '$telp')");
$id_pelanggan = mysqli_insert_id($connect);

// 2. Simpan Penjualan (UserID dihapus agar tidak error jika kolom tidak adaa)
mysqli_query($connect, "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) VALUES ('$tgl', 0, '$id_pelanggan')");
$id_penjualan = mysqli_insert_id($connect);

$total_akhir = 0;

// 3. Loop Detail & Potong Stok
foreach($pids as $key => $id_produk) {
    $qty = $qtys[$key];
    
    // Ambil Harga & Stok Terkini
    $res = mysqli_query($connect, "SELECT Harga, Stok FROM produk WHERE ProdukID = '$id_produk'");
    $dp  = mysqli_fetch_assoc($res);
    
    $subtotal = $dp['Harga'] * $qty;
    $total_akhir += $subtotal;

    // Insert Detail
    mysqli_query($connect, "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                         VALUES ('$id_penjualan', '$id_produk', '$qty', '$subtotal')");

    // Potong Stok
    $stok_baru = $dp['Stok'] - $qty;
    mysqli_query($connect, "UPDATE produk SET Stok = '$stok_baru' WHERE ProdukID = '$id_produk'");
}

// 4. Update Total Akhir
mysqli_query($connect, "UPDATE penjualan SET TotalHarga = '$total_akhir' WHERE PenjualanID = '$id_penjualan'");

// 5. Selesai
header("location:detail.php?id=$id_penjualan");
exit;
?>