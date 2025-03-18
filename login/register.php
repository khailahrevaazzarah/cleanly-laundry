<?php 
session_start();
include_once('../koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $admin_code = $_POST['admin_code']; // Ambil kode admin dari form

    // Kode rahasia untuk admin
    $secret_admin_code = "CLEANLY123"; 

    // Cek apakah kode admin benar
    if ($admin_code == $secret_admin_code) {
        $role = "admin";
    } else {
        $role = "user";
    }

    // Cek apakah username sudah ada
    $check_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check_user) > 0) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
    } else {
        // Simpan ke database
        $query = "INSERT INTO users (username, password, no_telp, role) 
                  VALUES ('$username', '$password', '$no_telp', '$role')";

        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Cleanly</title>
    <link rel="stylesheet" href="../css/login.css?v=<?php echo time();?>">
</head>
<body>
<div class="container">
        <div class="sidebar">
            <h1>CLEANLY</h1>
        </div>
        <div class="form-container">
            <h2>REGISTER TO CLEANLY</h2>
    <form action="" method="POST">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>No. Telepon</label>
    <input type="text" name="no_telp" required>

    <label>Kode Admin (Kosongkan jika bukan admin)</label>
    <input type="text" name="admin_code" placeholder="Masukkan kode jika admin">

    <button type="submit">Register</button>
</form>

    <p>Sudah punya akun? <a href="login.php">Login disini!</a></p>
    </div>
    </div>
</body>
</html>
