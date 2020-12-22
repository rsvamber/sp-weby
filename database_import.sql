-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2020 at 09:59 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `databaze`
--

-- --------------------------------------------------------

--
-- Table structure for table `clanky`
--

CREATE TABLE `clanky` (
  `id` int(11) NOT NULL,
  `nazev` tinytext COLLATE utf8mb4_czech_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `autor_id` int(11) NOT NULL,
  `obsah` text COLLATE utf8mb4_czech_ci NOT NULL,
  `authorized` enum('true','false') COLLATE utf8mb4_czech_ci NOT NULL DEFAULT 'false',
  `score` smallint(6) NOT NULL DEFAULT 0,
  `filename` text COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `clanky`
--

INSERT INTO `clanky` (`id`, `nazev`, `timestamp`, `autor_id`, `obsah`, `authorized`, `score`, `filename`) VALUES
(75, 'Toto je můj první článek!!', '2020-12-22 08:54:48', 26, '<p>V&iacute;t&aacute;m v&aacute;s u m&eacute;ho prvn&iacute;ho čl&aacute;nku.</p>\r\n\r\n<p>Zde m&aacute;te obr&aacute;zek&nbsp;m&eacute;ho obl&iacute;ben&eacute;ho telefonu&nbsp;<img alt=\"Nokia 3310\" src=\"https://www.sunnysoft.cz/obrazky/4/0/2/4021/134021/650x650.jpg\" style=\"height:150px; width:150px\" />.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>D&iacute;ky za pozornost.</p>\r\n\r\n<p>V př&iacute;loze m&aacute;te PDF</p>\r\n', 'true', 3, 'zadani.pdf'),
(78, 'Skryté hlášení', '2020-12-22 08:53:36', 1, '<p>Př&iacute;spěvky od &quot;Autor&quot; pros&iacute;m už neschvalovat, děkuji.</p>\r\n', 'false', 0, ''),
(79, 'Druhý článek', '2020-12-22 08:55:04', 26, '<p>Ahoj lidi, můj prvn&iacute; čl&aacute;nek tu měl velik&yacute; &uacute;spěch, tak přid&aacute;v&aacute;m dal&scaron;&iacute;.</p>\r\n\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">\r\n	<tbody>\r\n		<tr>\r\n			<td>Sloupec 1</td>\r\n			<td>Sloupec 2</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Text 1</td>\r\n			<td>Text 2</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>Dnes PDF bohužel nepřikl&aacute;d&aacute;m.</p>\r\n', 'false', -2, '');

-- --------------------------------------------------------

--
-- Table structure for table `hodnoceno`
--

CREATE TABLE `hodnoceno` (
  `uzivatel` int(11) NOT NULL,
  `clanky` int(11) NOT NULL,
  `positive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `hodnoceno`
--

INSERT INTO `hodnoceno` (`uzivatel`, `clanky`, `positive`) VALUES
(27, 75, 1),
(28, 79, 0),
(29, 75, 1),
(50, 75, 1),
(50, 79, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pravo`
--

CREATE TABLE `pravo` (
  `id_pravo` int(11) NOT NULL,
  `nazev` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `vaha` int(11) NOT NULL,
  `popisPravo` text COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `pravo`
--

INSERT INTO `pravo` (`id_pravo`, `nazev`, `vaha`, `popisPravo`) VALUES
(1, 'Admin', 20, '<h4>Spravování uživatelů</h4>\r\n<ul>\r\n<li>\r\nmožnost mazat uživatele\r\n</li>\r\n<li>\r\nmožnost upravovat práva uživatelům\r\n</li>\r\n<li>\r\npřehled všech uživatelů\r\n</li>\r\n</ul>\r\n<h4>Administrace článků</h4>\r\n<ul>\r\n<li>možnost mazat články</li>\r\n<li>možnost přiřazovat recenzenty</li>\r\n<li>vidí nepublikované články</li>\r\n</ul>\r\n\r\n<h4>Možnost vytváření článků</h4>\r\n<ul>\r\n<li>možnost vytvořit nový článek</li>\r\n<li>při schválení recenzenty bude článek publikován</li>\r\n<li>\r\nPři skóre 3+ se článek automaticky publikuje\r\n</li>\r\n</ul>'),
(3, 'Recenzent', 5, '<h4>Možnost recenzování článků</h4>\r\n<ul>\r\n<li>Po přidělení článku Adminem možnost recenzovat článek\r\n</li>\r\n<li>\r\nčlánek se dá ohodnotit palcem nahoru či dolů\r\n</li>\r\n<li>\r\nPokud článek ještě není publikován, může hodnocení změnit\r\n</li>\r\n<li>\r\nVidí nepublikované články\r\n</li>\r\n<li>\r\nPři skóre 3+ se článek automaticky publikuje\r\n</li>\r\n</ul>\r\n<h4>Možnost vytváření článků</h4>\r\n<ul>\r\n<li>možnost vytvořit nový článek</li>\r\n<li>při schválení recenzenty bude článek publikován</li>\r\n<li>\r\nPři skóre 3+ se článek automaticky publikuje\r\n</li>\r\n</ul>'),
(4, 'Autor', 2, '<h4>Možnost vytváření článků</h4>\r\n<ul>\r\n<li>možnost vytvořit nový článek</li>\r\n<li>při schválení recenzenty bude článek publikován</li>\r\n<li>\r\nPři skóre 3+ se článek automaticky publikuje\r\n</li>\r\n</ul>');

-- --------------------------------------------------------

--
-- Table structure for table `recenzovano`
--

CREATE TABLE `recenzovano` (
  `uzivatel` int(11) NOT NULL,
  `clanky` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `recenzovano`
--

INSERT INTO `recenzovano` (`uzivatel`, `clanky`) VALUES
(27, 75),
(28, 79),
(29, 75),
(29, 79),
(50, 75),
(50, 79);

-- --------------------------------------------------------

--
-- Table structure for table `terminy`
--

CREATE TABLE `terminy` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `lokace` text COLLATE utf8mb4_czech_ci NOT NULL,
  `autor_id` int(11) NOT NULL,
  `nazev` tinytext COLLATE utf8mb4_czech_ci NOT NULL,
  `obsah` text COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `terminy`
--

INSERT INTO `terminy` (`id`, `datetime`, `lokace`, `autor_id`, `nazev`, `obsah`) VALUES
(1, '2020-12-12 13:00:00', 'Plzen, Czech Republic', 1, '1. konference', 'Těší nás vám oznámit náš první termín konference, uskutečňující se 12.12. 2020 ve 13:00. Doufáme, že se s vámi uvidíme.');

-- --------------------------------------------------------

--
-- Table structure for table `uzivatel`
--

CREATE TABLE `uzivatel` (
  `id_uzivatel` int(11) NOT NULL,
  `id_pravo` int(11) NOT NULL DEFAULT 3,
  `jmeno` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `login` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(35) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `uzivatel`
--

INSERT INTO `uzivatel` (`id_uzivatel`, `id_pravo`, `jmeno`, `login`, `heslo`, `email`) VALUES
(1, 1, 'Admin', 'admin', '$2y$12$Vppb2XlQ0aCYopGdfSIsgu1BBlpy3/mfsPHvftcjtbZ9qPZHc02Sy', 'admin@gmail.com'),
(26, 4, 'Autor', 'Autor', '$2y$12$Vppb2XlQ0aCYopGdfSIsgu1BBlpy3/mfsPHvftcjtbZ9qPZHc02Sy', 'autor@gmail.com'),
(27, 3, 'Recenzent 1', 'recenzent1', '$2y$12$Vppb2XlQ0aCYopGdfSIsgu1BBlpy3/mfsPHvftcjtbZ9qPZHc02Sy', 'recenzent1@gmail.com'),
(28, 3, 'Recenzent 2', 'recenzent2', '$2y$12$Vppb2XlQ0aCYopGdfSIsgu1BBlpy3/mfsPHvftcjtbZ9qPZHc02Sy', 'recenzent2@gmail.com'),
(29, 3, 'Recenzent 3', 'recenzent3', '$2y$12$Vppb2XlQ0aCYopGdfSIsgu1BBlpy3/mfsPHvftcjtbZ9qPZHc02Sy', 'recenzent3@gmail.com'),
(50, 3, 'Recenzent 4', 'recenzent4', '$2y$12$Vppb2XlQ0aCYopGdfSIsgu1BBlpy3/mfsPHvftcjtbZ9qPZHc02Sy', 'recenzent4@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clanky`
--
ALTER TABLE `clanky`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor_id` (`autor_id`);

--
-- Indexes for table `hodnoceno`
--
ALTER TABLE `hodnoceno`
  ADD PRIMARY KEY (`uzivatel`,`clanky`);

--
-- Indexes for table `pravo`
--
ALTER TABLE `pravo`
  ADD PRIMARY KEY (`id_pravo`);

--
-- Indexes for table `recenzovano`
--
ALTER TABLE `recenzovano`
  ADD PRIMARY KEY (`uzivatel`,`clanky`);

--
-- Indexes for table `terminy`
--
ALTER TABLE `terminy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor_id` (`autor_id`);

--
-- Indexes for table `uzivatel`
--
ALTER TABLE `uzivatel`
  ADD PRIMARY KEY (`id_uzivatel`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_uzivatele_prava_idx` (`id_pravo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clanky`
--
ALTER TABLE `clanky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `terminy`
--
ALTER TABLE `terminy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `uzivatel`
--
ALTER TABLE `uzivatel`
  MODIFY `id_uzivatel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clanky`
--
ALTER TABLE `clanky`
  ADD CONSTRAINT `autor_id` FOREIGN KEY (`autor_id`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `uzivatel`
--
ALTER TABLE `uzivatel`
  ADD CONSTRAINT `fk_uzivatele_prava` FOREIGN KEY (`id_pravo`) REFERENCES `pravo` (`id_pravo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
