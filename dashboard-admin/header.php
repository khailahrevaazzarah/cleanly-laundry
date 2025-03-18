<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time();?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<header> 
    <h1>🫧CLEANLY🫧</h1>
    <button class="menu-toggle" aria-label="Toggle Menu">
        <i class="fa-solid fa-bars"></i>
    </button>
    <nav>
        <a href="daftarPelanggan.php">Daftar Pelanggan</a>
        <a href="daftarPesanan.php">Daftar Pesanan</a>
        <a href="daftarPengiriman.php">Daftar Pengiriman</a>
        <a href="pembayaran.php">Pembayaran</a>
    </nav>
    <a href="../log-out/logout.php" class="logout-icon">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
    </a>
</header>

<script>
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('header nav').classList.toggle('show');
    });

    document.addEventListener("DOMContentLoaded", function() {
    let currentLocation = window.location.pathname.split("/").pop(); // Ambil nama file halaman
    let menuLinks = document.querySelectorAll("header nav a");

    menuLinks.forEach(link => {
        if (link.getAttribute("href") === currentLocation) {
            link.classList.add("active");
        }
    });
});

</script>


</body>
</html>