<?php
$server = "localhost";
$user = "root";
$password ="";
$database = "db_laundry";

$koneksi = mysqli_connect($server, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>