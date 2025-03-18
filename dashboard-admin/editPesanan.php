<?php
include_once('../koneksi.php');

if (isset($_POST['save'])) {
    $id_pesanan = mysqli_real_escape_string($koneksi, $_POST['id_pesanan']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $update_query = "UPDATE daftar_pesanan 
                     SET status = '$status' 
                     WHERE id_pesanan = '$id_pesanan'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('Pesanan berhasil diupdate!'); window.location.href = 'daftarPesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
