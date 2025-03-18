<?php
include_once('../koneksi.php');

if (isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];
    $query_hapus = "DELETE FROM daftar_pesanan WHERE id_pesanan = '$id_pesanan'";
    $proses = mysqli_query($koneksi, $query_hapus);

    if ($proses) {
        header("location: daftarPesanan.php");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    echo "ID Pesanan tidak ditemukan!";
}
?>
