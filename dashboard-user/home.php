<?php 
session_start();
include_once('../koneksi.php');

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = '../login/login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis_pesanan = mysqli_real_escape_string($koneksi, $_POST['jenis_pesanan']);
    $metode_pengiriman = mysqli_real_escape_string($koneksi, $_POST['metode_pengiriman']);
    $alamat = !empty($_POST['alamat']) ? mysqli_real_escape_string($koneksi, $_POST['alamat']) : NULL;
    $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);

    // 1. Simpan ke daftar_pesanan
    $query_pesanan = "INSERT INTO daftar_pesanan (id_user, jenis_pesanan, tanggal_pesanan, status) 
                      VALUES ('$id_user', '$jenis_pesanan', CURRENT_TIMESTAMP, 'Pending')";
    
    if (mysqli_query($koneksi, $query_pesanan)) {
        $id_pesanan = mysqli_insert_id($koneksi);
        
        // Simpan ke daftar_pengiriman
        $query_pengiriman = "INSERT INTO daftar_pengiriman (id_user, id_pesanan, metode_pengiriman, alamat, status) 
                             VALUES ('$id_user', '$id_pesanan', '$metode_pengiriman', '$alamat', 'Menunggu')";
        mysqli_query($koneksi, $query_pengiriman);
    
        // Simpan ke pembayaran
        $query_pembayaran = "INSERT INTO pembayaran (id_user, id_pesanan, metode_pembayaran, total, status) 
                             VALUES ('$id_user', '$id_pesanan', '$metode_pembayaran', NULL, 'Menunggu Konfirmasi')";
        mysqli_query($koneksi, $query_pembayaran);
    
        // Tambahkan redirect untuk mencegah resubmission
        header("Location: ../dashboard-user/home.php");
        exit;
    }
}    

$query = "SELECT dp.id_pesanan, pb.id_pembayaran, dp.jenis_pesanan, dp.tanggal_pesanan, dp.status AS status_pesanan, 
                 COALESCE(pg.metode_pengiriman, 'Belum Dipilih') AS metode_pengiriman, 
                 COALESCE(pg.status, 'Belum Diproses') AS status_pengiriman,
                 COALESCE(pg.tanggal_pengiriman, NULL) AS tanggal_pengiriman,
                 COALESCE(pb.metode_pembayaran, 'Belum Dipilih') AS metode_pembayaran, 
                 COALESCE(pb.status, 'Belum Dibayar') AS status_pembayaran,
                 COALESCE(pb.total, NULL) AS total,
                 COALESCE(pb.jumlah, 0) AS jumlah,
                 COALESCE(pb.tanggal_pembayaran, NULL) AS tanggal_pembayaran
          FROM daftar_pesanan dp
          LEFT JOIN daftar_pengiriman pg ON dp.id_pesanan = pg.id_pesanan
          LEFT JOIN pembayaran pb ON dp.id_pesanan = pb.id_pesanan
          WHERE dp.id_user = '$id_user'
          ORDER BY dp.id_pesanan DESC";

$result = mysqli_query($koneksi, $query);

include('header.php'); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Cleanly</title>
</head>
<body>

<div class="container">
    <div class="atasan">
        <h2 class="teks">SELAMAT DATANG! di Cleanly</h2>
        <p class="kalimat">Nikmati Pengalaman Laundry Tanpa Ribet, Tinggak Klik Cucianmu Beres</p>
    </div>

    <div class="order">
    <button class="order-button" onclick="openOrderModal()">Tambah Pesanan</button>
    <div class="harga">   
        <button class="order-button" onclick="openPriceModal()">Informasi Harga</button>
    </div>
</div>


    <div class="info-cards">
        <?php 
        if (mysqli_num_rows($result) > 0) {
            while ($data_pesanan = mysqli_fetch_assoc($result)) { ?>
<div class="card">
    <h3>PESANAN</h3>
    <p>
        <?php 
        echo "Tanggal Pesanan: " . date('d-m-Y', strtotime($data_pesanan['tanggal_pesanan'])) . "<br>";
        echo $data_pesanan['jenis_pesanan'] . " - " . $data_pesanan['status_pesanan'];
        if ($data_pesanan['status_pesanan'] == 'Pending') {
            echo '<br><button class="bayar-sekarang" onclick="batalkanPesanan(' . $data_pesanan['id_pesanan'] . ')">Batalkan Pesanan</button>';
        }
        ?>
    </p>
</div>


<div class="card">
    <h3>PENGIRIMAN</h3>
    <p>
        <?php 
        echo $data_pesanan['metode_pengiriman'] . " - " . $data_pesanan['status_pengiriman'] . "<br>";
        
        if ($data_pesanan['status_pengiriman'] != 'Menunggu' && !empty($data_pesanan['tanggal_pengiriman'])) {
            echo "Tanggal Dikirim: " . date('d-m-Y', strtotime($data_pesanan['tanggal_pengiriman']));
        }
        ?>
    </p>
</div>



<div class="card">
    <h3>PEMBAYARAN</h3>
    <p>
        <?php 
        echo $data_pesanan['metode_pembayaran'];
        echo " - " . $data_pesanan['status_pembayaran'];

        if ($data_pesanan['total'] !== NULL) {
            echo "<br>Total: Rp " . number_format($data_pesanan['total'], 0, ',', '.');
        }
        
        if ($data_pesanan['jumlah'] > 0) {
            echo "<br>Jumlah Dibayar: Rp " . number_format($data_pesanan['jumlah'], 0, ',', '.');
        }
        

        if ($data_pesanan['status_pesanan'] != 'Dibatalkan' 
            && in_array($data_pesanan['metode_pembayaran'], ['Transfer', 'E-Wallet']) 
            && $data_pesanan['status_pembayaran'] != 'Lunas') {
            echo '<br><button class="bayar-sekarang" onclick="window.location.href=\'bayar.php?id_pembayaran=' . $data_pesanan['id_pembayaran'] . '&total=' . $data_pesanan['total'] . '\'">Bayar Sekarang</button>';
        }                        
        ?>
    </p>
</div>


            <?php } 
        } else { ?>
            <p class="tidak-pesan">Belum ada pesanan yang masuk saat ini.</p>
        <?php } ?>
    </div>

    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeOrderModal()">&times;</span>
            <h2>Tambah Pesanan</h2>
            <form action="" method="POST">
                <label>Jenis Pesanan:</label>
                <input type="text" name="jenis_pesanan" required>

                <label>Metode Pengiriman:</label>
                <select name="metode_pengiriman" required>
                    <option value="pesan-ambil">Pesan & Ambil Sendiri</option>
                    <option value="pesan-antar">Pesan & Antar</option>
                </select>

                <label>Alamat:</label>
                <input type="text" name="alamat">

                <label>Metode Pembayaran:</label>
                <select name="metode_pembayaran" required>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="e-wallet">E-Wallet</option>
                </select>

                <button type="submit">Tambah Pesanan</button>
            </form>
        </div>
    </div>
</div>


<div id="modalHarga" class="modal">
  <div class="modal-content">
    <span class="close" onclick="tutupHarga()">&times;</span>
    <h2>Daftar Paket Laundry & Harga per Kg</h2>
    <table>
      <thead>
        <tr>
          <th>Paket</th>
          <th>Deskripsi</th>
          <th>Harga (Rp/kg)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Cuci Setrika</td>
          <td>Pencucian + setrika rapi</td>
          <td>3.000</td>
        </tr>
        <tr>
          <td>Cuci Kering</td>
          <td>Pencucian tanpa setrika</td>
          <td>2.500</td>
        </tr>
        <tr>
          <td>Setrika Saja</td>
          <td>Hanya setrika tanpa cuci</td>
          <td>2.000</td>
        </tr>
        <tr>
          <td>Cuci Express</td>
          <td>Cuci + setrika (Selesai 6 jam)</td>
          <td>5.000</td>
        </tr>
        <tr>
          <td>Cuci Kilat</td>
          <td>Cuci + setrika (Selesai 3 jam)</td>
          <td>7.000</td>
        </tr>
        <tr>
          <td>Dry Cleaning</td>
          <td>Pencucian khusus pakaian tertentu</td>
          <td>10.000</td>
        </tr>
        <tr>
          <td>Cuci Sprei & Bedcover</td>
          <td>Untuk sprei, bedcover, dan selimut</td>
          <td>15.000</td>
        </tr>
        <tr>
          <td>Cuci Sepatu</td>
          <td>Pencucian sepatu berbahan kain & kulit</td>
          <td>20.000 / pasang</td>
        </tr>
        <tr>
          <td>Cuci Boneka</td>
          <td>Pencucian boneka berbulu & kain</td>
          <td>15.000</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


<script>
    function openOrderModal() {
        document.getElementById("orderModal").style.display = "block";
    }

    function closeOrderModal() {
        document.getElementById("orderModal").style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById("orderModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    function openPriceModal() {
    document.getElementById("modalHarga").style.display = "block";
}

function tutupHarga() {
    document.getElementById("modalHarga").style.display = "none";
}

function batalkanPesanan(id_pesanan) {
    if (confirm("Apakah Anda yakin ingin membatalkan pesanan ini?")) {
        window.location.href = 'batalkan_pesanan.php?id_pesanan=' + id_pesanan;
    }
}

window.onclick = function(event) {
    let modalHarga = document.getElementById("modalHarga");
    if (event.target === modalHarga) {
        modalHarga.style.display = "none";
    }
}


    document.querySelector('select[name="metode_pengiriman"]').addEventListener('change', function() {
    let alamatInput = document.querySelector('input[name="alamat"]');
    
    if (this.value === 'pesan-antar' || this.value === 'pesan-ambil') {
        alamatInput.required = true; // Alamat wajib diisi untuk kedua metode
        alamatInput.disabled = false; // Aktifkan input
    }
});


</script>

</body>
</html>
