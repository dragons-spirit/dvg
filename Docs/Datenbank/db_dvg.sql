-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Dez 2016 um 19:47
-- Server-Version: 10.1.13-MariaDB
-- PHP-Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db_dvg`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int(10) NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aktiv` tinyint(1) NOT NULL,
  `Rolle` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `letzter_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `account`
--

INSERT INTO `account` (`id`, `login`, `passwort`, `email`, `aktiv`, `Rolle`, `letzter_login`) VALUES
(10, 'Tina', 'Tina', 'eisdrache@gmx.de', 1, 'Admin', '2016-07-13 18:20:18'),
(11, 'hendrik', 'feuerdrache', 'feuerdrache@gmx.de', 1, 'Admin', '2016-07-13 21:06:28'),
(12, 'mustafa', 'kyrillisch', 'afatsum@mustafa.ru', 1, 'Spieler', '2016-07-13 18:02:18'),
(13, 'balduin', 'xyzzyx', 'balduin@gmail.com', 1, 'Spieler', '2016-07-13 18:00:13'),
(14, 'klaus_trophobie', 'zuckerwatte', 'register@klaustrophobie.de', 1, 'Spieler', '2016-07-13 18:00:13'),
(15, 'Apfel', 'Achorle', 'Apfel.Schorle@Saft.com', 1, 'Spieler', '2016-07-13 18:20:23'),
(16, 'hugo', '123456', 'hugo@gmx.de', 1, 'Spieler', '2016-07-13 18:03:06'),
(17, 'erwin', 'erwin', 'erwin@gmx.de', 1, 'Spieler', '2016-07-13 18:21:11'),
(31, 'tester', 'tester', 'tester@gmx.de', 1, 'Spieler', '2016-07-13 20:19:48'),
(32, 'test', 'test', 'test@gmx.de', 1, 'Spieler', '2016-07-13 20:41:09'),
(33, 'zonk', 'zonk', 'zonk@zonk.de', 1, 'Spieler', '2016-07-22 19:03:10'),
(34, 'zonk1', 'zonk1', 'zonk1@zonk1.de', 1, 'Spieler', '2016-07-22 19:09:54'),
(35, 'zonk2', 'zonk2', 'zonk2@zonk2.de', 1, 'Spieler', '2016-07-22 19:10:26'),
(36, 'zonk3', 'zonk3', 'zonk3@zonk3.de', 1, 'Spieler', '2016-07-22 19:17:33');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bilder`
--

DROP TABLE IF EXISTS `bilder`;
CREATE TABLE `bilder` (
  `id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pfad` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `bilder`
--

INSERT INTO `bilder` (`id`, `titel`, `pfad`, `beschreibung`) VALUES
(1, 'blank', '', ''),
(2, 'Sumpf', '../Platzhalter_gebiete/Gross/sumpf', 'Nass, schmutzig und schauerlich. Ein Sumpf eben!'),
(3, 'Vulkan', '../Platzhalter_gebiete/Gross/vulkan.jpg', 'Das feurige Herz von Gaia. Vorsicht heiß!'),
(4, 'Eissee', '../Platzhalter_gebiete/Gross/eissee.jpg', 'Ein schöner großer See lädt euch zum Baden gehen ein. An die Hacke, fertig, los!'),
(5, 'Dschungel', '../Platzhalter_gebiete/Gross/dschungel.jpg', 'Gestrüpp soweit das Auge reicht. Was hier alles kreucht und fleucht, wollt ihr euch gar nicht erst ausmalen.'),
(6, 'Klippe', '../Platzhalter_gebiete/Gross/klippe.jpg', 'Ihr schaut in den Abgrund und sogleich juckt es euch in den Flügeln. Traut euch!'),
(7, 'Kristallhoehle', '../Platzhalter_gebiete/Gross/kristallhoehle.jpg', 'Funkelnde Steine weit und breit. Ein wahres Paradies ... wenn man es kalt, muffig und feucht mag.'),
(8, 'Wueste', '../Platzhalter_gebiete/Gross/wueste.jpg', 'Naaaa, wollt ihr etwas zu trinken? Hier ganz sicher nicht!'),
(9, 'Mammutbaum', '../Platzhalter_gebiete/Gross/mammutbaum.jpg', 'Ein mächtiger Stamm, gewaltiges Blattwerk und die schier endlose Höhe lassen auf einen Mammutbaum schließen.'),
(10, 'Wald', '../Platzhalter_gebiete/Gross/wald.jpg', 'Manchereins sieht den Wald vor lauter Bäumen nicht. Hinweis: Ihr steht gerade in einem!'),
(11, 'Oase', '../Platzhalter_gebiete/Gross/oase.jpg', 'Träumt ihr oder halluziniert ihr nur? Wasser und Grün mitten in der Wüste. Das kann doch nicht mit rechten Dingen zugehen.'),
(12, 'Steppe', '../Platzhalter_gebiete/Gross/steppe.jpg', 'Gras überall Gras. Ihr schlagt die Hände über dem Kopf zusammen und denkt: ''Wenn man es wenigstens rauchen könnte ...''');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `element`
--

DROP TABLE IF EXISTS `element`;
CREATE TABLE `element` (
  `id` int(10) NOT NULL,
  `element_id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `element`
--

INSERT INTO `element` (`id`, `element_id`, `bilder_id`, `titel`, `beschreibung`) VALUES
(1, 0, 1, 'Feuer', ''),
(2, 0, 1, 'Wasser', ''),
(3, 0, 1, 'Erde', ''),
(4, 0, 1, 'Luft', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faehigkeiten`
--

DROP TABLE IF EXISTS `faehigkeiten`;
CREATE TABLE `faehigkeiten` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `faehigkeiten`
--

INSERT INTO `faehigkeiten` (`id`, `bilder_id`, `titel`, `beschreibung`) VALUES
(1, 1, 'Fliegen', ''),
(2, 1, 'Töpfern', ''),
(3, 1, 'Sprinten', ''),
(4, 1, 'Lyrik', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faehigkeiten_spieler`
--

DROP TABLE IF EXISTS `faehigkeiten_spieler`;
CREATE TABLE `faehigkeiten_spieler` (
  `id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `faehigkeiten_id` int(10) NOT NULL,
  `wert` float NOT NULL,
  `stufe` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `faehigkeiten_spieler`
--

INSERT INTO `faehigkeiten_spieler` (`id`, `spieler_id`, `faehigkeiten_id`, `wert`, `stufe`) VALUES
(1, 1, 1, 13.76, 2),
(2, 1, 2, 9.83, 1),
(3, 1, 3, 38.48, 4),
(4, 1, 4, 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gattung`
--

DROP TABLE IF EXISTS `gattung`;
CREATE TABLE `gattung` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_staerke` float NOT NULL,
  `start_intelligenz` float NOT NULL,
  `start_magie` float NOT NULL,
  `start_element_feuer` float NOT NULL,
  `start_element_wasser` float NOT NULL,
  `start_element_erde` float NOT NULL,
  `start_element_luft` float NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `gattung`
--

INSERT INTO `gattung` (`id`, `bilder_id`, `titel`, `start_staerke`, `start_intelligenz`, `start_magie`, `start_element_feuer`, `start_element_wasser`, `start_element_erde`, `start_element_luft`, `beschreibung`) VALUES
(1, 1, 'Feuerdrache', 10, 5, 0, 5, 1, 1, 1, ''),
(2, 1, 'Wasserdrache', 10, 5, 0, 1, 5, 1, 1, ''),
(3, 1, 'Erddrache', 10, 5, 0, 1, 1, 5, 1, ''),
(4, 1, 'Luftdrache', 10, 5, 0, 1, 1, 1, 5, ''),
(5, 1, 'Zayine', 10, 10, 10, 10, 10, 10, 10, ''),
(6, 1, 'Fisch', 2, 1, 0, 0, 30, 0, 0, ''),
(7, 1, 'Paarhufer', 5, 4, 1, 0, 5, 10, 10, ''),
(8, 1, 'Vogel', 1, 3, 0, 0, 3, 1, 30, ''),
(9, 1, 'Nager', 1, 2, 0, 1, 1, 20, 0, ''),
(10, 1, 'Raubtier', 10, 7, 0, 5, 3, 10, 3, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE `level` (
  `id` int(10) NOT NULL,
  `voraussetzung` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `stufe` int(10) NOT NULL,
  `modifikator` float NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `level`
--

INSERT INTO `level` (`id`, `voraussetzung`, `titel`, `stufe`, `modifikator`, `beschreibung`) VALUES
(1, '', 'Level 1', 1, 0, ''),
(2, '', 'Level 2', 2, 0, ''),
(3, '', 'Level 3', 3, 0, ''),
(4, '', 'Level 4', 4, 0, ''),
(5, '', 'Level 5', 5, 0, ''),
(6, '', 'Level 6', 6, 0, ''),
(7, '', 'Level 7', 7, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `npc`
--

DROP TABLE IF EXISTS `npc`;
CREATE TABLE `npc` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `element_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Familie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `staerke` float NOT NULL,
  `intelligenz` float NOT NULL,
  `magie` float NOT NULL,
  `element_feuer` float NOT NULL,
  `element_wasser` float NOT NULL,
  `element_erde` float NOT NULL,
  `element_luft` float NOT NULL,
  `gesundheit` int(10) NOT NULL,
  `energie` int(10) NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `npc`
--

INSERT INTO `npc` (`id`, `bilder_id`, `element_id`, `titel`, `Familie`, `staerke`, `intelligenz`, `magie`, `element_feuer`, `element_wasser`, `element_erde`, `element_luft`, `gesundheit`, `energie`, `beschreibung`) VALUES
(1, 1, 1, 'Wymar', 'Drache', 75, 100, 50, 100, 20, 20, 20, 1000, 100, 'Wymar ist einer der ältesten bekannten Drachen und wird für seine Weisheit hoch geschätzt. Ihr tut gut daran, seinen Ratschlägen aufs genauste zu folgen.'),
(2, 1, 3, 'Ratte', 'Nager', 3, 3, 0, 0, 5, 5, 0, 15, 10, 'Eklige Biester! Entweder kreischend davonrennen und den erstbesten Kammerjäger um Hilfe bitten oder einfach selbst Hand anlegen. '),
(3, 1, 0, 'Zayinenkrieger', 'Zayine', 50, 50, 40, 25, 25, 25, 25, 1000, 750, 'Einen Krieger der Zayinen. Am besten ihr schleicht euch ungesehen an ihm vorbei, denn schon auf den ersten Blick könnt ihr erkennen, dass mit ihm nicht gut Kirschen essen sein wird.'),
(4, 1, 3, 'Fuchs', 'Fuchs', 10, 10, 0, 0, 2, 10, 5, 35, 50, 'Ein Fuchs, kräftig gebaut, jedoch scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er auf den ersten Blick ganz niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.'),
(5, 1, 3, 'Junger Fuchs', 'Fuchs', 5, 5, 0, 0, 1, 5, 3, 25, 40, 'Ein Fuchs, relativ klein, scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er klein und niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quest`
--

DROP TABLE IF EXISTS `quest`;
CREATE TABLE `quest` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titel_erweitert` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `voraussetzung_level` int(10) NOT NULL,
  `belohnung` int(10) NOT NULL,
  `text_start` text COLLATE utf8_unicode_ci NOT NULL,
  `text_mitte` text COLLATE utf8_unicode_ci NOT NULL,
  `text_sieg` text COLLATE utf8_unicode_ci NOT NULL,
  `text_niederlage` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `quest`
--

INSERT INTO `quest` (`id`, `bilder_id`, `titel`, `titel_erweitert`, `voraussetzung_level`, `belohnung`, `text_start`, `text_mitte`, `text_sieg`, `text_niederlage`) VALUES
(1, 1, 'Mein erstes Abenteuer', '', 1, 10, 'Los geht''s. Das erste Abenteuer wartet auf euch.', 'Das Leben leben, die Welt erkunden. So ist das Leben eines Abenteurers.', 'Kopfstand, Rückwärtssalto, Feuerspucken im freien Fall, 100-zeiligen Zungenbrecher fehlerfrei vortragen, ein Spiel programmieren ... Ihr hättet wirklich eine Herausforderung erwartet und nicht solch Kinderkram.', 'Eine Runde um den Block und schon schleppt ihr euch keuchend und ächzend zum nächsten Heiler? Gaja scheint euch nicht wohlgesonnen zu sein.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quest_spieler`
--

DROP TABLE IF EXISTS `quest_spieler`;
CREATE TABLE `quest_spieler` (
  `id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `quest_id` int(10) NOT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `ende` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `quest_spieler`
--

INSERT INTO `quest_spieler` (`id`, `spieler_id`, `quest_id`, `start`, `ende`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spieler`
--

DROP TABLE IF EXISTS `spieler`;
CREATE TABLE `spieler` (
  `id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `gattung_id` int(10) NOT NULL,
  `level_id` int(10) NOT NULL,
  `gebiet_id` int(10) NOT NULL,
  `name` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `geschlecht` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `staerke` float NOT NULL COMMENT 'Stärke',
  `intelligenz` float NOT NULL COMMENT 'Intelligenz',
  `magie` float NOT NULL COMMENT 'Magie',
  `element_feuer` float NOT NULL,
  `element_wasser` float NOT NULL,
  `element_erde` float NOT NULL,
  `element_luft` float NOT NULL,
  `gesundheit` int(10) NOT NULL,
  `max_gesundheit` int(10) NOT NULL,
  `energie` int(10) NOT NULL,
  `max_energie` int(10) NOT NULL,
  `balance` float NOT NULL,
  `zuletzt_gespielt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `spieler`
--

INSERT INTO `spieler` (`id`, `account_id`, `bilder_id`, `gattung_id`, `level_id`, `gebiet_id`, `name`, `geschlecht`, `staerke`, `intelligenz`, `magie`, `element_feuer`, `element_wasser`, `element_erde`, `element_luft`, `gesundheit`, `max_gesundheit`, `energie`, `max_energie`, `balance`, `zuletzt_gespielt`) VALUES
(7, 17, 1, 2, 1, 3, 'Saphira', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(8, 17, 1, 1, 1, 7, 'Drako', 'W', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(9, 17, 1, 4, 1, 5, 'Luftikuss', 'W', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2016-12-18 16:43:57'),
(10, 31, 1, 3, 1, 6, 'Testdrachin', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(11, 32, 1, 3, 1, 4, 'Heino', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-18 16:18:43'),
(12, 36, 1, 1, 1, 2, 'Quiecker', 'W', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(13, 36, 1, 2, 1, 1, 'Lambadina', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(14, 11, 1, 4, 1, 8, 'Baldrian', 'W', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2016-12-18 16:49:15'),
(15, 11, 1, 3, 1, 8, 'Cecilia', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-18 17:46:48'),
(16, 17, 1, 2, 1, 3, 'Blauer Enzian', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(17, 17, 1, 1, 1, 7, 'WÃ¼stenfuchs', 'M', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(18, 17, 1, 4, 1, 8, 'Rosaroter Panter', 'M', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(19, 11, 1, 3, 1, 8, 'Shizophrenia', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-18 18:26:34'),
(20, 11, 1, 3, 1, 4, 'Erdwurmi', 'M', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-18 16:14:44');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zauber`
--

DROP TABLE IF EXISTS `zauber`;
CREATE TABLE `zauber` (
  `id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `element_id` int(10) NOT NULL COMMENT 'Zuordnung zu Element',
  `bilder_id` int(10) NOT NULL,
  `zauberart_id` int(10) NOT NULL COMMENT 'aktiv/passiv, defensiv/offensiv',
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titel_erweitert` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `voraussetzung` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `verbrauch` int(10) NOT NULL,
  `effekt` float NOT NULL COMMENT 'Grundwert für Angriff/Verteidigung und sonstige Effekte',
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `zauber`
--

INSERT INTO `zauber` (`id`, `spieler_id`, `element_id`, `bilder_id`, `zauberart_id`, `titel`, `titel_erweitert`, `voraussetzung`, `verbrauch`, `effekt`, `beschreibung`) VALUES
(1, 1, 1, 1, 1, 'Feuerball', '', '', 1, 10, ''),
(2, 1, 2, 1, 2, 'Heilung', '', '', 1, -10, ''),
(3, 1, 3, 1, 6, 'Lehm', '', '', 1, 1, ''),
(4, 1, 4, 1, 5, 'Schnelligkeit', '', '', 1, 1, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zauberart`
--

DROP TABLE IF EXISTS `zauberart`;
CREATE TABLE `zauberart` (
  `id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `modifikator` float NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `zauberart`
--

INSERT INTO `zauberart` (`id`, `titel`, `modifikator`, `beschreibung`) VALUES
(1, 'Offensiv', 0, ''),
(2, 'Defnsiv', 0, ''),
(5, 'Aktiv', 0, ''),
(6, 'Passiv', 0, '');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indizes für die Tabelle `bilder`
--
ALTER TABLE `bilder`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `element`
--
ALTER TABLE `element`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `faehigkeiten`
--
ALTER TABLE `faehigkeiten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `faehigkeiten_spieler`
--
ALTER TABLE `faehigkeiten_spieler`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `gattung`
--
ALTER TABLE `gattung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `npc`
--
ALTER TABLE `npc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `quest_spieler`
--
ALTER TABLE `quest_spieler`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `zauber`
--
ALTER TABLE `zauber`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `zauberart`
--
ALTER TABLE `zauberart`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT für Tabelle `bilder`
--
ALTER TABLE `bilder`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `element`
--
ALTER TABLE `element`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `faehigkeiten`
--
ALTER TABLE `faehigkeiten`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `faehigkeiten_spieler`
--
ALTER TABLE `faehigkeiten_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `gattung`
--
ALTER TABLE `gattung`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `level`
--
ALTER TABLE `level`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `npc`
--
ALTER TABLE `npc`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `quest`
--
ALTER TABLE `quest`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `quest_spieler`
--
ALTER TABLE `quest_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `spieler`
--
ALTER TABLE `spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT für Tabelle `zauber`
--
ALTER TABLE `zauber`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `zauberart`
--
ALTER TABLE `zauberart`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
