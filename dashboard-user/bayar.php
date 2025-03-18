<?php
session_start();
include_once ('../koneksi.php');

if (isset($_POST['bayar'])) {
    $id_pembayaran = mysqli_real_escape_string($koneksi, $_POST['id_pembayaran']);
    $jumlah_bayar = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
    $tanggal_pembayaran = date('Y-m-d');

    // Ambil total harga, metode pembayaran, dan jumlah yang sudah dibayar sebelumnya
    $result = mysqli_query($koneksi, "SELECT total, jumlah FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'");
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Data pembayaran tidak ditemukan!'); window.location.href = 'home.php';</script>";
        exit();
    }

    $total = $data['total'];
    $jumlah_sudah_dibayar = $data['jumlah'];

    // Perbarui jumlah pembayaran dengan menambahkan yang baru
    $total_bayar_sekarang = $jumlah_sudah_dibayar + $jumlah_bayar;

    // Tentukan status pembayaran
    if ($total_bayar_sekarang >= $total) {
        $status_pembayaran = "Lunas";
        $kembalian = $total_bayar_sekarang - $total;
    } else {
        $status_pembayaran = "Belum Lunas";
        $kembalian = 0;
    }

    // Update jumlah pembayaran di database
    $update_query = "UPDATE pembayaran 
                     SET jumlah = '$total_bayar_sekarang', 
                         tanggal_pembayaran = '$tanggal_pembayaran',
                         status = '$status_pembayaran'
                     WHERE id_pembayaran = '$id_pembayaran'";

    if (mysqli_query($koneksi, $update_query)) {
        if ($kembalian > 0) {
            echo "<script>alert('Pembayaran berhasil terkirim ke nomor tujuan! Kembalian Rp " . number_format($kembalian, 0, ',', '.') . "'); window.location.href = 'home.php';</script>";
        } else {
            echo "<script>alert('Pembayaran berhasil terkirim ke nomor tujuan!'); window.location.href = 'home.php';</script>";
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

// Ambil informasi pembayaran berdasarkan id_pembayaran
$id_pembayaran = $_GET['id_pembayaran'];
$query = mysqli_query($koneksi, "SELECT total, metode_pembayaran FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data pembayaran tidak ditemukan!'); window.location.href = 'home.php';</script>";
    exit();
}

$total_harga = $data['total'];
$metode_pembayaran = $data['metode_pembayaran'];

// Tentukan nomor rekening tujuan berdasarkan metode pembayaran
$rekeningTujuan = "";
if ($metode_pembayaran == "Transfer") {
    $rekeningTujuan = "<label>Nomor Rekening Tujuan:</label> <span>1234567890 (Bank KHAI)</span>";
} elseif ($metode_pembayaran == "E-Wallet") {
    $rekeningTujuan = "<label>Nomor E-Wallet Tujuan:</label> <span>081234567890 (E-Wallet KY)</span>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="../css/user.css?v=<?php echo time();?>">
</head>
<body>

<h2 class="judul-bayar">Pembayaran</h2>

<form method="POST" action="bayar.php" class="form-bayar">
    <input type="hidden" name="id_pembayaran" value="<?php echo $id_pembayaran; ?>">

    <div class="total-harga">
        <label>Total Harga:</label>
        <span>Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></span>
    </div>

    <div class="metode-pembayaran">
    <label>Metode Pembayaran:</label>
    <span><?php echo $metode_pembayaran; ?></span>
</div>


    <?php if (!empty($rekeningTujuan)) { ?>
        <div class="rekening-tujuan" style="margin-top: 10px;">
            <?php echo $rekeningTujuan; ?>
        </div>
    <?php } ?>

    <div class="label-jumlah">
    <label>Jumlah Dibayar (Rp):</label>
    <input type="number" name="jumlah" required class="input-jumlah" placeholder="Masukkan jumlah pembayaran">
    </div>

    <button type="submit" name="bayar" class="tombol-bayar">Bayar Sekarang</button>
    <a href="../dashboard-user/home.php" class="tombol-kembali">Kembali ke Home</a>
</form>

</body>
</html>
