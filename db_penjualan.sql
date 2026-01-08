-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 05:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `subtotal`) VALUES
(1, 2, 10, 3, 130298.07),
(2, 3, 10, 3, 130298.07),
(3, 4, 2, 1, 200000.00),
(4, 5, 54, 1, 300000.00),
(5, 6, 16, 4, 1618907.28),
(6, 6, 5, 2, 681439.98),
(7, 6, 36, 2, 1566401.78),
(8, 6, 7, 5, 1638378.80),
(9, 7, 27, 2, 271029.76),
(10, 7, 30, 2, 2415213.98),
(11, 7, 12, 5, 3569418.55),
(12, 7, 7, 2, 655351.52),
(13, 8, 47, 5, 6164982.35),
(14, 9, 13, 2, 1499669.98),
(15, 9, 25, 4, 905215.40),
(16, 9, 31, 4, 2246823.00),
(17, 10, 52, 2, 1188803.50),
(18, 10, 36, 4, 3132803.56),
(19, 10, 39, 4, 3257307.64),
(20, 10, 48, 1, 874886.11),
(21, 11, 41, 1, 118765.18),
(22, 11, 21, 4, 4776647.68),
(23, 11, 38, 5, 762107.40),
(24, 11, 31, 2, 1123411.50),
(25, 12, 10, 5, 217163.45),
(26, 12, 31, 4, 2246823.00),
(27, 13, 18, 1, 390157.08),
(28, 13, 43, 5, 2288157.75),
(29, 14, 31, 5, 2808528.75),
(30, 14, 18, 5, 1950785.40),
(31, 15, 13, 5, 3749174.95),
(32, 16, 16, 4, 1618907.28),
(33, 16, 21, 1, 1194161.92),
(34, 17, 35, 4, 3852496.08),
(35, 17, 25, 2, 452607.70),
(36, 18, 52, 5, 2972008.75),
(37, 18, 28, 4, 5173012.80),
(38, 18, 21, 1, 1194161.92),
(39, 18, 7, 1, 327675.76),
(40, 19, 52, 4, 2377607.00),
(41, 19, 54, 1, 300000.00),
(42, 19, 7, 3, 983027.28),
(43, 20, 4, 4, 3904048.32),
(44, 20, 52, 1, 594401.75),
(45, 20, 47, 2, 2465992.94),
(46, 21, 5, 4, 1362879.96),
(47, 22, 30, 2, 2415213.98),
(48, 22, 36, 3, 2349602.67),
(49, 22, 39, 1, 814326.91),
(50, 22, 45, 1, 193813.10),
(51, 23, 33, 3, 4156731.93),
(52, 23, 16, 4, 1618907.28),
(53, 24, 36, 3, 2349602.67),
(54, 24, 41, 2, 237530.36),
(55, 24, 15, 1, 105424.42),
(56, 24, 19, 2, 1395285.50),
(57, 25, 37, 3, 724625.46),
(58, 25, 9, 5, 1763350.25),
(59, 25, 5, 1, 340719.99),
(60, 26, 21, 2, 2388323.84),
(61, 26, 28, 5, 6466266.00),
(62, 26, 14, 2, 2538225.40),
(63, 27, 2, 4, 800000.00),
(64, 27, 36, 2, 1566401.78),
(65, 27, 38, 1, 152421.48),
(66, 27, 22, 3, 3665467.77),
(67, 28, 8, 2, 843700.36),
(68, 28, 36, 2, 1566401.78),
(69, 28, 34, 5, 4833932.80),
(70, 29, 17, 5, 2756840.75),
(71, 29, 20, 2, 1162131.60),
(72, 29, 25, 4, 905215.40),
(73, 29, 19, 2, 1395285.50),
(74, 30, 14, 4, 5076450.80),
(75, 30, 42, 1, 221510.80),
(76, 30, 46, 4, 1763656.68),
(77, 30, 54, 4, 1200000.00),
(78, 31, 18, 1, 390157.08),
(79, 32, 45, 4, 775252.40),
(80, 32, 25, 2, 452607.70),
(81, 32, 47, 4, 4931985.88),
(82, 32, 54, 4, 1200000.00),
(83, 33, 35, 2, 1926248.04),
(84, 33, 14, 3, 3807338.10),
(85, 33, 43, 4, 1830526.20),
(86, 34, 44, 5, 7189720.15),
(87, 34, 54, 2, 600000.00),
(88, 34, 11, 5, 1762265.70),
(89, 35, 3, 4, 5484304.52),
(90, 35, 6, 5, 5691823.35),
(91, 35, 16, 1, 404726.82),
(92, 35, 44, 4, 5751776.12),
(93, 36, 53, 2, 4688888.00),
(94, 36, 17, 1, 551368.15),
(95, 37, 20, 3, 1743197.40),
(96, 38, 22, 1, 1221822.59),
(97, 38, 49, 5, 1084730.65),
(98, 38, 42, 5, 1107554.00),
(99, 39, 25, 2, 452607.70),
(100, 39, 32, 2, 524620.02),
(101, 39, 42, 2, 443021.60),
(102, 40, 6, 4, 4553458.68),
(103, 40, 52, 3, 1783205.25),
(104, 40, 15, 3, 316273.26),
(105, 41, 35, 3, 2889372.06),
(106, 41, 46, 5, 2204570.85),
(107, 41, 25, 4, 905215.40),
(108, 42, 8, 2, 843700.36),
(109, 42, 27, 5, 677574.40),
(110, 42, 36, 2, 1566401.78),
(111, 43, 46, 1, 440914.17),
(112, 43, 55, 2, 80000.00),
(113, 43, 19, 2, 1395285.50),
(114, 44, 46, 1, 440914.17),
(115, 44, 18, 3, 1170471.24),
(116, 44, 12, 5, 3569418.55),
(117, 45, 39, 4, 3257307.64),
(118, 45, 50, 4, 78989.96),
(119, 45, 32, 3, 786930.03),
(120, 45, 10, 2, 86865.38),
(121, 46, 4, 5, 4880060.40),
(122, 47, 2, 1, 200000.00),
(123, 47, 53, 3, 7033332.00),
(124, 48, 43, 4, 1830526.20),
(125, 48, 11, 4, 1409812.56),
(126, 49, 40, 1, 937814.70),
(127, 50, 23, 3, 3403170.21),
(128, 51, 19, 4, 2790571.00),
(129, 51, 41, 5, 593825.90),
(130, 51, 13, 2, 1499669.98),
(131, 51, 9, 3, 1058010.15),
(132, 52, 31, 4, 2246823.00),
(133, 52, 39, 2, 1628653.82),
(134, 53, 39, 3, 2442980.73),
(135, 53, 17, 1, 551368.15),
(136, 53, 9, 4, 1410680.20),
(137, 54, 50, 2, 39494.98),
(138, 54, 43, 3, 1372894.65),
(139, 54, 18, 4, 1560628.32),
(140, 54, 35, 3, 2889372.06),
(141, 55, 6, 3, 3415094.01),
(142, 56, 8, 1, 421850.18),
(143, 57, 6, 3, 3415094.01);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `email`, `no_hp`, `alamat`) VALUES
(4, 'ariff', 'arif@gmail.com', '0858557488484', 'ogan'),
(5, 'Kayla Palastri S.Kom', 'qori.hidayanto@example.net', '(+62) 25 3197 471', 'Psr. Uluwatu No. 152, Samarinda 26026, Sulut'),
(6, 'Elvina Uyainah', 'pmulyani@example.org', '0584 6752 100', 'Jr. Tangkuban Perahu No. 230, Probolinggo 73205, Pabar'),
(7, 'Tiara Astuti', 'puti30@example.net', '0839 1777 559', 'Dk. Wahid No. 265, Pagar Alam 89054, Babel'),
(8, 'Candra Situmorang M.Kom.', 'satya.namaga@example.com', '0275 3884 3184', 'Jr. Dahlia No. 572, Sibolga 32150, Sumsel'),
(9, 'Kardi Utama', 'hasim.hakim@example.com', '(+62) 335 3379 099', 'Jln. Madrasah No. 501, Pontianak 49334, Jateng'),
(10, 'Paulin Agustina', 'fitriani.anggraini@example.net', '0816 262 459', 'Psr. Bakti No. 937, Bengkulu 37144, Sumbar'),
(11, 'Cinta Novitasari', 'umar73@example.org', '(+62) 645 7565 887', 'Jr. Moch. Yamin No. 82, Samarinda 46010, Lampung'),
(12, 'Ida Tari Uyainah', 'chandra.nashiruddin@example.org', '0209 1426 9764', 'Ki. Bambu No. 385, Solok 10577, Aceh'),
(13, 'Anita Faizah Pudjiastuti', 'rachel.haryanti@example.org', '0636 9986 7033', 'Dk. Camar No. 484, Tanjungbalai 39283, Jateng'),
(14, 'Calista Clara Handayani M.TI.', 'xpermadi@example.org', '(+62) 680 4583 1206', 'Jr. Tambak No. 189, Pontianak 26465, Pabar'),
(15, 'Saka Marbun M.M.', 'cakrawala.mustofa@example.com', '(+62) 24 7716 5277', 'Kpg. Ters. Buah Batu No. 795, Cimahi 77990, Sulut'),
(16, 'Edward Jamil Waluyo', 'rika.sihombing@example.net', '0824 1312 045', 'Dk. Bak Mandi No. 321, Kediri 64544, Babel'),
(17, 'Gina Anggraini', 'hariyah.tomi@example.com', '(+62) 821 3547 2547', 'Jr. HOS. Cjokroaminoto (Pasirkaliki) No. 449, Bandar Lampung 54757, Bengkulu'),
(18, 'Puput Ana Farida', 'xsihombing@example.com', '(+62) 366 9039 740', 'Dk. Jamika No. 63, Binjai 82411, Bengkulu'),
(19, 'Hilda Rahmawati', 'oliva46@example.net', '0436 5230 377', 'Jr. Mulyadi No. 626, Kendari 61758, Kaltara'),
(20, 'Lili Malika Rahayu S.Kom', 'bkusmawati@example.org', '0555 1892 826', 'Jln. Ciwastra No. 775, Tegal 14051, NTB'),
(21, 'Vanya Haryanti', 'ami.pangestu@example.org', '0827 4898 1981', 'Ds. Jaksa No. 574, Kotamobagu 58477, Jateng'),
(22, 'Rangga Ramadan', 'mustika.sihombing@example.com', '(+62) 228 7085 678', 'Dk. Cihampelas No. 872, Tangerang 21970, Papua'),
(23, 'Widya Laksmiwati', 'mardhiyah.suci@example.org', '(+62) 570 9284 025', 'Jln. Ciumbuleuit No. 404, Tangerang 80720, Sulbar'),
(24, 'Suci Citra Mayasari S.E.I', 'queen.safitri@example.org', '0807 5950 8374', 'Psr. Abdul Muis No. 411, Subulussalam 35583, Papua'),
(25, 'Kamidin Dimas Uwais', 'cakrawala69@example.net', '028 6747 164', 'Ki. Gegerkalong Hilir No. 89, Kotamobagu 15671, Kalbar'),
(26, 'Uchita Rahayu', 'clara81@example.com', '(+62) 708 8894 4317', 'Jr. Ters. Kiaracondong No. 889, Sabang 47668, Maluku'),
(27, 'Elma Yolanda', 'zelda.winarsih@example.org', '0201 3414 0781', 'Gg. Basuki Rahmat  No. 880, Sungai Penuh 22917, Sulut'),
(28, 'Jagapati Wacanat', 'uli.susanti@example.org', '029 1750 861', 'Jln. Teuku Umar No. 177, Padang 52355, Jabar'),
(29, 'Kairav Sinaga', 'pkuswandari@example.net', '(+62) 428 5986 0155', 'Dk. M.T. Haryono No. 437, Parepare 12915, Kepri'),
(30, 'Queen Hamima Halimah', 'jhalim@example.com', '0372 5195 2103', 'Ki. Banceng Pondok No. 546, Makassar 78156, Pabar'),
(31, 'Radika Hutagalung', 'nardi37@example.org', '(+62) 877 4861 0624', 'Ds. Sudirman No. 486, Blitar 49631, Sumsel'),
(32, 'Vicky Puspita', 'diah.maulana@example.com', '(+62) 388 7627 5725', 'Jr. Ketandan No. 923, Surabaya 99307, Riau'),
(33, 'Zelaya Prastuti', 'jane07@example.com', '0402 3303 5762', 'Jr. Dr. Junjunan No. 376, Cirebon 94388, DKI'),
(34, 'Kasiyah Pertiwi', 'enteng50@example.com', '0595 1330 3388', 'Gg. Bak Air No. 963, Banjarbaru 73208, Aceh'),
(35, 'tambahhhh', 'tambah@gmail.com', '09585757575', 'damarr'),
(36, 'tambahhhhhh', 'tayftftmbah@gmail.com', '095857575988', 'sss'),
(38, 'fitri', 'jdjdddjjdtmbah@gmail.com', '029 1750 861', 'mmwmw');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `kode_produk` varchar(20) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `stok`) VALUES
(2, 'PROD 1111', 'ayam', 'hewan', 200000.00, 93),
(3, 'PROD-824299', 'Aut Doloribus Ex', 'Et', 1371076.13, 97),
(4, 'PROD-440701', 'Consequuntur Itaque Explicabo', 'Libero', 976012.08, 45),
(5, 'PROD-908588', 'Sint Eaque Iste', 'Vel', 340719.99, 4),
(6, 'PROD-821615', 'Voluptas Odit Veniam', 'Quae', 1138364.67, 67),
(7, 'PROD-557284', 'Dolores Doloremque Dolores', 'Earum', 327675.76, 173),
(8, 'PROD-536607', 'Praesentium Error Voluptatem', 'Ut', 421850.18, 19),
(9, 'PROD-114026', 'Consequatur Voluptas Nemo', 'Et', 352670.05, 146),
(10, 'PROD-588323', 'Iste Assumenda Quas', 'Veritatis', 43432.69, 121),
(11, 'PROD-050245', 'Repudiandae Qui Eveniet', 'Incidunt', 352453.14, 188),
(12, 'PROD-899419', 'Asperiores Aut Fugit', 'Sapiente', 713883.71, 4),
(13, 'PROD-604339', 'Doloremque Libero Facere', 'Suscipit', 749834.99, 183),
(14, 'PROD-423360', 'Ut Aperiam Et', 'Occaecati', 1269112.70, 123),
(15, 'PROD-785927', 'Vitae Quia Nam', 'Ducimus', 105424.42, 170),
(16, 'PROD-424177', 'Quis Tempore Ex', 'Voluptatibus', 404726.82, 28),
(17, 'PROD-215847', 'Et Voluptas Voluptates', 'Voluptatibus', 551368.15, 148),
(18, 'PROD-497520', 'Incidunt Ut Qui', 'Aut', 390157.08, 117),
(19, 'PROD-879793', 'Eum Quam Occaecati', 'Consequuntur', 697642.75, 109),
(20, 'PROD-852980', 'Esse Reiciendis Et', 'Minima', 581065.80, 141),
(21, 'PROD-023276', 'Ducimus Eos Et', 'Modi', 1194161.92, 122),
(22, 'PROD-072697', 'Ab Culpa Rerum', 'Sint', 1221822.59, 172),
(23, 'PROD-043284', 'Consectetur Omnis Magnam', 'Et', 1134390.07, 32),
(25, 'PROD-489643', 'Hic Esse Ipsam', 'Eos', 226303.85, 70),
(26, 'PROD-785670', 'Praesentium Consequuntur Voluptas', 'Dolor', 1246585.35, 198),
(27, 'PROD-373900', 'Voluptatem Ut Id', 'Est', 135514.88, 77),
(28, 'PROD-153604', 'Harum Perspiciatis Id', 'Expedita', 1293253.20, 160),
(29, 'PROD-165462', 'Ipsum Quidem Alias', 'Reiciendis', 1353809.21, 32),
(30, 'PROD-239640', 'Id Maxime Magnam', 'Sed', 1207606.99, 192),
(31, 'PROD-815354', 'Repellendus Non Quidem', 'Sunt', 561705.75, 50),
(32, 'PROD-149550', 'Eum Tempora Nemo', 'Necessitatibus', 262310.01, 116),
(33, 'PROD-212427', 'Unde Velit Provident', 'Ut', 1385577.31, 70),
(34, 'PROD-315398', 'Voluptatem Nam Delectus', 'Ipsa', 966786.56, 47),
(35, 'PROD-934940', 'Quam Consectetur Illo', 'Doloribus', 963124.02, 44),
(36, 'PROD-214665', 'Modi Perferendis Et', 'Eaque', 783200.89, 156),
(37, 'PROD-547280', 'Delectus Vel Sed', 'Nemo', 241541.82, 81),
(38, 'PROD-720663', 'Quae In Illum', 'Ea', 152421.48, 108),
(39, 'PROD-734312', 'Sit Nulla Omnis', 'Voluptate', 814326.91, 127),
(40, 'PROD-628001', 'Distinctio Minus Et', 'Earum', 937814.70, 122),
(41, 'PROD-221230', 'Et Ipsum Quia', 'Aut', 118765.18, 78),
(42, 'PROD-008407', 'Voluptas Ut Quos', 'Consequatur', 221510.80, 105),
(43, 'PROD-268477', 'Cum Consequatur Nostrum', 'Qui', 457631.55, 43),
(44, 'PROD-950584', 'Dolores Dolores Illo', 'Dolores', 1437944.03, 143),
(45, 'PROD-434906', 'Quam Quasi Blanditiis', 'Quo', 193813.10, 183),
(46, 'PROD-550803', 'Odio Sit Placeat', 'Consequunturuuuu', 440914.17, 5),
(47, 'PROD-157040', 'Sequi Aperiam Molestiae', 'Libero', 1232996.47, 36),
(48, 'PROD-469397', 'Voluptatem Nobis Quibusdam', 'Sapiente', 874886.11, 140),
(49, 'PROD-450210', 'Et Quis Et', 'Non', 216946.13, 124),
(50, 'PROD-099299', 'Tenetur Exercitationem Voluptatem', 'Rerum', 19747.49, 24),
(52, 'PROD-636608', 'Eius Quos Accusamus', 'Mollitia', 594401.75, 77),
(53, 'prod 22222', 'ddddd', 'aaaa', 2344444.00, 294),
(54, 'prod 555555', 'fitrul', 'tumbuhan', 300000.00, 210),
(55, 'prod 33333', 'sarden', 'makan', 40000.00, 220),
(57, 'PROD 7888', 'energen', 'minuman', 5000.00, 200);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `total_harga` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `id_pelanggan`, `total_harga`) VALUES
(1, '2025-10-15', NULL, 200000.00),
(2, '2025-10-15', NULL, 130298.07),
(3, '2025-10-15', NULL, 130298.07),
(4, '2025-10-15', 4, 200000.00),
(5, '2025-10-01', 30, 300000.00),
(6, '2025-09-05', 23, 5505127.84),
(7, '2025-10-12', 27, 6911013.81),
(8, '2025-09-22', 23, 6164982.35),
(9, '2025-08-31', 21, 4651708.38),
(10, '2025-08-23', 32, 8453800.81),
(11, '2025-09-14', 17, 6780931.76),
(12, '2025-08-28', 24, 2463986.45),
(13, '2025-08-23', 14, 2678314.83),
(14, '2025-09-15', 12, 4759314.15),
(15, '2025-10-09', 25, 3749174.95),
(16, '2025-08-22', 23, 2813069.20),
(17, '2025-09-05', 32, 4305103.78),
(18, '2025-09-24', 33, 9666859.23),
(19, '2025-08-19', 6, 3660634.28),
(20, '2025-09-13', 28, 6964443.01),
(21, '2025-09-22', 12, 1362879.96),
(22, '2025-10-12', 21, 5772956.66),
(23, '2025-08-27', 21, 5775639.21),
(24, '2025-10-02', 4, 4087842.95),
(25, '2025-08-25', 26, 2828695.70),
(26, '2025-09-14', 30, 11392815.24),
(27, '2025-09-05', 13, 6184291.03),
(28, '2025-09-27', 19, 7244034.94),
(29, '2025-10-04', 10, 6219473.25),
(30, '2025-08-30', 28, 8261618.28),
(31, '2025-09-15', 34, 390157.08),
(32, '2025-09-11', 24, 7359845.98),
(33, '2025-08-16', 15, 7564112.34),
(34, '2025-08-16', 9, 9551985.85),
(35, '2025-09-14', 21, 17332630.81),
(36, '2025-08-27', 10, 5240256.15),
(37, '2025-08-25', 19, 1743197.40),
(38, '2025-09-28', 21, 3414107.24),
(39, '2025-10-10', 18, 1420249.32),
(40, '2025-10-01', 7, 6652937.19),
(41, '2025-10-05', 10, 5999158.31),
(42, '2025-09-08', 34, 3087676.54),
(43, '2025-08-24', 21, 1916199.67),
(44, '2025-08-29', 34, 5180803.96),
(45, '2025-09-09', 19, 4210093.01),
(46, '2025-08-31', 27, 4880060.40),
(47, '2025-10-06', 30, 7233332.00),
(48, '2025-10-06', 4, 3240338.76),
(49, '2025-10-13', 13, 937814.70),
(50, '2025-09-02', 4, 3403170.21),
(51, '2025-08-22', 27, 5942077.03),
(52, '2025-09-09', 10, 3875476.82),
(53, '2025-09-07', 21, 4405029.08),
(54, '2025-09-13', 6, 5862390.01),
(55, '2025-10-15', 9, 3415094.01),
(56, '2025-10-15', 4, 421850.18),
(57, '2025-10-16', 10, 3415094.01);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Kasir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Administrator', 'admin', '$2y$10$1YG/wpbmfqFH7UoZXlLF4OA5gP5NVYdJH4T.jF.h6Q3ihiLDiZtxe', 'Admin'),
(2, 'Kasir Toko', 'kasir', '$2y$10$M9K6tOzATObS8piI9rZHQuC0E7XBNxXl/q2o842l5Q1osyqcWyzMC', 'Kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi_ibfk_1` (`id_pelanggan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
