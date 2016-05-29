-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 24. Mai 2016 um 20:19
-- Server-Version: 10.0.17-MariaDB
-- PHP-Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `browsergame`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `drachenbilder`
--

CREATE TABLE `drachenbilder` (
  `id_d` int(20) NOT NULL,
  `drachenart` varchar(20) NOT NULL,
  `pfad` int(20) NOT NULL,
  `id_g` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `drachenbilder`
--

INSERT INTO `drachenbilder` (`id_d`, `drachenart`, `pfad`, `id_g`) VALUES
(1, 'erddrache_w_1', 0, 0),
(2, 'erddrache_w_2', 0, 0),
(3, 'erddrache_w_3', 0, 0),
(4, 'erddrache_w_4', 0, 0),
(5, 'erddrache_w_5', 0, 0),
(6, 'erddrache_w_6', 0, 0),
(7, 'erddrache_w_7', 0, 0),
(8, 'erddrache_m_1', 0, 0),
(9, 'erddrache_m_2', 0, 0),
(10, 'erddrache_m_3', 0, 0),
(11, 'erddrache_m_4', 0, 0),
(12, 'erddrache_m_5', 0, 0),
(13, 'erddrache_m_6', 0, 0),
(14, 'erddrache_m_7', 0, 0),
(15, 'wasserdrache_w_1', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `erdelement`
--

CREATE TABLE `erdelement` (
  `id` int(20) NOT NULL,
  `erdelement1` int(20) NOT NULL,
  `erdelement2` int(20) NOT NULL,
  `erdelement3` int(20) NOT NULL,
  `erdelement4` int(20) NOT NULL,
  `erdelement5` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feuerelement`
--

CREATE TABLE `feuerelement` (
  `id` int(20) NOT NULL,
  `feuerelement1` int(20) NOT NULL,
  `feuerelement2` int(20) NOT NULL,
  `feuerelement3` int(20) NOT NULL,
  `feuerelement4` int(20) NOT NULL,
  `feuerelement5` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gebietsbilder`
--

CREATE TABLE `gebietsbilder` (
  `id_g` int(20) NOT NULL,
  `titel` text NOT NULL,
  `pfad` varchar(20) NOT NULL,
  `beschreibung` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Level`
--

CREATE TABLE `Level` (
  `Level1` varchar(50) NOT NULL,
  `Level2` varchar(50) NOT NULL,
  `Level3` varchar(50) NOT NULL,
  `Level4` varchar(50) NOT NULL,
  `Level5` varchar(50) NOT NULL,
  `Level6` varchar(50) NOT NULL,
  `Level7` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `luftelement`
--

CREATE TABLE `luftelement` (
  `id` int(20) NOT NULL,
  `luftelement1` int(20) NOT NULL,
  `luftelement2` int(20) NOT NULL,
  `luftelement3` int(20) NOT NULL,
  `luftelement4` int(20) NOT NULL,
  `luftelement5` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pri_faehigkeiten`
--

CREATE TABLE `pri_faehigkeiten` (
  `id` int(20) NOT NULL,
  `staerke` int(20) NOT NULL,
  `intelligenz` int(20) NOT NULL,
  `magie` int(20) NOT NULL,
  `feuerspeien` int(20) NOT NULL,
  `fliegen` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spieler`
--

CREATE TABLE `spieler` (
  `id` int(20) NOT NULL,
  `session` varchar(20) NOT NULL,
  `username` text NOT NULL,
  `userpswd` varchar(20) NOT NULL,
  `id_g` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wasserelement`
--

CREATE TABLE `wasserelement` (
  `id` int(20) NOT NULL,
  `wasserelement1` int(20) NOT NULL,
  `wasserelement2` int(20) NOT NULL,
  `wasserelement3` int(20) NOT NULL,
  `wasserelement4` int(20) NOT NULL,
  `wasserelement5` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `drachenbilder`
--
ALTER TABLE `drachenbilder`
  ADD PRIMARY KEY (`id_d`);

--
-- Indizes für die Tabelle `erdelement`
--
ALTER TABLE `erdelement`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `feuerelement`
--
ALTER TABLE `feuerelement`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `gebietsbilder`
--
ALTER TABLE `gebietsbilder`
  ADD PRIMARY KEY (`id_g`);

--
-- Indizes für die Tabelle `luftelement`
--
ALTER TABLE `luftelement`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `pri_faehigkeiten`
--
ALTER TABLE `pri_faehigkeiten`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `wasserelement`
--
ALTER TABLE `wasserelement`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `drachenbilder`
--
ALTER TABLE `drachenbilder`
  MODIFY `id_d` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT für Tabelle `erdelement`
--
ALTER TABLE `erdelement`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `feuerelement`
--
ALTER TABLE `feuerelement`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `gebietsbilder`
--
ALTER TABLE `gebietsbilder`
  MODIFY `id_g` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `luftelement`
--
ALTER TABLE `luftelement`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `pri_faehigkeiten`
--
ALTER TABLE `pri_faehigkeiten`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `spieler`
--
ALTER TABLE `spieler`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `wasserelement`
--
ALTER TABLE `wasserelement`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
