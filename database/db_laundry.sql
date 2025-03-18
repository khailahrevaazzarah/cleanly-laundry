-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:35 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `daftar_pengiriman`
--

CREATE TABLE `daftar_pengiriman` (
  `id_pengiriman` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `metode_pengiriman` enum('Pesan-Antar','Pesan-Ambil') NOT NULL,
  `alamat` text NOT NULL,
  `tanggal_pengiriman` date DEFAULT NULL,
  `status` enum('Menunggu','Kurir akan datang','Dikirim','Selesai','Dibatalkan') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_pengiriman`
--

INSERT INTO `daftar_pengiriman` (`id_pengiriman`, `id_user`, `id_pesanan`, `metode_pengiriman`, `alamat`, `tanggal_pengiriman`, `status`) VALUES
(1, 1, 1, 'Pesan-Antar', 'DESA JAMBU', '2025-03-13', 'Menunggu'),
(3, 3, 3, 'Pesan-Antar', 'desa x', '2025-03-13', 'Menunggu'),
(4, 6, 4, 'Pesan-Ambil', 'tulis', '2025-03-14', 'Dikirim'),
(5, 7, 5, 'Pesan-Antar', 'batang', '2025-03-14', 'Dikirim'),
(6, 3, 6, 'Pesan-Antar', 'desa ponowareng', NULL, 'Menunggu'),
(7, 1, 7, 'Pesan-Antar', 'Kampoeng', NULL, 'Menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_pesanan`
--

CREATE TABLE `daftar_pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_pesanan` date DEFAULT NULL,
  `jenis_pesanan` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Diproses','Selesai','Dibatalkan') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_pesanan`
--

INSERT INTO `daftar_pesanan` (`id_pesanan`, `id_user`, `tanggal_pesanan`, `jenis_pesanan`, `status`) VALUES
(1, 1, '2025-03-13', 'Cuci Setrika', 'Diproses'),
(3, 3, '2025-03-13', 'Cuci Setrika', 'Diproses'),
(4, 6, '2025-03-13', 'Cuci Express', 'Diproses'),
(5, 7, '2025-03-13', 'Cuci Setrika', 'Diproses'),
(6, 3, '2025-03-17', 'Dry Cleaning', 'Diproses'),
(7, 1, '2025-03-18', 'Cuci Kering', 'Diproses');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `metode_pembayaran` enum('Cash','Transfer','E-Wallet') NOT NULL,
  `berat` decimal(5,2) NOT NULL,
  `total` int(11) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `status` enum('Belum Dibayar','Lunas','Belum Lunas','Dibatalkan') DEFAULT 'Belum Dibayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_user`, `id_pesanan`, `metode_pembayaran`, `berat`, `total`, `tanggal_pembayaran`, `jumlah`, `status`) VALUES
(1, 1, 1, 'Transfer', 5.00, 15000, '2025-03-13', 15000, 'Lunas'),
(3, 3, 3, 'Transfer', 5.00, 15000, '2025-03-13', 15000, 'Lunas'),
(4, 6, 4, 'Cash', 5.00, 25000, NULL, NULL, 'Lunas'),
(5, 7, 5, 'Transfer', 4.00, 12000, '2025-03-13', 10000, 'Belum Lunas'),
(6, 3, 6, 'Transfer', 3.00, 30000, '2025-03-17', 30000, 'Lunas'),
(7, 1, 7, 'E-Wallet', 5.00, 12500, '2025-03-18', 12500, 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `no_telp`, `role`) VALUES
(1, 'jamal', '$2y$10$L66AfSRG5gAV9crS9rZLb.UzLEqyyzmbCpkyBAIYNVUjzkHWXUwUy', '-', 'user'),
(2, 'admin', '$2y$10$JgRxtKigm3w9UIV.n8aENe44TAJjrVNSYSWQFBn0wo9UC9D5IP0je', '-', 'admin'),
(3, 'eky', '$2y$10$/1moefQ6SSGqyyTb9aSrDuABK/vxWguwlqcN3JAdzRDiuAGUDehHC', '-', 'user'),
(4, 'admin1', '$2y$10$OovnayEzuVo2Pbzpfr8XV.kvqpI0YtLZqHQLl9yXK5HcCM/SoEmKi', '-', 'user'),
(5, 'admin2', '$2y$10$wHEEsiCRbQFP5w4B10QRruBDgYyJMyGJmNzh1aSX4LilC1lxbYcQK', '-', 'admin'),
(6, 'pak ismail', '$2y$10$HKWOm406rQXV6re4XUpNQ.OxKvhoygwFBZ1kqh/EbUiwCmtE/65oO', '08888', 'user'),
(7, 'bumaria', '$2y$10$9nzps1ZxKZE4Y7zawyjjRORrRg7vriNd4KueHEJBtV31tRJBINVnS', '081', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daftar_pengiriman`
--
ALTER TABLE `daftar_pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `daftar_pesanan`
--
ALTER TABLE `daftar_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daftar_pengiriman`
--
ALTER TABLE `daftar_pengiriman`
  MODIFY `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `daftar_pesanan`
--
ALTER TABLE `daftar_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daftar_pengiriman`
--
ALTER TABLE `daftar_pengiriman`
  ADD CONSTRAINT `daftar_pengiriman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `daftar_pengiriman_ibfk_2` FOREIGN KEY (`id_pesanan`) REFERENCES `daftar_pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `daftar_pesanan`
--
ALTER TABLE `daftar_pesanan`
  ADD CONSTRAINT `daftar_pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_pesanan`) REFERENCES `daftar_pesanan` (`id_pesanan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
