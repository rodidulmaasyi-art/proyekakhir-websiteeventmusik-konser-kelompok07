-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 21, 2026 at 06:42 AM
-- Server version: 8.0.45
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `future_music_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id_artist` int NOT NULL,
  `artist_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spotify` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id_artist`, `artist_name`, `genre`, `bio`, `image_url`, `instagram`, `youtube`, `spotify`, `created_at`) VALUES
(1, 'Nova Aria', 'EDM / Future Bass', 'DJ dengan karakter sound futuristic dan visual laser performance.', 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=900&q=80', '@novaaria', 'NovaAriaLive', 'Nova Aria', '2026-06-21 06:41:32'),
(2, 'Synthra', 'Synthwave', 'Artist synthwave dengan nuansa retro cyber dan vokal cinematic.', 'https://images.unsplash.com/photo-1521337581100-8ca9a73a5f79?auto=format&fit=crop&w=900&q=80', '@synthra', 'SynthraOfficial', 'Synthra', '2026-06-21 06:41:32'),
(3, 'DJ Orbit', 'Techno', 'Producer techno dengan set gelap, cepat, dan immersive.', 'https://images.unsplash.com/photo-1511735111819-9a3f7709049c?auto=format&fit=crop&w=900&q=80', '@djorbit', 'DJOrbit', 'DJ Orbit', '2026-06-21 06:41:32'),
(4, 'Luna Voltage', 'Pop Electronic', 'Penyanyi pop electronic dengan stage act holographic.', 'https://images.unsplash.com/photo-1487180144351-b8472da7d491?auto=format&fit=crop&w=900&q=80', '@lunavoltage', 'LunaVoltage', 'Luna Voltage', '2026-06-21 06:41:32'),
(5, 'Bass Vector', 'Bass Music', 'Duo bass music dengan drop intens dan visual AI generated.', 'https://images.unsplash.com/photo-1508973379184-7517410fb0bc?auto=format&fit=crop&w=900&q=80', '@bassvector', 'BassVector', 'Bass Vector', '2026-06-21 06:41:32'),
(6, 'Echo Prime', 'Indie Electronic', 'Band indie electronic dengan aransemen atmosferik dan eksperimental.', 'https://images.unsplash.com/photo-1521336575822-6da63fb45455?auto=format&fit=crop&w=900&q=80', '@echoprime', 'EchoPrime', 'Echo Prime', '2026-06-21 06:41:32'),
(7, 'Mira Neon', 'K-Pop Dance', 'Performer dance-pop dengan koreografi neon dan energy tinggi.', 'https://images.unsplash.com/photo-1541532713592-79a0317b6b77?auto=format&fit=crop&w=900&q=80', '@miraneon', 'MiraNeon', 'Mira Neon', '2026-06-21 06:41:32'),
(8, 'Pulse Theory', 'Electronic Jazz', 'Grup electronic jazz dengan improvisasi live synth.', 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&w=900&q=80', '@pulsetheory', 'PulseTheory', 'Pulse Theory', '2026-06-21 06:41:32'),
(9, 'Astra Beat', 'House', 'DJ house dengan groove festival internasional.', 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=900&q=80', '@astrabeat', 'AstraBeat', 'Astra Beat', '2026-06-21 06:41:32'),
(10, 'Cyber Choir', 'Choral Electronic', 'Kolektif vokal elektronik dengan ambience cinematic.', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=900&q=80', '@cyberchoir', 'CyberChoir', 'Cyber Choir', '2026-06-21 06:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id_event` int NOT NULL,
  `id_venue` int NOT NULL,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('upcoming','popular','featured','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upcoming',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat_total` int NOT NULL DEFAULT '1000',
  `seat_available` int NOT NULL DEFAULT '1000',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id_event`, `id_venue`, `title`, `slug`, `description`, `event_date`, `start_time`, `category`, `status`, `image_url`, `video_url`, `seat_total`, `seat_available`, `created_at`) VALUES
(1, 1, 'Future Music Experience 2026', 'future-music-experience-2026', 'Festival musik futuristik dengan AI stage, hologram artist, dan digital ticketing.', '2026-08-05', '18:00:00', 'Festival', 'featured', 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1400&q=80', 'https://cdn.coverr.co/videos/coverr-concert-crowd-6235/1080p.mp4', 14000, 9120, '2026-06-21 06:41:32'),
(2, 2, 'Neon Pulse Night', 'neon-pulse-night', 'Malam EDM cyber-modern dengan light show electric pink dan neon purple.', '2026-07-03', '19:30:00', 'EDM', 'popular', 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=1400&q=80', '', 9000, 5275, '2026-06-21 06:41:32'),
(3, 3, 'AI Synthwave Summit', 'ai-synthwave-summit', 'Konser synthwave dengan rekomendasi lagu berbasis AI dan visual generatif.', '2026-07-13', '20:00:00', 'Synthwave', 'upcoming', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1400&q=80', '', 12000, 7800, '2026-06-21 06:41:32'),
(4, 4, 'Galaxy Bass Carnival', 'galaxy-bass-carnival', 'Bass music, techno, dan stage interaktif berbasis sensor gerak.', '2026-08-23', '17:00:00', 'Bass', 'upcoming', 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=80', '', 7000, 3610, '2026-06-21 06:41:32'),
(5, 5, 'Hologram Pop Fest', 'hologram-pop-fest', 'Pop futuristik dengan hologram performance dan AR crowd experience.', '2026-07-22', '18:30:00', 'Pop', 'popular', 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1400&q=80', '', 15000, 11350, '2026-06-21 06:41:32'),
(6, 6, 'Quantum Jazz Electronic', 'quantum-jazz-electronic', 'Fusion jazz dan electronic dengan panggung immersive 360.', '2026-08-14', '19:00:00', 'Electronic Jazz', 'upcoming', 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=1400&q=80', '', 8000, 4820, '2026-06-21 06:41:32'),
(7, 7, 'Cyber K-Pop Wave', 'cyber-kpop-wave', 'K-Pop dance festival dengan fan zone digital dan QR access.', '2026-06-29', '18:00:00', 'K-Pop', 'featured', 'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&w=1400&q=80', '', 11000, 1420, '2026-06-21 06:41:32'),
(8, 8, 'Electric Indie Space', 'electric-indie-space', 'Indie futuristic showcase dengan visual galaksi dan membership lounge.', '2026-07-29', '17:30:00', 'Indie', 'upcoming', 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1400&q=80', '', 10000, 6400, '2026-06-21 06:41:32'),
(9, 9, 'Vortex Techno Arena', 'vortex-techno-arena', 'Techno rave dengan laser grid, live seat availability, dan smart pass.', '2026-08-30', '21:00:00', 'Techno', 'popular', 'https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=1400&q=80', '', 6500, 2890, '2026-06-21 06:41:32'),
(10, 10, 'Starlight Acoustic Future', 'starlight-acoustic-future', 'Acoustic night dengan sentuhan AI ambience dan visual bintang.', '2026-07-09', '19:00:00', 'Acoustic', 'upcoming', 'https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2?auto=format&fit=crop&w=1400&q=80', '', 13000, 8350, '2026-06-21 06:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id_payment` int NOT NULL,
  `id_purchase` int NOT NULL,
  `provider` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Midtrans Demo',
  `transaction_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','settlement','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id_payment`, `id_purchase`, `provider`, `transaction_code`, `amount`, `status`, `paid_at`) VALUES
(1, 1, 'Midtrans Demo', 'MID-0001', 700000.00, 'settlement', '2026-06-21 13:41:32'),
(2, 2, 'Midtrans Demo', 'MID-0002', 250000.00, 'settlement', '2026-06-21 13:41:32'),
(3, 3, 'Midtrans Demo', 'MID-0003', 560000.00, 'pending', NULL),
(4, 4, 'Midtrans Demo', 'MID-0004', 1500000.00, 'settlement', '2026-06-21 13:41:32'),
(5, 5, 'Midtrans Demo', 'MID-0005', 750000.00, 'settlement', '2026-06-21 13:41:32'),
(6, 6, 'Midtrans Demo', 'MID-0006', 2200000.00, 'pending', NULL),
(7, 7, 'Midtrans Demo', 'MID-0007', 2500000.00, 'settlement', '2026-06-21 13:41:32'),
(8, 8, 'Midtrans Demo', 'MID-0008', 1300000.00, 'settlement', '2026-06-21 13:41:32'),
(9, 9, 'Midtrans Demo', 'MID-0009', 600000.00, 'failed', NULL),
(10, 10, 'Midtrans Demo', 'MID-0010', 1500000.00, 'settlement', '2026-06-21 13:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id_purchase` int NOT NULL,
  `id_user` int NOT NULL,
  `id_event` int NOT NULL,
  `id_ticket` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `buyer_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `qr_code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id_purchase`, `id_user`, `id_event`, `id_ticket`, `quantity`, `total_price`, `buyer_name`, `buyer_email`, `status`, `qr_code`, `created_at`) VALUES
(1, 2, 1, 1, 2, 700000.00, 'Gibran Hendarto', 'user@futuremusic.test', 'paid', 'FMX-USER-0001', '2026-06-21 06:41:32'),
(2, 3, 2, 5, 1, 250000.00, 'Rara Neon', 'rara@test.com', 'paid', 'FMX-USER-0002', '2026-06-21 06:41:32'),
(3, 4, 3, 7, 2, 560000.00, 'Bimo Wave', 'bimo@test.com', 'pending', 'FMX-USER-0003', '2026-06-21 06:41:32'),
(4, 5, 7, 9, 1, 1500000.00, 'Sasha Pulse', 'sasha@test.com', 'paid', 'FMX-USER-0004', '2026-06-21 06:41:32'),
(5, 6, 1, 2, 1, 750000.00, 'Dion Orbit', 'dion@test.com', 'paid', 'FMX-USER-0005', '2026-06-21 06:41:32'),
(6, 7, 9, 10, 1, 2200000.00, 'Mira Echo', 'mira@test.com', 'pending', 'FMX-USER-0006', '2026-06-21 06:41:32'),
(7, 8, 1, 4, 1, 2500000.00, 'Naya Flux', 'naya@test.com', 'paid', 'FMX-USER-0007', '2026-06-21 06:41:32'),
(8, 9, 3, 8, 2, 1300000.00, 'Rio Synth', 'rio@test.com', 'paid', 'FMX-USER-0008', '2026-06-21 06:41:32'),
(9, 10, 2, 6, 1, 600000.00, 'Luna Bass', 'luna@test.com', 'cancelled', 'FMX-USER-0009', '2026-06-21 06:41:32'),
(10, 2, 7, 9, 1, 1500000.00, 'Gibran Hendarto', 'user@futuremusic.test', 'paid', 'FMX-USER-0010', '2026-06-21 06:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id_schedule` int NOT NULL,
  `id_event` int NOT NULL,
  `id_artist` int NOT NULL,
  `stage_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perform_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id_schedule`, `id_event`, `id_artist`, `stage_name`, `perform_date`, `start_time`, `end_time`) VALUES
(1, 1, 1, 'Main Neon Stage', '2026-08-05', '19:00:00', '20:00:00'),
(2, 1, 2, 'AI Dome', '2026-08-05', '20:15:00', '21:15:00'),
(3, 1, 5, 'Galaxy Stage', '2026-08-05', '21:30:00', '22:30:00'),
(4, 2, 1, 'Pulse Stage', '2026-07-03', '20:00:00', '21:00:00'),
(5, 3, 2, 'Synth Stage', '2026-07-13', '20:30:00', '21:30:00'),
(6, 4, 5, 'Bass Arena', '2026-08-23', '18:00:00', '19:30:00'),
(7, 5, 4, 'Holo Pop Stage', '2026-07-22', '19:30:00', '20:30:00'),
(8, 6, 8, 'Quantum Stage', '2026-08-14', '20:00:00', '21:15:00'),
(9, 7, 7, 'Cyber Wave Stage', '2026-06-29', '19:00:00', '20:30:00'),
(10, 9, 3, 'Vortex Stage', '2026-08-30', '22:00:00', '23:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_scans`
--

CREATE TABLE `ticket_scans` (
  `id_scan` int NOT NULL,
  `id_purchase` int NOT NULL,
  `scan_status` enum('valid','invalid','used') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `scanned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_scans`
--

INSERT INTO `ticket_scans` (`id_scan`, `id_purchase`, `scan_status`, `scanned_at`) VALUES
(1, 1, 'valid', '2026-06-21 06:41:32'),
(2, 2, 'valid', '2026-06-21 06:41:32'),
(3, 4, 'used', '2026-06-21 06:41:32'),
(4, 5, 'valid', '2026-06-21 06:41:32'),
(5, 7, 'valid', '2026-06-21 06:41:32'),
(6, 8, 'valid', '2026-06-21 06:41:32'),
(7, 10, 'valid', '2026-06-21 06:41:32'),
(8, 3, 'invalid', '2026-06-21 06:41:32'),
(9, 6, 'valid', '2026-06-21 06:41:32'),
(10, 9, 'invalid', '2026-06-21 06:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id_ticket` int NOT NULL,
  `id_event` int NOT NULL,
  `ticket_name` enum('Regular','VIP','VVIP','Backstage Pass') COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quota` int NOT NULL,
  `sold` int NOT NULL DEFAULT '0',
  `benefits` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id_ticket`, `id_event`, `ticket_name`, `price`, `quota`, `sold`, `benefits`) VALUES
(1, 1, 'Regular', 350000.00, 5000, 1200, 'Festival access, digital ticket, public zone'),
(2, 1, 'VIP', 750000.00, 2500, 880, 'Priority gate, VIP area, merchandise'),
(3, 1, 'VVIP', 1250000.00, 900, 310, 'Front stage, lounge access, exclusive merch'),
(4, 1, 'Backstage Pass', 2500000.00, 150, 60, 'Backstage access, meet artist, premium lounge'),
(5, 2, 'Regular', 250000.00, 3000, 900, 'Event access, digital ticket'),
(6, 2, 'VIP', 600000.00, 1000, 425, 'VIP area, priority entrance'),
(7, 3, 'Regular', 280000.00, 4500, 600, 'Event access, digital ticket'),
(8, 3, 'VIP', 650000.00, 1500, 320, 'VIP deck, premium view'),
(9, 7, 'VVIP', 1500000.00, 700, 560, 'VVIP lounge, premium view, fast lane'),
(10, 9, 'Backstage Pass', 2200000.00, 100, 40, 'Backstage access and artist meet');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `membership` enum('Free','Neon','Galaxy','Legend') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Free',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `name`, `email`, `password`, `role`, `membership`, `phone`, `created_at`) VALUES
(1, 'Admin Future', 'admin@futuremusic.test', '$2y$10$QBTphVAeHtwi2iNVHLFo3eQ6CJvEtpfaHgUiF0zQRxscg8Oo2qpte', 'admin', 'Legend', '081111111111', '2026-06-21 06:41:32'),
(2, 'Gibran Hendarto', 'user@futuremusic.test', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Galaxy', '082222222222', '2026-06-21 06:41:32'),
(3, 'Rara Neon', 'rara@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Neon', '083333333333', '2026-06-21 06:41:32'),
(4, 'Bimo Wave', 'bimo@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Free', '084444444444', '2026-06-21 06:41:32'),
(5, 'Sasha Pulse', 'sasha@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Neon', '085555555555', '2026-06-21 06:41:32'),
(6, 'Dion Orbit', 'dion@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Galaxy', '086666666666', '2026-06-21 06:41:32'),
(7, 'Mira Echo', 'mira@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Free', '087777777777', '2026-06-21 06:41:32'),
(8, 'Naya Flux', 'naya@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Legend', '088888888888', '2026-06-21 06:41:32'),
(9, 'Rio Synth', 'rio@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Free', '089999999999', '2026-06-21 06:41:32'),
(10, 'Luna Bass', 'luna@test.com', '$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay', 'user', 'Neon', '080000000000', '2026-06-21 06:41:32'),
(11, 'Admin_BARU', 'BARU@futuremusic.test', '123456', 'admin', 'Legend', '081111111111', '2026-06-21 06:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id_venue` int NOT NULL,
  `venue_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id_venue`, `venue_name`, `city`, `address`, `capacity`, `created_at`) VALUES
(1, 'Neon Dome Arena', 'Jakarta', 'Jl. Future Light No. 10, Jakarta', 14000, '2026-06-21 06:41:32'),
(2, 'Cyber Bay Stage', 'Bandung', 'Jl. Synthwave No. 22, Bandung', 9000, '2026-06-21 06:41:32'),
(3, 'Aurora Sky Park', 'Surabaya', 'Jl. Galaxy Selatan No. 7, Surabaya', 12000, '2026-06-21 06:41:32'),
(4, 'Quantum Hall', 'Yogyakarta', 'Jl. Orbit Musik No. 12, Yogyakarta', 7000, '2026-06-21 06:41:32'),
(5, 'Starlight Beach Club', 'Bali', 'Jl. Pantai Digital No. 18, Bali', 15000, '2026-06-21 06:41:32'),
(6, 'Pulse Convention Center', 'Semarang', 'Jl. Pulse Raya No. 9, Semarang', 8000, '2026-06-21 06:41:32'),
(7, 'Echo Stadium', 'Medan', 'Jl. Bassline No. 3, Medan', 11000, '2026-06-21 06:41:32'),
(8, 'Nova Expo Field', 'Makassar', 'Jl. Nova Timur No. 6, Makassar', 10000, '2026-06-21 06:41:32'),
(9, 'Vortex Hall', 'Malang', 'Jl. Neon Garden No. 4, Malang', 6500, '2026-06-21 06:41:32'),
(10, 'Laser Grid Square', 'Tangerang', 'Jl. Grid City No. 99, Tangerang', 13000, '2026-06-21 06:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id_wishlist` int NOT NULL,
  `id_user` int NOT NULL,
  `id_event` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id_wishlist`, `id_user`, `id_event`, `created_at`) VALUES
(1, 2, 1, '2026-06-21 06:41:32'),
(2, 2, 3, '2026-06-21 06:41:32'),
(3, 2, 7, '2026-06-21 06:41:32'),
(4, 3, 1, '2026-06-21 06:41:32'),
(5, 4, 2, '2026-06-21 06:41:32'),
(6, 5, 3, '2026-06-21 06:41:32'),
(7, 6, 4, '2026-06-21 06:41:32'),
(8, 7, 5, '2026-06-21 06:41:32'),
(9, 8, 6, '2026-06-21 06:41:32'),
(10, 9, 9, '2026-06-21 06:41:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id_artist`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id_event`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_events_venue` (`id_venue`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `fk_payment_purchase` (`id_purchase`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id_purchase`),
  ADD KEY `fk_purchase_user` (`id_user`),
  ADD KEY `fk_purchase_event` (`id_event`),
  ADD KEY `fk_purchase_ticket` (`id_ticket`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id_schedule`),
  ADD KEY `fk_schedule_event` (`id_event`),
  ADD KEY `fk_schedule_artist` (`id_artist`);

--
-- Indexes for table `ticket_scans`
--
ALTER TABLE `ticket_scans`
  ADD PRIMARY KEY (`id_scan`),
  ADD KEY `fk_scan_purchase` (`id_purchase`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `fk_ticket_event` (`id_event`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id_venue`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_wishlist`),
  ADD UNIQUE KEY `unique_user_event` (`id_user`,`id_event`),
  ADD KEY `fk_wishlist_event` (`id_event`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id_artist` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id_event` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id_purchase` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id_schedule` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ticket_scans`
--
ALTER TABLE `ticket_scans`
  MODIFY `id_scan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id_ticket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id_venue` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_wishlist` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_venue` FOREIGN KEY (`id_venue`) REFERENCES `venues` (`id_venue`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_purchase` FOREIGN KEY (`id_purchase`) REFERENCES `purchases` (`id_purchase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `fk_purchase_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `ticket_types` (`id_ticket`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_schedule_artist` FOREIGN KEY (`id_artist`) REFERENCES `artists` (`id_artist`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_schedule_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_scans`
--
ALTER TABLE `ticket_scans`
  ADD CONSTRAINT `fk_scan_purchase` FOREIGN KEY (`id_purchase`) REFERENCES `purchases` (`id_purchase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD CONSTRAINT `fk_ticket_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
