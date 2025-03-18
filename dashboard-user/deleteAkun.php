<?php
session_start();
include('../koneksi.php');

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = '../login/login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

// Hapus Data Pembayaran
mysqli_query($koneksi, "DELETE FROM pembayaran WHERE id_user='$id_user'");

// Hapus Data Pengiriman
mysqli_query($koneksi, "DELETE FROM daftar_pengiriman WHERE id_user='$id_user'");

// Hapus Data Pesanan
mysqli_query($koneksi, "DELETE FROM daftar_pesanan WHERE id_user='$id_user'");

// Hapus Akun
$query = "DELETE FROM users WHERE id_user='$id_user'";
if (mysqli_query($koneksi, $query)) {
    session_destroy();
    echo "<script>alert('Akun berhasil dihapus!'); window.location.href = '../login/login.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus akun!'); window.location.href = 'informasiAkun.php';</script>";
}
?>
