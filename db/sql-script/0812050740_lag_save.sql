-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 05, 2008 at 07:52 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6-2ubuntu4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `motiomera`
--

-- --------------------------------------------------------

--
-- Table structure for table `mm_lag_save`
--

CREATE TABLE IF NOT EXISTS `mm_lag_save` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `lag_id` int(10) unsigned NOT NULL,
  `bildUrl` varchar(255) collate utf8_swedish_ci NOT NULL,
  `namn` varchar(255) collate utf8_swedish_ci NOT NULL,
  `foretag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`,`lag_id`),
  KEY `foretag_id` (`foretag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=78 ;

--
-- Dumping data for table `mm_lag_save`
--

INSERT INTO `mm_lag_save` (`id`, `lag_id`, `bildUrl`, `namn`, `foretag_id`) VALUES
(1, 620, 'Lag_46.jpg', 'Tassande Katterna', 162),
(2, 619, 'Lag_21.jpg', 'Hoppande Grodorna', 162),
(3, 618, 'Lag_28.jpg', 'Listiga Rävarna', 162),
(4, 686, 'Lag_24.jpg', 'Joggande krokodilerna', 160),
(5, 523, 'Lag_55.jpg', 'Självsäkra lejonen', 157),
(6, 522, 'Lag_18.jpg', 'Grymtande svinen', 157),
(7, 521, 'Lag_10.jpg', 'Kloka ugglorna', 157),
(8, 515, 'Lag_4.jpg', 'Hurtiga vampyrerna', 154),
(9, 516, 'Lag_51.jpg', 'Tveksamma hundvalparna', 154),
(10, 519, 'Lag_17.jpg', 'Dansmössen', 156),
(11, 520, 'Lag_23.jpg', 'Frigjorda katterna', 156),
(12, 659, 'Lag_26.jpg', 'PåGång', 202),
(13, 715, 'Lag_1.jpg', 'Allgott', 202),
(14, 679, 'Lagbild_679.jpg', 'Farmerboys', 202),
(15, 676, 'Lag_52.jpg', 'ServiceGångarna', 202),
(16, 716, 'Lagbild_716.jpg', 'Inge Spring', 202),
(17, 661, 'Lag_23.jpg', 'Gått &amp; blandat', 202),
(18, 684, 'Lag_22.jpg', 'Lunkande kaninerna', 134),
(19, 672, 'Lag_27.jpg', 'Activerna', 119),
(20, 671, 'Lag_35.jpg', 'Comforterna', 119),
(21, 621, 'Lag_46.jpg', 'Elegancerna', 119),
(22, 685, 'Lag_17.jpg', 'Spatserande mössen', 128),
(23, 682, 'Lag_50.jpg', 'SSES Snabba step!', 171),
(24, 535, 'Lag_8.jpg', 'Kunniga koalorna', 169),
(25, 536, 'Lag_29.jpg', 'Godissugna lammen', 169),
(26, 545, 'Lag_19.jpg', 'Knatande björnarna', 172),
(27, 546, 'Lag_25.jpg', 'Modemedvetna ödlorna', 172),
(28, 547, 'Lag_33.jpg', 'Nafsande näbbdjuren', 172),
(29, 678, 'Lag_57.jpg', 'Hurtiga pingviner', 187),
(30, 580, 'Lag_17.jpg', 'Dansmössen', 187),
(31, 553, 'Lag_10.jpg', 'Kloka ugglorna', 174),
(32, 554, 'Lag_43.jpg', 'Eldiga ekorrarna', 174),
(33, 558, 'Lag_12.jpg', 'Stapplande chimpanserna', 177),
(34, 559, 'Lag_55.jpg', 'Självsäkra lejonen', 177),
(35, 570, 'Lag_58.jpg', 'Roliga rävungarna', 182),
(36, 574, 'Lag_18.jpg', 'Grymtande svinen', 184),
(37, 575, 'Lag_47.jpg', 'Färgglada pippifåglarna', 184),
(38, 576, 'Lag_60.jpg', 'Skrytiga sjöstjärnorna', 184),
(39, 578, 'Lag_50.jpg', 'Glupska hajarna', 186),
(40, 581, 'Lag_56.jpg', 'Styrketränande noshörningarna', 187),
(41, 583, 'Lag_39.jpg', 'Pratglada pelikanerna', 188),
(42, 584, 'Lag_25.jpg', 'Modemedvetna ödlorna', 189),
(43, 592, 'Lag_34.jpg', 'Lata lodjuren', 191),
(44, 593, 'Lag_45.jpg', 'Klampande sköldpaddorna', 191),
(45, 594, 'Lag_3.jpg', 'Glada flodhästarna', 192),
(46, 595, 'Lag_18.jpg', 'Grymtande svinen', 192),
(47, 645, 'Lag_38.jpg', 'The puffy purples (want to be piffiga)', 193),
(48, 597, 'Lag_18.jpg', 'Grymtande svinen', 194),
(49, 598, 'Lag_50.jpg', 'Glupska hajarna', 194),
(50, 608, 'Lag_43.jpg', 'Grymtarna i Gryet', 197),
(51, 646, 'Lagbild_646.jpg', 'Go Aller No Daller', 202),
(52, 667, 'Lag_22.jpg', 'KorsEttorna', 202),
(53, 656, 'Lag_58.jpg', 'Till-gångarna', 202),
(54, 673, 'Lag_14.jpg', 'Postgången', 202),
(55, 652, 'Lag_24.jpg', 'Snickarboa', 202),
(56, 650, 'Lagbild_650.jpg', 'Morgenspaziergang', 202),
(57, 654, 'Lag_16.jpg', 'Reportage', 202),
(58, 649, 'Lag_57.jpg', 'Fem på G', 202),
(59, 664, 'Lag_44.jpg', 'GÅret Runt', 202),
(60, 662, 'Lag_30.jpg', 'GåBra', 202),
(61, 658, 'Lag_18.jpg', 'Jaktjournalen', 202),
(62, 647, 'Lag_8.jpg', 'Sengångarna', 202),
(63, 648, 'Lag_61.jpg', 'Premie League', 202),
(64, 651, 'Lag_46.jpg', 'Lauringarna', 202),
(65, 670, 'Lagbild_670.jpg', 'P-nissarna', 202),
(66, 668, 'Lag_20.jpg', 'Green Feet', 202),
(67, 711, 'Lag_39.jpg', 'Team CHIC', 202),
(68, 665, 'Lag_34.jpg', 'Skoskav', 202),
(69, 657, 'Lagbild_657.jpg', 'Allas Go-Go girls', 202),
(70, 683, 'Lag_12.jpg', 'Annons', 202),
(71, 653, 'Lag_42.jpg', 'Gunnar Wiklund', 202),
(72, 669, 'Lag_49.jpg', 'Korrigåren', 202),
(73, 655, 'Lag_25.jpg', 'Benknäckarna', 202),
(74, 666, 'Lag_13.jpg', 'PS pg', 202),
(75, 660, 'Lagbild_660.jpg', 'Fjällvandrarna', 202),
(76, 663, 'Lagbild_663.jpg', 'Solian', 202),
(77, 617, 'Lag_24.jpg', 'Klåfingriga krokodilerna', 201);
