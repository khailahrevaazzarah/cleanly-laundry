<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='../login/login.php';</script>";
    exit();
}
include_once ('../koneksi.php');

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;


$result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role = 'user'");
$total = mysqli_fetch_assoc($result)['total'];
$get_pelanggan = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'user' LIMIT $start, $limit");
$total_pages = ceil($total / $limit);

include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan</title>
</head>
<body>
<h2>DAFTAR PELANGGAN</h2>

<div class="search-bar">
        <input type="text" id="search" placeholder="Cari Pelanggan...">
    </div>

<table class="daftar-pelanggan">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Nomor Telepon</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($data = mysqli_fetch_array($get_pelanggan)) { ?>
        <tr>
            <td><?php echo $data['username']; ?></td>
            <td><?php echo $data['no_telp']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div>
    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php } ?>
</div>

</body>
</html>

<?php

?>

<script>
document.getElementById("search").addEventListener("keyup", function() {
    var keyword = this.value.toLowerCase();
    var rows = document.querySelectorAll("tbody tr");

    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        if (text.includes(keyword)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>


</body>
</html>
