/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.3-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: desa_pinabetengan
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB-1+b1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `admin` VALUES
(1,'admin','$2y$12$/GeaBEXRAgyMuVZVBc5IGO1nGP3ievSK5XEt7wkkiVV2qYqkXTF3O');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `berita`
--

DROP TABLE IF EXISTS `berita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `berita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` date DEFAULT curdate(),
  `kategori` enum('kegiatan','pengumuman') DEFAULT 'kegiatan',
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `berita`
--

LOCK TABLES `berita` WRITE;
/*!40000 ALTER TABLE `berita` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `berita` VALUES
(3,'TESTING BERITA 2','ohihhkhu;oup9','2025-11-02','kegiatan',NULL),
(5,'TESTING BERITA 3','ohihhkhu;oup9','2025-11-02','kegiatan','berita_1762102456_69078cb8679e1.png'),
(6,'RAPAT DEWAN DESA','INI bagian info pengumuman','2025-11-02','pengumuman',NULL),
(7,'Gotong Royong Pembersihan Lingkungan','Warga masyarakat Desa Pinabetengan Selatan bersama-sama melakukan kegiatan gotong royong membersihkan lingkungan sekitar. Kegiatan ini dilaksanakan dalam rangka menyambut musim penghujan dan mencegah terjadinya banjir. Semua warga berpartisipasi dengan antusias mulai dari membersihkan selokan, sampah dedaunan, hingga merapikan taman desa.','2024-10-28','kegiatan',NULL),
(8,'Pelatihan Kewirausahaan Pemuda Desa','Pemerintah desa bekerjasama dengan Dinas Koperasi dan UKM mengadakan pelatihan kewirausahaan bagi pemuda desa. Pelatihan ini bertujuan untuk memberdayakan generasi muda dalam menciptakan lapangan kerja mandiri melalui berbagai usaha kreatif dan inovatif.','2024-10-25','kegiatan',NULL),
(9,'Pengumuman Jadwal Pemadaman Listrik','Diberitahukan kepada seluruh warga bahwa akan dilakukan pemadaman listrik bergilir pada tanggal 5 November 2024 pukul 09.00 - 15.00 WITA untuk keperluan perawatan jaringan. Mohon maaf atas ketidaknyamanannya.','2024-10-30','pengumuman',NULL),
(10,'Pendaftaran Bantuan Sosial Tahap II','Pemerintah Desa membuka pendaftaran bantuan sosial tahap II untuk warga yang memenuhi kriteria. Pendaftaran dibuka mulai tanggal 1-10 November 2024 di kantor desa. Syarat dan ketentuan dapat dilihat di pengumuman resmi.','2024-10-29','pengumuman',NULL),
(11,'Senam Sehat Bersama Warga','Dalam rangka memperingati Hari Kesehatan Nasional, desa mengadakan senam sehat bersama yang diikuti oleh seluruh warga. Kegiatan ini bertujuan untuk meningkatkan kesadaran akan pentingnya menjaga kesehatan melalui olahraga rutin.','2024-10-20','kegiatan',NULL),
(12,'Perbaikan Jalan Desa','Mulai minggu depan akan dilakukan perbaikan jalan di beberapa ruas jalan desa yang mengalami kerusakan. Mohon pengertian dan kerjasama warga untuk menggunakan jalur alternatif selama proses perbaikan berlangsung.','2024-10-27','pengumuman',NULL),
(13,'RAPAT DEWAN DESA','INI bagian info pengumuman','2025-11-02','pengumuman',NULL);
/*!40000 ALTER TABLE `berita` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `data_pekerjaan`
--

DROP TABLE IF EXISTS `data_pekerjaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_pekerjaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_pekerjaan`
--

LOCK TABLES `data_pekerjaan` WRITE;
/*!40000 ALTER TABLE `data_pekerjaan` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `data_pekerjaan` VALUES
(1,'Petani',1245,'2025-11-02 18:01:32'),
(2,'Pedagang',856,'2025-11-02 18:01:32'),
(3,'PNS/TNI/Polri',324,'2025-11-02 18:01:32'),
(4,'Karyawan Swasta',1087,'2025-11-02 18:01:32'),
(5,'Wiraswasta',892,'2025-11-02 18:01:32'),
(6,'Lainnya',843,'2025-11-02 18:01:32');
/*!40000 ALTER TABLE `data_pekerjaan` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `data_pendidikan`
--

DROP TABLE IF EXISTS `data_pendidikan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_pendidikan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tingkat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_pendidikan`
--

LOCK TABLES `data_pendidikan` WRITE;
/*!40000 ALTER TABLE `data_pendidikan` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `data_pendidikan` VALUES
(10,'Tidak Sekolah',80,'2025-11-02 18:12:45'),
(11,'SD/Sederajat',14,'2025-11-02 18:12:45'),
(12,'SMP/Sederajat',130,'2025-11-02 18:12:45'),
(13,'SMA/Sederajat',15,'2025-11-02 18:12:45');
/*!40000 ALTER TABLE `data_pendidikan` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `data_penduduk`
--

DROP TABLE IF EXISTS `data_penduduk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_penduduk` int(11) NOT NULL,
  `laki_laki` int(11) NOT NULL,
  `perempuan` int(11) NOT NULL,
  `kepala_keluarga` int(11) NOT NULL,
  `kepadatan_penduduk` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_penduduk`
--

LOCK TABLES `data_penduduk` WRITE;
/*!40000 ALTER TABLE `data_penduduk` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `data_penduduk` VALUES
(1,500,262,2626,100,245,2024,'2025-11-02 18:01:31','2025-11-02 18:13:50');
/*!40000 ALTER TABLE `data_penduduk` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `galeri`
--

DROP TABLE IF EXISTS `galeri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `galeri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galeri`
--

LOCK TABLES `galeri` WRITE;
/*!40000 ALTER TABLE `galeri` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `galeri` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pejabat`
--

DROP TABLE IF EXISTS `pejabat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pejabat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pejabat`
--

LOCK TABLES `pejabat` WRITE;
/*!40000 ALTER TABLE `pejabat` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `pejabat` VALUES
(2,'ANDRO','Kepala Desa','2025-11-03','2030-12-30','uploads/pejabat/6907e54cd99cf_ANDRO.png','2025-11-02 23:12:12');
/*!40000 ALTER TABLE `pejabat` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `potensi`
--

DROP TABLE IF EXISTS `potensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `potensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_potensi` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `icon` varchar(100) DEFAULT 'fas fa-gem',
  `urutan` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `potensi`
--

LOCK TABLES `potensi` WRITE;
/*!40000 ALTER TABLE `potensi` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `potensi` VALUES
(3,'Wisata Alam','Bendang Stable dan panorama alam yang memukau, menawarkan pengalaman wisata edukasi dan petualangan yang tak terlupakan.','fas fa-horse',3,'active','2025-11-02 15:12:35'),
(4,'Watu Pinawetengan','Situs bersejarah megalitikum yang menjadi saksi bisu peradaban Minahasa, menawarkan wisata budaya dan spiritual yang mendalam.','fas fa-monument',1,'active','2025-11-02 15:48:48'),
(5,'Kuliner Tradisional','Pangsit jagung dan beragam hidangan khas yang memadukan cita rasa autentik dengan warisan kuliner turun-temurun.','fas fa-utensils',2,'active','2025-11-02 15:48:48'),
(7,'testing input admin side','Testing uplaod text','fas fa-gem',4,'active','2025-11-03 07:26:06');
/*!40000 ALTER TABLE `potensi` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `potensi_desa`
--

DROP TABLE IF EXISTS `potensi_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `potensi_desa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `potensi_desa`
--

LOCK TABLES `potensi_desa` WRITE;
/*!40000 ALTER TABLE `potensi_desa` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `potensi_desa` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `profil`
--

DROP TABLE IF EXISTS `profil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_desa` varchar(255) DEFAULT NULL,
  `tentang` text DEFAULT NULL,
  `jumlah_penduduk` int(11) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `sejarah` text DEFAULT NULL,
  `gambar_sejarah` varchar(255) DEFAULT NULL,
  `gambar_desa` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profil`
--

LOCK TABLES `profil` WRITE;
/*!40000 ALTER TABLE `profil` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `profil` VALUES
(1,'Desa Pinabetengan Selatan','TESING TENTANG DESA',200,'test','test','TESTING INPUT SISi ADMIN UNTUK SEJARAHA DESA','uploads/desa/69084cf16e238_sejarah_Desa_Pinabetengan_Selatan.webp','uploads/desa/6907f48215d80_Desa_Pinabetengan_Selatan.png','2025-11-03 06:34:25');
/*!40000 ALTER TABLE `profil` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `profil_desa`
--

DROP TABLE IF EXISTS `profil_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil_desa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_desa` varchar(255) DEFAULT NULL,
  `sejarah` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profil_desa`
--

LOCK TABLES `profil_desa` WRITE;
/*!40000 ALTER TABLE `profil_desa` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `profil_desa` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `statistik`
--

DROP TABLE IF EXISTS `statistik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `statistik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penduduk` int(11) NOT NULL DEFAULT 2500,
  `rukun_tetangga` int(11) NOT NULL DEFAULT 8,
  `umkm_aktif` int(11) NOT NULL DEFAULT 15,
  `destinasi_wisata` int(11) NOT NULL DEFAULT 5,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistik`
--

LOCK TABLES `statistik` WRITE;
/*!40000 ALTER TABLE `statistik` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `statistik` VALUES
(1,100,6,10,4,'2025-11-02 17:52:16');
/*!40000 ALTER TABLE `statistik` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `umkm`
--

DROP TABLE IF EXISTS `umkm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `umkm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umkm`
--

LOCK TABLES `umkm` WRITE;
/*!40000 ALTER TABLE `umkm` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `umkm` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping events for database 'desa_pinabetengan'
--

--
-- Dumping routines for database 'desa_pinabetengan'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-11-04  4:53:16
