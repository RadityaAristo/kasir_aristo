<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
$username = $_POST['Username'];
$password = $_POST['Password']; // Disarankan pakai password_hash() untuk real project
$role     = $_POST['Role'];

$query = mysqli_query($connect, "INSERT INTO user (Username, Password, Role) VALUES ('$username', '$password', '$role')");

if($query) {
    header("location:index.php?pesan=suksess");
} else {
    header("location:index.php?pesan=gagal");
}
?>