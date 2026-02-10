<?php
// Database Connection
$server = "localhost";
$user = "root";
$password = "";
$database = "kasir";

// Create connection
$connect = new mysqli($server, $user, $password, $database);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Set charset to utf8
$connect->set_charset("utf8");
?>
