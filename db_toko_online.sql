-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 08:43 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_toko_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`jumlah` * `harga_satuan`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `jumlah`, `harga_satuan`) VALUES
(1, 1, 12, 4, '1800000.00'),
(2, 1, 11, 1, '960000.00'),
(3, 2, 10, 1, '960000.00'),
(4, 3, 9, 1, '2150000.00'),
(5, 4, 11, 1, '960000.00'),
(6, 5, 12, 1, '1800000.00'),
(7, 6, 12, 1, '1800000.00'),
(8, 7, 12, 1, '1800000.00'),
(9, 8, 12, 1, '1800000.00'),
(10, 9, 12, 1, '1800000.00'),
(11, 10, 12, 1, '1800000.00'),
(12, 11, 12, 1, '1800000.00'),
(13, 12, 12, 1, '1800000.00'),
(14, 12, 11, 1, '960000.00'),
(15, 12, 9, 1, '2150000.00'),
(16, 13, 12, 1, '1800000.00'),
(17, 14, 12, 1, '1800000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_produk`
--

INSERT INTO `kategori_produk` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Periferal', 'Perangkat periferal gaming'),
(2, 'Penyimpanan', 'Media penyimpanan SSD/HDD untuk gaming'),
(3, 'Komponen', 'Komponen inti PC gaming'),
(4, 'Casing & PSU', 'Casing PC dan Power Supply'),
(5, 'Monitor', 'Monitor gaming');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `email`, `no_hp`, `alamat`) VALUES
(1, 'Ghiraldy', '081234567891@guest.local', '081234567891', 'jalan nangka'),
(3, 'Andreas', '08123456897@guest.local', '08123456897', 'jalan nangka');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `metode_pembayaran` enum('Transfer Bank','E-Wallet','Kartu Kredit','COD') NOT NULL,
  `tanggal_pembayaran` datetime DEFAULT current_timestamp(),
  `jumlah_bayar` decimal(12,2) NOT NULL,
  `status_pembayaran` enum('Belum Dibayar','Lunas','Gagal') DEFAULT 'Belum Dibayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `metode_pembayaran`, `tanggal_pembayaran`, `jumlah_bayar`, `status_pembayaran`) VALUES
(1, 4, 'E-Wallet', '2025-11-05 23:31:28', '960000.00', 'Lunas'),
(2, 5, 'E-Wallet', '2025-11-05 23:31:54', '1800000.00', 'Lunas'),
(3, 7, 'E-Wallet', '2025-11-05 23:39:00', '1800000.00', 'Lunas'),
(4, 8, 'E-Wallet', '2025-11-05 23:43:17', '1800000.00', 'Lunas'),
(5, 9, 'E-Wallet', '2025-11-05 23:44:05', '1800000.00', 'Lunas'),
(6, 10, 'E-Wallet', '2025-11-09 19:35:05', '1800000.00', 'Lunas'),
(7, 11, 'E-Wallet', '2025-11-10 15:13:45', '1800000.00', 'Lunas'),
(8, 12, 'E-Wallet', '2025-11-10 15:19:02', '4910000.00', 'Lunas'),
(9, 13, 'E-Wallet', '2025-11-10 16:13:08', '1800000.00', 'Lunas'),
(10, 14, 'E-Wallet', '2025-11-26 18:02:32', '1800000.00', 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tanggal_pesanan` datetime DEFAULT current_timestamp(),
  `status` enum('Menunggu Pembayaran','Dikemas','Dikirim','Selesai','Dibatalkan') DEFAULT 'Menunggu Pembayaran',
  `total_harga` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `tanggal_pesanan`, `status`, `total_harga`) VALUES
(1, 1, '2025-11-05 23:00:01', 'Menunggu Pembayaran', '8160000.00'),
(2, 1, '2025-11-05 23:07:45', 'Menunggu Pembayaran', '960000.00'),
(3, 1, '2025-11-05 23:07:57', 'Menunggu Pembayaran', '2150000.00'),
(4, 1, '2025-11-05 23:12:59', 'Selesai', '960000.00'),
(5, 1, '2025-11-05 23:31:46', 'Selesai', '1800000.00'),
(6, 1, '2025-11-05 23:32:07', 'Menunggu Pembayaran', '1800000.00'),
(7, 1, '2025-11-05 23:38:56', 'Selesai', '1800000.00'),
(8, 1, '2025-11-05 23:39:08', 'Selesai', '1800000.00'),
(9, 3, '2025-11-05 23:43:58', 'Selesai', '1800000.00'),
(10, 3, '2025-11-09 19:34:57', 'Selesai', '1800000.00'),
(11, 1, '2025-11-10 15:13:23', 'Selesai', '1800000.00'),
(12, 3, '2025-11-10 15:18:59', 'Selesai', '4910000.00'),
(13, 3, '2025-11-10 16:13:02', 'Selesai', '1800000.00'),
(14, 3, '2025-11-26 18:02:24', 'Selesai', '1800000.00');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `tanggal_ditambahkan` datetime DEFAULT current_timestamp(),
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `nama_produk`, `harga`, `stok`, `deskripsi`, `tanggal_ditambahkan`, `gambar`) VALUES
(1, 1, 'Fantech Headset Gaming Surround 7.1', '750000.00', 50, 'Headset gaming dengan mikrofon noise-cancelling.', '2025-11-05 21:07:11', 'headset.jpeg'),
(2, 1, 'Ajazz AJ159 PRO 8K Mouse Gaming 26000 DPI', '615000.00', 50, 'Gaming Mouse Three Mode PAW3395 up to 26000 DPI.', '2025-11-05 21:07:11', 'mouse.jpeg'),
(3, 1, 'AJAZZ AK873 Wired TKL 87 Keys Hotswappable Mechanical Keyboard', '600000.00', 50, 'GREEN Switch (linear) atau AS BLUE Switch (clicky).', '2025-11-05 21:07:11', 'keyboard.jpeg'),
(4, 2, 'M2.NVME SSD 2280 1Tb Hdd Hard Drive M.2', '1450000.00', 50, 'Kecepatan baca hingga 5100MB/s dan kecepatan tulis hingga 4500MB/s.', '2025-11-05 21:07:11', 'ssd.jpeg'),
(5, 2, 'Hardisk Harddisk HDD Internal PC Seagate Barracuda 2TB | 3.5\" inch | 7200RPM', '1300000.00', 50, 'Penyimpanan besar untuk game-library.', '2025-11-05 21:07:11', 'hdd.jpeg'),
(6, 3, 'Kingston Fury Beast RGB DDR4 16GB (2x8GB) 3600Mhz Dual Channel', '900000.00', 50, 'Speed sampai dengan 3733MT/s dan kapasitas kit sampai dengan 128GB.', '2025-11-05 21:07:11', 'ram.jpeg'),
(7, 3, 'AMD RYZEN 5 5600 BOX 6-Core 12-Thread', '1700000.00', 50, 'Prosesor performa gaming hemat daya.', '2025-11-05 21:07:11', 'cpu.jpeg'),
(8, 3, 'VGA RTX 3060Ti 8GB MSI GAMING X TRIO GDDR6', '1600000.00', 50, 'Kartu grafis mid-high untuk 1080p/1440p.', '2025-11-05 21:07:11', 'gpu.jpeg'),
(9, 3, 'GIGABYTE B550 GAMING X V2 AMD AM4 MOTHERBOARD', '2150000.00', 48, 'VRM kuat, M.2 heatsink, ARGB header.', '2025-11-05 21:07:11', 'motherboard.jpeg'),
(10, 4, 'GAMDIAS ATLAS M4 | ATX PC Case Tempered Glass Include 4 ARGB Fans', '960000.00', 49, '4 fans ARGB | 8 Port 3-PIN ARGB and 8 Port 4-PIN PWM Hub |  Mini-ITX, Micro-ATX and ATX |Kipas bawah yang dapat disesuaikan untuk aliran udara bersudut atau datar yang dioptimalkan | Strip lampu RGB.', '2025-11-05 21:07:11', 'pc_case.jpeg'),
(11, 4, 'CORSAIR CX Series CX650 – 650 Watt 80 PLUS Bronze ATX Power Supply', '960000.00', 47, 'Memiliki fitur low-noise cooling dan desain compact untuk memudahkan pemasangan di hampir semua casing modern.', '2025-11-05 21:07:11', 'psu.jpeg'),
(12, 5, 'Xiaomi MI Monitor 27\" G27i 165Hz HDMI IPS 1080p Gaming AMD FreeSync', '1800000.00', 36, 'Monitor Gaming Terbaik untuk Pemain Game Profesional\r\nBersiaplah untuk pengalaman gaming yang tak tertandingi dengan Monitor Gaming IPS Cepat, dengan refresh rate tinggi 165Hz dan waktu respon GTG 1ms. Monitor ini dirancang khusus untuk memenuhi kebutuhan para pemain game yang menginginkan performa maksimal dan visual yang memukau.', '2025-11-05 21:07:11', 'monitor.jpeg'),
(13, 1, 'Logitech G203 Mouse Gaming Wired RGB Lightsync with Macro', '260000.00', 100, 'Tinggi: 116,6 mm\r\nLebar: 62,15 mm\r\nTebal: 38,2 mm\r\nBerat: 85 g (hanya mouse)\r\nPanjang Kabel: 2,1 m\r\n\r\nSPESIFIKASI TEKNIS\r\nPencahayaan RGB LIGHTSYNC\r\n6 tombol yang dapat diprogram\r\nResolusi: 200 – 8.000 DPI\r\n\r\nKETANGGAPAN\r\nFormat data USB: 16 bit/axis\r\nReport rate USB: 1.000 Hz (1 md)\r\nMikroprosesor: 32-bit ARM', '2025-11-26 18:12:33', 'mouse2-20251126-101233-d8e6ac.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_produk` (`id_kategori`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
