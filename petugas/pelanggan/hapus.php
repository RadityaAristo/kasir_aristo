<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Proses hapuss
    $query = mysqli_query($connect, "DELETE FROM pelanggan WHERE PelangganID='$id'");

    if($query) {
        echo "<script>alert('Data pelanggan berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data! Data mungkin masih terikat dengan transaksi.'); window.location='index.php';</script>";
    }
}
?>