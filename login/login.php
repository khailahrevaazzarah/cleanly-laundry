<?php 
session_start();
include_once('../koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            echo "<script>alert('Login sebagai Admin berhasil!'); window.location='../dashboard-admin/daftarPelanggan.php';</script>";
        } else {
            echo "<script>alert('Login berhasil!'); window.location='../dashboard-user/home.php';</script>";
        }
    } else {
        echo "<script>alert('Username atau password salah!'); window.location='login.php';</script>";
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Cleanly</title>
    <link rel="stylesheet" href="../css/login.css?v=<?php echo time();?>">
</head>
<body>
<div class="container">
        <div class="sidebar">
            <h1>CLEANLY</h1>
            <!-- <img src="washingMachine.png" alt="Washing Machine" class="mesin-cuci"> -->
        </div>
        <div class="form-container">
            <h2>LOGIN TO CLEANLY</h2>
    <form action="" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Register disini!</a></p>
    </div>
    </div>
</body>
</html>
