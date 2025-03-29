-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2025 at 08:13 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skkpd`
--

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `jurusan`) VALUES
('J1', 'RPL'),
('J2', 'TKJ'),
('J3', 'AN'),
('J4', 'DKV');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Wajib','Optional') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sub_kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `kategori`, `sub_kategori`) VALUES
('KTG1', 'Wajib', 'Kurikulum Merdeka Project P5'),
('KTG2', 'Wajib', 'Ekstra Kurikuler'),
('KTG3', 'Optional', 'TEFA (Teaching Factory)'),
('KTG4', 'Optional', 'Organisasi Sekolah'),
('KTG5', 'Optional', 'Komunitas Kreatif Siswa'),
('KTG6', 'Optional', 'Penalaran/Karya Ilmiah/Akademik'),
('KTG7', 'Optional', 'Perlombaan/Kejuaraan/Kompetisi'),
('KTG8', 'Optional', 'Kegiatan Lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id_kegiatan` int NOT NULL,
  `jenis_kegiatan` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `angka_kredit` int NOT NULL,
  `id_kategori` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id_kegiatan`, `jenis_kegiatan`, `angka_kredit`, `id_kategori`) VALUES
(1, 'Project Gaya Hidup Berkelanjutan', 1, 'KTG1'),
(2, 'Project Kearifan Lokal', 1, 'KTG1'),
(3, 'Project Kebekerjaan', 1, 'KTG1'),
(4, 'Project Kewirausahaan', 1, 'KTG1'),
(5, 'Project Bhineka Tunggal Ika', 1, 'KTG1'),
(6, 'Project Rekayasa dan Teknologi', 1, 'KTG1'),
(7, 'Ekstra Kurikuler Wajib', 2, 'KTG2'),
(8, 'Ekstra Kurikuler Pilihan', 2, 'KTG2'),
(9, 'Bekerja dengan stake holder', 5, 'KTG3'),
(10, 'Kewirausahaan', 5, 'KTG3'),
(11, 'Ketua Osis', 15, 'KTG4'),
(12, 'Wakil Ketua Osis', 12, 'KTG4'),
(13, 'Sekretaris Osis', 12, 'KTG4'),
(14, 'Bendahara Osis', 12, 'KTG4'),
(15, 'Anggota Osis', 10, 'KTG4'),
(16, 'Ketua Rosis/Sehati/Nasrani', 8, 'KTG4'),
(17, 'Wakil Ketua Rosis/Sehati/Nasrani', 5, 'KTG4'),
(18, 'Sekretaris Rosis/Sehati/Nasrani', 5, 'KTG4'),
(19, 'Bendahara Rosis/Sehati/Nasrani', 5, 'KTG4'),
(20, 'Anggota Rosis/Sehati/Nasrani', 3, 'KTG4'),
(21, 'Ketua Podcast', 12, 'KTG5'),
(22, 'Wakil Ketua Podcast', 10, 'KTG5'),
(23, 'Sekretaris Podcast', 10, 'KTG5'),
(24, 'Bendahara Podcast', 10, 'KTG5'),
(25, 'Anggota Podcast', 8, 'KTG5'),
(26, 'Ketua Broadcast', 12, 'KTG5'),
(27, 'Wakil Ketua Broadcast', 10, 'KTG5'),
(28, 'Sekretaris Broadcast', 10, 'KTG5'),
(29, 'Bendahara Broadcast', 10, 'KTG5'),
(30, 'Anggota Broadcast', 8, 'KTG5'),
(31, 'Penulisan karya ilmiah/riset/buletin/jurnal', 5, 'KTG6'),
(32, 'Peserta (seminar, simposium, lokakarya, diskusi panel)', 2, 'KTG6'),
(33, 'Pelatihan (penulisan karya ilmiah, kewirausahaan)', 2, 'KTG6'),
(34, 'Pengembangan Bahasa asing (English) dengan kegiatan International', 2, 'KTG6'),
(35, 'Juara 1 Internasional', 7, 'KTG7'),
(36, 'Juara 2 Internasional', 5, 'KTG7'),
(37, 'Juara 3 Internasional', 3, 'KTG7'),
(38, 'Harapan 1/2/3 Internasional', 2, 'KTG7'),
(39, 'Peserta Internasional', 1, 'KTG7'),
(40, 'Juara 1 nasional', 5, 'KTG7'),
(41, 'Juara 2 nasional', 4, 'KTG7'),
(42, 'Juara 3 nasional', 3, 'KTG7'),
(43, 'Harapan 1/2/3 nasional', 2, 'KTG7'),
(44, 'Peserta nasional', 1, 'KTG7'),
(45, 'Juara 1 Regional / Provinsi /  Kabupaten / Kota', 4, 'KTG7'),
(46, 'Juara 2 Regional / Provinsi /  Kabupaten / Kota', 3, 'KTG7'),
(47, 'Juara 3 Regional / Provinsi /  Kabupaten / Kota', 2, 'KTG7'),
(48, 'Harapan 1/2/3 Regional / Provinsi /  Kabupaten / Kota', 1, 'KTG7'),
(49, 'Peserta Regional / Provinsi /  Kabupaten / Kota', 1, 'KTG7'),
(50, 'Juara 1 internal', 3, 'KTG7'),
(51, 'Juara 2 internal', 2, 'KTG7'),
(52, 'Juara 3 internal', 1, 'KTG7'),
(53, 'Bakti Sosial', 2, 'KTG8'),
(54, 'Kepanitiaan Kegiatan Sekolah selain OSIS, Organisasi siswa, dan Komunitas', 2, 'KTG8'),
(55, 'Undangan sebagai Nara Sumber Podcast', 2, 'KTG8');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `nama_lengkap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`nama_lengkap`, `username`) VALUES
('Putu Yenni Suryantari', 'Yenny');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nis` int DEFAULT NULL,
  `password` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `nis`, `password`) VALUES
(1, 'Yenny', NULL, '$2y$10$nhq.0gBft5sOb0VlmhGxFuuU2KCQXfcS4kwOVZpYqSk2u4FGB.11S'),
(9, NULL, 6298, '$2y$10$RxqRt69SWwcYjWN/rjsP5OGWKG1UmvvXqEEw.MSrTGVbnNp8Rej9e'),
(66, NULL, 6292, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(67, NULL, 6293, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(68, NULL, 6294, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(69, NULL, 6295, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(70, NULL, 6296, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(71, NULL, 6297, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(72, NULL, 6299, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(73, NULL, 6300, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(74, NULL, 6301, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(75, NULL, 6302, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(76, NULL, 6303, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(77, NULL, 6304, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(78, NULL, 6306, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(79, NULL, 6307, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(80, NULL, 6308, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(81, NULL, 6309, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(82, NULL, 6310, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(83, NULL, 6311, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(84, NULL, 6312, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(85, NULL, 6313, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(86, NULL, 6314, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(87, NULL, 6315, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(88, NULL, 6317, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(89, NULL, 6318, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(90, NULL, 6319, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai'),
(91, NULL, 6626, '$2y$10$hbzKYPCSxiPlqT0gj8AFIeNw8s2lsJfv6CjMtuzR/whQ32uO5Bsai');

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `id_sertifikat` int NOT NULL,
  `tgl_upload` date NOT NULL,
  `catatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sertifikat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_status_berubah` date DEFAULT NULL,
  `nis` int NOT NULL,
  `id_kegiatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`id_sertifikat`, `tgl_upload`, `catatan`, `sertifikat`, `status`, `tgl_status_berubah`, `nis`, `id_kegiatan`) VALUES
(110, '2025-02-28', NULL, 'sertif1.pdf', 'Valid', '2025-03-10', 6298, 1),
(118, '2025-03-11', NULL, 'sertif2.pdf', 'Valid', '2025-03-14', 6298, 1),
(119, '2025-03-11', 'Salah File', 'sertif1.pdf', 'Tidak Valid', '2025-03-14', 6298, 8),
(126, '2025-03-14', NULL, 'sertif1.pdf', 'Menunggu Validasi', NULL, 6298, 22),
(127, '2025-03-14', NULL, 'sertif2.pdf', 'Menunggu Validasi', NULL, 6292, 12),
(128, '2025-03-14', NULL, '6304_sertif.pdf', 'Menunggu Validasi', NULL, 6304, 22);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` int NOT NULL,
  `no_absen` int NOT NULL,
  `nama_siswa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_jurusan` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` int NOT NULL,
  `angkatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `no_absen`, `nama_siswa`, `no_telp`, `email`, `id_jurusan`, `kelas`, `angkatan`) VALUES
(6292, 1, 'Albert Made Marvell Adnyana', '89644952917', 'writeforaby@gmail.com', 'J1', 1, 2024),
(6293, 2, 'Alif Rizki Raditya', '081239801107', 'alifrizky286@gmail.com', 'J1', 1, 2024),
(6294, 3, 'Ariza Falah Subyantoro', '082145584677', 'ariza.subyantoro@gmail.com', 'J1', 1, 2024),
(6295, 4, 'Aurelio Fransiskus Sinarta', '089516724229', 'aureliofs01@gmail.com', 'J1', 1, 2024),
(6296, 5, 'Bezaleel Elderoy Sulastiyo', '08977303987', 'bezaleelelderoys@gmail.com', 'J1', 1, 2024),
(6297, 6, 'Chien Shevanya Lie', '082144049709', 'chienshevanyalie@gmail.com', 'J1', 2, 2024),
(6298, 7, 'Faiz Zaidan Hadi', '085738170030', 'faizzaidanhadi007@gmail.com', 'J1', 2, 2024),
(6299, 8, 'Helmi Malik Nur Robi', '08990420398', 'codename.mee@gmail.com', 'J1', 2, 2024),
(6300, 9, 'Henri Saputra', '082146357077', 'hs5565566@gmail.com', 'J1', 3, 2024),
(6301, 10, 'I Gede Made Paramartha Nugraha', '081239330607', 'paramartha1307@gmail.com', 'J1', 3, 2024),
(6302, 11, 'I Gede Sheva Putrayana', '08980588086', 'seavweb@gmail.com', 'J1', 4, 2024),
(6303, 12, 'I Gusti Agung Putu Aswinanta Wikrama', '085739285687', 'nntxwww@gmail.com', 'J1', 5, 2024),
(6304, 13, 'I Gusti Ngurah Pranajaya Udiartha', '081934996141', 'gejeee14@gmail.com', 'J1', 4, 2024),
(6306, 14, 'I Made Seva Santepi Putra Winata', '081338369652', 'sevawinata@gmail.com', 'J1', 3, 2023),
(6307, 15, 'I Putu Adita Pratama', '085903627353', 'aditapratama654@gmail.com', 'J1', 5, 2023),
(6308, 16, 'I Putu Gede Deva Suka Dian Pratama', '087757862060', 'putudeva49@gmail.com', 'J2', 1, 2023),
(6309, 17, 'Ida Bagus Dwiya Kusala Mahari Prabhaswara', '082145280323', 'maharikusala@gmail.com', 'J2', 1, 2023),
(6310, 18, 'Komang Krisna Puspanta', '087754766536', 'ikomangkrisna40@gmail.com', 'J2', 1, 2023),
(6311, 19, 'Krisna Septiadji Suhaya', '081337858322', 'krisnaseptiaji@gmail.com', 'J2', 2, 2023),
(6312, 20, 'Lulu Ilyana Lintang Az-zahra', '081363716909', 'luluilyanaaz.9107@gmail.com', 'J2', 2, 2023),
(6313, 21, 'M.Hidayatullah', '081237278762', 'hidayat04191@gmail.com', 'J4', 1, 2023),
(6314, 22, 'Muhammad Jaffan Hanindito', '085772209371', 'haninditoj@gmail.com', 'J4', 1, 2023),
(6315, 23, 'Ni Kadek Sherly Cempaka Dewi', '081529942897', 'sherlycempaka@icloud.com', 'J4', 2, 2023),
(6317, 24, 'Sadewa Bharaka Mahaputra', '082247814145', 'sadeeznut@gmail.com', 'J4', 2, 2023),
(6318, 25, 'Satria Bela Pratama', '085157099482', 'codewithsatria@gmail.com', 'J3', 1, 2023),
(6319, 26, 'Vania Bella Amadea', '089524649718', 'amadeabella007@gmail.com', 'J3', 1, 2023),
(6626, 27, 'Ida Ayu Lalita', '087755725057', 'lalitaputri2429@gmail.com', 'J3', 2, 2023);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD KEY `username` (`username`,`nis`),
  ADD KEY `nis` (`nis`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`id_sertifikat`),
  ADD KEY `nis` (`nis`,`id_kegiatan`),
  ADD KEY `id_kegiatan` (`id_kegiatan`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `id_sertifikat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengguna_ibfk_2` FOREIGN KEY (`username`) REFERENCES `pegawai` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `sertifikat_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sertifikat_ibfk_2` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
