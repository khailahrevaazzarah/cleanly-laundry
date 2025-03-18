<?php
session_start();
include_once('../koneksi.php');

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = '../login/login.php';</script>";
    exit;
}

if (isset($_POST['save'])) {
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);

    // Ambil password lama
    $query_get_user = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user = '$id_user'");
    $user_data = mysqli_fetch_assoc($query_get_user);
    $password_hash = $user_data['password']; // Ambil password lama

    // Jika password diisi, hash ulang, jika tidak gunakan yang lama
    if (!empty($_POST['password'])) {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // Update user
    $update_query = "UPDATE users SET username = '$username', password = '$password_hash', no_telp = '$no_telp' WHERE id_user = '$id_user'";

    if (mysqli_query($koneksi, $update_query)) {
        // Perbarui session dengan username baru
        $_SESSION['username'] = $username;

        echo "<script>alert('Informasi akun berhasil diperbarui!'); window.location.href = 'informasiAkun.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
