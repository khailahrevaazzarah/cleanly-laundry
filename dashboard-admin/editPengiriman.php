<?php
include_once('../koneksi.php');

if (isset($_POST['save'])) {
    $id_pengiriman = mysqli_real_escape_string($koneksi, $_POST['id_pengiriman']);
    $tanggal_pengiriman = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengiriman']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $update_query = "UPDATE daftar_pengiriman 
                     SET tanggal_pengiriman = '$tanggal_pengiriman', 
                         status = '$status' 
                     WHERE id_pengiriman = '$id_pengiriman'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('Pengiriman berhasil diupdate!'); window.location.href = 'daftarPengiriman.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
