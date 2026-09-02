-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2026 at 01:53 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dpr_personal`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `judul`, `lokasi`, `tanggal`, `waktu`, `deskripsi`, `created_at`) VALUES
(1, 'kunjungan kerja', 'lampung barat', '2026-01-11', '08:00:00', 'penyuluhan', '2026-01-11 14:30:10');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_dpr`
--

CREATE TABLE `anggota_dpr` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `komite` varchar(50) DEFAULT NULL,
  `daerah_pemilihan` varchar(100) DEFAULT NULL,
  `visi` text,
  `misi` text,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_dpr`
--

INSERT INTO `anggota_dpr` (`id`, `nama`, `foto`, `jabatan`, `komite`, `daerah_pemilihan`, `visi`, `misi`, `email`, `password`, `created_at`) VALUES
(1, 'Almira Nabila Fauzi, B.Bus.Com', 'edi.jpg', '-', '-', '-', '-', '-', '123@gmail.com', '$2y$10$VcMgD5OzJxZ2QqVnDDEAdu3.Hgq9RmnEeCo3HRcaUbMuNm0H.8ELq', '2026-01-10 14:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id` int NOT NULL,
  `masyarakat_id` int NOT NULL,
  `bidang_id` int NOT NULL,
  `kabupaten_id` int NOT NULL,
  `kecamatan_id` int NOT NULL,
  `desa_id` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi_aspirasi` text NOT NULL,
  `lokasi_jalan` varchar(255) DEFAULT NULL,
  `status` enum('Masuk','Diproses','Ditindaklanjuti','Selesai') DEFAULT 'Masuk',
  `tanggal_aspirasi` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id`, `masyarakat_id`, `bidang_id`, `kabupaten_id`, `kecamatan_id`, `desa_id`, `judul`, `isi_aspirasi`, `lokasi_jalan`, `status`, `tanggal_aspirasi`, `created_at`, `foto`) VALUES
(1, 1, 5, 6, 1, 5, 'perbaikan jalan yang berlubang', 'kami dari warga sekitar berharap aspirasi kami bisa di dengar oleh orang atas tentang keluh kesah kami terhadap jalan yang berlubang yang sering kali membuat celaka baik warga sekitar maupun warga luar', 'jalan raya taman sari', 'Masuk', '2026-01-11', '2026-01-11 15:32:16', NULL),
(2, 1, 3, 6, 1, 6, 'kunjungan kerja', 'ghdgygc', 'jalan raya taman sari', 'Masuk', '2026-01-11', '2026-01-11 16:50:39', NULL),
(3, 2, 11, 6, 1, 8, 'ucup', 'jalan rusak', 'jalan raya taman sari', 'Selesai', '2026-04-27', '2026-04-27 00:43:08', NULL),
(4, 2, 8, 26, 4, 3, 'tes', 'jambret', 'jalan raya 1', 'Diproses', '2026-04-27', '2026-04-27 01:05:53', '20260427010553_69eeb6711737b.png');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `isi`, `gambar`, `tanggal_post`) VALUES
(1, 'prestasi yang gemilang di awal tahun 2025', 'dasgasg', '1768146916_9579.jpeg', '2026-01-11 15:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `bidang`
--

CREATE TABLE `bidang` (
  `id` int NOT NULL,
  `nama_bidang` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bidang`
--

INSERT INTO `bidang` (`id`, `nama_bidang`, `keterangan`) VALUES
(1, 'Komisi I – Pertahanan, Luar Negeri, Kominfo', NULL),
(2, 'Komisi II – Pemerintahan Dalam Negeri', NULL),
(3, 'Komisi III – Hukum dan HAM', NULL),
(4, 'Komisi IV – Pertanian, Kehutanan, Kelautan', NULL),
(5, 'Komisi V – Infrastruktur dan Perhubungan', NULL),
(6, 'Komisi VI – Perdagangan dan BUMN', NULL),
(7, 'Komisi VII – Energi dan Riset', NULL),
(8, 'Komisi VIII – Sosial dan Keagamaan', NULL),
(9, 'Komisi IX – Kesehatan dan Ketenagakerjaan', NULL),
(10, 'Komisi X – Pendidikan dan Kebudayaan', NULL),
(11, 'Komisi XI – Keuangan dan Perbankan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `desa`
--

CREATE TABLE `desa` (
  `id` int NOT NULL,
  `kecamatan_id` int NOT NULL,
  `nama_desa` varchar(100) NOT NULL,
  `jenis` enum('Desa','Kelurahan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `desa`
--

INSERT INTO `desa` (`id`, `kecamatan_id`, `nama_desa`, `jenis`) VALUES
(1, 4, 'Gadingrejo', 'Desa'),
(2, 4, 'Gading Rejo Timur', 'Desa'),
(3, 4, 'Gading Rejo Utara', 'Desa'),
(4, 4, 'Blitarejo', 'Desa'),
(5, 1, 'Suka Agung', 'Desa'),
(6, 1, 'Pekon Negeri Ratu', 'Desa'),
(7, 1, 'Tanjung Jati', 'Desa'),
(8, 1, 'Banjarsari', 'Desa'),
(9, 4, 'Mataram', 'Desa'),
(10, 4, 'Panjerejo', 'Desa'),
(11, 4, 'Parerejo', 'Desa'),
(12, 4, 'Tambahrejo', 'Desa'),
(13, 4, 'Tambah Rejo Barat', 'Desa'),
(14, 4, 'Tegalsari', 'Desa'),
(15, 4, 'Tulung Agung', 'Desa'),
(16, 4, 'Wates', 'Desa'),
(17, 4, 'Wates Selatan', 'Desa'),
(18, 4, 'Wates Timur', 'Desa'),
(19, 4, 'Wonodadi', 'Desa'),
(20, 4, 'Wonodadi Utara', 'Desa'),
(21, 4, 'Wonosari', 'Desa'),
(22, 4, 'Bulokarto', 'Desa'),
(23, 4, 'Bulurejo', 'Desa'),
(24, 4, 'Kediri', 'Desa'),
(25, 4, 'Klaten', 'Desa'),
(26, 7, 'Pardasuka', 'Desa'),
(27, 7, 'Pardasuka Timur', 'Desa'),
(28, 7, 'Pardasuka Selatan', 'Desa'),
(29, 7, 'Sidodadi', 'Desa'),
(30, 7, 'Sidodadi Timur', 'Desa'),
(31, 7, 'Sidodadi Selatan', 'Desa'),
(32, 7, 'Sukorejo', 'Desa'),
(33, 7, 'Sukorejo Timur', 'Desa'),
(34, 7, 'Sukorejo Selatan', 'Desa'),
(35, 7, 'Tanjung Rusia', 'Desa'),
(36, 7, 'Tanjung Rusia Timur', 'Desa'),
(37, 7, 'Tanjung Rusia Selatan', 'Desa'),
(38, 7, 'Wargomulyo', 'Desa'),
(39, 7, 'Wargomulyo Timur', 'Desa'),
(40, 7, 'Wargomulyo Selatan', 'Desa'),
(41, 2, 'Ambarawa', 'Desa'),
(42, 2, 'Ambarawa Barat', 'Desa'),
(43, 2, 'Ambarawa Timur', 'Desa'),
(44, 2, 'Sumber Agung', 'Desa'),
(45, 2, 'Sumber Agung Barat', 'Desa'),
(46, 2, 'Sumber Agung Timur', 'Desa'),
(47, 2, 'Margodadi', 'Desa'),
(48, 2, 'Margodadi Selatan', 'Desa'),
(49, 2, 'Margodadi Timur', 'Desa'),
(50, 2, 'Kresnomulyo', 'Desa'),
(51, 2, 'Kresnomulyo Barat', 'Desa'),
(52, 2, 'Kresnomulyo Timur', 'Desa'),
(53, 2, 'Sidoharjo', 'Desa'),
(54, 2, 'Sidoharjo Barat', 'Desa'),
(55, 2, 'Sidoharjo Timur', 'Desa');

-- --------------------------------------------------------

--
-- Table structure for table `kabupaten`
--

CREATE TABLE `kabupaten` (
  `id` int NOT NULL,
  `nama_kabupaten` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kabupaten`
--

INSERT INTO `kabupaten` (`id`, `nama_kabupaten`) VALUES
(6, 'Kabupaten Tanggamus'),
(16, 'Kabupaten Lampung Selatan'),
(17, 'Kabupaten Lampung Tengah'),
(18, 'Kabupaten Lampung Timur'),
(19, 'Kabupaten Lampung Utara'),
(20, 'Kabupaten Lampung Barat'),
(22, 'Kabupaten Tulang Bawang'),
(23, 'Kabupaten Tulang Bawang Barat'),
(24, 'Kabupaten Way Kanan'),
(25, 'Kabupaten Pesawaran'),
(26, 'Kabupaten Pringsewu'),
(27, 'Kabupaten Mesuji'),
(28, 'Kabupaten Pesisir Barat'),
(29, 'Kota Bandar Lampung'),
(30, 'Kota Metro');

-- --------------------------------------------------------

--
-- Table structure for table `kecamatan`
--

CREATE TABLE `kecamatan` (
  `id` int NOT NULL,
  `kabupaten_id` int NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kecamatan`
--

INSERT INTO `kecamatan` (`id`, `kabupaten_id`, `nama_kecamatan`) VALUES
(1, 6, 'Bulok'),
(2, 26, 'Ambarawa'),
(3, 26, 'Banyumas'),
(4, 26, 'Gadingrejo'),
(5, 26, 'Pagelaran'),
(6, 26, 'Pagelaran Utara'),
(7, 26, 'Pardasuka'),
(8, 26, 'Pringsewu'),
(9, 26, 'Sukoharjo'),
(10, 26, 'Adiluwih'),
(22, 6, 'Cukuh Balak'),
(23, 6, 'Gisting'),
(24, 6, 'Gunung Alip'),
(25, 6, 'Kota Agung'),
(26, 6, 'Kota Agung Barat'),
(27, 6, 'Kota Agung Timur'),
(28, 6, 'Pulau Panggung'),
(29, 6, 'Talang Padang'),
(30, 6, 'Wonosobo');

-- --------------------------------------------------------

--
-- Table structure for table `masyarakat`
--

CREATE TABLE `masyarakat` (
  `id` int NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `masyarakat`
--

INSERT INTO `masyarakat` (`id`, `nik`, `nama`, `email`, `password`, `provinsi`, `kabupaten`, `kecamatan`, `desa`, `created_at`) VALUES
(1, '1810271410040001', 'admin@gmail.com', NULL, '$2y$10$m0Ol0P52Npr4OcqbdjlX2uE1moEeEICWcgqCdiugZoDek4zaVLkxm', NULL, NULL, NULL, NULL, '2026-01-10 14:31:43'),
(2, '12345', 'Edi Kurniawan', NULL, '$2y$10$2RTqhnzqnCLwlb1T/o.CTudMcvNasx9/lZh8O6dXnu37PPgGU/jw.', NULL, NULL, NULL, NULL, '2026-02-04 12:24:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anggota_dpr`
--
ALTER TABLE `anggota_dpr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_masyarakat` (`masyarakat_id`),
  ADD KEY `fk_bidang` (`bidang_id`),
  ADD KEY `fk_aspirasi_kabupaten` (`kabupaten_id`),
  ADD KEY `fk_aspirasi_kecamatan` (`kecamatan_id`),
  ADD KEY `fk_aspirasi_desa` (`desa_id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bidang`
--
ALTER TABLE `bidang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `desa`
--
ALTER TABLE `desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kecamatan_id` (`kecamatan_id`);

--
-- Indexes for table `kabupaten`
--
ALTER TABLE `kabupaten`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kabupaten_id` (`kabupaten_id`);

--
-- Indexes for table `masyarakat`
--
ALTER TABLE `masyarakat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `anggota_dpr`
--
ALTER TABLE `anggota_dpr`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bidang`
--
ALTER TABLE `bidang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `desa`
--
ALTER TABLE `desa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `kabupaten`
--
ALTER TABLE `kabupaten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `kecamatan`
--
ALTER TABLE `kecamatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `masyarakat`
--
ALTER TABLE `masyarakat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `fk_aspirasi_desa` FOREIGN KEY (`desa_id`) REFERENCES `desa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aspirasi_kabupaten` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aspirasi_kecamatan` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bidang` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_masyarakat` FOREIGN KEY (`masyarakat_id`) REFERENCES `masyarakat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `desa`
--
ALTER TABLE `desa`
  ADD CONSTRAINT `desa_ibfk_1` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`);

--
-- Constraints for table `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD CONSTRAINT `kecamatan_ibfk_1` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
