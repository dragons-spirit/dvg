-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Mrz 2018 um 17:47
-- Server-Version: 10.1.13-MariaDB
-- PHP-Version: 5.6.21

SET FOREIGN_KEY_CHECKS=0;
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
DROP TABLE IF EXISTS `account`, `aktion`, `aktion_spieler`, `bilder`, `element`, `faehigkeiten`, `faehigkeiten_spieler`, `gattung`, `gebiet`, `gebiet_gebiet`, `items`, `items_spieler`, `level`, `npc`, `npc_gebiet`, `npc_items`, `quest`, `quest_spieler`, `spieler`, `zauber`, `zauberart`, `zauber_spieler`;


DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int(10) NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aktiv` tinyint(1) NOT NULL,
  `Rolle` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `letzter_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Logindaten für Nutzer';

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
-- Tabellenstruktur für Tabelle `aktion`
--

DROP TABLE IF EXISTS `aktion`;
CREATE TABLE `aktion` (
  `id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
  `art` enum('kurz','normal','lang') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal',
  `dauer` time NOT NULL,
  `statusbild` enum('laufend','kaempfend','wartend','') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='alle möglichen Aktionen die ein Spieler ausführen kann';

--
-- Daten für Tabelle `aktion`
--

INSERT INTO `aktion` (`id`, `titel`, `text`, `beschreibung`, `art`, `dauer`, `statusbild`) VALUES
(1, 'erkunden_normal', 'Gegend erkunden', 'Du läufst zielstrebig im Kreis und hoffst mit etwas Glück, auf einen tollen Fund zu stoßen.', 'normal', '00:01:00', 'laufend'),
(2, 'erkunden_kurz', 'Gegend erkunden', 'Du schaust vor deine Füße und hoffst ,etwas zu entdecken, was deine Aufmerksamkeit wert ist.', 'kurz', '00:00:05', 'laufend'),
(5, 'erkunden_lang', 'Gegend erkunden', 'Du drehst und wendest stundelang jeden Stein, der dir über den Weg hüpft, in der Hoffnung endlichen den großen Schatz zu finden.', 'lang', '00:05:00', 'laufend'),
(6, 'jagen_normal', 'Jagen', 'Das böse Tierchen wird in dir seinen Meister finden und dir alle seine Schätze offenbaren.\r\nOder es läuft anders herum!', 'normal', '00:00:05', 'kaempfend'),
(7, 'sammeln_normal', 'Sammeln', 'Du fällst schreiend über die Pflanze her und stellst erschrocken fest, dass es sich doch nur um eine normale Pflanze handelt, die ohnehin nicht wegrennen geschweige denn um sich schlagen kann.', 'normal', '00:00:05', 'kaempfend'),
(8, 'reden', 'Reden', 'Du versucht dein Gegenüber anzusprechen.', 'normal', '00:00:01', 'wartend'),
(9, 'fliegen', 'Fliegen', 'Du fliegst von Punkt A zu Punkt B. Das könnte ein bis drei Weilchen dauern.', 'normal', '00:01:00', 'laufend'),
(10, 'laufen', 'Laufen', 'Du läufst von Punkt A zu Punkt B. Das könnte zwei Weilchen dauern.', 'normal', '00:00:10', 'laufend');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aktion_spieler`
--

DROP TABLE IF EXISTS `aktion_spieler`;
CREATE TABLE `aktion_spieler` (
  `id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `aktion_id` int(10) NOT NULL,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ende` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('gestartet','beendet','abgeschlossen','') COLLATE utf8_unicode_ci NOT NULL,
  `any_id_1` int(10) NOT NULL,
  `any_id_2` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Aktionen die die Spieler derzeit ausführen mit Start- und Endzeit';

--
-- Daten für Tabelle `aktion_spieler`
--

INSERT INTO `aktion_spieler` (`id`, `spieler_id`, `aktion_id`, `start`, `ende`, `status`, `any_id_1`, `any_id_2`) VALUES
(1, 26, 10, '2017-05-27 15:03:29', '2017-05-27 15:03:39', 'abgeschlossen', 9, 0),
(2, 19, 2, '2017-05-30 18:56:18', '2017-05-30 18:56:23', 'abgeschlossen', 0, 0),
(3, 19, 7, '2017-05-30 18:56:29', '2017-05-30 18:56:34', 'abgeschlossen', 6, 0),
(4, 19, 2, '2017-06-14 17:10:18', '2017-06-14 17:10:23', 'abgeschlossen', 0, 0),
(5, 19, 2, '2017-06-14 17:10:29', '2017-06-14 17:10:34', 'abgeschlossen', 0, 0),
(6, 19, 6, '2017-06-14 17:10:46', '2017-06-14 17:10:51', 'abgeschlossen', 2, 0),
(7, 14, 2, '2017-11-23 20:18:02', '2017-11-23 20:18:07', 'abgeschlossen', 0, 0),
(8, 14, 7, '2017-11-23 20:18:12', '2017-11-23 20:18:17', 'abgeschlossen', 7, 0),
(9, 26, 2, '2018-03-17 14:52:13', '2018-03-17 14:52:18', 'abgeschlossen', 0, 0),
(10, 26, 10, '2018-03-17 15:06:58', '2018-03-17 15:07:08', 'abgeschlossen', 6, 0),
(11, 26, 10, '2018-03-17 15:07:22', '2018-03-17 15:07:32', 'abgeschlossen', 3, 0),
(12, 26, 2, '2018-03-17 15:07:44', '2018-03-17 15:07:49', 'abgeschlossen', 0, 0),
(13, 26, 2, '2018-03-17 15:08:14', '2018-03-17 15:08:19', 'abgeschlossen', 0, 0),
(14, 26, 2, '2018-03-17 15:08:30', '2018-03-17 15:08:35', 'abgeschlossen', 0, 0),
(15, 26, 6, '2018-03-17 15:08:39', '2018-03-17 15:08:44', 'abgeschlossen', 10, 0),
(16, 26, 10, '2018-03-17 15:38:11', '2018-03-17 15:38:21', 'abgeschlossen', 5, 0),
(17, 26, 10, '2018-03-17 15:38:26', '2018-03-17 15:38:36', 'abgeschlossen', 2, 0),
(18, 26, 2, '2018-03-17 15:38:44', '2018-03-17 15:38:49', 'abgeschlossen', 0, 0),
(19, 26, 2, '2018-03-17 15:38:52', '2018-03-17 15:38:57', 'abgeschlossen', 0, 0),
(20, 26, 6, '2018-03-17 15:38:59', '2018-03-17 15:39:04', 'abgeschlossen', 2, 0),
(21, 26, 2, '2018-03-17 15:39:07', '2018-03-17 15:39:12', 'abgeschlossen', 0, 0),
(22, 26, 6, '2018-03-17 15:39:16', '2018-03-17 15:39:21', 'abgeschlossen', 2, 0),
(23, 26, 10, '2018-03-17 15:39:26', '2018-03-17 15:39:36', 'abgeschlossen', 5, 0),
(24, 26, 2, '2018-03-17 15:39:41', '2018-03-17 15:39:46', 'abgeschlossen', 0, 0),
(25, 26, 7, '2018-03-17 15:39:50', '2018-03-17 15:39:55', 'abgeschlossen', 7, 0),
(26, 26, 2, '2018-03-17 15:40:04', '2018-03-17 15:40:09', 'abgeschlossen', 0, 0),
(27, 26, 7, '2018-03-17 15:40:11', '2018-03-17 15:40:16', 'abgeschlossen', 8, 0),
(28, 26, 2, '2018-03-17 15:40:19', '2018-03-17 15:40:24', 'abgeschlossen', 0, 0),
(29, 26, 7, '2018-03-17 15:40:26', '2018-03-17 15:40:31', 'abgeschlossen', 12, 0),
(30, 26, 2, '2018-03-17 15:46:25', '2018-03-17 15:46:30', 'abgeschlossen', 0, 0),
(31, 26, 2, '2018-03-17 15:49:07', '2018-03-17 15:49:12', 'abgeschlossen', 0, 0),
(32, 26, 2, '2018-03-17 15:55:27', '2018-03-17 15:55:32', 'abgeschlossen', 0, 0),
(33, 26, 7, '2018-03-17 15:55:35', '2018-03-17 15:55:40', 'abgeschlossen', 7, 0),
(34, 26, 2, '2018-03-17 15:59:30', '2018-03-17 15:59:35', 'abgeschlossen', 0, 0),
(35, 26, 7, '2018-03-17 15:59:37', '2018-03-17 15:59:42', 'abgeschlossen', 8, 0),
(36, 26, 2, '2018-03-17 16:05:11', '2018-03-17 16:05:16', 'abgeschlossen', 0, 0),
(37, 26, 7, '2018-03-17 16:05:18', '2018-03-17 16:05:23', 'abgeschlossen', 7, 0),
(38, 26, 2, '2018-03-17 16:22:59', '2018-03-17 16:23:04', 'abgeschlossen', 0, 0),
(39, 26, 7, '2018-03-17 16:23:06', '2018-03-17 16:23:11', 'abgeschlossen', 12, 0),
(40, 26, 10, '2018-03-17 16:23:22', '2018-03-17 16:23:32', 'abgeschlossen', 2, 0),
(41, 26, 10, '2018-03-17 16:23:38', '2018-03-17 16:23:48', 'abgeschlossen', 5, 0),
(42, 26, 10, '2018-03-17 16:23:52', '2018-03-17 16:24:02', 'abgeschlossen', 7, 0),
(43, 26, 10, '2018-03-17 16:24:05', '2018-03-17 16:24:15', 'abgeschlossen', 11, 0),
(44, 26, 10, '2018-03-17 16:24:17', '2018-03-17 16:24:27', 'abgeschlossen', 4, 0),
(45, 26, 10, '2018-03-17 16:24:30', '2018-03-17 16:24:40', 'abgeschlossen', 8, 0),
(46, 26, 10, '2018-03-17 16:25:38', '2018-03-17 16:25:48', 'abgeschlossen', 9, 0),
(47, 26, 2, '2018-03-17 16:33:15', '2018-03-17 16:33:20', 'abgeschlossen', 0, 0),
(48, 26, 7, '2018-03-17 16:33:22', '2018-03-17 16:33:27', 'abgeschlossen', 12, 0),
(49, 26, 2, '2018-03-17 16:33:33', '2018-03-17 16:33:38', 'abgeschlossen', 0, 0),
(50, 26, 7, '2018-03-17 16:33:46', '2018-03-17 16:33:51', 'abgeschlossen', 6, 0),
(51, 26, 2, '2018-03-17 16:34:03', '2018-03-17 16:34:08', 'abgeschlossen', 0, 0),
(52, 26, 7, '2018-03-17 16:34:11', '2018-03-17 16:34:16', 'abgeschlossen', 14, 0),
(53, 26, 10, '2018-03-17 16:34:26', '2018-03-17 16:34:36', 'abgeschlossen', 6, 0),
(54, 26, 2, '2018-03-17 16:34:40', '2018-03-17 16:34:45', 'abgeschlossen', 0, 0),
(55, 26, 7, '2018-03-17 16:34:48', '2018-03-17 16:34:53', 'abgeschlossen', 7, 0),
(56, 26, 10, '2018-03-17 16:34:57', '2018-03-17 16:35:07', 'abgeschlossen', 3, 0),
(57, 26, 2, '2018-03-17 16:35:09', '2018-03-17 16:35:14', 'abgeschlossen', 0, 0),
(58, 26, 2, '2018-03-17 16:35:19', '2018-03-17 16:35:24', 'abgeschlossen', 0, 0),
(59, 26, 7, '2018-03-17 16:35:28', '2018-03-17 16:35:33', 'abgeschlossen', 12, 0),
(60, 26, 2, '2018-03-17 16:35:38', '2018-03-17 16:35:43', 'abgeschlossen', 0, 0),
(61, 26, 2, '2018-03-17 16:35:49', '2018-03-17 16:35:54', 'abgeschlossen', 0, 0),
(62, 26, 2, '2018-03-17 16:36:00', '2018-03-17 16:36:05', 'abgeschlossen', 0, 0),
(63, 26, 2, '2018-03-17 16:36:11', '2018-03-17 16:36:16', 'abgeschlossen', 0, 0),
(64, 26, 2, '2018-03-17 16:36:20', '2018-03-17 16:36:25', 'abgeschlossen', 0, 0),
(65, 26, 2, '2018-03-17 16:36:29', '2018-03-17 16:36:34', 'abgeschlossen', 0, 0),
(66, 26, 2, '2018-03-17 16:36:37', '2018-03-17 16:36:42', 'abgeschlossen', 0, 0),
(67, 26, 6, '2018-03-17 16:36:47', '2018-03-17 16:36:52', 'abgeschlossen', 10, 0),
(68, 26, 10, '2018-03-17 16:36:57', '2018-03-17 16:37:07', 'abgeschlossen', 5, 0),
(69, 26, 10, '2018-03-17 16:37:13', '2018-03-17 16:37:23', 'abgeschlossen', 2, 0),
(70, 26, 10, '2018-03-17 16:37:25', '2018-03-17 16:37:35', 'abgeschlossen', 6, 0),
(71, 26, 10, '2018-03-17 16:37:38', '2018-03-17 16:37:48', 'abgeschlossen', 9, 0),
(72, 26, 10, '2018-03-17 16:37:53', '2018-03-17 16:38:03', 'abgeschlossen', 8, 0),
(73, 26, 10, '2018-03-17 16:38:05', '2018-03-17 16:38:15', 'abgeschlossen', 4, 0),
(74, 26, 2, '2018-03-17 16:38:18', '2018-03-17 16:38:23', 'abgeschlossen', 0, 0),
(75, 26, 6, '2018-03-17 16:38:33', '2018-03-17 16:38:38', 'abgeschlossen', 11, 0),
(76, 26, 2, '2018-03-17 16:38:45', '2018-03-17 16:38:50', 'abgeschlossen', 0, 0),
(77, 26, 7, '2018-03-17 16:38:54', '2018-03-17 16:38:59', 'abgeschlossen', 7, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bilder`
--

DROP TABLE IF EXISTS `bilder`;
CREATE TABLE `bilder` (
  `id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pfad` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle genutzen Bilder mit Speicherpfad, separatem Bildtitel und Beschreibung';

--
-- Daten für Tabelle `bilder`
--

INSERT INTO `bilder` (`id`, `titel`, `pfad`) VALUES
(1, '---ohne---', ''),
(2, 'Sumpf', '../Platzhalter_gebiete/Gross/sumpf.jpg'),
(3, 'Vulkan', '../Platzhalter_gebiete/Gross/vulkan.jpg'),
(4, 'Eissee', '../Platzhalter_gebiete/Gross/eissee.jpg'),
(5, 'Dschungel', '../Platzhalter_gebiete/Gross/dschungel.jpg'),
(6, 'Klippe', '../Platzhalter_gebiete/Gross/klippe.jpg'),
(7, 'Kristallhoehle', '../Platzhalter_gebiete/Gross/kristallhoehle.jpg'),
(8, 'Wueste', '../Platzhalter_gebiete/Gross/wueste.jpg'),
(9, 'Mammutbaum', '../Platzhalter_gebiete/Gross/mammutbaum.jpg'),
(10, 'Wald', '../Platzhalter_gebiete/Gross/wald.jpg'),
(11, 'Oase', '../Platzhalter_gebiete/Gross/oase.jpg'),
(12, 'Steppe', '../Platzhalter_gebiete/Gross/steppe.jpg'),
(13, 'Drache_laufend', '../Bilder/Drache_laeuft.gif'),
(14, 'Drache_kaempfend', '../Bilder/Drache_kaempft.gif'),
(15, 'Drache_wartend', '../Bilder/Drache_wartet.png'),
(37, 'Adelie Pinguin', '../Bilder/NPC/Adelie pinguin.png'),
(38, 'Gürteltier', '../Bilder/NPC/Gürteltier.png'),
(39, 'Hase', '../Bilder/NPC/Hase.png'),
(40, 'Hibiskus', '../Bilder/NPC/Hibiskus.png'),
(41, 'Alligator', '../Bilder/NPC/alligator.png'),
(42, 'Alpaca', '../Bilder/NPC/alpaca.png'),
(43, 'Ara', '../Bilder/NPC/ara.png'),
(44, 'Bambus', '../Bilder/NPC/bambus.png'),
(45, 'Eisbär', '../Bilder/NPC/eisbär.png'),
(46, 'Erdmännchen', '../Bilder/NPC/erdmännchen.png'),
(47, 'Frettchen', '../Bilder/NPC/frettchen.png'),
(48, 'Hahn', '../Bilder/NPC/hahn.png'),
(49, 'Hyäne', '../Bilder/NPC/hyäne.png'),
(57, 'AlterDrache', '../Bilder/AlterDrache.png'),
(58, 'AusgewachsenerDrache', '../Bilder/AusgewachsenerDrache.png'),
(59, 'Babydrache', '../Bilder/Babydrache.png'),
(60, 'Deckblatt', '../Bilder/Deckblatt.png'),
(61, 'Deckblatt_alt', '../Bilder/Deckblatt_alt.png'),
(62, 'Drachenkind', '../Bilder/Drachenkind.png'),
(63, 'Dschungel', '../Bilder/Dschungel.png'),
(64, 'Eissee', '../Bilder/Eissee.png'),
(65, 'Erdei', '../Bilder/Erdei.png'),
(66, 'Erdei_klein', '../Bilder/Erdei_klein.png'),
(67, 'ErfahrenerDrache', '../Bilder/ErfahrenerDrache.png'),
(68, 'ErwachsenerDrache', '../Bilder/ErwachsenerDrache.png'),
(69, 'Feuerei', '../Bilder/Feuerei.png'),
(70, 'Feuerei_klein', '../Bilder/Feuerei_klein.png'),
(71, 'JugendlicherDrache', '../Bilder/JugendlicherDrache.png'),
(72, 'Klippe', '../Bilder/Klippe.png'),
(73, 'Luftei', '../Bilder/Luftei.png'),
(74, 'Luftei_klein', '../Bilder/Luftei_klein.png'),
(75, 'Perlmutterfalter', '../Bilder/NPC/Perlmutterfalter.png'),
(76, 'Rohrkolben', '../Bilder/NPC/Rohrkolben.png'),
(77, 'Rotfuchs', '../Bilder/NPC/Rotfuchs.png'),
(78, 'Sonnenblume', '../Bilder/NPC/Sonnenblume.png'),
(79, 'Tiger', '../Bilder/NPC/Tiger.png'),
(80, 'Toco', '../Bilder/NPC/Toco.png'),
(81, 'Trauermantel', '../Bilder/NPC/Trauermantel.png'),
(82, 'impala', '../Bilder/NPC/impala.png'),
(83, 'jaguar', '../Bilder/NPC/jaguar.png'),
(84, 'knoblauch', '../Bilder/NPC/knoblauch.png'),
(85, 'kupfer_schmetterling', '../Bilder/NPC/kupfer_schmetterling.png'),
(86, 'kürbis', '../Bilder/NPC/kürbis.png'),
(87, 'luchs', '../Bilder/NPC/luchs.png'),
(88, 'löwe', '../Bilder/NPC/löwe.png'),
(89, 'mandrill', '../Bilder/NPC/mandrill.png'),
(90, 'marienkäfer', '../Bilder/NPC/marienkäfer.png'),
(91, 'panda', '../Bilder/NPC/panda.png'),
(92, 'papageitaucher', '../Bilder/NPC/papageitaucher.png'),
(93, 'schwein', '../Bilder/NPC/schwein.png'),
(94, 'scorpion', '../Bilder/NPC/scorpion.png'),
(95, 'steinpilzis', '../Bilder/NPC/steinpilzis.png'),
(96, 'stockente', '../Bilder/NPC/stockente.png'),
(97, 'tapir', '../Bilder/NPC/tapir.png'),
(98, 'wallaby', '../Bilder/NPC/wallaby.png'),
(99, 'wolf', '../Bilder/NPC/wolf.png'),
(100, 'Vulkan', '../Bilder/Vulkan.png'),
(101, 'Wasserei', '../Bilder/Wasserei.png'),
(102, 'Wasserei_klein', '../Bilder/Wasserei_klein.png'),
(103, 'buchstabe_E', '../Bilder/buchstabe_E.png'),
(104, 'buchstabe_F', '../Bilder/buchstabe_F.png'),
(105, 'buchstabe_L', '../Bilder/buchstabe_L.png'),
(106, 'buchstabe_W', '../Bilder/buchstabe_W.png'),
(107, 'erde', '../Bilder/erde.png'),
(108, 'feuer', '../Bilder/feuer.png'),
(109, 'feuerbutton', '../Bilder/feuerbutton.png'),
(110, 'flugbutton', '../Bilder/flugbutton.png'),
(111, 'jagenbutton', '../Bilder/jagenbutton.png'),
(112, 'luft', '../Bilder/luft.png'),
(113, 'pflanzenbutton', '../Bilder/pflanzenbutton.png'),
(114, 'wasser', '../Bilder/wasser.png'),
(115, 'zahl_1', '../Bilder/zahl_1.png'),
(116, 'zahl_2', '../Bilder/zahl_2.png'),
(117, 'zahl_3', '../Bilder/zahl_3.png'),
(118, 'zahl_4', '../Bilder/zahl_4.png'),
(119, 'zahl_5', '../Bilder/zahl_5.png'),
(120, 'zahl_6', '../Bilder/zahl_6.png'),
(121, 'zahl_7', '../Bilder/zahl_7.png'),
(122, 'Alkohol', '../Bilder/Elemente/Alkohol.png'),
(123, 'Asche', '../Bilder/Elemente/Asche.png'),
(124, 'Asteroid', '../Bilder/Elemente/Asteroid.png'),
(125, 'Blitz', '../Bilder/Elemente/Blitz.png'),
(126, 'Boee', '../Bilder/Elemente/Boee.png'),
(127, 'Donner', '../Bilder/Elemente/Donner.png'),
(128, 'Eissturm', '../Bilder/Elemente/Eissturm.png'),
(129, 'Erdbeben', '../Bilder/Elemente/Erdbeben.png'),
(130, 'Erdgas', '../Bilder/Elemente/Erdgas.png'),
(131, 'Erdloch', '../Bilder/Elemente/Erdloch.png'),
(132, 'Erdoel', '../Bilder/Elemente/Erdoel.png'),
(133, 'Explosion', '../Bilder/Elemente/Explosion.png'),
(134, 'Feueratem', '../Bilder/Elemente/Feueratem.png'),
(135, 'Feuerball', '../Bilder/Elemente/Feuerball.png'),
(136, 'Feuerschneisse', '../Bilder/Elemente/Feuerschneisse.png'),
(137, 'Feuersturm', '../Bilder/Elemente/Feuersturm.png'),
(138, 'Flamme', '../Bilder/Elemente/Flamme.png'),
(139, 'Funke', '../Bilder/Elemente/Funke.png'),
(140, 'Funkenflug', '../Bilder/Elemente/Funkenflug.png'),
(141, 'Geroelllawine', '../Bilder/Elemente/Geroelllawine.png'),
(142, 'Gewitter', '../Bilder/Elemente/Gewitter.png'),
(143, 'Geysir', '../Bilder/Elemente/Geysir.png'),
(144, 'Gift', '../Bilder/Elemente/Gift.png'),
(145, 'Glas', '../Bilder/Elemente/Glas.png'),
(146, 'Gletscher', '../Bilder/Elemente/Gletscher.png'),
(147, 'Glut', '../Bilder/Elemente/Glut.png'),
(148, 'Hauch', '../Bilder/Elemente/Hauch.png'),
(149, 'Heisse_Quelle', '../Bilder/Elemente/Heisse_Quelle.png'),
(150, 'Hurrikan', '../Bilder/Elemente/Hurrikan.png'),
(151, 'Keramik', '../Bilder/Elemente/Keramik.png'),
(152, 'Kohle', '../Bilder/Elemente/Kohle.png'),
(153, 'Korrosion', '../Bilder/Elemente/Korrosion.png'),
(154, 'Kreide', '../Bilder/Elemente/Kreide.png'),
(155, 'Kristall', '../Bilder/Elemente/Kristall.png'),
(156, 'Lava', '../Bilder/Elemente/Lava.png'),
(157, 'Lehm', '../Bilder/Elemente/Lehm.png'),
(158, 'Lichtstrahl', '../Bilder/Elemente/Lichtstrahl.png'),
(159, 'Lichtwelle', '../Bilder/Elemente/Lichtwelle.png'),
(160, 'Magma', '../Bilder/Elemente/Magma.png'),
(161, 'Metall', '../Bilder/Elemente/Metall.png'),
(162, 'Monsun', '../Bilder/Elemente/Monsun.png'),
(163, 'Moraene', '../Bilder/Elemente/Moraene.png'),
(164, 'Nebel', '../Bilder/Elemente/Nebel.png'),
(165, 'Oelbrand', '../Bilder/Elemente/Oelbrand.png'),
(166, 'Orkan', '../Bilder/Elemente/Orkan.png'),
(167, 'Regenbogen', '../Bilder/Elemente/Regenbogen.png'),
(168, 'Regenschauer', '../Bilder/Elemente/Regenschauer.png'),
(169, 'Saeure', '../Bilder/Elemente/Saeure.png'),
(170, 'Salzwasser', '../Bilder/Elemente/Salzwasser.png'),
(171, 'Sandhoehle', '../Bilder/Elemente/Sandhoehle.png'),
(172, 'Sandstein', '../Bilder/Elemente/Sandstein.png'),
(173, 'Sandsturm', '../Bilder/Elemente/Sandsturm.png'),
(174, 'Schlamm', '../Bilder/Elemente/Schlamm.png'),
(175, 'Schneesturm', '../Bilder/Elemente/Schneesturm.png'),
(176, 'Schwelbrand', '../Bilder/Elemente/Schwelbrand.png'),
(177, 'Smog', '../Bilder/Elemente/Smog.png'),
(178, 'Staub', '../Bilder/Elemente/Staub.png'),
(179, 'Steinschlag', '../Bilder/Elemente/Steinschlag.png'),
(180, 'Ton', '../Bilder/Elemente/Ton.png'),
(181, 'Tornado', '../Bilder/Elemente/Tornado.png'),
(182, 'Treibsand', '../Bilder/Elemente/Treibsand.png'),
(183, 'Tropfstein', '../Bilder/Elemente/Tropfstein.png'),
(184, 'Tsunami', '../Bilder/Elemente/Tsunami.png'),
(185, 'Wanderduene', '../Bilder/Elemente/Wanderduene.png'),
(186, 'Wasserdampf', '../Bilder/Elemente/Wasserdampf.png'),
(187, 'Wasserfall', '../Bilder/Elemente/Wasserfall.png'),
(188, 'Wasserstrahl', '../Bilder/Elemente/Wasserstrahl.png'),
(189, 'Wasserwelle', '../Bilder/Elemente/Wasserwelle.png'),
(190, 'Windhose', '../Bilder/Elemente/Windhose.png'),
(191, 'Windstoss', '../Bilder/Elemente/Windstoss.png'),
(192, 'Wolke', '../Bilder/Elemente/Wolke.png'),
(193, 'Wuestenwind', '../Bilder/Elemente/Wuestenwind.png'),
(194, 'elementbutton', '../Bilder/elementbutton.png'),
(195, 'erdelemente', '../Bilder/erdelemente.png'),
(196, 'erkundenbutton', '../Bilder/erkundenbutton.png'),
(197, 'feuerelemente', '../Bilder/feuerelemente.png'),
(198, 'gepäckbutton', '../Bilder/gepäckbutton.png'),
(199, 'luftelemente', '../Bilder/luftelemente.png'),
(200, 'wasserelemente', '../Bilder/wasserelemente.png'),
(201, 'Tagebuch', '../Bilder/Tagebuch.png'),
(202, 'gepaeckbutton', '../Bilder/gepaeckbutton.png'),
(203, 'kap_o', '../Bilder/kap_o.png'),
(204, 'kap_u', '../Bilder/kap_u.png'),
(205, 'streifen', '../Bilder/streifen.png'),
(207, 'Osterei_gruen', '../Bilder/NPC/Osterei_gruen.png'),
(208, 'Osterei_lila', '../Bilder/NPC/Osterei_lila.png');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle möglichen Elemente';

--
-- Daten für Tabelle `element`
--

INSERT INTO `element` (`id`, `element_id`, `bilder_id`, `titel`, `beschreibung`) VALUES
(1, 0, 1, '---ohne---', ''),
(2, 0, 1, 'Feuer', ''),
(3, 0, 1, 'Wasser', ''),
(4, 0, 1, 'Erde', ''),
(5, 0, 1, 'Luft', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle möglichen Fähigkeiten';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fähigkeiten die die einzelnen Spieler besitzen bzw. beherrschen';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Gattungen aller möglichen Lebewesen';

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
-- Tabellenstruktur für Tabelle `gebiet`
--

DROP TABLE IF EXISTS `gebiet`;
CREATE TABLE `gebiet` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Vorhandene Gebiete';

--
-- Daten für Tabelle `gebiet`
--

INSERT INTO `gebiet` (`id`, `bilder_id`, `titel`, `beschreibung`) VALUES
(1, 2, 'Sumpf', 'Nass, schmutzig und schauerlich. Ein Sumpf eben!'),
(2, 3, 'Vulkan', 'Das feurige Herz von Gaia. Vorsicht heiß!'),
(3, 4, 'Eissee', 'Ein schöner großer See lädt euch zum Baden gehen ein. An die Hacke, fertig, los!'),
(4, 5, 'Dschungel', 'Gestrüpp soweit das Auge reicht. Was hier alles kreucht und fleucht, wollt ihr euch gar nicht erst ausmalen.'),
(5, 6, 'Klippe', 'Ihr schaut in den Abgrund und sogleich juckt es euch in den Flügeln. Traut euch!'),
(6, 7, 'Kristallhoehle', 'Funkelnde Steine weit und breit. Ein wahres Paradies ... wenn man es kalt, muffig und feucht mag.'),
(7, 8, 'Wueste', 'Naaaa, wollt ihr etwas zu trinken? Hier ganz sicher nicht!'),
(8, 9, 'Mammutbaum', 'Ein mächtiger Stamm, gewaltiges Blattwerk und die schier endlose Höhe lassen auf einen Mammutbaum schließen.'),
(9, 10, 'Wald', 'Manchereins sieht den Wald vor lauter Bäumen nicht. Hinweis: Ihr steht gerade in einem!'),
(10, 11, 'Oase', 'Träumt ihr oder halluziniert ihr nur? Wasser und Grün mitten in der Wüste. Das kann doch nicht mit rechten Dingen zugehen.'),
(11, 12, 'Steppe', 'Gras überall Gras. Ihr schlagt die Hände über dem Kopf zusammen und denkt: ''Wenn man es wenigstens rauchen könnte ...'''),
(12, 1, '---ohne---', '---ohne---');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gebiet_gebiet`
--

DROP TABLE IF EXISTS `gebiet_gebiet`;
CREATE TABLE `gebiet_gebiet` (
  `id` int(10) NOT NULL,
  `von_gebiet_id` int(10) NOT NULL,
  `nach_gebiet_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Verbindungen von Gebieten untereinander';

--
-- Daten für Tabelle `gebiet_gebiet`
--

INSERT INTO `gebiet_gebiet` (`id`, `von_gebiet_id`, `nach_gebiet_id`) VALUES
(1, 1, 4),
(2, 1, 9),
(3, 2, 5),
(4, 2, 6),
(5, 3, 5),
(6, 3, 6),
(7, 4, 1),
(8, 4, 8),
(9, 4, 11),
(10, 5, 2),
(11, 5, 3),
(12, 5, 7),
(13, 6, 2),
(14, 6, 3),
(15, 6, 9),
(16, 7, 5),
(17, 7, 10),
(18, 7, 11),
(19, 8, 4),
(20, 8, 9),
(21, 9, 1),
(22, 9, 6),
(23, 9, 8),
(24, 10, 7),
(25, 11, 4),
(26, 11, 7);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(10) NOT NULL,
  `titel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
  `typ` enum('Pflanze','Pilz','Werkzeug','Kleidung','Material','Nahrung','Spielzeug') COLLATE utf8_unicode_ci NOT NULL,
  `bilder_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle möglichen Items';

--
-- Daten für Tabelle `items`
--

INSERT INTO `items` (`id`, `titel`, `beschreibung`, `typ`, `bilder_id`) VALUES
(1, 'Apfel', 'Eine Hälfte knallrot, die andere goldgelb. Ein klassischer Apfel.', 'Pflanze', 1),
(2, 'Rinde', 'Die äußere Schale eines Apfelbaumes. Wozu die wohl gut ist?', 'Material', 1),
(3, 'Knoblauchlauchknolle', 'Ein Leckerbissen für die die''s wissen.', 'Pflanze', 1),
(4, 'Fuchsfell', 'Ein rotes Fell von einem Fuchs. Schön flauschig!', 'Material', 1),
(5, 'Steinpilz', 'Ein schöner Pilz. Hoffentlich ist er nicht so hart, wie der Name es verspricht.', 'Pilz', 1),
(6, 'Ring des Feuers', 'Ein funkelnder Ring in dem kleine Flammen züngeln. Passt wunderbar an eine Drachenklaue.', 'Kleidung', 1),
(7, 'Messer', 'Ein kleines scharfes Messer. Nützlich um so allerlei Dinge zu ernten, zu teilen, zu filetieren oder was man sonst noch damit anstellen kann. Auf jeden Fall präziser als eine klobige Drachenkralle.', 'Werkzeug', 1),
(8, 'Knochen', 'Grau, staubig, schaurig ... ein Knochen wie er im Buche steht.', 'Material', 1),
(9, 'Schokoladendrache', 'Süßer Drache zum Vernaschen', 'Nahrung', 1),
(10, 'Drachenfigur', 'Nicht zum Verzehr geeignet!', 'Spielzeug', 1),
(11, 'Plüschdrache', 'Einfach zum Knuddeln ... für einsame Stunden', 'Spielzeug', 1),
(12, 'Drachenpuzzleteil 1', 'Das 1. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(13, 'Drachenpuzzleteil 2', 'Das 2. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(14, 'Drachenpuzzleteil 3', 'Das 3. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(15, 'Drachenpuzzleteil 4', 'Das 4. Teil des 4-Teile Puzzles', 'Spielzeug', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items_spieler`
--

DROP TABLE IF EXISTS `items_spieler`;
CREATE TABLE `items_spieler` (
  `id` int(10) NOT NULL,
  `items_id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `anzahl` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Items die ein Spieler im Besitz hat';

--
-- Daten für Tabelle `items_spieler`
--

INSERT INTO `items_spieler` (`id`, `items_id`, `spieler_id`, `anzahl`) VALUES
(1, 3, 26, 4),
(2, 6, 26, 1),
(3, 7, 26, 2),
(4, 1, 19, 4),
(5, 2, 19, 1),
(6, 8, 19, 1),
(7, 5, 14, 1),
(8, 8, 26, 1),
(9, 5, 26, 5),
(10, 11, 26, 2),
(11, 1, 26, 3),
(12, 2, 26, 1),
(13, 14, 26, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Level und die einzelnen Voraussetzungen zum Erreichen des Levels';

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
  `familie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `staerke` float NOT NULL,
  `intelligenz` float NOT NULL,
  `magie` float NOT NULL,
  `element_feuer` float NOT NULL,
  `element_wasser` float NOT NULL,
  `element_erde` float NOT NULL,
  `element_luft` float NOT NULL,
  `gesundheit` int(10) NOT NULL,
  `energie` int(10) NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
  `typ` enum('angreifbar','sammelbar','ansprechbar','') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle möglichen Objekten (Tiere, Pflanzen, Personen, usw.)';

--
-- Daten für Tabelle `npc`
--

INSERT INTO `npc` (`id`, `bilder_id`, `element_id`, `titel`, `familie`, `staerke`, `intelligenz`, `magie`, `element_feuer`, `element_wasser`, `element_erde`, `element_luft`, `gesundheit`, `energie`, `beschreibung`, `typ`) VALUES
(1, 1, 2, 'Wymar (Name von der Redaktion geändert)', 'Drache', 75, 100, 50, 100, 20, 20, 20, 1000, 100, 'Wymar ist einer der ältesten bekannten Drachen und wird für seine Weisheit hoch geschätzt. Ihr tut gut daran, seinen Ratschlägen aufs genauste zu folgen.', 'ansprechbar'),
(2, 1, 4, 'Ratte', 'Nager', 3, 3, 0, 0, 5, 5, 0, 15, 10, 'Eklige Biester! Entweder kreischend davonrennen und den erstbesten Kammerjäger um Hilfe bitten oder einfach selbst Hand anlegen. ', 'angreifbar'),
(3, 1, 1, 'Zayinenkrieger', 'Zayine', 50, 50, 40, 25, 25, 25, 25, 1000, 750, 'Einen Krieger der Zayinen. Am besten ihr schleicht euch ungesehen an ihm vorbei, denn schon auf den ersten Blick könnt ihr erkennen, dass mit ihm nicht gut Kirschen essen sein wird.', 'angreifbar'),
(4, 77, 4, 'Fuchs', 'Fuchs', 10, 10, 0, 0, 2, 10, 5, 35, 50, 'Ein Fuchs, kräftig gebaut, jedoch scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er auf den ersten Blick ganz niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.', 'angreifbar'),
(5, 77, 4, 'Junger Fuchs', 'Fuchs', 5, 5, 0, 0, 1, 5, 3, 25, 40, 'Ein Fuchs, relativ klein, scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er klein und niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.', 'angreifbar'),
(6, 1, 3, 'Apfelbaum', 'Pflanze', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Ein stattlicher Apfelbaum mit einer Menge Äpfeln in der Krone. Verlockend!', 'sammelbar'),
(7, 95, 4, 'Steinpilz', 'Pilz', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Ein Pilz mit breitem Stiel und einem hellbraunen Hut. Das müsste ein Steinpilz sein.', 'sammelbar'),
(8, 84, 4, 'Knoblauch', 'Pflanze', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Eine schöne Knoblauchpflanze. Der unterirdische Teil ist ein wahrer Gaumenschmaus für Kenner. Der oberirdische Teil hingegen ist ein Haufen unnützes Grünzeug.', 'sammelbar'),
(10, 45, 3, 'Eisbär', 'Bär', 100, 75, 0, 0, 20, 10, 0, 500, 500, 'Ein kräftiger Bär mit großen Pranken und einem schneeweißen Pelz. Da könnte man sich bestimmt schön hinein kuscheln, wenn er nicht so wehrhaft wäre.', 'angreifbar'),
(11, 91, 4, 'Großer Panda', 'Bär', 10, 5, 100, 0, 0, 20, 5, 50, 20, 'Ein großer knuddeliger Bär mit traurigen schwarzen Augen. Er knabbert gemütlich an einem Bambuszweig.', 'angreifbar'),
(12, 208, 5, 'Osterei lila', 'Ei', 0, 0, 1, 0, 0, 0, 1, 1, 1, 'Nicht ganz rund aber trotzdem perfekt geformt. Ein tolles Souvenir.', 'sammelbar'),
(14, 207, 4, 'Osterei grün', 'Ei', 0, 0, 1, 0, 0, 1, 0, 1, 1, 'Nicht ganz rund aber trotzdem perfekt geformt. Ein tolles Souvenir.', 'sammelbar');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `npc_gebiet`
--

DROP TABLE IF EXISTS `npc_gebiet`;
CREATE TABLE `npc_gebiet` (
  `id` int(10) NOT NULL,
  `npc_id` int(10) NOT NULL,
  `gebiet_id` int(10) NOT NULL,
  `wahrscheinlichkeit` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zuordnung von NPCs zu den Gebieten mit individueller Wahrscheinlichkeit zum Gebiet';

--
-- Daten für Tabelle `npc_gebiet`
--

INSERT INTO `npc_gebiet` (`id`, `npc_id`, `gebiet_id`, `wahrscheinlichkeit`) VALUES
(1, 2, 1, 50),
(2, 2, 2, 50),
(3, 2, 3, 50),
(4, 2, 4, 50),
(6, 2, 6, 50),
(7, 2, 7, 50),
(8, 2, 8, 50),
(9, 2, 9, 50),
(10, 2, 10, 50),
(11, 2, 11, 50),
(12, 1, 5, 100),
(13, 4, 4, 40),
(14, 5, 4, 10),
(15, 4, 7, 40),
(16, 5, 7, 10),
(17, 4, 9, 40),
(18, 5, 9, 10),
(19, 4, 11, 40),
(20, 5, 11, 10),
(21, 6, 1, 30),
(22, 6, 4, 30),
(23, 6, 5, 30),
(24, 6, 8, 10),
(25, 6, 9, 70),
(26, 6, 10, 50),
(27, 6, 11, 20),
(28, 7, 2, 20),
(29, 7, 4, 50),
(30, 7, 5, 25),
(31, 7, 6, 80),
(32, 7, 8, 35),
(33, 7, 9, 80),
(34, 8, 5, 100),
(43, 10, 3, 30),
(44, 11, 4, 50),
(78, 12, 4, 10),
(79, 12, 3, 10),
(80, 12, 5, 10),
(81, 12, 6, 10),
(82, 12, 8, 10),
(83, 12, 10, 10),
(84, 12, 11, 10),
(85, 12, 2, 10),
(86, 12, 1, 10),
(87, 12, 9, 10),
(88, 12, 7, 10),
(100, 14, 4, 10),
(101, 14, 3, 10),
(102, 14, 5, 10),
(103, 14, 6, 10),
(104, 14, 8, 10),
(105, 14, 10, 10),
(106, 14, 11, 10),
(107, 14, 1, 10),
(108, 14, 2, 10),
(109, 14, 9, 10),
(110, 14, 7, 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `npc_items`
--

DROP TABLE IF EXISTS `npc_items`;
CREATE TABLE `npc_items` (
  `id` int(10) NOT NULL,
  `npc_id` int(10) NOT NULL,
  `items_id` int(10) NOT NULL,
  `wahrscheinlichkeit` double UNSIGNED NOT NULL,
  `anzahl_min` int(10) UNSIGNED NOT NULL,
  `anzahl_max` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zuordnung von Items, die ein NPC erzeugen kann (durch töten, ernten, ansprechen, u.ä.)';

--
-- Daten für Tabelle `npc_items`
--

INSERT INTO `npc_items` (`id`, `npc_id`, `items_id`, `wahrscheinlichkeit`, `anzahl_min`, `anzahl_max`) VALUES
(1, 6, 1, 100, 1, 3),
(2, 6, 2, 25, 1, 1),
(3, 7, 5, 100, 1, 1),
(4, 8, 3, 50, 1, 1),
(5, 5, 8, 75, 1, 2),
(6, 5, 4, 10, 1, 1),
(7, 4, 8, 85, 2, 3),
(8, 4, 4, 75, 1, 1),
(9, 4, 6, 5, 1, 1),
(10, 2, 1, 45, 1, 1),
(11, 2, 7, 10, 1, 1),
(12, 2, 8, 50, 1, 1),
(14, 10, 7, 10, 0, 1),
(15, 11, 2, 10, 0, 3),
(28, 12, 10, 10, 1, 1),
(29, 12, 12, 10, 1, 1),
(30, 12, 13, 10, 1, 1),
(31, 12, 14, 10, 1, 1),
(32, 12, 15, 10, 1, 1),
(33, 12, 11, 5, 1, 1),
(40, 14, 10, 10, 1, 1),
(41, 14, 12, 10, 1, 1),
(42, 14, 13, 10, 1, 1),
(43, 14, 14, 10, 1, 1),
(44, 14, 15, 10, 1, 1),
(45, 14, 11, 5, 1, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle möglichen vorhandenen Quests';

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
  `status` enum('gestartet','beendet') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'gestartet',
  `start` timestamp NULL DEFAULT NULL,
  `ende` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zuordnung von Quests zu Spieler zum Speichern des Status einer Quest und bereits abgeschlossener Quests';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle vorhandenen Spieler mit Zuordnung zu den jeweiligen Accounts';

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
(17, 17, 1, 1, 1, 7, 'Wüstenfuchs', 'M', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2017-03-12 20:27:17'),
(18, 17, 1, 4, 1, 8, 'Rosaroter Panter', 'M', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2016-12-18 16:14:44'),
(19, 11, 1, 3, 7, 1, 'Shizophrenia', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-28 18:45:59'),
(26, 10, 1, 2, 1, 4, 'Rashiel', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2018-03-17 16:38:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zauber`
--

DROP TABLE IF EXISTS `zauber`;
CREATE TABLE `zauber` (
  `id` int(10) NOT NULL,
  `element_id` int(10) NOT NULL COMMENT 'Zuordnung zu Element',
  `bilder_id` int(10) NOT NULL,
  `zauberart_id` int(10) NOT NULL COMMENT 'aktiv/passiv, defensiv/offensiv',
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titel_erweitert` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `voraussetzung` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `verbrauch` int(10) NOT NULL,
  `effekt` float NOT NULL COMMENT 'Grundwert für Angriff/Verteidigung und sonstige Effekte',
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zauber die ein Spieler wirken kann (z.B. im Kampf oder beim Erkunden)';

--
-- Daten für Tabelle `zauber`
--

INSERT INTO `zauber` (`id`, `element_id`, `bilder_id`, `zauberart_id`, `titel`, `titel_erweitert`, `voraussetzung`, `verbrauch`, `effekt`, `beschreibung`) VALUES
(1, 2, 1, 1, 'Feuerball', '', '', 1, 10, ''),
(2, 3, 1, 2, 'Heilung', '', '', 1, -10, ''),
(3, 4, 1, 6, 'Lehm', '', '', 1, 1, ''),
(4, 4, 1, 5, 'Schnelligkeit', '', '', 1, 1, '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details zu Zaubern um verschiedenste Wirkungen abbilden zu können.';

--
-- Daten für Tabelle `zauberart`
--

INSERT INTO `zauberart` (`id`, `titel`, `modifikator`, `beschreibung`) VALUES
(1, 'Offensiv', 0, ''),
(2, 'Defnsiv', 0, ''),
(5, 'Aktiv', 0, ''),
(6, 'Passiv', 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zauber_spieler`
--

DROP TABLE IF EXISTS `zauber_spieler`;
CREATE TABLE `zauber_spieler` (
  `id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `zauber_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Zuordnung Zauber zu Spieler';

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
-- Indizes für die Tabelle `aktion`
--
ALTER TABLE `aktion`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `aktion_spieler`
--
ALTER TABLE `aktion_spieler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_spieler_id_aktion_spieler` (`spieler_id`),
  ADD KEY `FK_aktion_id_aktion_spieler` (`aktion_id`);

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
  ADD UNIQUE KEY `titel` (`titel`),
  ADD KEY `FK_bilder_id_element` (`bilder_id`);

--
-- Indizes für die Tabelle `faehigkeiten`
--
ALTER TABLE `faehigkeiten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`),
  ADD KEY `FK_bilder_id_faehigkeiten` (`bilder_id`);

--
-- Indizes für die Tabelle `faehigkeiten_spieler`
--
ALTER TABLE `faehigkeiten_spieler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_faehigkeiten_id_faehigkeiten_spieler` (`faehigkeiten_id`),
  ADD KEY `FK_spieler_id_faehigkeiten_spieler` (`spieler_id`);

--
-- Indizes für die Tabelle `gattung`
--
ALTER TABLE `gattung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`),
  ADD KEY `FK_bilder_id_gattung` (`bilder_id`);

--
-- Indizes für die Tabelle `gebiet`
--
ALTER TABLE `gebiet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`),
  ADD KEY `FK_bilder_id_gebiet` (`bilder_id`);

--
-- Indizes für die Tabelle `gebiet_gebiet`
--
ALTER TABLE `gebiet_gebiet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_gebiet_id_(von)_gebiet_gebiet` (`von_gebiet_id`),
  ADD KEY `FK_gebiet_id_(nach)_gebiet_gebiet` (`nach_gebiet_id`);

--
-- Indizes für die Tabelle `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_bilder_id_items` (`bilder_id`);

--
-- Indizes für die Tabelle `items_spieler`
--
ALTER TABLE `items_spieler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_items_id_items_spieler` (`items_id`),
  ADD KEY `FK_spieler_id_items_spieler` (`spieler_id`);

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
  ADD UNIQUE KEY `titel` (`titel`),
  ADD KEY `FK_bilder_id_npc` (`bilder_id`),
  ADD KEY `FK_element_id_npc` (`element_id`);

--
-- Indizes für die Tabelle `npc_gebiet`
--
ALTER TABLE `npc_gebiet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_npc_id_npc_gebiet` (`npc_id`),
  ADD KEY `FK_gebiet_id_npc_gebiet` (`gebiet_id`);

--
-- Indizes für die Tabelle `npc_items`
--
ALTER TABLE `npc_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_items_id_npc_items` (`items_id`),
  ADD KEY `FK_npc_id_npc_items` (`npc_id`);

--
-- Indizes für die Tabelle `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_bilder_id_quest` (`bilder_id`);

--
-- Indizes für die Tabelle `quest_spieler`
--
ALTER TABLE `quest_spieler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_quest_id_quest_spieler` (`quest_id`),
  ADD KEY `FK_spieler_id_quest_spieler` (`spieler_id`);

--
-- Indizes für die Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_account_id_spieler` (`account_id`),
  ADD KEY `FK_bilder_id_spieler` (`bilder_id`),
  ADD KEY `FK_gattung_id_spieler` (`gattung_id`),
  ADD KEY `FK_level_id_spieler` (`level_id`),
  ADD KEY `FK_gebiet_id_spieler` (`gebiet_id`);

--
-- Indizes für die Tabelle `zauber`
--
ALTER TABLE `zauber`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`),
  ADD KEY `FK_element_id_zauber` (`element_id`),
  ADD KEY `FK_bilder_id_zauber` (`bilder_id`),
  ADD KEY `FK_zauberart_id_zauber` (`zauberart_id`);

--
-- Indizes für die Tabelle `zauberart`
--
ALTER TABLE `zauberart`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `zauber_spieler`
--
ALTER TABLE `zauber_spieler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_spieler_id_zauber_spieler` (`spieler_id`),
  ADD KEY `FK_zauber_id_zauber_spieler` (`zauber_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT für Tabelle `aktion`
--
ALTER TABLE `aktion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `aktion_spieler`
--
ALTER TABLE `aktion_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT für Tabelle `bilder`
--
ALTER TABLE `bilder`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;
--
-- AUTO_INCREMENT für Tabelle `element`
--
ALTER TABLE `element`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `faehigkeiten`
--
ALTER TABLE `faehigkeiten`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `faehigkeiten_spieler`
--
ALTER TABLE `faehigkeiten_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `gattung`
--
ALTER TABLE `gattung`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `gebiet`
--
ALTER TABLE `gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `gebiet_gebiet`
--
ALTER TABLE `gebiet_gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT für Tabelle `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT für Tabelle `items_spieler`
--
ALTER TABLE `items_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT für Tabelle `level`
--
ALTER TABLE `level`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `npc`
--
ALTER TABLE `npc`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT für Tabelle `npc_gebiet`
--
ALTER TABLE `npc_gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT für Tabelle `npc_items`
--
ALTER TABLE `npc_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT für Tabelle `quest`
--
ALTER TABLE `quest`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `quest_spieler`
--
ALTER TABLE `quest_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `spieler`
--
ALTER TABLE `spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
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
--
-- AUTO_INCREMENT für Tabelle `zauber_spieler`
--
ALTER TABLE `zauber_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `aktion_spieler`
--
ALTER TABLE `aktion_spieler`
  ADD CONSTRAINT `FK_aktion_id_aktion_spieler` FOREIGN KEY (`aktion_id`) REFERENCES `aktion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_spieler_id_aktion_spieler` FOREIGN KEY (`spieler_id`) REFERENCES `spieler` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `element`
--
ALTER TABLE `element`
  ADD CONSTRAINT `FK_bilder_id_element` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`);

--
-- Constraints der Tabelle `faehigkeiten`
--
ALTER TABLE `faehigkeiten`
  ADD CONSTRAINT `FK_bilder_id_faehigkeiten` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`);

--
-- Constraints der Tabelle `faehigkeiten_spieler`
--
ALTER TABLE `faehigkeiten_spieler`
  ADD CONSTRAINT `FK_faehigkeiten_id_faehigkeiten_spieler` FOREIGN KEY (`faehigkeiten_id`) REFERENCES `faehigkeiten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_spieler_id_faehigkeiten_spieler` FOREIGN KEY (`spieler_id`) REFERENCES `spieler` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `gattung`
--
ALTER TABLE `gattung`
  ADD CONSTRAINT `FK_bilder_id_gattung` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`);

--
-- Constraints der Tabelle `gebiet`
--
ALTER TABLE `gebiet`
  ADD CONSTRAINT `FK_bilder_id_gebiet` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`);

--
-- Constraints der Tabelle `gebiet_gebiet`
--
ALTER TABLE `gebiet_gebiet`
  ADD CONSTRAINT `FK_gebiet_id_(nach)_gebiet_gebiet` FOREIGN KEY (`nach_gebiet_id`) REFERENCES `gebiet` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_gebiet_id_(von)_gebiet_gebiet` FOREIGN KEY (`von_gebiet_id`) REFERENCES `gebiet` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `FK_bilder_id_items` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`);

--
-- Constraints der Tabelle `items_spieler`
--
ALTER TABLE `items_spieler`
  ADD CONSTRAINT `FK_items_id_items_spieler` FOREIGN KEY (`items_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_spieler_id_items_spieler` FOREIGN KEY (`spieler_id`) REFERENCES `spieler` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `npc`
--
ALTER TABLE `npc`
  ADD CONSTRAINT `FK_bilder_id_npc` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`),
  ADD CONSTRAINT `FK_element_id_npc` FOREIGN KEY (`element_id`) REFERENCES `element` (`id`);

--
-- Constraints der Tabelle `npc_gebiet`
--
ALTER TABLE `npc_gebiet`
  ADD CONSTRAINT `FK_gebiet_id_npc_gebiet` FOREIGN KEY (`gebiet_id`) REFERENCES `gebiet` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_npc_id_npc_gebiet` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `npc_items`
--
ALTER TABLE `npc_items`
  ADD CONSTRAINT `FK_items_id_npc_items` FOREIGN KEY (`items_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_npc_id_npc_items` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `quest`
--
ALTER TABLE `quest`
  ADD CONSTRAINT `FK_bilder_id_quest` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`);

--
-- Constraints der Tabelle `quest_spieler`
--
ALTER TABLE `quest_spieler`
  ADD CONSTRAINT `FK_quest_id_quest_spieler` FOREIGN KEY (`quest_id`) REFERENCES `quest` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_spieler_id_quest_spieler` FOREIGN KEY (`spieler_id`) REFERENCES `spieler` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD CONSTRAINT `FK_account_id_spieler` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_bilder_id_spieler` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`),
  ADD CONSTRAINT `FK_gattung_id_spieler` FOREIGN KEY (`gattung_id`) REFERENCES `gattung` (`id`),
  ADD CONSTRAINT `FK_gebiet_id_spieler` FOREIGN KEY (`gebiet_id`) REFERENCES `gebiet` (`id`),
  ADD CONSTRAINT `FK_level_id_spieler` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`);

--
-- Constraints der Tabelle `zauber`
--
ALTER TABLE `zauber`
  ADD CONSTRAINT `FK_bilder_id_zauber` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`),
  ADD CONSTRAINT `FK_element_id_zauber` FOREIGN KEY (`element_id`) REFERENCES `element` (`id`),
  ADD CONSTRAINT `FK_zauberart_id_zauber` FOREIGN KEY (`zauberart_id`) REFERENCES `zauberart` (`id`);

--
-- Constraints der Tabelle `zauber_spieler`
--
ALTER TABLE `zauber_spieler`
  ADD CONSTRAINT `FK_spieler_id_zauber_spieler` FOREIGN KEY (`spieler_id`) REFERENCES `spieler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_zauber_id_zauber_spieler` FOREIGN KEY (`zauber_id`) REFERENCES `zauber` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
