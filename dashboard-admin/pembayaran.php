<?php 
session_start();
include_once('../koneksi.php');

// Pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$get_pembayaran = mysqli_query($koneksi, "SELECT pembayaran.id_pembayaran, users.username, pembayaran.metode_pembayaran, pembayaran.berat, pembayaran.total, pembayaran.jumlah, pembayaran.tanggal_pembayaran, pembayaran.status 
FROM users 
INNER JOIN pembayaran ON pembayaran.id_user = users.id_user
LIMIT $start, $limit");

$total_records = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pembayaran"));
$total_pages = ceil($total_records / $limit);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
</head>
<body>

<h2>PEMBAYARAN</h2>

<div class="search-bar">
    <input type="text" id="search" placeholder="Cari Pembayaran...">
</div>

<table>
<thead>
    <tr>
        <th>Nama</th>
        <th>Metode Pembayaran</th>
        <th>Berat</th>
        <th>Total</th>
        <th>Jumlah Dibayar</th>
        <th>Tanggal Pembayaran</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
<?php while($data = mysqli_fetch_array($get_pembayaran)) { ?>
    <tr data-id="<?php echo $data['id_pembayaran']; ?>">
        <td><?php echo $data['username'];?></td>
        <td><?php echo $data['metode_pembayaran'];?></td>
        <td><?php echo $data['berat'];?></td>
        <td>Rp <?php echo number_format($data['total'] ?? 0, 0, ',', '.'); ?></td>
        <td>Rp <?php echo number_format($data['jumlah'] ?? 0, 0, ',', '.'); ?></td>
        <td><?php echo $data['tanggal_pembayaran'] ?? '-'; ?></td>
        <td><?php echo $data['status'];?></td>
        <td>
            <div class="updel">
                <i class="fa-solid fa-pen edit-pembayaran" style="cursor: pointer;"></i>
                <!-- <i class="fa-solid fa-trash-can"></i> -->
            </div>
        </td>
    </tr>
<?php } ?>    
</tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <a href="?page=<?php echo $i; ?>" <?php echo ($page == $i) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
    <?php } ?>
</div>


<!-- Modal Edit Pembayaran -->
<div id="editPembayaranModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Pembayaran</h2>
        <form id="editPembayaranForm" method="POST" action="../dashboard-admin/editPembayaran.php">
            <input type="hidden" name="id_pembayaran" id="id_pembayaran">

            <label>Berat (Kg):</label>
            <input type="number" name="berat" id="berat" required>

            <label>Status:</label>
            <select name="status" id="status_pembayaran" required>
            <option value="Belum Dibayar">Belum Dibayar</option>
            <option value="Belum Lunas">Belum Lunas</option>
            <option value="Lunas">Lunas</option>
            </select>


            <button type="submit" name="save">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    var modalPembayaran = document.getElementById("editPembayaranModal");
    var closePembayaran = modalPembayaran.querySelector(".close");

    document.querySelectorAll('.edit-pembayaran').forEach(function(editIcon) {
        editIcon.addEventListener('click', function() {
            var row = this.closest('tr');
            var id_pembayaran = row.getAttribute('data-id');
            var berat = row.children[2].innerText.trim(); // Kolom Berat
var total = row.children[3].innerText.trim(); // Kolom Total
var status = row.children[6].innerText.trim(); // Kolom Status (Urutan ke-7)


            document.getElementById("id_pembayaran").value = id_pembayaran;
            document.getElementById("berat").value = berat;
            document.getElementById("status_pembayaran").value = status;

            modalPembayaran.style.display = "block";
        });
    });

    closePembayaran.onclick = function() {
        modalPembayaran.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target === modalPembayaran) {
            modalPembayaran.style.display = "none";
        }
    }

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
