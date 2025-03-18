<?php 
session_start();
include_once('../koneksi.php');

$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mendapatkan data dengan pagination
$get_pesanan = mysqli_query($koneksi, "
    SELECT daftar_pesanan.id_pesanan, users.username, daftar_pesanan.tanggal_pesanan, daftar_pesanan.jenis_pesanan, daftar_pesanan.status
    FROM users
    INNER JOIN daftar_pesanan ON daftar_pesanan.id_user = users.id_user
    LIMIT $start, $limit
");

// Query untuk menghitung total data
$total_pesanan = mysqli_query($koneksi, "
    SELECT COUNT(*) AS jumlah FROM daftar_pesanan
");
$total = mysqli_fetch_assoc($total_pesanan)['jumlah'];

$total_pages = ceil($total / $limit);

include('header.php'); 
?>

<h2>DAFTAR PESANAN</h2>

<div class="search-bar">
        <input type="text" id="search" placeholder="Cari Pesanan...">
    </div>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Tanggal Pesanan</th>
            <th>Jenis Pesanan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($data = mysqli_fetch_array($get_pesanan)) { ?>
        <tr data-id="<?php echo $data['id_pesanan']; ?>">
            <td><?php echo $data['username']; ?></td>
            <td><?php echo $data['tanggal_pesanan']; ?></td>
            <td><?php echo $data['jenis_pesanan']; ?></td>
            <td><?php echo $data['status']; ?></td>
            <td>
    <div class="updel">
        <i class="fa-solid fa-pen edit-status" style="cursor: pointer;"></i>
        <a href="hapusPesanan.php?id_pesanan=<?php echo $data['id_pesanan']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
            <i class="fa-solid fa-trash-can"></i>
        </a>
    </div>
</td>

        </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Pagination -->
<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" <?php if ($page == $i) echo 'class="active"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<div id="editPesananModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Status Pesanan</h2>
        <form id="editPesananForm" method="POST" action="../dashboard-admin/editPesanan.php">
            <input type="hidden" name="id_pesanan" id="id_pesanan">
            <label>Status:</label>
            <select name="status" id="status" required>
                <option value="Pending">Pending</option>
                <option value="Diproses">Diproses</option>
                <option value="Selesai">Selesai</option>
                <!-- <option value="Dibatalkan">Dibatalkan</option> -->
            </select>
            <button type="submit" name="save">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
var modalPesanan = document.getElementById("editPesananModal");
var closePesanan = modalPesanan.querySelector(".close");

document.querySelectorAll('.edit-status').forEach(function(editIcon) {
    editIcon.addEventListener('click', function() {
        var row = this.closest('tr');
        var id_pesanan = row.getAttribute('data-id');
        var status = row.children[3].innerText.trim();

        document.getElementById("id_pesanan").value = id_pesanan;
        document.getElementById("status").value = status;

        modalPesanan.style.display = "block";
    });
});

closePesanan.addEventListener("click", function() {
    modalPesanan.style.display = "none";
});

window.onclick = function(event) {
    if (event.target === modalPesanan) {
        modalPesanan.style.display = "none";
    }
};

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
