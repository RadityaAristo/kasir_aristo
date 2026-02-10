<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['role'] != "admin"){
    header("location:index.php");
    exit();
}

// Ambil ID dari URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Query Hapus
    $query = mysqli_query($connect, "DELETE FROM user WHERE UserID='$id'");

    if($query){
        header("location:index.php?pesan=hapus_suksess");
    } else {
        header("location:index.php?pesan=gagal");
    }
} else {
    header("location:index.php");
}
?>