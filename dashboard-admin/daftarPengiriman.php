<?php 
session_start();
include_once('../koneksi.php');

// Pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$get_pengiriman = mysqli_query($koneksi, "SELECT daftar_pengiriman.id_pengiriman, users.username, daftar_pengiriman.metode_pengiriman, daftar_pengiriman.alamat, daftar_pengiriman.tanggal_pengiriman, daftar_pengiriman.status 
FROM users 
INNER JOIN daftar_pengiriman ON daftar_pengiriman.id_user = users.id_user
LIMIT $start, $limit");

$total_records = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM daftar_pengiriman"));
$total_pages = ceil($total_records / $limit);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengiriman</title>
</head>
<body>
   
    <h2>DAFTAR PENGIRIMAN</h2>


    <div class="search-bar">
        <input type="text" id="search" placeholder="Cari Pengiriman...">
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Metode Pengiriman</th>
                <th>Alamat</th>
                <th>Tanggal Dikirim</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while($data = mysqli_fetch_array($get_pengiriman)) { ?>
            <tr data-id="<?php echo $data['id_pengiriman']; ?>">
                <td><?php echo $data['username'];?></td>
                <td><?php echo $data['metode_pengiriman'];?></td>
                <td><?php echo $data['alamat'];?></td>
                <td><?php echo $data['tanggal_pengiriman'];?></td>
                <td><?php echo $data['status'];?></td>
                <td>
                    <div class="updel">
                    <i class="fa-solid fa-pen edit-status" style="cursor: pointer;"></i>
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


<div id="editPengirimanModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Pengiriman</h2>
        <form id="editPengirimanForm" method="POST" action="../dashboard-admin/editPengiriman.php">
            <input type="hidden" name="id_pengiriman" id="id_pengiriman">

            <label>Tanggal Pengiriman:</label>
            <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman" required>

            <label>Status:</label>
            <select name="status" id="status_pengiriman" required>
                <option value="Menunggu">Menunggu</option>
                <option value="Kurir akan datang">Kurir akan datang</option>
                <option value="Dikirim">Dikirim</option>
                <option value="Selesai">Selesai</option>
                
            </select>

            <button type="submit" name="save">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    var modalPengiriman = document.getElementById("editPengirimanModal");
    var closePengiriman = modalPengiriman.querySelector(".close");

    document.querySelectorAll('.edit-status').forEach(function(editIcon) {
        editIcon.addEventListener('click', function() {
            var row = this.closest('tr');
            var id_pengiriman = row.getAttribute('data-id');
            var tanggal_pengiriman = row.children[4].innerText.trim(); // Ambil tanggal kirim
            var status = row.children[5].innerText.trim(); // Ambil status

            document.getElementById("id_pengiriman").value = id_pengiriman;
            document.getElementById("tanggal_pengiriman").value = tanggal_pengiriman;
            document.getElementById("status_pengiriman").value = status; // Sesuai ENUM

            modalPengiriman.style.display = "block";
        });
    });

    closePengiriman.onclick = function() {
        modalPengiriman.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target === modalPengiriman) {
            modalPengiriman.style.display = "none";
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
