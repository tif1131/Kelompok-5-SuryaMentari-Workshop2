-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 03:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `servicemobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_layanan`
--

CREATE TABLE `jenis_layanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `biaya` decimal(10,2) NOT NULL,
  `durasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_layanan`
--

INSERT INTO `jenis_layanan` (`id`, `nama`, `biaya`, `durasi`) VALUES
(1, 'Ganti Oli', 250000.00, 60),
(2, 'Servis Ringan', 500000.00, 120),
(3, 'Servis Besar', 1000000.00, 240);

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL,
  `pemilik` varchar(100) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `tipe` varchar(100) NOT NULL,
  `plat_nomor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `pemilik`, `merk`, `tipe`, `plat_nomor`) VALUES
(11, '', '', '', ''),
(12, '', '', '', ''),
(13, 'aryok', 'honda', 'vario', 'bodong'),
(14, 'jeki', 'yamaha', 'erokk', '11');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_transaksi`
--

CREATE TABLE `layanan_transaksi` (
  `transaction_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_transaksi`
--

INSERT INTO `layanan_transaksi` (`transaction_id`, `layanan_id`) VALUES
(4, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `kendaraan_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tanggal_jadwal` datetime DEFAULT NULL,
  `total_biaya` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kendaraan_id`, `tanggal`, `tanggal_jadwal`, `total_biaya`) VALUES
(3, 12, '2025-05-26 15:12:46', '2025-05-30 15:20:00', 0.00),
(4, 13, '2025-05-26 15:13:10', '2025-05-14 15:20:00', 250000.00),
(5, 14, '2025-05-26 15:19:56', '2025-05-26 15:20:00', 500000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'ahnaf', '$2y$10$op8.xEqw930GMX7csPT0lu95.YZzyyzUDWoLx.lQ7dYYkLAPeRl6S');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_layanan`
--
ALTER TABLE `jenis_layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanan_transaksi`
--
ALTER TABLE `layanan_transaksi`
  ADD PRIMARY KEY (`transaction_id`,`layanan_id`),
  ADD KEY `layanan_id` (`layanan_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kendaraan_id` (`kendaraan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_layanan`
--
ALTER TABLE `jenis_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `layanan_transaksi`
--
ALTER TABLE `layanan_transaksi`
  ADD CONSTRAINT `layanan_transaksi_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `layanan_transaksi_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `jenis_layanan` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
