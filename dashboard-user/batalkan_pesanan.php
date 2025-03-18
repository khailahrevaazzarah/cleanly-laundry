<?php
session_start();
include_once('../koneksi.php');

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = '../login/login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

if (isset($_GET['id_pesanan'])) {
    $id_pesanan = mysqli_real_escape_string($koneksi, $_GET['id_pesanan']);

    // Update status di daftar_pesanan, daftar_pengiriman, dan pembayaran menjadi "Dibatalkan"
    $query1 = "UPDATE daftar_pesanan SET status = 'Dibatalkan' WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user' AND status = 'Pending'";
    $query2 = "UPDATE daftar_pengiriman SET status = 'Dibatalkan' WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user' AND status = 'Menunggu'";
    $query3 = "UPDATE pembayaran SET status = 'Dibatalkan' WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user'";

    $result1 = mysqli_query($koneksi, $query1);
if (!$result1) {
    die("Error Query 1: " . mysqli_error($koneksi));
}

$result2 = mysqli_query($koneksi, $query2);
if (!$result2) {
    die("Error Query 2: " . mysqli_error($koneksi));
}

$result3 = mysqli_query($koneksi, $query3);
if (!$result3) {
    die("Error Query 3: " . mysqli_error($koneksi));
}


    if ($result1 && $result2 && $result3) {
        echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location.href = '../dashboard-user/home.php';</script>";
    } else {
        echo "<script>alert('Gagal membatalkan pesanan.'); window.location.href = '../dashboard-user/home.php';</script>";
    }
}
?>
