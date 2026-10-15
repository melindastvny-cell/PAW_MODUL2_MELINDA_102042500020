<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "paw_modul2_showroom";
$port = 3308;


$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>