/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.2.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: gereja
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

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
-- Table structure for table `aktivitas_jemaats`
--

DROP TABLE IF EXISTS `aktivitas_jemaats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktivitas_jemaats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `kegiatan` varchar(255) DEFAULT NULL,
  `kehadiran` varchar(255) DEFAULT NULL,
  `pager` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jemaat` varchar(255) DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jemaat_ids` varchar(255) DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` varchar(255) DEFAULT NULL,
  `waktu_hadir` varchar(255) DEFAULT NULL,
  `status_kehadiran` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_absen` varchar(255) DEFAULT NULL,
  `dicatat_oleh` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jam_mulai` varchar(255) DEFAULT NULL,
  `jam_selesai` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tempat` varchar(255) DEFAULT NULL,
  `anggota` varchar(255) DEFAULT NULL,
  `laporan` varchar(255) DEFAULT NULL,
  `columns` varchar(255) DEFAULT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `statistik_bulan_ini` varchar(255) DEFAULT NULL,
  `kehadiran_terakhir` varchar(255) DEFAULT NULL,
  `jadwal_ibadah_bulan_ini` varchar(255) DEFAULT NULL,
  `aktivitas_pelayanan_terbaru` varchar(255) DEFAULT NULL,
  `peringkat` varchar(255) DEFAULT NULL,
  `hadir` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aktivitas_jemaats`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `aktivitas_jemaats` WRITE;
/*!40000 ALTER TABLE `aktivitas_jemaats` DISABLE KEYS */;
/*!40000 ALTER TABLE `aktivitas_jemaats` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `anggarans`
--

DROP TABLE IF EXISTS `anggarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggarans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `anggaran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `tahun_anggaran` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `nama_anggaran` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kode_anggaran` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `total_anggaran` text DEFAULT NULL,
  `tanggal_cetak` date DEFAULT NULL,
  `total_anggaran_tahun_ini` text DEFAULT NULL,
  `anggaran_disetujui` text DEFAULT NULL,
  `anggaran_realisasi` text DEFAULT NULL,
  `anggaran_per_program` text DEFAULT NULL,
  `anggaran_per_periode` text DEFAULT NULL,
  `error` text DEFAULT NULL,
  `rencana` text DEFAULT NULL,
  `diajukan` text DEFAULT NULL,
  `disetujui` text DEFAULT NULL,
  `ditolak` text DEFAULT NULL,
  `realisasi` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggarans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `anggarans` WRITE;
/*!40000 ALTER TABLE `anggarans` DISABLE KEYS */;
/*!40000 ALTER TABLE `anggarans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `anggota_komisis`
--

DROP TABLE IF EXISTS `anggota_komisis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota_komisis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `komisi` text DEFAULT NULL,
  `nama_komisi` text DEFAULT NULL,
  `tugas_pokok` text DEFAULT NULL,
  `warna` text DEFAULT NULL,
  `valid_color` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `sekretaris_id` int(11) DEFAULT NULL,
  `bendahara_id` int(11) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `users` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `numeric` text DEFAULT NULL,
  `jabatan` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `komisi_id` int(11) DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `success` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `staff` text DEFAULT NULL,
  `sekretaris` text DEFAULT NULL,
  `bendahara` text DEFAULT NULL,
  `wakil_ketua` text DEFAULT NULL,
  `ketua` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota_komisis`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `anggota_komisis` WRITE;
/*!40000 ALTER TABLE `anggota_komisis` DISABLE KEYS */;
/*!40000 ALTER TABLE `anggota_komisis` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `anggota_pelayanan`
--

DROP TABLE IF EXISTS `anggota_pelayanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota_pelayanan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `anggota_id` int(11) unsigned NOT NULL,
  `pelayanan_id` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anggota_id` (`anggota_id`),
  CONSTRAINT `1` FOREIGN KEY (`anggota_id`) REFERENCES `anggotas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota_pelayanan`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `anggota_pelayanan` WRITE;
/*!40000 ALTER TABLE `anggota_pelayanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `anggota_pelayanan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `anggotas`
--

DROP TABLE IF EXISTS `anggotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggotas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jemaat_ids` text DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` text DEFAULT NULL,
  `waktu_hadir` text DEFAULT NULL,
  `status_kehadiran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_absen` text DEFAULT NULL,
  `dicatat_oleh` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jam_mulai` text DEFAULT NULL,
  `jam_selesai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `columns` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `statistik_bulan_ini` text DEFAULT NULL,
  `kehadiran_terakhir` text DEFAULT NULL,
  `jadwal_ibadah_bulan_ini` text DEFAULT NULL,
  `aktivitas_pelayanan_terbaru` text DEFAULT NULL,
  `peringkat` text DEFAULT NULL,
  `hadir` text DEFAULT NULL,
  `data_pelayanan` text DEFAULT NULL,
  `nama` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `telepon` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `jenis_kelamin` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `status_anggota` text DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `max_size` text DEFAULT NULL,
  `is_image` text DEFAULT NULL,
  `mime_in` text DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `anggota_pelayanan` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `filter` text DEFAULT NULL,
  `tanggal_cetak` date DEFAULT NULL,
  `success` text DEFAULT NULL,
  `pelayanan` text DEFAULT NULL,
  `stats` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requests` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `pending` text DEFAULT NULL,
  `approved` text DEFAULT NULL,
  `rejected` text DEFAULT NULL,
  `revised` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `approved_at` text DEFAULT NULL,
  `approval_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rejected_by` text DEFAULT NULL,
  `rejected_at` text DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `revised_by` text DEFAULT NULL,
  `revised_at` text DEFAULT NULL,
  `revision_deadline` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `approval_status` text DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` text DEFAULT NULL,
  `super_admin` text DEFAULT NULL,
  `admin` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `notifications` text DEFAULT NULL,
  `sistem` text DEFAULT NULL,
  `reminder` text DEFAULT NULL,
  `unread` text DEFAULT NULL,
  `read` text DEFAULT NULL,
  `archived` text DEFAULT NULL,
  `all` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `low` text DEFAULT NULL,
  `normal` text DEFAULT NULL,
  `high` text DEFAULT NULL,
  `urgent` text DEFAULT NULL,
  `users` text DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `prioritas` text DEFAULT NULL,
  `expired_at` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `action_text` text DEFAULT NULL,
  `action_url` text DEFAULT NULL,
  `notifikasi_id` int(11) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `unread_count` text DEFAULT NULL,
  `jam` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `deadline` text DEFAULT NULL,
  `jadwal_kirim` text DEFAULT NULL,
  `reminder_title` text DEFAULT NULL,
  `reminder_message` text DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `reminder_time` text DEFAULT NULL,
  `target_type` text DEFAULT NULL,
  `reminder_datetime` date DEFAULT NULL,
  `location` text DEFAULT NULL,
  `sent_at` text DEFAULT NULL,
  `emergency_title` text DEFAULT NULL,
  `emergency_message` text DEFAULT NULL,
  `emergency_type` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `channels` text DEFAULT NULL,
  `enable_email` text DEFAULT NULL,
  `enable_push` text DEFAULT NULL,
  `quiet_hours_start` text DEFAULT NULL,
  `quiet_hours_end` text DEFAULT NULL,
  `notification_types` text DEFAULT NULL,
  `target_tipe` text DEFAULT NULL,
  `target_roles` text DEFAULT NULL,
  `target_user_ids` text DEFAULT NULL,
  `target_anggota_ids` text DEFAULT NULL,
  `target_pelayanan_ids` text DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `specific_user_ids` text DEFAULT NULL,
  `reminder_location` text DEFAULT NULL,
  `reminder_link` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggotas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `anggotas` WRITE;
/*!40000 ALTER TABLE `anggotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `anggotas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `approvals`
--

DROP TABLE IF EXISTS `approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `approvals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `stats` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requests` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `pending` text DEFAULT NULL,
  `approved` text DEFAULT NULL,
  `rejected` text DEFAULT NULL,
  `revised` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `approved_at` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approval_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rejected_by` text DEFAULT NULL,
  `rejected_at` text DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `revised_by` text DEFAULT NULL,
  `revised_at` text DEFAULT NULL,
  `revision_deadline` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `approval_status` text DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` text DEFAULT NULL,
  `super_admin` text DEFAULT NULL,
  `admin` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvals`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `approvals` WRITE;
/*!40000 ALTER TABLE `approvals` DISABLE KEYS */;
/*!40000 ALTER TABLE `approvals` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `calendars`
--

DROP TABLE IF EXISTS `calendars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `month` text DEFAULT NULL,
  `year` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `event` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` text DEFAULT NULL,
  `end_time` text DEFAULT NULL,
  `contact_phone` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `events` text DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `service` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `meeting` text DEFAULT NULL,
  `social` text DEFAULT NULL,
  `success` text DEFAULT NULL,
  `count` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendars`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `calendars` WRITE;
/*!40000 ALTER TABLE `calendars` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendars` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ibadahs`
--

DROP TABLE IF EXISTS `ibadahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ibadahs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jemaat_ids` text DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` text DEFAULT NULL,
  `waktu_hadir` text DEFAULT NULL,
  `status_kehadiran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_absen` text DEFAULT NULL,
  `dicatat_oleh` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jam_mulai` text DEFAULT NULL,
  `jam_selesai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `columns` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `statistik_bulan_ini` text DEFAULT NULL,
  `kehadiran_terakhir` text DEFAULT NULL,
  `jadwal_ibadah_bulan_ini` text DEFAULT NULL,
  `aktivitas_pelayanan_terbaru` text DEFAULT NULL,
  `peringkat` text DEFAULT NULL,
  `hadir` text DEFAULT NULL,
  `stats` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requests` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `pending` text DEFAULT NULL,
  `approved` text DEFAULT NULL,
  `rejected` text DEFAULT NULL,
  `revised` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `approved_at` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approval_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rejected_by` text DEFAULT NULL,
  `rejected_at` text DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `revised_by` text DEFAULT NULL,
  `revised_at` text DEFAULT NULL,
  `revision_deadline` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `approval_status` text DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` text DEFAULT NULL,
  `super_admin` text DEFAULT NULL,
  `admin` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `notifications` text DEFAULT NULL,
  `sistem` text DEFAULT NULL,
  `reminder` text DEFAULT NULL,
  `unread` text DEFAULT NULL,
  `read` text DEFAULT NULL,
  `archived` text DEFAULT NULL,
  `all` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `pelayanan` text DEFAULT NULL,
  `low` text DEFAULT NULL,
  `normal` text DEFAULT NULL,
  `high` text DEFAULT NULL,
  `urgent` text DEFAULT NULL,
  `users` text DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `prioritas` text DEFAULT NULL,
  `expired_at` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `action_text` text DEFAULT NULL,
  `action_url` text DEFAULT NULL,
  `notifikasi_id` int(11) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `unread_count` text DEFAULT NULL,
  `jam` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `deadline` text DEFAULT NULL,
  `jadwal_kirim` text DEFAULT NULL,
  `reminder_title` text DEFAULT NULL,
  `reminder_message` text DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `reminder_time` text DEFAULT NULL,
  `target_type` text DEFAULT NULL,
  `reminder_datetime` date DEFAULT NULL,
  `location` text DEFAULT NULL,
  `sent_at` text DEFAULT NULL,
  `emergency_title` text DEFAULT NULL,
  `emergency_message` text DEFAULT NULL,
  `emergency_type` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `channels` text DEFAULT NULL,
  `enable_email` text DEFAULT NULL,
  `enable_push` text DEFAULT NULL,
  `quiet_hours_start` text DEFAULT NULL,
  `quiet_hours_end` text DEFAULT NULL,
  `notification_types` text DEFAULT NULL,
  `target_tipe` text DEFAULT NULL,
  `target_roles` text DEFAULT NULL,
  `target_user_ids` text DEFAULT NULL,
  `target_anggota_ids` text DEFAULT NULL,
  `target_pelayanan_ids` text DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `specific_user_ids` text DEFAULT NULL,
  `reminder_location` text DEFAULT NULL,
  `reminder_link` text DEFAULT NULL,
  `pengeluaran` text DEFAULT NULL,
  `total_pengeluaran` text DEFAULT NULL,
  `total_pengeluaran_bulan_ini` text DEFAULT NULL,
  `pengeluaran_per_program` text DEFAULT NULL,
  `user_level` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `metode_pembayaran` text DEFAULT NULL,
  `bukti_pengeluaran` text DEFAULT NULL,
  `program_kerja_id` int(11) DEFAULT NULL,
  `keterangan_tambahan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `diupdate_oleh` date DEFAULT NULL,
  `pengeluaran_bulanan` text DEFAULT NULL,
  `pengeluaran_tahunan` text DEFAULT NULL,
  `pengeluaran_per_kategori` text DEFAULT NULL,
  `total_pengeluaran_bulanan` text DEFAULT NULL,
  `total_pengeluaran_tahunan` text DEFAULT NULL,
  `selected_month` text DEFAULT NULL,
  `selected_year` text DEFAULT NULL,
  `selected_kategori` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ibadahs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ibadahs` WRITE;
/*!40000 ALTER TABLE `ibadahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ibadahs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `inventaris`
--

DROP TABLE IF EXISTS `inventaris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventaris` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `kategori_list` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  `kategori_filter` text DEFAULT NULL,
  `status_filter` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `lokasi_list` text DEFAULT NULL,
  `user_list` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `nama_barang` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `merk` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `no_seri` text DEFAULT NULL,
  `tahun_pembelian` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `kondisi` text DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `sumber_dana` text DEFAULT NULL,
  `harga_beli` text DEFAULT NULL,
  `nilai_residu` text DEFAULT NULL,
  `masa_manfaat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `foto_barang` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `riwayat_pemeliharaan` text DEFAULT NULL,
  `riwayat_peminjaman` text DEFAULT NULL,
  `pengguna_list` text DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `inventaris_id` int(11) DEFAULT NULL,
  `status_sebelum` text DEFAULT NULL,
  `status_sesudah` text DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `peminjaman` text DEFAULT NULL,
  `inventaris_tersedia` text DEFAULT NULL,
  `peminjam_list` text DEFAULT NULL,
  `peminjam_id` int(11) DEFAULT NULL,
  `jumlah_pinjam` text DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `jaminan` text DEFAULT NULL,
  `kode_peminjaman` text DEFAULT NULL,
  `status_peminjaman` text DEFAULT NULL,
  `kondisi_kembali` text DEFAULT NULL,
  `keterangan_pengembalian` text DEFAULT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `pemeliharaan` text DEFAULT NULL,
  `inventaris_list` text DEFAULT NULL,
  `teknisi_list` text DEFAULT NULL,
  `jenis_pemeliharaan` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `biaya` text DEFAULT NULL,
  `status_pemeliharaan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaris`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `inventaris` WRITE;
/*!40000 ALTER TABLE `inventaris` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventaris` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jadwal_ibadah`
--

DROP TABLE IF EXISTS `jadwal_ibadah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_ibadah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_ibadah` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_ibadah`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jadwal_ibadah` WRITE;
/*!40000 ALTER TABLE `jadwal_ibadah` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwal_ibadah` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jadwal_ibadahs`
--

DROP TABLE IF EXISTS `jadwal_ibadahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_ibadahs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `metadata` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `limit` text DEFAULT NULL,
  `page` text DEFAULT NULL,
  `total_pages` text DEFAULT NULL,
  `tanggal_server` date DEFAULT NULL,
  `update_otomatis` date DEFAULT NULL,
  `pelayan` text DEFAULT NULL,
  `info` text DEFAULT NULL,
  `hari` text DEFAULT NULL,
  `waktu_display` text DEFAULT NULL,
  `status_display` text DEFAULT NULL,
  `status_badge` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` text DEFAULT NULL,
  `nama_ibadah` text DEFAULT NULL,
  `jenis_ibadah` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `pemimpin_ibadah` text DEFAULT NULL,
  `pemusik` text DEFAULT NULL,
  `pemandu_pujian` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `tema` text DEFAULT NULL,
  `bacaan_alkitab` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jadwal` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `nama_bulan` text DEFAULT NULL,
  `total_hari_ibadah` text DEFAULT NULL,
  `total_ibadah` text DEFAULT NULL,
  `selamat` text DEFAULT NULL,
  `jenis_data` text DEFAULT NULL,
  `jenis_url` text DEFAULT NULL,
  `breadcrumb` text DEFAULT NULL,
  `kembali_url` text DEFAULT NULL,
  `edit_url` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `can_edit` text DEFAULT NULL,
  `can_delete` text DEFAULT NULL,
  `aksi_hapus` text DEFAULT NULL,
  `url_kembali` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `data_sebelum` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `alasan_hapus` text DEFAULT NULL,
  `petugas` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `id_ibadah` text DEFAULT NULL,
  `id_jemaat` text DEFAULT NULL,
  `peran` text DEFAULT NULL,
  `status_konfirmasi` text DEFAULT NULL,
  `id_jadwal_petugas` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `total_pengeluaran_bulan_ini` text DEFAULT NULL,
  `saldo_akhir` text DEFAULT NULL,
  `transaksi_terbaru` text DEFAULT NULL,
  `kategori_pemasukan` text DEFAULT NULL,
  `kategori_pengeluaran` text DEFAULT NULL,
  `transaksi` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `jenis` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pengeluaran` text DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `jadwal_ibadah` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `jenis_transaksi` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nominal` text DEFAULT NULL,
  `metode_pembayaran` text DEFAULT NULL,
  `dokumen_bukti` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `jadwal_ibadah_id` int(11) DEFAULT NULL,
  `program_kerja_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pemasukan` text DEFAULT NULL,
  `pengeluaran` text DEFAULT NULL,
  `modul` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_ibadahs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jadwal_ibadahs` WRITE;
/*!40000 ALTER TABLE `jadwal_ibadahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwal_ibadahs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jadwal_petugas_ibadahs`
--

DROP TABLE IF EXISTS `jadwal_petugas_ibadahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_petugas_ibadahs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `petugas` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `id_ibadah` text DEFAULT NULL,
  `id_jemaat` text DEFAULT NULL,
  `peran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_konfirmasi` text DEFAULT NULL,
  `id_jadwal_petugas` text DEFAULT NULL,
  `pemimpin_ibadah` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `pemandu_pujian` text DEFAULT NULL,
  `pemusik` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `jadwal` text DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_petugas_ibadahs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jadwal_petugas_ibadahs` WRITE;
/*!40000 ALTER TABLE `jadwal_petugas_ibadahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwal_petugas_ibadahs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jemaats`
--

DROP TABLE IF EXISTS `jemaats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jemaats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `petugas` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `id_ibadah` text DEFAULT NULL,
  `id_jemaat` text DEFAULT NULL,
  `peran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_konfirmasi` text DEFAULT NULL,
  `id_jadwal_petugas` text DEFAULT NULL,
  `pemimpin_ibadah` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `pemandu_pujian` text DEFAULT NULL,
  `pemusik` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `jadwal` text DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `total_jemaat` text DEFAULT NULL,
  `total_hadir` text DEFAULT NULL,
  `persentase` text DEFAULT NULL,
  `export_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status_jemaat` varchar(20) DEFAULT 'aktif',
  `status_aktif` int(1) DEFAULT 1,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jemaats`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jemaats` WRITE;
/*!40000 ALTER TABLE `jemaats` DISABLE KEYS */;
/*!40000 ALTER TABLE `jemaats` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jenis_kegiatans`
--

DROP TABLE IF EXISTS `jenis_kegiatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_kegiatans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan` text DEFAULT NULL,
  `jenis_kegiatan` text DEFAULT NULL,
  `jadwal` text DEFAULT NULL,
  `jenis` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `penanggung_jawab` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `events` text DEFAULT NULL,
  `nama_kegiatan` text DEFAULT NULL,
  `jenis_kegiatan_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu_mulai` text DEFAULT NULL,
  `waktu_selesai` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_kegiatans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jenis_kegiatans` WRITE;
/*!40000 ALTER TABLE `jenis_kegiatans` DISABLE KEYS */;
/*!40000 ALTER TABLE `jenis_kegiatans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jenis_pemasukans`
--

DROP TABLE IF EXISTS `jenis_pemasukans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_pemasukans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pemasukan` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `kegiatan_list` text DEFAULT NULL,
  `jenis_pemasukan_list` text DEFAULT NULL,
  `sumber_list` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `jenis_pemasukan_id` int(11) DEFAULT NULL,
  `sumber` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `bukti_pemasukan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `dibuat_pada` text DEFAULT NULL,
  `diperbarui_oleh` text DEFAULT NULL,
  `diperbarui_pada` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `total_per_jenis` text DEFAULT NULL,
  `total_per_kegiatan` text DEFAULT NULL,
  `total_keseluruhan` text DEFAULT NULL,
  `gereja_nama` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_pemasukans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jenis_pemasukans` WRITE;
/*!40000 ALTER TABLE `jenis_pemasukans` DISABLE KEYS */;
/*!40000 ALTER TABLE `jenis_pemasukans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `kategori_inventaris`
--

DROP TABLE IF EXISTS `kategori_inventaris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_inventaris` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventaris` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `kategori_list` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  `kategori_filter` text DEFAULT NULL,
  `status_filter` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `lokasi_list` text DEFAULT NULL,
  `user_list` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `nama_barang` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `merk` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `no_seri` text DEFAULT NULL,
  `tahun_pembelian` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `kondisi` text DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `sumber_dana` text DEFAULT NULL,
  `harga_beli` text DEFAULT NULL,
  `nilai_residu` text DEFAULT NULL,
  `masa_manfaat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `foto_barang` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `riwayat_pemeliharaan` text DEFAULT NULL,
  `riwayat_peminjaman` text DEFAULT NULL,
  `pengguna_list` text DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `inventaris_id` int(11) DEFAULT NULL,
  `status_sebelum` text DEFAULT NULL,
  `status_sesudah` text DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `peminjaman` text DEFAULT NULL,
  `inventaris_tersedia` text DEFAULT NULL,
  `peminjam_list` text DEFAULT NULL,
  `peminjam_id` int(11) DEFAULT NULL,
  `jumlah_pinjam` text DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `jaminan` text DEFAULT NULL,
  `kode_peminjaman` text DEFAULT NULL,
  `status_peminjaman` text DEFAULT NULL,
  `kondisi_kembali` text DEFAULT NULL,
  `keterangan_pengembalian` text DEFAULT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `pemeliharaan` text DEFAULT NULL,
  `inventaris_list` text DEFAULT NULL,
  `teknisi_list` text DEFAULT NULL,
  `jenis_pemeliharaan` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `biaya` text DEFAULT NULL,
  `status_pemeliharaan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_inventaris`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `kategori_inventaris` WRITE;
/*!40000 ALTER TABLE `kategori_inventaris` DISABLE KEYS */;
/*!40000 ALTER TABLE `kategori_inventaris` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `kegiatan_pelayanans`
--

DROP TABLE IF EXISTS `kegiatan_pelayanans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kegiatan_pelayanans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_data` text DEFAULT NULL,
  `jenis_url` text DEFAULT NULL,
  `breadcrumb` text DEFAULT NULL,
  `kembali_url` text DEFAULT NULL,
  `edit_url` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `can_edit` text DEFAULT NULL,
  `can_delete` text DEFAULT NULL,
  `aksi_hapus` text DEFAULT NULL,
  `url_kembali` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `data_sebelum` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `alasan_hapus` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatan_pelayanans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `kegiatan_pelayanans` WRITE;
/*!40000 ALTER TABLE `kegiatan_pelayanans` DISABLE KEYS */;
/*!40000 ALTER TABLE `kegiatan_pelayanans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `kegiatans`
--

DROP TABLE IF EXISTS `kegiatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kegiatans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jemaat_ids` text DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` text DEFAULT NULL,
  `waktu_hadir` text DEFAULT NULL,
  `status_kehadiran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_absen` text DEFAULT NULL,
  `dicatat_oleh` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jam_mulai` text DEFAULT NULL,
  `jam_selesai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `columns` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `statistik_bulan_ini` text DEFAULT NULL,
  `kehadiran_terakhir` text DEFAULT NULL,
  `jadwal_ibadah_bulan_ini` text DEFAULT NULL,
  `aktivitas_pelayanan_terbaru` text DEFAULT NULL,
  `peringkat` text DEFAULT NULL,
  `hadir` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `tahun_anggaran` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `nama_anggaran` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `kode_anggaran` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `total_anggaran` text DEFAULT NULL,
  `tanggal_cetak` date DEFAULT NULL,
  `total_anggaran_tahun_ini` text DEFAULT NULL,
  `anggaran_disetujui` text DEFAULT NULL,
  `anggaran_realisasi` text DEFAULT NULL,
  `anggaran_per_program` text DEFAULT NULL,
  `anggaran_per_periode` text DEFAULT NULL,
  `error` text DEFAULT NULL,
  `rencana` text DEFAULT NULL,
  `diajukan` text DEFAULT NULL,
  `disetujui` text DEFAULT NULL,
  `ditolak` text DEFAULT NULL,
  `realisasi` text DEFAULT NULL,
  `jenis_kegiatan` text DEFAULT NULL,
  `jadwal` text DEFAULT NULL,
  `jenis` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `penanggung_jawab` text DEFAULT NULL,
  `events` text DEFAULT NULL,
  `nama_kegiatan` text DEFAULT NULL,
  `jenis_kegiatan_id` int(11) DEFAULT NULL,
  `waktu_mulai` text DEFAULT NULL,
  `waktu_selesai` text DEFAULT NULL,
  `total_jemaat` text DEFAULT NULL,
  `total_hadir` text DEFAULT NULL,
  `persentase` text DEFAULT NULL,
  `export_date` date DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `pelayan` text DEFAULT NULL,
  `nama_proker` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `penanggung_jawab_id` int(11) DEFAULT NULL,
  `success` text DEFAULT NULL,
  `total_kegiatan` text DEFAULT NULL,
  `kegiatan_by_kategori` text DEFAULT NULL,
  `kegiatan_by_status` text DEFAULT NULL,
  `kegiatan_terbaru` text DEFAULT NULL,
  `komisi` text DEFAULT NULL,
  `nama_komisi` text DEFAULT NULL,
  `tugas_pokok` text DEFAULT NULL,
  `warna` text DEFAULT NULL,
  `valid_color` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `sekretaris_id` int(11) DEFAULT NULL,
  `bendahara_id` int(11) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `users` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `numeric` text DEFAULT NULL,
  `jabatan` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `komisi_id` int(11) DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `total` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `staff` text DEFAULT NULL,
  `sekretaris` text DEFAULT NULL,
  `bendahara` text DEFAULT NULL,
  `wakil_ketua` text DEFAULT NULL,
  `ketua` text DEFAULT NULL,
  `pemasukan` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `kegiatan_list` text DEFAULT NULL,
  `jenis_pemasukan_list` text DEFAULT NULL,
  `sumber_list` text DEFAULT NULL,
  `jenis_pemasukan_id` int(11) DEFAULT NULL,
  `sumber` text DEFAULT NULL,
  `bukti_pemasukan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `dibuat_pada` text DEFAULT NULL,
  `diperbarui_oleh` text DEFAULT NULL,
  `diperbarui_pada` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_per_jenis` text DEFAULT NULL,
  `total_per_kegiatan` text DEFAULT NULL,
  `total_keseluruhan` text DEFAULT NULL,
  `gereja_nama` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `kegiatans` WRITE;
/*!40000 ALTER TABLE `kegiatans` DISABLE KEYS */;
/*!40000 ALTER TABLE `kegiatans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `kehadirans`
--

DROP TABLE IF EXISTS `kehadirans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadirans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `total_jemaat` text DEFAULT NULL,
  `total_hadir` text DEFAULT NULL,
  `persentase` text DEFAULT NULL,
  `export_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kehadirans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `kehadirans` WRITE;
/*!40000 ALTER TABLE `kehadirans` DISABLE KEYS */;
/*!40000 ALTER TABLE `kehadirans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `keuangans`
--

DROP TABLE IF EXISTS `keuangans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `total_pengeluaran_bulan_ini` text DEFAULT NULL,
  `saldo_akhir` text DEFAULT NULL,
  `transaksi_terbaru` text DEFAULT NULL,
  `kategori_pemasukan` text DEFAULT NULL,
  `kategori_pengeluaran` text DEFAULT NULL,
  `transaksi` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `jenis` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pengeluaran` text DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `jadwal_ibadah` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `jenis_transaksi` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nominal` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_pembayaran` text DEFAULT NULL,
  `dokumen_bukti` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `jadwal_ibadah_id` int(11) DEFAULT NULL,
  `program_kerja_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pemasukan` text DEFAULT NULL,
  `pengeluaran` text DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `modul` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keuangans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `keuangans` WRITE;
/*!40000 ALTER TABLE `keuangans` DISABLE KEYS */;
/*!40000 ALTER TABLE `keuangans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `komisi`
--

DROP TABLE IF EXISTS `komisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `komisi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `komisi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `komisi` WRITE;
/*!40000 ALTER TABLE `komisi` DISABLE KEYS */;
/*!40000 ALTER TABLE `komisi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `komisis`
--

DROP TABLE IF EXISTS `komisis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `komisis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pagination` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `limit` text DEFAULT NULL,
  `page` text DEFAULT NULL,
  `total_pages` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `total_program` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `id_komisi` text DEFAULT NULL,
  `nama_proker` text DEFAULT NULL,
  `tujuan` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `penanggung_jawab` text DEFAULT NULL,
  `kontak_pj` text DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `dokumentasi` text DEFAULT NULL,
  `statistik` text DEFAULT NULL,
  `total_anggaran` text DEFAULT NULL,
  `rata_anggaran` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `total_seluruh_anggaran` text DEFAULT NULL,
  `total_seluruh_program` text DEFAULT NULL,
  `rata_seluruh_anggaran` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `komisi` text DEFAULT NULL,
  `perencanaan` text DEFAULT NULL,
  `berjalan` text DEFAULT NULL,
  `selesai` text DEFAULT NULL,
  `dibatalkan` text DEFAULT NULL,
  `nama_komisi` text DEFAULT NULL,
  `tugas_pokok` text DEFAULT NULL,
  `warna` text DEFAULT NULL,
  `valid_color` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `sekretaris_id` int(11) DEFAULT NULL,
  `bendahara_id` int(11) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `users` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `numeric` text DEFAULT NULL,
  `jabatan` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `komisi_id` int(11) DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `success` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `staff` text DEFAULT NULL,
  `sekretaris` text DEFAULT NULL,
  `bendahara` text DEFAULT NULL,
  `wakil_ketua` text DEFAULT NULL,
  `ketua` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `komisis`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `komisis` WRITE;
/*!40000 ALTER TABLE `komisis` DISABLE KEYS */;
/*!40000 ALTER TABLE `komisis` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `log_activitys`
--

DROP TABLE IF EXISTS `log_activitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_activitys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `nama_lengkap` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `jabatan_gereja` text DEFAULT NULL,
  `logged_in` text DEFAULT NULL,
  `last_login` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `telepon` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `activation_code` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `activated_at` text DEFAULT NULL,
  `reset_token` text DEFAULT NULL,
  `reset_expires` text DEFAULT NULL,
  `token` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user` text DEFAULT NULL,
  `current_password` text DEFAULT NULL,
  `new_password` text DEFAULT NULL,
  `confirm_password` text DEFAULT NULL,
  `remember_token` text DEFAULT NULL,
  `remember_expires` text DEFAULT NULL,
  `tipe_aktivitas` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `nama` text DEFAULT NULL,
  `remember` text DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `breadcrumbs` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `activity_type` text DEFAULT NULL,
  `module` text DEFAULT NULL,
  `total_today` text DEFAULT NULL,
  `total_users` text DEFAULT NULL,
  `top_activities` text DEFAULT NULL,
  `log` text DEFAULT NULL,
  `related_logs` text DEFAULT NULL,
  `success` text DEFAULT NULL,
  `meta` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `page` text DEFAULT NULL,
  `per_page` text DEFAULT NULL,
  `total_pages` text DEFAULT NULL,
  `create` text DEFAULT NULL,
  `update` date DEFAULT NULL,
  `delete` text DEFAULT NULL,
  `login` text DEFAULT NULL,
  `logout` text DEFAULT NULL,
  `view` text DEFAULT NULL,
  `export` text DEFAULT NULL,
  `approve` text DEFAULT NULL,
  `reject` text DEFAULT NULL,
  `download` text DEFAULT NULL,
  `upload` text DEFAULT NULL,
  `logs` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_activitys`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `log_activitys` WRITE;
/*!40000 ALTER TABLE `log_activitys` DISABLE KEYS */;
INSERT INTO `log_activitys` VALUES
(1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:16:06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN_GAGAL','Password salah','::1','Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:16:14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN_GAGAL','Password salah','::1','Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:16:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN','Login berhasil','::1','Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:17:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN','Login berhasil','::1','Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:17:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN','Login berhasil','::1','Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(6,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:18:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN','Login berhasil','::1','Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(7,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-30 11:22:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LOGIN','Login berhasil','::1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `log_activitys` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_aktivitas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_data` text DEFAULT NULL,
  `aksi_hapus` text DEFAULT NULL,
  `url_kembali` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `data_sebelum` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `alasan_hapus` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `total_pengeluaran_bulan_ini` text DEFAULT NULL,
  `saldo_akhir` text DEFAULT NULL,
  `transaksi_terbaru` text DEFAULT NULL,
  `kategori_pemasukan` text DEFAULT NULL,
  `kategori_pengeluaran` text DEFAULT NULL,
  `transaksi` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `jenis` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pengeluaran` text DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `jadwal_ibadah` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `jenis_transaksi` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nominal` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_pembayaran` text DEFAULT NULL,
  `dokumen_bukti` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `jadwal_ibadah_id` int(11) DEFAULT NULL,
  `program_kerja_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pemasukan` text DEFAULT NULL,
  `pengeluaran` text DEFAULT NULL,
  `modul` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `kegiatan_list` text DEFAULT NULL,
  `jenis_pemasukan_list` text DEFAULT NULL,
  `sumber_list` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `jenis_pemasukan_id` int(11) DEFAULT NULL,
  `sumber` text DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `bukti_pemasukan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `dibuat_pada` text DEFAULT NULL,
  `diperbarui_oleh` text DEFAULT NULL,
  `diperbarui_pada` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `total_per_jenis` text DEFAULT NULL,
  `total_per_kegiatan` text DEFAULT NULL,
  `total_keseluruhan` text DEFAULT NULL,
  `gereja_nama` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lokasis`
--

DROP TABLE IF EXISTS `lokasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lokasis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventaris` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `kategori_list` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  `kategori_filter` text DEFAULT NULL,
  `status_filter` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `lokasi_list` text DEFAULT NULL,
  `user_list` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `nama_barang` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `merk` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `no_seri` text DEFAULT NULL,
  `tahun_pembelian` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `kondisi` text DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `sumber_dana` text DEFAULT NULL,
  `harga_beli` text DEFAULT NULL,
  `nilai_residu` text DEFAULT NULL,
  `masa_manfaat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `foto_barang` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `riwayat_pemeliharaan` text DEFAULT NULL,
  `riwayat_peminjaman` text DEFAULT NULL,
  `pengguna_list` text DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `inventaris_id` int(11) DEFAULT NULL,
  `status_sebelum` text DEFAULT NULL,
  `status_sesudah` text DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `peminjaman` text DEFAULT NULL,
  `inventaris_tersedia` text DEFAULT NULL,
  `peminjam_list` text DEFAULT NULL,
  `peminjam_id` int(11) DEFAULT NULL,
  `jumlah_pinjam` text DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `jaminan` text DEFAULT NULL,
  `kode_peminjaman` text DEFAULT NULL,
  `status_peminjaman` text DEFAULT NULL,
  `kondisi_kembali` text DEFAULT NULL,
  `keterangan_pengembalian` text DEFAULT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `pemeliharaan` text DEFAULT NULL,
  `inventaris_list` text DEFAULT NULL,
  `teknisi_list` text DEFAULT NULL,
  `jenis_pemeliharaan` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `biaya` text DEFAULT NULL,
  `status_pemeliharaan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lokasis`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lokasis` WRITE;
/*!40000 ALTER TABLE `lokasis` DISABLE KEYS */;
/*!40000 ALTER TABLE `lokasis` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'20260401090000','App\\Database\\Migrations\\CreateGerejaSchema','default','App',1776724746,1),
(2,'20260420223533','App\\Database\\Migrations\\CreateAktivitasJemaatTable','default','App',1776724746,1),
(39,'20260420223534','App\\Database\\Migrations\\CreateAnggotaTable','default','App',1776725309,2),
(40,'20260420223535','App\\Database\\Migrations\\CreateIbadahTable','default','App',1776725309,2),
(41,'20260420223536','App\\Database\\Migrations\\CreateKegiatanTable','default','App',1776725309,2),
(42,'20260420223537','App\\Database\\Migrations\\CreatePelayananTable','default','App',1776725309,2),
(43,'20260420223538','App\\Database\\Migrations\\CreateWilayahTable','default','App',1776725309,2),
(44,'20260420223539','App\\Database\\Migrations\\CreateAnggaranTable','default','App',1776725309,2),
(45,'20260420223540','App\\Database\\Migrations\\CreateProgramKerjaTable','default','App',1776725309,2),
(46,'20260420223541','App\\Database\\Migrations\\CreateUserTable','default','App',1776725309,2),
(47,'20260420223542','App\\Database\\Migrations\\CreateJadwalIbadahTable','default','App',1776725309,2),
(48,'20260420223543','App\\Database\\Migrations\\CreatePelayanTable','default','App',1776725309,2),
(49,'20260420223544','App\\Database\\Migrations\\CreateProkerTable','default','App',1776725309,2),
(50,'20260420223545','App\\Database\\Migrations\\CreateKomisiTable','default','App',1776725309,2),
(51,'20260420223546','App\\Database\\Migrations\\CreateApprovalTable','default','App',1776725309,2),
(52,'20260420223547','App\\Database\\Migrations\\CreateNotificationTable','default','App',1776725309,2),
(53,'20260420223548','App\\Database\\Migrations\\CreateLogActivityTable','default','App',1776725309,2),
(54,'20260420223549','App\\Database\\Migrations\\CreateCalendarTable','default','App',1776725309,2),
(55,'20260420223550','App\\Database\\Migrations\\CreateServiceTable','default','App',1776725309,2),
(56,'20260420223551','App\\Database\\Migrations\\CreateProgramTable','default','App',1776725309,2),
(57,'20260420223552','App\\Database\\Migrations\\CreateTukarMimbarTable','default','App',1776725309,2),
(58,'20260420223553','App\\Database\\Migrations\\CreateKegiatanPelayananTable','default','App',1776725309,2),
(59,'20260420223554','App\\Database\\Migrations\\CreateLogAktivitasTable','default','App',1776725309,2),
(60,'20260420223555','App\\Database\\Migrations\\CreateInventarisTable','default','App',1776725309,2),
(61,'20260420223556','App\\Database\\Migrations\\CreateKategoriInventarisTable','default','App',1776725309,2),
(62,'20260420223557','App\\Database\\Migrations\\CreateLokasiTable','default','App',1776725309,2),
(63,'20260420223558','App\\Database\\Migrations\\CreateJadwalPetugasIbadahTable','default','App',1776725309,2),
(64,'20260420223559','App\\Database\\Migrations\\CreateJemaatTable','default','App',1776725309,2),
(65,'20260420223600','App\\Database\\Migrations\\CreateJenisKegiatanTable','default','App',1776725309,2),
(66,'20260420223601','App\\Database\\Migrations\\CreateKehadiranTable','default','App',1776725309,2),
(67,'20260420223602','App\\Database\\Migrations\\CreateKeuanganTable','default','App',1776725309,2),
(68,'20260420223603','App\\Database\\Migrations\\CreateAnggotaKomisiTable','default','App',1776725309,2),
(69,'20260420223604','App\\Database\\Migrations\\CreateNotifikasiTable','default','App',1776725309,2),
(70,'20260420223605','App\\Database\\Migrations\\CreatePasswordHistoryTable','default','App',1776725309,2),
(71,'20260420223606','App\\Database\\Migrations\\CreatePasswordResetTable','default','App',1776725309,2),
(72,'20260420223607','App\\Database\\Migrations\\CreatePemasukanTable','default','App',1776725309,2),
(73,'20260420223608','App\\Database\\Migrations\\CreateJenisPemasukanTable','default','App',1776725309,2),
(74,'20260420223609','App\\Database\\Migrations\\CreatePengeluaranTable','default','App',1776725309,2),
(75,'2026-04-21-005334','App\\Database\\Migrations\\AddStatusToJemaat','default','App',1776732827,3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `stats` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requests` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `pending` text DEFAULT NULL,
  `approved` text DEFAULT NULL,
  `rejected` text DEFAULT NULL,
  `revised` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `approved_at` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approval_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rejected_by` text DEFAULT NULL,
  `rejected_at` text DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `revised_by` text DEFAULT NULL,
  `revised_at` text DEFAULT NULL,
  `revision_deadline` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `approval_status` text DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` text DEFAULT NULL,
  `super_admin` text DEFAULT NULL,
  `admin` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notifikasis`
--

DROP TABLE IF EXISTS `notifikasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notifications` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `sistem` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `reminder` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `unread` text DEFAULT NULL,
  `read` text DEFAULT NULL,
  `archived` text DEFAULT NULL,
  `all` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `pelayanan` text DEFAULT NULL,
  `low` text DEFAULT NULL,
  `normal` text DEFAULT NULL,
  `high` text DEFAULT NULL,
  `urgent` text DEFAULT NULL,
  `users` text DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `prioritas` text DEFAULT NULL,
  `expired_at` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `action_text` text DEFAULT NULL,
  `action_url` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `notifikasi_id` int(11) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `unread_count` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `deadline` text DEFAULT NULL,
  `jadwal_kirim` text DEFAULT NULL,
  `reminder_title` text DEFAULT NULL,
  `reminder_message` text DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `reminder_time` text DEFAULT NULL,
  `target_type` text DEFAULT NULL,
  `reminder_datetime` date DEFAULT NULL,
  `location` text DEFAULT NULL,
  `sent_at` text DEFAULT NULL,
  `emergency_title` text DEFAULT NULL,
  `emergency_message` text DEFAULT NULL,
  `emergency_type` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `channels` text DEFAULT NULL,
  `enable_email` text DEFAULT NULL,
  `enable_push` text DEFAULT NULL,
  `quiet_hours_start` text DEFAULT NULL,
  `quiet_hours_end` text DEFAULT NULL,
  `notification_types` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `target_tipe` text DEFAULT NULL,
  `target_roles` text DEFAULT NULL,
  `target_user_ids` text DEFAULT NULL,
  `target_anggota_ids` text DEFAULT NULL,
  `target_pelayanan_ids` text DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `specific_user_ids` text DEFAULT NULL,
  `reminder_location` text DEFAULT NULL,
  `reminder_link` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasis`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifikasis` WRITE;
/*!40000 ALTER TABLE `notifikasis` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifikasis` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_historys`
--

DROP TABLE IF EXISTS `password_historys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_historys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `current_password` text DEFAULT NULL,
  `new_password` text DEFAULT NULL,
  `confirm_password` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `password_changed_at` text DEFAULT NULL,
  `password_expires_at` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `password_hash` text DEFAULT NULL,
  `changed_at` text DEFAULT NULL,
  `changed_by` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `valid` text DEFAULT NULL,
  `score` text DEFAULT NULL,
  `strength` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `force_password_change` text DEFAULT NULL,
  `is_forced_change` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `to` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `time` text DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `platform` text DEFAULT NULL,
  `expired` text DEFAULT NULL,
  `days_remaining` text DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `force_change` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_historys`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_historys` WRITE;
/*!40000 ALTER TABLE `password_historys` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_historys` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `current_password` text DEFAULT NULL,
  `new_password` text DEFAULT NULL,
  `confirm_password` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `password_changed_at` text DEFAULT NULL,
  `password_expires_at` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `password_hash` text DEFAULT NULL,
  `changed_at` text DEFAULT NULL,
  `changed_by` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `valid` text DEFAULT NULL,
  `score` text DEFAULT NULL,
  `strength` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `force_password_change` text DEFAULT NULL,
  `is_forced_change` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `to` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `time` text DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `platform` text DEFAULT NULL,
  `expired` text DEFAULT NULL,
  `days_remaining` text DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `force_change` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pelayanans`
--

DROP TABLE IF EXISTS `pelayanans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelayanans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jemaat_ids` text DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` text DEFAULT NULL,
  `waktu_hadir` text DEFAULT NULL,
  `status_kehadiran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_absen` text DEFAULT NULL,
  `dicatat_oleh` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jam_mulai` text DEFAULT NULL,
  `jam_selesai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `columns` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `statistik_bulan_ini` text DEFAULT NULL,
  `kehadiran_terakhir` text DEFAULT NULL,
  `jadwal_ibadah_bulan_ini` text DEFAULT NULL,
  `aktivitas_pelayanan_terbaru` text DEFAULT NULL,
  `peringkat` text DEFAULT NULL,
  `hadir` text DEFAULT NULL,
  `data_pelayanan` text DEFAULT NULL,
  `nama` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `telepon` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `jenis_kelamin` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `status_anggota` text DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `max_size` text DEFAULT NULL,
  `is_image` text DEFAULT NULL,
  `mime_in` text DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `anggota_pelayanan` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `filter` text DEFAULT NULL,
  `tanggal_cetak` date DEFAULT NULL,
  `success` text DEFAULT NULL,
  `pelayanan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelayanans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pelayanans` WRITE;
/*!40000 ALTER TABLE `pelayanans` DISABLE KEYS */;
/*!40000 ALTER TABLE `pelayanans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pelayans`
--

DROP TABLE IF EXISTS `pelayans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelayans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `metadata` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `limit` text DEFAULT NULL,
  `page` text DEFAULT NULL,
  `total_pages` text DEFAULT NULL,
  `tanggal_server` date DEFAULT NULL,
  `update_otomatis` date DEFAULT NULL,
  `pelayan` text DEFAULT NULL,
  `info` text DEFAULT NULL,
  `hari` text DEFAULT NULL,
  `waktu_display` text DEFAULT NULL,
  `status_display` text DEFAULT NULL,
  `status_badge` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` text DEFAULT NULL,
  `nama_ibadah` text DEFAULT NULL,
  `jenis_ibadah` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `pemimpin_ibadah` text DEFAULT NULL,
  `pemusik` text DEFAULT NULL,
  `pemandu_pujian` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `tema` text DEFAULT NULL,
  `bacaan_alkitab` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jadwal` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `nama_bulan` text DEFAULT NULL,
  `total_hari_ibadah` text DEFAULT NULL,
  `total_ibadah` text DEFAULT NULL,
  `selamat` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelayans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pelayans` WRITE;
/*!40000 ALTER TABLE `pelayans` DISABLE KEYS */;
/*!40000 ALTER TABLE `pelayans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pemasukans`
--

DROP TABLE IF EXISTS `pemasukans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pemasukans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pemasukan` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `kegiatan_list` text DEFAULT NULL,
  `jenis_pemasukan_list` text DEFAULT NULL,
  `sumber_list` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `jenis_pemasukan_id` int(11) DEFAULT NULL,
  `sumber` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `bukti_pemasukan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `dibuat_pada` text DEFAULT NULL,
  `diperbarui_oleh` text DEFAULT NULL,
  `diperbarui_pada` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `total_per_jenis` text DEFAULT NULL,
  `total_per_kegiatan` text DEFAULT NULL,
  `total_keseluruhan` text DEFAULT NULL,
  `gereja_nama` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemasukans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pemasukans` WRITE;
/*!40000 ALTER TABLE `pemasukans` DISABLE KEYS */;
/*!40000 ALTER TABLE `pemasukans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pengeluarans`
--

DROP TABLE IF EXISTS `pengeluarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengeluarans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pengeluaran` text DEFAULT NULL,
  `total_pengeluaran` text DEFAULT NULL,
  `total_pengeluaran_bulan_ini` text DEFAULT NULL,
  `pengeluaran_per_program` text DEFAULT NULL,
  `user_level` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `metode_pembayaran` text DEFAULT NULL,
  `bukti_pengeluaran` text DEFAULT NULL,
  `program_kerja_id` int(11) DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `keterangan_tambahan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `diupdate_oleh` date DEFAULT NULL,
  `pengeluaran_bulanan` text DEFAULT NULL,
  `pengeluaran_tahunan` text DEFAULT NULL,
  `pengeluaran_per_kategori` text DEFAULT NULL,
  `total_pengeluaran_bulanan` text DEFAULT NULL,
  `total_pengeluaran_tahunan` text DEFAULT NULL,
  `selected_month` text DEFAULT NULL,
  `selected_year` text DEFAULT NULL,
  `selected_kategori` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengeluarans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pengeluarans` WRITE;
/*!40000 ALTER TABLE `pengeluarans` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengeluarans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `program_kerja`
--

DROP TABLE IF EXISTS `program_kerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_kerja` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `komisi_id` int(11) unsigned NOT NULL,
  `nama_program` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('perencanaan','proses','selesai') NOT NULL DEFAULT 'perencanaan',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `program_kerja_komisi_id_foreign` (`komisi_id`),
  CONSTRAINT `program_kerja_komisi_id_foreign` FOREIGN KEY (`komisi_id`) REFERENCES `komisi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_kerja`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `program_kerja` WRITE;
/*!40000 ALTER TABLE `program_kerja` DISABLE KEYS */;
/*!40000 ALTER TABLE `program_kerja` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `program_kerjas`
--

DROP TABLE IF EXISTS `program_kerjas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_kerjas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `anggaran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `tahun_anggaran` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `nama_anggaran` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kode_anggaran` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `total_anggaran` text DEFAULT NULL,
  `tanggal_cetak` date DEFAULT NULL,
  `total_anggaran_tahun_ini` text DEFAULT NULL,
  `anggaran_disetujui` text DEFAULT NULL,
  `anggaran_realisasi` text DEFAULT NULL,
  `anggaran_per_program` text DEFAULT NULL,
  `anggaran_per_periode` text DEFAULT NULL,
  `error` text DEFAULT NULL,
  `rencana` text DEFAULT NULL,
  `diajukan` text DEFAULT NULL,
  `disetujui` text DEFAULT NULL,
  `ditolak` text DEFAULT NULL,
  `realisasi` text DEFAULT NULL,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `stats` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requests` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `pending` text DEFAULT NULL,
  `approved` text DEFAULT NULL,
  `rejected` text DEFAULT NULL,
  `revised` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `approved_at` text DEFAULT NULL,
  `approval_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rejected_by` text DEFAULT NULL,
  `rejected_at` text DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `revised_by` text DEFAULT NULL,
  `revised_at` text DEFAULT NULL,
  `revision_deadline` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `approval_status` text DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` text DEFAULT NULL,
  `super_admin` text DEFAULT NULL,
  `admin` text DEFAULT NULL,
  `penatua` text DEFAULT NULL,
  `diaken` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `total_pemasukan_bulan_ini` text DEFAULT NULL,
  `total_pengeluaran_bulan_ini` text DEFAULT NULL,
  `saldo_akhir` text DEFAULT NULL,
  `transaksi_terbaru` text DEFAULT NULL,
  `kategori_pemasukan` text DEFAULT NULL,
  `kategori_pengeluaran` text DEFAULT NULL,
  `transaksi` text DEFAULT NULL,
  `jenis` text DEFAULT NULL,
  `bulan` text DEFAULT NULL,
  `total_pemasukan` text DEFAULT NULL,
  `total_pengeluaran` text DEFAULT NULL,
  `kategori` text DEFAULT NULL,
  `jadwal_ibadah` text DEFAULT NULL,
  `jenis_transaksi` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nominal` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `metode_pembayaran` text DEFAULT NULL,
  `dokumen_bukti` text DEFAULT NULL,
  `jadwal_ibadah_id` int(11) DEFAULT NULL,
  `program_kerja_id` int(11) DEFAULT NULL,
  `pemasukan` text DEFAULT NULL,
  `pengeluaran` text DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `modul` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `komisi` text DEFAULT NULL,
  `nama_komisi` text DEFAULT NULL,
  `tugas_pokok` text DEFAULT NULL,
  `warna` text DEFAULT NULL,
  `valid_color` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `sekretaris_id` int(11) DEFAULT NULL,
  `bendahara_id` int(11) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `users` text DEFAULT NULL,
  `numeric` text DEFAULT NULL,
  `jabatan` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `komisi_id` int(11) DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `success` text DEFAULT NULL,
  `staff` text DEFAULT NULL,
  `sekretaris` text DEFAULT NULL,
  `bendahara` text DEFAULT NULL,
  `wakil_ketua` text DEFAULT NULL,
  `ketua` text DEFAULT NULL,
  `notifications` text DEFAULT NULL,
  `sistem` text DEFAULT NULL,
  `reminder` text DEFAULT NULL,
  `unread` text DEFAULT NULL,
  `read` text DEFAULT NULL,
  `archived` text DEFAULT NULL,
  `all` text DEFAULT NULL,
  `pelayanan` text DEFAULT NULL,
  `low` text DEFAULT NULL,
  `normal` text DEFAULT NULL,
  `high` text DEFAULT NULL,
  `urgent` text DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `prioritas` text DEFAULT NULL,
  `expired_at` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `action_text` text DEFAULT NULL,
  `action_url` text DEFAULT NULL,
  `notifikasi_id` int(11) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `unread_count` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jam` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `deadline` text DEFAULT NULL,
  `jadwal_kirim` text DEFAULT NULL,
  `reminder_title` text DEFAULT NULL,
  `reminder_message` text DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `reminder_time` text DEFAULT NULL,
  `target_type` text DEFAULT NULL,
  `reminder_datetime` date DEFAULT NULL,
  `location` text DEFAULT NULL,
  `sent_at` text DEFAULT NULL,
  `emergency_title` text DEFAULT NULL,
  `emergency_message` text DEFAULT NULL,
  `emergency_type` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `channels` text DEFAULT NULL,
  `enable_email` text DEFAULT NULL,
  `enable_push` text DEFAULT NULL,
  `quiet_hours_start` text DEFAULT NULL,
  `quiet_hours_end` text DEFAULT NULL,
  `notification_types` text DEFAULT NULL,
  `target_tipe` text DEFAULT NULL,
  `target_roles` text DEFAULT NULL,
  `target_user_ids` text DEFAULT NULL,
  `target_anggota_ids` text DEFAULT NULL,
  `target_pelayanan_ids` text DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `specific_user_ids` text DEFAULT NULL,
  `reminder_location` text DEFAULT NULL,
  `reminder_link` text DEFAULT NULL,
  `pengeluaran_per_program` text DEFAULT NULL,
  `user_level` text DEFAULT NULL,
  `bukti_pengeluaran` text DEFAULT NULL,
  `keterangan_tambahan` text DEFAULT NULL,
  `dibuat_oleh` text DEFAULT NULL,
  `diupdate_oleh` date DEFAULT NULL,
  `pengeluaran_bulanan` text DEFAULT NULL,
  `pengeluaran_tahunan` text DEFAULT NULL,
  `pengeluaran_per_kategori` text DEFAULT NULL,
  `total_pengeluaran_bulanan` text DEFAULT NULL,
  `total_pengeluaran_tahunan` text DEFAULT NULL,
  `selected_month` text DEFAULT NULL,
  `selected_year` text DEFAULT NULL,
  `selected_kategori` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_kerjas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `program_kerjas` WRITE;
/*!40000 ALTER TABLE `program_kerjas` DISABLE KEYS */;
/*!40000 ALTER TABLE `program_kerjas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `month` text DEFAULT NULL,
  `year` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `event` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` text DEFAULT NULL,
  `end_time` text DEFAULT NULL,
  `contact_phone` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `events` text DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `service` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `meeting` text DEFAULT NULL,
  `social` text DEFAULT NULL,
  `success` text DEFAULT NULL,
  `count` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `prokers`
--

DROP TABLE IF EXISTS `prokers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prokers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pagination` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `limit` text DEFAULT NULL,
  `page` text DEFAULT NULL,
  `total_pages` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `total_program` text DEFAULT NULL,
  `tahun` text DEFAULT NULL,
  `id_komisi` text DEFAULT NULL,
  `nama_proker` text DEFAULT NULL,
  `tujuan` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `penanggung_jawab` text DEFAULT NULL,
  `kontak_pj` text DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `anggaran` text DEFAULT NULL,
  `dokumentasi` text DEFAULT NULL,
  `statistik` text DEFAULT NULL,
  `total_anggaran` text DEFAULT NULL,
  `rata_anggaran` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `total_seluruh_anggaran` text DEFAULT NULL,
  `total_seluruh_program` text DEFAULT NULL,
  `rata_seluruh_anggaran` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `komisi` text DEFAULT NULL,
  `perencanaan` text DEFAULT NULL,
  `berjalan` text DEFAULT NULL,
  `selesai` text DEFAULT NULL,
  `dibatalkan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prokers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `prokers` WRITE;
/*!40000 ALTER TABLE `prokers` DISABLE KEYS */;
/*!40000 ALTER TABLE `prokers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `month` text DEFAULT NULL,
  `year` text DEFAULT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `event` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` text DEFAULT NULL,
  `end_time` text DEFAULT NULL,
  `contact_phone` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `events` text DEFAULT NULL,
  `borders` text DEFAULT NULL,
  `service` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `meeting` text DEFAULT NULL,
  `social` text DEFAULT NULL,
  `success` text DEFAULT NULL,
  `count` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tukar_mimbars`
--

DROP TABLE IF EXISTS `tukar_mimbars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tukar_mimbars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_data` text DEFAULT NULL,
  `jenis_url` text DEFAULT NULL,
  `breadcrumb` text DEFAULT NULL,
  `kembali_url` text DEFAULT NULL,
  `edit_url` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `can_edit` text DEFAULT NULL,
  `can_delete` text DEFAULT NULL,
  `aksi_hapus` text DEFAULT NULL,
  `url_kembali` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `data_sebelum` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `alasan_hapus` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tukar_mimbars`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tukar_mimbars` WRITE;
/*!40000 ALTER TABLE `tukar_mimbars` DISABLE KEYS */;
/*!40000 ALTER TABLE `tukar_mimbars` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `confirm_password` text DEFAULT NULL,
  `nomor_telepon` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` text DEFAULT NULL,
  `is_active` text DEFAULT NULL,
  `user` text DEFAULT NULL,
  `current_password` text DEFAULT NULL,
  `reset_link` text DEFAULT NULL,
  `new_password` text DEFAULT NULL,
  `exists` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` text DEFAULT NULL,
  `jabatan_gereja` text DEFAULT NULL,
  `logged_in` text DEFAULT NULL,
  `last_login` text DEFAULT NULL,
  `telepon` text DEFAULT NULL,
  `activation_code` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `activated_at` text DEFAULT NULL,
  `reset_token` text DEFAULT NULL,
  `reset_expires` text DEFAULT NULL,
  `token` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_token` text DEFAULT NULL,
  `remember_expires` text DEFAULT NULL,
  `tipe_aktivitas` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `nama` text DEFAULT NULL,
  `remember` text DEFAULT NULL,
  `inventaris` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `kategori_list` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  `kategori_filter` text DEFAULT NULL,
  `status_filter` text DEFAULT NULL,
  `user_role` text DEFAULT NULL,
  `lokasi_list` text DEFAULT NULL,
  `user_list` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `nama_barang` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `merk` text DEFAULT NULL,
  `tipe` text DEFAULT NULL,
  `no_seri` text DEFAULT NULL,
  `tahun_pembelian` text DEFAULT NULL,
  `jumlah` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `kondisi` text DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `sumber_dana` text DEFAULT NULL,
  `harga_beli` text DEFAULT NULL,
  `nilai_residu` text DEFAULT NULL,
  `masa_manfaat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `foto_barang` text DEFAULT NULL,
  `created_by` text DEFAULT NULL,
  `riwayat_pemeliharaan` text DEFAULT NULL,
  `riwayat_peminjaman` text DEFAULT NULL,
  `pengguna_list` text DEFAULT NULL,
  `updated_by` date DEFAULT NULL,
  `inventaris_id` int(11) DEFAULT NULL,
  `status_sebelum` text DEFAULT NULL,
  `status_sesudah` text DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `peminjaman` text DEFAULT NULL,
  `inventaris_tersedia` text DEFAULT NULL,
  `peminjam_list` text DEFAULT NULL,
  `peminjam_id` int(11) DEFAULT NULL,
  `jumlah_pinjam` text DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `jaminan` text DEFAULT NULL,
  `kode_peminjaman` text DEFAULT NULL,
  `status_peminjaman` text DEFAULT NULL,
  `kondisi_kembali` text DEFAULT NULL,
  `keterangan_pengembalian` text DEFAULT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `pemeliharaan` text DEFAULT NULL,
  `inventaris_list` text DEFAULT NULL,
  `teknisi_list` text DEFAULT NULL,
  `jenis_pemeliharaan` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `biaya` text DEFAULT NULL,
  `status_pemeliharaan` text DEFAULT NULL,
  `komisi` text DEFAULT NULL,
  `nama_komisi` text DEFAULT NULL,
  `tugas_pokok` text DEFAULT NULL,
  `warna` text DEFAULT NULL,
  `valid_color` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `sekretaris_id` int(11) DEFAULT NULL,
  `bendahara_id` int(11) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `users` text DEFAULT NULL,
  `numeric` text DEFAULT NULL,
  `jabatan` text DEFAULT NULL,
  `in_list` text DEFAULT NULL,
  `komisi_id` int(11) DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `success` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `koordinator` text DEFAULT NULL,
  `staff` text DEFAULT NULL,
  `sekretaris` text DEFAULT NULL,
  `bendahara` text DEFAULT NULL,
  `wakil_ketua` text DEFAULT NULL,
  `ketua` text DEFAULT NULL,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notifications` text DEFAULT NULL,
  `filters` text DEFAULT NULL,
  `types` text DEFAULT NULL,
  `sistem` text DEFAULT NULL,
  `ibadah` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `reminder` text DEFAULT NULL,
  `approval` text DEFAULT NULL,
  `lainnya` text DEFAULT NULL,
  `statuses` text DEFAULT NULL,
  `unread` text DEFAULT NULL,
  `read` text DEFAULT NULL,
  `archived` text DEFAULT NULL,
  `all` text DEFAULT NULL,
  `pelayanan` text DEFAULT NULL,
  `low` text DEFAULT NULL,
  `normal` text DEFAULT NULL,
  `high` text DEFAULT NULL,
  `urgent` text DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `prioritas` text DEFAULT NULL,
  `expired_at` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `action_text` text DEFAULT NULL,
  `action_url` text DEFAULT NULL,
  `notifikasi_id` int(11) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `unread_count` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `deadline` text DEFAULT NULL,
  `jadwal_kirim` text DEFAULT NULL,
  `reminder_title` text DEFAULT NULL,
  `reminder_message` text DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `reminder_time` text DEFAULT NULL,
  `target_type` text DEFAULT NULL,
  `reminder_datetime` date DEFAULT NULL,
  `location` text DEFAULT NULL,
  `sent_at` text DEFAULT NULL,
  `emergency_title` text DEFAULT NULL,
  `emergency_message` text DEFAULT NULL,
  `emergency_type` text DEFAULT NULL,
  `contact_person` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `channels` text DEFAULT NULL,
  `enable_email` text DEFAULT NULL,
  `enable_push` text DEFAULT NULL,
  `quiet_hours_start` text DEFAULT NULL,
  `quiet_hours_end` text DEFAULT NULL,
  `notification_types` text DEFAULT NULL,
  `target_tipe` text DEFAULT NULL,
  `target_roles` text DEFAULT NULL,
  `target_user_ids` text DEFAULT NULL,
  `target_anggota_ids` text DEFAULT NULL,
  `target_pelayanan_ids` text DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `specific_user_ids` text DEFAULT NULL,
  `reminder_location` text DEFAULT NULL,
  `reminder_link` text DEFAULT NULL,
  `password_changed_at` text DEFAULT NULL,
  `password_expires_at` text DEFAULT NULL,
  `password_hash` text DEFAULT NULL,
  `changed_at` text DEFAULT NULL,
  `changed_by` text DEFAULT NULL,
  `valid` text DEFAULT NULL,
  `score` text DEFAULT NULL,
  `strength` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `force_password_change` text DEFAULT NULL,
  `is_forced_change` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `to` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `time` text DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `platform` text DEFAULT NULL,
  `expired` text DEFAULT NULL,
  `days_remaining` text DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `force_change` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Administrator Sistem','admin@gmail.com','$2y$12$nrW8bdykfRd2pmCvBh2vd.v.y1sP4NWPcN1sWNgfEn9kgROSa3JyG',NULL,NULL,'admin','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'admin','IT Support',NULL,'2026-04-30 11:16:29',NULL,NULL,'2026-04-20 22:48:34',NULL,NULL,NULL,NULL,'2026-04-30 11:16:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,'Pdt. Yohanes Setiawan','pendeta@gmail.com','$2y$12$zt4QnpzBiPSw2QorrORxsuZ.V3NSQwfLltuZ7HEmmNhjKoWUTfeMi',NULL,NULL,'pendeta','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pendeta','Pendeta Utama',NULL,'2026-04-30 11:17:13',NULL,NULL,'2026-04-20 22:48:34',NULL,NULL,NULL,NULL,'2026-04-30 11:17:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,'Budi Santoso','pengurus@gmail.com','$2y$12$T2gy8i6Fp/xBnhdIxjbHCOLBv6Soaprtye.y8bkeyZ/6Z7JHUYARy',NULL,NULL,'pengurus','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pengurus','Sekretaris Jemaat',NULL,'2026-04-30 11:17:40',NULL,NULL,'2026-04-20 22:48:34',NULL,NULL,NULL,NULL,'2026-04-30 11:17:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,'Agnes Monica','jemaat@gmail.com','$2y$12$f5eZCpl80UXQbvwUATKk5eIgqWix6HE5TVgD/rrXMSqJwgmMimp7m',NULL,NULL,'jemaat','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'jemaat','Anggota Jemaat',NULL,'2026-04-30 11:22:18',NULL,NULL,'2026-04-20 22:48:35',NULL,NULL,NULL,NULL,'2026-04-30 11:22:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wilayahs`
--

DROP TABLE IF EXISTS `wilayahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wilayahs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breadcrumb` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `kehadiran` text DEFAULT NULL,
  `pager` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jemaat` text DEFAULT NULL,
  `ibadah_id` int(11) DEFAULT NULL,
  `jemaat_ids` text DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` text DEFAULT NULL,
  `waktu_hadir` text DEFAULT NULL,
  `status_kehadiran` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode_absen` text DEFAULT NULL,
  `dicatat_oleh` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `pelayanan_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jam_mulai` text DEFAULT NULL,
  `jam_selesai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `anggota` text DEFAULT NULL,
  `laporan` text DEFAULT NULL,
  `columns` text DEFAULT NULL,
  `periode` text DEFAULT NULL,
  `statistik_bulan_ini` text DEFAULT NULL,
  `kehadiran_terakhir` text DEFAULT NULL,
  `jadwal_ibadah_bulan_ini` text DEFAULT NULL,
  `aktivitas_pelayanan_terbaru` text DEFAULT NULL,
  `peringkat` text DEFAULT NULL,
  `hadir` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wilayahs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wilayahs` WRITE;
/*!40000 ALTER TABLE `wilayahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `wilayahs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'gereja'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-04-30 18:24:56
