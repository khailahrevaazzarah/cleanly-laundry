<?php
include_once ('../koneksi.php');

if (isset($_POST['save'])) {
    $id_pembayaran = mysqli_real_escape_string($koneksi, $_POST['id_pembayaran']);
    $berat = mysqli_real_escape_string($koneksi, $_POST['berat']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Ambil jenis pesanan untuk menentukan harga per kg
    $query = "SELECT jenis_pesanan FROM daftar_pesanan WHERE id_pesanan = (SELECT id_pesanan FROM pembayaran WHERE id_pembayaran = '$id_pembayaran')";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $jenis_pesanan = $row['jenis_pesanan'];

    $harga = 0;
    if ($jenis_pesanan == 'Cuci Setrika') {
        $harga = 3000;
    } elseif ($jenis_pesanan == 'Cuci Kering') {
        $harga = 2500;
    } elseif ($jenis_pesanan == 'Setrika Saja') {
        $harga = 2000;
    } elseif ($jenis_pesanan == 'Cuci Express') {
        $harga = 5000;
    } elseif ($jenis_pesanan == 'Cuci Kilat') {
        $harga = 7000;
    } elseif ($jenis_pesanan == 'Dry Cleaning') {
        $harga = 10000;
    } elseif ($jenis_pesanan == 'Cuci Sprei & Bedcover') {
        $harga = 15000;
    } elseif ($jenis_pesanan == 'Cuci Sepatu') {
        $harga = 20000;
    } elseif ($jenis_pesanan == 'Cuci Boneka') {
        $harga = 15000;
    }

    $total = $berat * $harga;

    if ($berat > 10) {
        $diskon = $total * 0.10; 
        $total = $total - $diskon;
    }

    $update_query = "UPDATE pembayaran 
                     SET berat = '$berat',
                         total = '$total', 
                         status = '$status' 
                     WHERE id_pembayaran = '$id_pembayaran'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('Pembayaran berhasil diupdate!'); window.location.href = 'pembayaran.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
