<?php 
session_start();
include_once('../koneksi.php');

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = '../login/login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$get_akun = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id_user'");

include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Akun - Cleanly</title>
</head>
<body class="informasi-akun-page">
 
    <div class="container-akun">
        <div class="account-card">
            <img src="account-removebg-preview.png" alt="Profile Picture" class="profile-image">
            <?php if ($data = mysqli_fetch_array($get_akun)) { ?>
                <h3>USERNAME</h3>
                <p id="akun_username"><?php echo htmlspecialchars($data['username']); ?></p>

                <h3>NO. TELEPON</h3>
                <p id="akun_no_telp"><?php echo htmlspecialchars($data['no_telp']); ?></p>
            <?php } else { ?>
                <p>Data akun tidak ditemukan.</p>
            <?php } ?>
        
            <div class="account-actions">
                <button class="edit-button" id="openEditModal"><i class="fa-solid fa-pen"></i></button>
                <button class="delete-button" onclick="konfirmasiHapus()"><i class="fa-solid fa-trash-can"></i></button>
                <a href="../log-out/logout.php" class="logout-icon">
                    <button class="logout-button"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </a>
            </div>
        </div>
    </div>

<div id="editAkunModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Akun</h2>
        <form id="editAkunForm" method="POST" action="editAkun.php">
            <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user; ?>">

            <label>Username:</label>
            <input type="text" name="username" id="username" required>

            <label>Password Baru:</label>
            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah">

            <label>No. Telepon:</label>
            <input type="text" name="no_telp" id="no_telp" required>

            <button type="submit" name="save">Simpan Perubahan</button>
        </form>
    </div>
</div>

    <script>
 var modal = document.getElementById("editAkunModal");
var btn = document.getElementById("openEditModal");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    document.getElementById("username").value = document.getElementById("akun_username").innerText.trim();
    document.getElementById("no_telp").value = document.getElementById("akun_no_telp").innerText.trim();
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

function konfirmasiHapus() {
    if (confirm("Apakah Anda yakin ingin menghapus akun? Semua data pesanan akan ikut terhapus!")) {
        window.location.href = "deleteAkun.php";
    }
}
    </script>

</body>
</html>
