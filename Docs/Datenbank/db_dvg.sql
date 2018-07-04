-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 04. Jul 2018 um 21:05
-- Server-Version: 10.1.21-MariaDB
-- PHP-Version: 5.6.30

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

DROP TABLE IF EXISTS `account`, `aktion`, `aktion_spieler`, `bilder`, `element`, `faehigkeiten`, `faehigkeiten_spieler`, `gattung`, `gebiet`, `gebiet_gebiet`, `items`, `items_spieler`, `level`, `level_bilder`, `npc`, `npc_gebiet`, `npc_items`, `quest`, `quest_spieler`, `spieler`, `zauber`, `zauberart`, `zauber_spieler`;

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
(77, 26, 7, '2018-03-17 16:38:54', '2018-03-17 16:38:59', 'abgeschlossen', 7, 0),
(78, 19, 2, '2018-04-14 15:31:17', '2018-04-14 15:31:22', 'abgeschlossen', 0, 0),
(79, 19, 10, '2018-04-14 15:41:29', '2018-04-14 15:41:39', 'abgeschlossen', 9, 0),
(80, 19, 2, '2018-04-14 15:41:42', '2018-04-14 15:41:47', 'abgeschlossen', 0, 0),
(81, 19, 2, '2018-04-14 15:41:53', '2018-04-14 15:41:58', 'abgeschlossen', 0, 0),
(82, 19, 2, '2018-04-14 15:42:03', '2018-04-14 15:42:08', 'abgeschlossen', 0, 0),
(83, 19, 7, '2018-04-14 15:42:12', '2018-04-14 15:42:17', 'abgeschlossen', 6, 0),
(84, 32, 10, '2018-06-25 11:53:12', '2018-06-25 11:53:22', 'abgeschlossen', 4, 0),
(85, 32, 2, '2018-06-25 11:53:27', '2018-06-25 11:53:32', 'abgeschlossen', 0, 0),
(86, 32, 6, '2018-06-25 11:53:36', '2018-06-25 11:53:41', 'abgeschlossen', 11, 0),
(87, 26, 10, '2018-06-25 12:04:07', '2018-06-25 12:04:17', 'abgeschlossen', 11, 0),
(88, 26, 2, '2018-06-25 12:04:20', '2018-06-25 12:04:25', 'abgeschlossen', 0, 0),
(89, 26, 2, '2018-06-25 12:04:31', '2018-06-25 12:04:36', 'abgeschlossen', 0, 0),
(90, 26, 10, '2018-06-25 12:04:40', '2018-06-25 12:04:50', 'abgeschlossen', 4, 0),
(91, 26, 2, '2018-06-25 12:04:52', '2018-06-25 12:04:57', 'abgeschlossen', 0, 0),
(92, 26, 7, '2018-06-25 12:05:01', '2018-06-25 12:05:06', 'abgeschlossen', 14, 0),
(93, 26, 2, '2018-06-25 12:05:10', '2018-06-25 12:05:15', 'abgeschlossen', 0, 0),
(94, 26, 2, '2018-06-25 12:05:20', '2018-06-25 12:05:25', 'abgeschlossen', 0, 0),
(95, 26, 6, '2018-06-25 12:05:29', '2018-06-25 12:05:34', 'abgeschlossen', 24, 0),
(96, 26, 10, '2018-06-25 12:05:49', '2018-06-25 12:05:59', 'abgeschlossen', 11, 0),
(97, 26, 2, '2018-06-25 12:06:04', '2018-06-25 12:06:09', 'abgeschlossen', 0, 0),
(98, 26, 6, '2018-06-25 12:06:12', '2018-06-25 12:06:17', 'abgeschlossen', 22, 0),
(99, 26, 10, '2018-06-25 12:06:43', '2018-06-25 12:06:53', 'abgeschlossen', 4, 0),
(100, 26, 2, '2018-06-25 12:06:55', '2018-06-25 12:07:00', 'abgeschlossen', 0, 0),
(101, 26, 2, '2018-06-25 12:07:04', '2018-06-25 12:07:09', 'abgeschlossen', 0, 0),
(102, 26, 2, '2018-06-25 12:07:13', '2018-06-25 12:07:18', 'abgeschlossen', 0, 0),
(103, 26, 6, '2018-06-25 12:07:20', '2018-06-25 12:07:25', 'abgeschlossen', 11, 0),
(104, 26, 10, '2018-06-25 16:23:50', '2018-06-25 16:24:00', 'abgeschlossen', 1, 0),
(105, 26, 2, '2018-06-25 16:24:07', '2018-06-25 16:24:12', 'abgeschlossen', 0, 0),
(106, 26, 2, '2018-06-25 16:25:15', '2018-06-25 16:25:20', 'abgeschlossen', 0, 0),
(107, 26, 6, '2018-06-25 16:25:22', '2018-06-25 16:25:27', 'abgeschlossen', 27, 0),
(108, 26, 10, '2018-06-25 16:25:31', '2018-06-25 16:25:41', 'abgeschlossen', 4, 0),
(109, 26, 2, '2018-06-25 16:25:43', '2018-06-25 16:25:48', 'abgeschlossen', 0, 0),
(110, 26, 6, '2018-06-25 16:26:02', '2018-06-25 16:26:07', 'abgeschlossen', 28, 0),
(111, 26, 10, '2018-06-25 16:26:13', '2018-06-25 16:26:23', 'abgeschlossen', 11, 0),
(112, 26, 2, '2018-06-25 16:26:25', '2018-06-25 16:26:30', 'abgeschlossen', 0, 0),
(113, 26, 2, '2018-06-25 16:26:39', '2018-06-25 16:26:44', 'abgeschlossen', 0, 0),
(114, 26, 10, '2018-06-25 16:26:49', '2018-06-25 16:26:59', 'abgeschlossen', 7, 0),
(115, 26, 2, '2018-06-25 16:27:01', '2018-06-25 16:27:06', 'abgeschlossen', 0, 0),
(116, 26, 2, '2018-06-25 16:27:11', '2018-06-25 16:27:16', 'abgeschlossen', 0, 0),
(117, 26, 2, '2018-06-25 16:27:20', '2018-06-25 16:27:25', 'abgeschlossen', 0, 0),
(118, 26, 2, '2018-06-25 16:27:30', '2018-06-25 16:27:35', 'abgeschlossen', 0, 0),
(119, 26, 10, '2018-06-25 16:27:40', '2018-06-25 16:27:50', 'abgeschlossen', 10, 0),
(120, 26, 2, '2018-06-25 16:27:52', '2018-06-25 16:27:57', 'abgeschlossen', 0, 0),
(121, 26, 2, '2018-06-25 16:28:01', '2018-06-25 16:28:06', 'abgeschlossen', 0, 0),
(122, 26, 10, '2018-06-25 16:29:02', '2018-06-25 16:29:12', 'abgeschlossen', 7, 0),
(123, 26, 10, '2018-06-25 16:29:15', '2018-06-25 16:29:25', 'abgeschlossen', 5, 0),
(124, 26, 2, '2018-06-25 16:29:27', '2018-06-25 16:29:32', 'abgeschlossen', 0, 0),
(125, 26, 2, '2018-06-25 16:29:38', '2018-06-25 16:29:43', 'abgeschlossen', 0, 0),
(126, 26, 7, '2018-06-25 16:29:48', '2018-06-25 16:29:53', 'abgeschlossen', 12, 0),
(127, 26, 2, '2018-06-25 16:29:58', '2018-06-25 16:30:03', 'abgeschlossen', 0, 0),
(128, 26, 2, '2018-06-25 16:30:07', '2018-06-25 16:30:12', 'abgeschlossen', 0, 0),
(129, 26, 2, '2018-06-25 16:30:16', '2018-06-25 16:30:21', 'abgeschlossen', 0, 0),
(130, 26, 7, '2018-06-25 16:30:26', '2018-06-25 16:30:31', 'abgeschlossen', 8, 0),
(131, 26, 10, '2018-06-25 16:30:48', '2018-06-25 16:30:58', 'abgeschlossen', 2, 0),
(132, 26, 2, '2018-06-25 16:31:00', '2018-06-25 16:31:05', 'abgeschlossen', 0, 0),
(133, 26, 7, '2018-06-25 16:31:08', '2018-06-25 16:31:13', 'abgeschlossen', 14, 0),
(134, 26, 2, '2018-06-25 16:31:19', '2018-06-25 16:31:24', 'abgeschlossen', 0, 0),
(135, 26, 10, '2018-06-25 16:31:28', '2018-06-25 16:31:38', 'abgeschlossen', 6, 0),
(136, 26, 2, '2018-06-25 16:31:40', '2018-06-25 16:31:45', 'abgeschlossen', 0, 0),
(137, 26, 2, '2018-06-25 16:31:50', '2018-06-25 16:31:55', 'abgeschlossen', 0, 0),
(138, 26, 7, '2018-06-25 16:31:57', '2018-06-25 16:32:02', 'abgeschlossen', 7, 0),
(139, 26, 10, '2018-06-25 16:32:23', '2018-06-25 16:32:33', 'abgeschlossen', 9, 0),
(140, 26, 2, '2018-06-25 16:32:36', '2018-06-25 16:32:41', 'abgeschlossen', 0, 0),
(141, 26, 6, '2018-06-25 16:32:46', '2018-06-25 16:32:51', 'abgeschlossen', 23, 0),
(142, 26, 2, '2018-06-25 16:42:13', '2018-06-25 16:42:18', 'abgeschlossen', 0, 0),
(143, 26, 6, '2018-06-25 16:42:21', '2018-06-25 16:42:26', 'abgeschlossen', 2, 0),
(144, 26, 10, '2018-06-25 16:42:31', '2018-06-25 16:42:41', 'abgeschlossen', 8, 0),
(145, 26, 2, '2018-06-25 16:42:44', '2018-06-25 16:42:49', 'abgeschlossen', 0, 0),
(146, 26, 2, '2018-06-25 16:42:56', '2018-06-25 16:43:01', 'abgeschlossen', 0, 0),
(147, 26, 6, '2018-06-25 16:43:03', '2018-06-25 16:43:08', 'abgeschlossen', 2, 0),
(148, 26, 10, '2018-06-25 16:43:15', '2018-06-25 16:43:25', 'abgeschlossen', 4, 0),
(149, 26, 2, '2018-06-25 16:43:27', '2018-06-25 16:43:32', 'abgeschlossen', 0, 0),
(150, 26, 2, '2018-06-25 16:43:40', '2018-06-25 16:43:45', 'abgeschlossen', 0, 0),
(151, 26, 2, '2018-06-25 16:43:51', '2018-06-25 16:43:56', 'abgeschlossen', 0, 0),
(152, 26, 6, '2018-06-25 16:43:59', '2018-06-25 16:44:04', 'abgeschlossen', 2, 0),
(153, 26, 2, '2018-06-25 16:44:10', '2018-06-25 16:44:15', 'abgeschlossen', 0, 0),
(154, 26, 6, '2018-06-25 16:44:17', '2018-06-25 16:44:22', 'abgeschlossen', 2, 0),
(155, 36, 10, '2018-06-27 14:51:05', '2018-06-27 14:51:15', 'abgeschlossen', 2, 0),
(156, 36, 10, '2018-06-27 14:51:41', '2018-06-27 14:51:51', 'abgeschlossen', 6, 0),
(157, 36, 10, '2018-06-27 14:52:03', '2018-06-27 14:52:13', 'abgeschlossen', 9, 0),
(158, 36, 2, '2018-06-27 14:53:03', '2018-06-27 14:53:08', 'abgeschlossen', 0, 0),
(159, 36, 6, '2018-06-27 14:53:19', '2018-06-27 14:53:24', 'abgeschlossen', 23, 0),
(160, 36, 2, '2018-06-27 14:53:37', '2018-06-27 14:53:42', 'abgeschlossen', 0, 0),
(161, 36, 6, '2018-06-27 14:53:52', '2018-06-27 14:53:57', 'abgeschlossen', 2, 0),
(162, 36, 2, '2018-06-27 14:54:05', '2018-06-27 14:54:10', 'abgeschlossen', 0, 0),
(163, 36, 7, '2018-06-27 14:54:30', '2018-06-27 14:54:35', 'abgeschlossen', 32, 0),
(164, 36, 2, '2018-06-27 14:54:42', '2018-06-27 14:54:47', 'abgeschlossen', 0, 0),
(165, 36, 7, '2018-06-27 14:54:57', '2018-06-27 14:55:02', 'abgeschlossen', 31, 0),
(166, 36, 2, '2018-06-27 14:55:08', '2018-06-27 14:55:13', 'abgeschlossen', 0, 0),
(167, 36, 6, '2018-06-27 14:55:17', '2018-06-27 14:55:22', 'abgeschlossen', 23, 0),
(168, 36, 10, '2018-06-27 14:55:28', '2018-06-27 14:55:38', 'abgeschlossen', 1, 0),
(169, 36, 2, '2018-06-27 14:55:42', '2018-06-27 14:55:47', 'abgeschlossen', 0, 0),
(170, 36, 7, '2018-06-27 14:55:51', '2018-06-27 14:55:56', 'abgeschlossen', 14, 0),
(171, 36, 2, '2018-06-27 14:56:00', '2018-06-27 14:56:05', 'abgeschlossen', 0, 0),
(172, 36, 7, '2018-06-27 14:56:07', '2018-06-27 14:56:12', 'abgeschlossen', 6, 0),
(173, 36, 2, '2018-06-27 14:56:15', '2018-06-27 14:56:20', 'abgeschlossen', 0, 0),
(174, 36, 6, '2018-06-27 14:56:22', '2018-06-27 14:56:27', 'abgeschlossen', 2, 0),
(175, 36, 2, '2018-06-27 14:56:30', '2018-06-27 14:56:35', 'abgeschlossen', 0, 0),
(176, 36, 6, '2018-06-27 14:56:37', '2018-06-27 14:56:42', 'abgeschlossen', 27, 0),
(177, 36, 2, '2018-06-27 14:56:46', '2018-06-27 14:56:51', 'abgeschlossen', 0, 0),
(178, 36, 7, '2018-06-27 14:56:53', '2018-06-27 14:56:58', 'abgeschlossen', 12, 0),
(179, 36, 2, '2018-06-27 14:57:02', '2018-06-27 14:57:07', 'abgeschlossen', 0, 0),
(180, 36, 7, '2018-06-27 14:57:09', '2018-06-27 14:57:14', 'abgeschlossen', 14, 0),
(181, 36, 10, '2018-06-27 14:57:28', '2018-06-27 14:57:38', 'abgeschlossen', 4, 0),
(182, 36, 10, '2018-06-27 14:57:43', '2018-06-27 14:57:53', 'abgeschlossen', 11, 0),
(183, 36, 2, '2018-06-27 14:58:06', '2018-06-27 14:58:11', 'abgeschlossen', 0, 0),
(184, 36, 6, '2018-06-27 14:58:16', '2018-06-27 14:58:21', 'abgeschlossen', 4, 0),
(185, 36, 2, '2018-06-27 14:58:26', '2018-06-27 14:58:31', 'abgeschlossen', 0, 0),
(186, 36, 6, '2018-06-27 14:58:33', '2018-06-27 14:58:38', 'abgeschlossen', 22, 0),
(187, 36, 2, '2018-06-27 14:58:41', '2018-06-27 14:58:46', 'abgeschlossen', 0, 0),
(188, 36, 7, '2018-06-27 14:58:49', '2018-06-27 14:58:54', 'abgeschlossen', 14, 0),
(189, 36, 2, '2018-06-27 14:58:58', '2018-06-27 14:59:03', 'abgeschlossen', 0, 0),
(190, 36, 7, '2018-06-27 14:59:05', '2018-06-27 14:59:10', 'abgeschlossen', 14, 0),
(191, 36, 2, '2018-06-27 14:59:13', '2018-06-27 14:59:18', 'abgeschlossen', 0, 0),
(192, 36, 6, '2018-06-27 14:59:20', '2018-06-27 14:59:25', 'abgeschlossen', 22, 0),
(193, 36, 2, '2018-06-27 14:59:28', '2018-06-27 14:59:33', 'abgeschlossen', 0, 0),
(194, 36, 6, '2018-06-27 14:59:36', '2018-06-27 14:59:41', 'abgeschlossen', 22, 0),
(195, 36, 2, '2018-06-27 14:59:45', '2018-06-27 14:59:50', 'abgeschlossen', 0, 0),
(196, 36, 6, '2018-06-27 14:59:53', '2018-06-27 14:59:58', 'abgeschlossen', 24, 0),
(197, 36, 10, '2018-06-27 15:00:05', '2018-06-27 15:00:15', 'abgeschlossen', 4, 0),
(198, 36, 2, '2018-06-27 15:00:19', '2018-06-27 15:00:24', 'abgeschlossen', 0, 0),
(199, 36, 6, '2018-06-27 15:00:26', '2018-06-27 15:00:31', 'abgeschlossen', 2, 0),
(200, 36, 2, '2018-06-27 15:00:34', '2018-06-27 15:00:39', 'abgeschlossen', 0, 0),
(201, 36, 6, '2018-06-27 15:00:46', '2018-06-27 15:00:51', 'abgeschlossen', 28, 0),
(202, 36, 2, '2018-06-27 15:00:54', '2018-06-27 15:00:59', 'abgeschlossen', 0, 0),
(203, 36, 6, '2018-06-27 15:01:03', '2018-06-27 15:01:08', 'abgeschlossen', 28, 0),
(204, 36, 2, '2018-06-27 15:01:11', '2018-06-27 15:01:16', 'abgeschlossen', 0, 0),
(205, 36, 6, '2018-06-27 15:01:18', '2018-06-27 15:01:23', 'abgeschlossen', 11, 0),
(206, 36, 2, '2018-06-27 15:01:27', '2018-06-27 15:01:32', 'abgeschlossen', 0, 0),
(207, 36, 6, '2018-06-27 15:01:38', '2018-06-27 15:01:43', 'abgeschlossen', 11, 0),
(208, 36, 10, '2018-06-27 15:02:01', '2018-06-27 15:02:11', 'abgeschlossen', 11, 0),
(209, 36, 10, '2018-06-27 15:02:54', '2018-06-27 15:03:04', 'abgeschlossen', 7, 0),
(210, 36, 2, '2018-06-27 15:03:08', '2018-06-27 15:03:13', 'abgeschlossen', 0, 0),
(211, 36, 7, '2018-06-27 15:03:15', '2018-06-27 15:03:20', 'abgeschlossen', 14, 0),
(212, 36, 2, '2018-06-27 15:03:23', '2018-06-27 15:03:28', 'abgeschlossen', 0, 0),
(213, 36, 6, '2018-06-27 15:03:30', '2018-06-27 15:03:35', 'abgeschlossen', 30, 0),
(214, 36, 2, '2018-06-27 15:03:40', '2018-06-27 15:03:45', 'abgeschlossen', 0, 0),
(215, 36, 10, '2018-06-27 15:03:51', '2018-06-27 15:04:01', 'abgeschlossen', 10, 0),
(216, 36, 10, '2018-06-27 15:04:05', '2018-06-27 15:04:15', 'abgeschlossen', 7, 0),
(217, 36, 10, '2018-06-27 15:04:19', '2018-06-27 15:04:29', 'abgeschlossen', 5, 0),
(218, 36, 10, '2018-06-27 15:04:33', '2018-06-27 15:04:43', 'abgeschlossen', 3, 0),
(219, 36, 2, '2018-06-27 15:04:45', '2018-06-27 15:04:50', 'abgeschlossen', 0, 0),
(220, 36, 6, '2018-06-27 15:04:56', '2018-06-27 15:05:01', 'abgeschlossen', 26, 0),
(221, 36, 2, '2018-06-27 15:05:05', '2018-06-27 15:05:10', 'abgeschlossen', 0, 0),
(222, 36, 6, '2018-06-27 15:05:13', '2018-06-27 15:05:18', 'abgeschlossen', 27, 0),
(223, 36, 2, '2018-06-27 15:05:23', '2018-06-27 15:05:28', 'abgeschlossen', 0, 0),
(224, 36, 6, '2018-06-27 15:05:30', '2018-06-27 15:05:35', 'abgeschlossen', 10, 0),
(225, 36, 2, '2018-06-27 15:05:40', '2018-06-27 15:05:45', 'abgeschlossen', 0, 0),
(226, 36, 6, '2018-06-27 15:05:48', '2018-06-27 15:05:53', 'abgeschlossen', 10, 0),
(227, 36, 2, '2018-06-27 15:05:56', '2018-06-27 15:06:01', 'abgeschlossen', 0, 0),
(228, 36, 2, '2018-06-27 15:06:06', '2018-06-27 15:06:11', 'abgeschlossen', 0, 0),
(229, 36, 6, '2018-06-27 15:06:13', '2018-06-27 15:06:18', 'abgeschlossen', 2, 0),
(230, 36, 2, '2018-06-27 15:06:21', '2018-06-27 15:06:26', 'abgeschlossen', 0, 0),
(231, 36, 6, '2018-06-27 15:06:28', '2018-06-27 15:06:33', 'abgeschlossen', 27, 0);

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
(208, 'Osterei_lila', '../Bilder/NPC/Osterei_lila.png'),
(209, 'L1_erde', '../Bilder/AlleDrachen/L1_erde.png'),
(210, 'L1_feuer', '../Bilder/AlleDrachen/L1_feuer.png'),
(211, 'L1_himmel', '../Bilder/AlleDrachen/L1_himmel.png'),
(212, 'L1_wasser', '../Bilder/AlleDrachen/L1_wasser.png'),
(213, 'L2_erde', '../Bilder/AlleDrachen/L2_erde.png'),
(214, 'L2_feuer', '../Bilder/AlleDrachen/L2_feuer.png'),
(215, 'L2_himmel', '../Bilder/AlleDrachen/L2_himmel.png'),
(216, 'L2_wasser', '../Bilder/AlleDrachen/L2_wasser.png'),
(217, 'L3_erde', '../Bilder/AlleDrachen/L3_erde.png'),
(218, 'L3_feuer', '../Bilder/AlleDrachen/L3_feuer.png'),
(219, 'L3_himmel', '../Bilder/AlleDrachen/L3_himmel.png'),
(220, 'L3_wasser', '../Bilder/AlleDrachen/L3_wasser.png'),
(221, 'L4_erde', '../Bilder/AlleDrachen/L4_erde.png'),
(222, 'L4_feuer', '../Bilder/AlleDrachen/L4_feuer.png'),
(223, 'L4_himmel', '../Bilder/AlleDrachen/L4_himmel.png'),
(224, 'L4_wasser', '../Bilder/AlleDrachen/L4_wasser.png'),
(225, 'L5_erde', '../Bilder/AlleDrachen/L5_erde.png'),
(226, 'L5_feuer', '../Bilder/AlleDrachen/L5_feuer.png'),
(227, 'L5_himmel', '../Bilder/AlleDrachen/L5_himmel.png'),
(228, 'L5_wasser', '../Bilder/AlleDrachen/L5_wasser.png'),
(229, 'L6_erde', '../Bilder/AlleDrachen/L6_erde.png'),
(230, 'L6_feuer', '../Bilder/AlleDrachen/L6_feuer.png'),
(231, 'L6_himmel', '../Bilder/AlleDrachen/L6_himmel.png'),
(232, 'L6_wasser', '../Bilder/AlleDrachen/L6_wasser.png'),
(233, 'L7_erde', '../Bilder/AlleDrachen/L7_erde.png'),
(234, 'L7_feuer', '../Bilder/AlleDrachen/L7_feuer.png'),
(235, 'L7_himmel', '../Bilder/AlleDrachen/L7_himmel.png'),
(236, 'L7_wasser', '../Bilder/AlleDrachen/L7_wasser.png'),
(237, 'apfel', '../Bilder/NPC/apfel.png'),
(238, 'apfelbaum', '../Bilder/NPC/apfelbaum.png'),
(240, 'Ratte', '../Bilder/NPC/Ratte.png'),
(241, 'catfish', '../Bilder/NPC/catfish.png'),
(244, 'fleisch', '../Bilder/NPC/fleisch.png'),
(245, 'gorilla', '../Bilder/NPC/gorilla.png'),
(247, 'knochen', '../Bilder/NPC/knochen.png'),
(251, 'plueschdrache', '../Bilder/NPC/plueschdrache.png'),
(252, 'zebra', '../Bilder/NPC/zebra.png');

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
(1, 210, 'Feuerdrache', 10, 5, 0, 5, 1, 1, 1, ''),
(2, 212, 'Wasserdrache', 10, 5, 0, 1, 5, 1, 1, ''),
(3, 209, 'Erddrache', 10, 5, 0, 1, 1, 5, 1, ''),
(4, 211, 'Luftdrache', 10, 5, 0, 1, 1, 1, 5, ''),
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
(11, 12, 'Steppe', 'Gras überall Gras. Ihr schlagt die Hände über dem Kopf zusammen und denkt: \'Wenn man es wenigstens rauchen könnte ...\''),
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
(1, 'Apfel', 'Eine Hälfte knallrot, die andere goldgelb. Ein klassischer Apfel.', 'Nahrung', 237),
(2, 'Rinde', 'Die äußere Schale eines Apfelbaumes. Wozu die wohl gut ist?', 'Material', 1),
(3, 'Knoblauchlauchknolle', 'Ein Leckerbissen für die die\'s wissen.', 'Pflanze', 84),
(4, 'Fuchsfell', 'Ein rotes Fell von einem Fuchs. Schön flauschig!', 'Material', 1),
(5, 'Steinpilz', 'Ein schöner Pilz. Hoffentlich ist er nicht so hart, wie der Name es verspricht.', 'Pilz', 95),
(6, 'Ring des Feuers', 'Ein funkelnder Ring in dem kleine Flammen züngeln. Passt wunderbar an eine Drachenklaue.', 'Kleidung', 1),
(7, 'Messer', 'Ein kleines scharfes Messer. Nützlich um so allerlei Dinge zu ernten, zu teilen, zu filetieren oder was man sonst noch damit anstellen kann. Auf jeden Fall präziser als eine klobige Drachenkralle.', 'Werkzeug', 1),
(8, 'Knochen', 'Grau, staubig, schaurig ... ein Knochen wie er im Buche steht.', 'Material', 247),
(9, 'Schokoladendrache', 'Süßer Drache zum Vernaschen', 'Nahrung', 1),
(10, 'Drachenfigur', 'Nicht zum Verzehr geeignet!', 'Spielzeug', 1),
(11, 'Plüschdrache', 'Einfach zum Knuddeln ... für einsame Stunden', 'Spielzeug', 251),
(12, 'Drachenpuzzleteil 1', 'Das 1. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(13, 'Drachenpuzzleteil 2', 'Das 2. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(14, 'Drachenpuzzleteil 3', 'Das 3. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(15, 'Drachenpuzzleteil 4', 'Das 4. Teil des 4-Teile Puzzles', 'Spielzeug', 1),
(18, 'Fleisch', 'Ein Stück Fleisch !', 'Nahrung', 244),
(19, 'Kürbis', 'Ekliges gelbes Ding. So manch einem soll das schmecken. Unglaublich!', 'Nahrung', 86);

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
(1, 3, 26, 5),
(2, 6, 26, 1),
(3, 7, 26, 2),
(4, 1, 19, 7),
(5, 2, 19, 1),
(6, 8, 19, 1),
(7, 5, 14, 1),
(8, 8, 26, 3),
(9, 5, 26, 6),
(10, 11, 26, 2),
(11, 1, 26, 6),
(12, 2, 26, 1),
(13, 14, 26, 1),
(14, 2, 32, 1),
(15, 15, 26, 1),
(16, 18, 26, 1),
(17, 8, 36, 9),
(18, 18, 36, 2),
(19, 1, 36, 4),
(20, 2, 36, 2),
(21, 13, 36, 2),
(22, 15, 36, 1),
(23, 11, 36, 1),
(24, 4, 36, 1),
(25, 7, 36, 1);

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
-- Tabellenstruktur für Tabelle `level_bilder`
--

DROP TABLE IF EXISTS `level_bilder`;
CREATE TABLE `level_bilder` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `level_id` int(10) NOT NULL,
  `gattung_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `level_bilder`
--

INSERT INTO `level_bilder` (`id`, `bilder_id`, `level_id`, `gattung_id`) VALUES
(1, 209, 1, 3),
(2, 210, 1, 1),
(3, 211, 1, 4),
(4, 212, 1, 2),
(5, 213, 2, 3),
(6, 214, 2, 1),
(7, 215, 2, 4),
(8, 216, 2, 2),
(9, 217, 3, 3),
(10, 218, 3, 1),
(11, 219, 3, 4),
(12, 220, 3, 2),
(13, 221, 4, 3),
(14, 222, 4, 1),
(15, 223, 4, 4),
(16, 224, 4, 2),
(17, 225, 5, 3),
(18, 226, 5, 1),
(19, 227, 5, 4),
(20, 228, 5, 2),
(21, 229, 6, 3),
(22, 230, 6, 1),
(23, 231, 6, 4),
(24, 232, 6, 2),
(25, 233, 7, 3),
(26, 234, 7, 1),
(27, 235, 7, 4),
(28, 236, 7, 2);

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
(2, 240, 4, 'Ratte', 'Nager', 3, 3, 0, 0, 5, 5, 0, 15, 10, 'Eklige Biester! Entweder kreischend davonrennen und den erstbesten Kammerjäger um Hilfe bitten oder einfach selbst Hand anlegen. ', 'angreifbar'),
(3, 1, 1, 'Zayinenkrieger', 'Zayine', 50, 50, 40, 25, 25, 25, 25, 1000, 750, 'Einen Krieger der Zayinen. Am besten ihr schleicht euch ungesehen an ihm vorbei, denn schon auf den ersten Blick könnt ihr erkennen, dass mit ihm nicht gut Kirschen essen sein wird.', 'angreifbar'),
(4, 77, 4, 'Fuchs', 'Fuchs', 10, 10, 0, 0, 2, 10, 5, 35, 50, 'Ein Fuchs, kräftig gebaut, jedoch scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er auf den ersten Blick ganz niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.', 'angreifbar'),
(5, 77, 4, 'Junger Fuchs', 'Fuchs', 5, 5, 0, 0, 1, 5, 3, 25, 40, 'Ein Fuchs, relativ klein, scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er klein und niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.', 'angreifbar'),
(6, 238, 3, 'Apfelbaum', 'Pflanze', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Ein stattlicher Apfelbaum mit einer Menge Äpfeln in der Krone. Verlockend!', 'sammelbar'),
(7, 95, 4, 'Steinpilz', 'Pilz', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Ein Pilz mit breitem Stiel und einem hellbraunen Hut. Das müsste ein Steinpilz sein.', 'sammelbar'),
(8, 84, 4, 'Knoblauch', 'Pflanze', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Eine schöne Knoblauchpflanze. Der unterirdische Teil ist ein wahrer Gaumenschmaus für Kenner. Der oberirdische Teil hingegen ist ein Haufen unnützes Grünzeug.', 'sammelbar'),
(10, 45, 3, 'Eisbär', 'Bär', 100, 75, 0, 0, 20, 10, 0, 500, 500, 'Ein kräftiger Bär mit großen Pranken und einem schneeweißen Pelz. Da könnte man sich bestimmt schön hinein kuscheln, wenn er nicht so wehrhaft wäre.', 'angreifbar'),
(11, 91, 4, 'Großer Panda', 'Bär', 10, 5, 100, 0, 0, 20, 5, 50, 20, 'Ein großer knuddeliger Bär mit traurigen schwarzen Augen. Er knabbert gemütlich an einem Bambuszweig.', 'angreifbar'),
(12, 208, 5, 'Osterei lila', 'Ei', 0, 0, 1, 0, 0, 0, 1, 1, 1, 'Nicht ganz rund aber trotzdem perfekt geformt. Ein tolles Souvenir.', 'sammelbar'),
(14, 207, 4, 'Osterei grün', 'Ei', 0, 0, 1, 0, 0, 1, 0, 1, 1, 'Nicht ganz rund aber trotzdem perfekt geformt. Ein tolles Souvenir.', 'sammelbar'),
(21, 90, 2, 'Feuerteufel', 'Elementarteilchen', 0, 0, 100, 100, 0, 0, 0, 1000, 1000, 'Ein kleines heißes Kerlchen, was mit Feuer umzugehen weiß. Vorsicht!', 'angreifbar'),
(22, 46, 4, 'Erdmännchen', '---ohne---', 5, 20, 5, 0, 0, 10, 0, 10, 30, 'Ein sehr guter Beobachter !', 'angreifbar'),
(23, 39, 4, 'Hase', 'Löffeltier', 10, 10, 5, 0, 0, 5, 0, 20, 30, 'Ein Hase !', 'angreifbar'),
(24, 88, 4, 'Löwe', 'Katzen', 80, 20, 10, 0, 0, 20, 0, 50, 40, 'Ein großer starker Löwe !', 'angreifbar'),
(25, 96, 3, 'Stockente', 'Vogel', 10, 20, 10, 0, 10, 0, 0, 20, 30, '---ohne---', 'angreifbar'),
(26, 37, 3, 'Pinguin', 'Vogel', 5, 15, 10, 0, 30, 0, 0, 15, 30, '---ohne---', 'angreifbar'),
(27, 241, 3, 'Katzenfisch', 'Fisch', 5, 10, 5, 0, 10, 0, 0, 20, 10, '---ohne---', 'angreifbar'),
(28, 43, 5, 'Ara', 'Vogel', 10, 40, 10, 0, 0, 0, 20, 30, 20, '---ohne---', 'angreifbar'),
(29, 86, 4, 'Kürbis', 'Gemüse', 2, 0, 5, 0, 0, 2, 0, 2, 0, '---ohne---', 'sammelbar'),
(30, 94, 2, 'Skorpion', 'Insekten', 10, 5, 0, 20, 0, 0, 0, 20, 10, 'Vorsicht Stachel !', 'angreifbar'),
(31, 78, 4, 'Sonnenblume', 'Pflanzen', 0, 0, 10, 0, 0, 10, 0, 2, 10, '---ohne---', 'sammelbar'),
(32, 40, 1, 'Hibiskus', 'Pflanze', 0, 0, 0, 0, 0, 10, 0, 10, 10, 'Irgendeine Pflanze mit schönen roten Blüten, wenn sie denn mal blühen !', 'sammelbar');

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
(13, 4, 4, 40),
(14, 5, 4, 10),
(15, 4, 7, 40),
(16, 5, 7, 10),
(17, 4, 9, 40),
(18, 5, 9, 10),
(19, 4, 11, 40),
(20, 5, 11, 10),
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
(110, 14, 7, 10),
(116, 21, 2, 30),
(117, 21, 7, 10),
(143, 30, 7, 20),
(144, 30, 10, 30),
(155, 31, 9, 10),
(156, 31, 4, 5),
(157, 29, 9, 5),
(158, 32, 9, 50),
(159, 32, 4, 30),
(164, 6, 1, 30),
(165, 6, 4, 30),
(166, 6, 5, 30),
(167, 6, 8, 10),
(168, 6, 9, 70),
(169, 6, 10, 1),
(170, 6, 11, 5),
(171, 27, 3, 20),
(172, 27, 1, 40),
(173, 27, 10, 10),
(174, 26, 3, 20),
(175, 25, 4, 5),
(176, 25, 9, 10),
(177, 25, 3, 10),
(178, 2, 1, 50),
(179, 2, 2, 50),
(180, 2, 3, 50),
(181, 2, 4, 50),
(182, 2, 6, 50),
(183, 2, 7, 50),
(184, 2, 8, 50),
(185, 2, 9, 50),
(186, 2, 10, 50),
(187, 2, 11, 50),
(188, 22, 11, 50),
(189, 22, 9, 20),
(190, 22, 9, 5),
(191, 23, 9, 60),
(192, 28, 4, 30),
(193, 28, 10, 10),
(198, 24, 11, 20),
(199, 24, 4, 10),
(200, 24, 7, 5),
(201, 24, 9, 5),
(203, 1, 5, 100);

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
(3, 7, 5, 100, 1, 1),
(4, 8, 3, 50, 1, 1),
(5, 5, 8, 75, 1, 2),
(6, 5, 4, 10, 1, 1),
(7, 4, 8, 85, 2, 3),
(8, 4, 4, 75, 1, 1),
(9, 4, 6, 5, 1, 1),
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
(45, 14, 11, 5, 1, 1),
(50, 21, 6, 10, 1, 1),
(51, 21, 8, 5, 1, 1),
(67, 6, 1, 100, 1, 3),
(68, 6, 2, 25, 1, 1),
(69, 27, 18, 10, 1, 1),
(70, 26, 18, 20, 1, 2),
(71, 25, 18, 10, 1, 1),
(72, 25, 8, 10, 1, 1),
(73, 2, 1, 45, 1, 1),
(74, 2, 7, 10, 1, 1),
(75, 2, 8, 50, 1, 1),
(76, 2, 18, 5, 1, 1),
(77, 22, 8, 10, 1, 2),
(78, 22, 18, 10, 1, 1),
(79, 23, 8, 20, 1, 2),
(80, 23, 18, 30, 1, 1),
(81, 28, 18, 10, 1, 1),
(86, 24, 6, 5, 1, 1),
(87, 24, 7, 10, 1, 1),
(88, 24, 8, 10, 1, 1),
(89, 24, 1, 10, 1, 1);

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
(1, 1, 'Mein erstes Abenteuer', '', 1, 10, 'Los geht\'s. Das erste Abenteuer wartet auf euch.', 'Das Leben leben, die Welt erkunden. So ist das Leben eines Abenteurers.', 'Kopfstand, Rückwärtssalto, Feuerspucken im freien Fall, 100-zeiligen Zungenbrecher fehlerfrei vortragen, ein Spiel programmieren ... Ihr hättet wirklich eine Herausforderung erwartet und nicht solch Kinderkram.', 'Eine Runde um den Block und schon schleppt ihr euch keuchend und ächzend zum nächsten Heiler? Gaja scheint euch nicht wohlgesonnen zu sein.');

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
(7, 17, 212, 2, 1, 3, 'Saphira', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 08:55:01'),
(8, 17, 210, 1, 1, 7, 'Drako', 'W', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 08:55:15'),
(9, 17, 211, 4, 1, 5, 'Luftikuss', 'W', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2018-05-12 08:55:30'),
(10, 31, 209, 3, 1, 6, 'Testdrachin', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2018-05-12 08:55:50'),
(11, 32, 209, 3, 1, 4, 'Heino', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2018-05-12 08:56:10'),
(12, 36, 210, 1, 1, 2, 'Quiecker', 'W', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 08:56:24'),
(13, 36, 212, 2, 1, 1, 'Lambadina', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 08:56:39'),
(14, 11, 211, 4, 1, 8, 'Baldrian', 'W', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2018-05-12 08:56:52'),
(15, 11, 209, 3, 1, 8, 'Cecilia', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2018-05-12 08:57:08'),
(16, 17, 212, 2, 1, 3, 'Blauer Enzian', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 08:57:25'),
(17, 17, 210, 1, 1, 7, 'Wüstenfuchs', 'M', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 08:57:58'),
(18, 17, 211, 4, 1, 8, 'Rosaroter Panter', 'M', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2018-05-12 08:58:12'),
(19, 11, 233, 3, 7, 9, 'Shizophrenia', 'W', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2018-05-12 08:59:13'),
(26, 10, 212, 2, 1, 4, 'Rashiel', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2018-06-25 16:43:25'),
(32, 10, 211, 4, 1, 4, 'Willy', 'M', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2018-06-25 11:53:22'),
(33, 11, 210, 1, 1, 2, 'Thylanna', 'W', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 15:31:51'),
(36, 11, 211, 4, 1, 3, 'Kurt', 'M', 10, 5, 0, 1, 1, 1, 5, 60, 60, 8, 8, 0, '2018-06-27 15:04:43'),
(37, 11, 212, 2, 1, 3, 'Ceifiro', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2018-05-12 15:36:59');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zauber`
--

DROP TABLE IF EXISTS `zauber`;
CREATE TABLE `zauber` (
  `id` int(10) NOT NULL,
  `bilder_id` int(10) NOT NULL,
  `zauberart_id` int(10) NOT NULL,
  `titel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `hauptelement_id` int(10) NOT NULL,
  `nebenelement_id` int(10) NOT NULL,
  `feuer` int(10) NOT NULL DEFAULT '0',
  `erde` int(10) NOT NULL DEFAULT '0',
  `luft` int(10) NOT NULL DEFAULT '0',
  `wasser` int(10) NOT NULL DEFAULT '0',
  `verbrauch` int(10) NOT NULL,
  `effekt` float NOT NULL COMMENT 'Grundwert für Angriff/Verteidigung und sonstige Effekte',
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zauber die ein Spieler wirken kann (z.B. im Kampf oder beim Erkunden)';

--
-- Daten für Tabelle `zauber`
--

INSERT INTO `zauber` (`id`, `bilder_id`, `zauberart_id`, `titel`, `hauptelement_id`, `nebenelement_id`, `feuer`, `erde`, `luft`, `wasser`, `verbrauch`, `effekt`, `beschreibung`) VALUES
(5, 122, 5, 'Alkohol', 2, 3, 2, 0, 0, 1, 0, 0, ''),
(6, 147, 1, 'Glut', 2, 3, 3, 0, 0, 1, 0, 0, ''),
(7, 169, 5, 'Säure', 2, 3, 3, 0, 0, 2, 0, 0, ''),
(8, 165, 1, 'Ölbrand', 2, 3, 4, 0, 0, 1, 0, 0, ''),
(9, 144, 5, 'Gift', 2, 3, 4, 0, 0, 2, 0, 0, ''),
(10, 133, 1, 'Explosion', 2, 3, 4, 0, 0, 3, 0, 0, ''),
(11, 139, 6, 'Funke', 2, 5, 2, 0, 1, 0, 0, 0, ''),
(12, 138, 6, 'Flamme', 2, 5, 3, 0, 1, 0, 0, 0, ''),
(13, 135, 1, 'Feuerball', 2, 5, 3, 0, 2, 0, 0, 0, ''),
(14, 158, 6, 'Lichtstrahl', 2, 5, 4, 0, 1, 0, 0, 0, ''),
(15, 125, 1, 'Blitz', 2, 5, 4, 0, 2, 0, 0, 0, ''),
(16, 137, 1, 'Feuersturm', 2, 5, 4, 0, 3, 0, 0, 0, ''),
(17, 123, 5, 'Asche', 2, 4, 2, 1, 0, 0, 0, 0, ''),
(18, 176, 1, 'Schwelbrand', 2, 4, 3, 1, 0, 0, 0, 0, ''),
(19, 156, 1, 'Lava', 2, 4, 3, 2, 0, 0, 0, 0, ''),
(20, 145, 5, 'Glas', 2, 4, 4, 1, 0, 0, 0, 0, ''),
(21, 161, 5, 'Metall', 2, 4, 4, 2, 0, 0, 0, 0, ''),
(22, 160, 1, 'Magma', 2, 4, 4, 3, 0, 0, 0, 0, ''),
(23, 152, 5, 'Kohle', 4, 2, 1, 2, 0, 0, 0, 0, ''),
(24, 132, 5, 'Erdöl', 4, 2, 1, 3, 0, 0, 0, 0, ''),
(25, 180, 5, 'Ton', 4, 2, 2, 3, 0, 0, 0, 0, ''),
(26, 151, 5, 'Keramik', 4, 2, 1, 4, 0, 0, 0, 0, ''),
(27, 155, 5, 'Kristall', 4, 2, 2, 4, 0, 0, 0, 0, ''),
(28, 129, 1, 'Erdbeben', 4, 2, 3, 4, 0, 0, 0, 0, ''),
(29, 178, 5, 'Staub', 4, 5, 0, 2, 1, 0, 0, 0, ''),
(30, 171, 5, 'Sandhöhle', 4, 5, 0, 3, 1, 0, 0, 0, ''),
(31, 130, 5, 'Erdgas', 4, 5, 0, 3, 2, 0, 0, 0, ''),
(32, 185, 7, 'Wanderdüne', 4, 5, 0, 4, 1, 0, 0, 0, ''),
(33, 179, 1, 'Steinschlag', 4, 5, 0, 4, 2, 0, 0, 0, ''),
(34, 124, 1, 'Asteroid', 4, 5, 0, 4, 3, 0, 0, 0, ''),
(35, 157, 5, 'Lehm', 4, 3, 0, 2, 0, 1, 0, 0, ''),
(36, 183, 5, 'Tropfstein', 4, 3, 0, 3, 0, 1, 0, 0, ''),
(37, 182, 5, 'Treibsand', 4, 3, 0, 3, 0, 2, 0, 0, ''),
(38, 172, 5, 'Sandstein', 4, 3, 0, 4, 0, 1, 0, 0, ''),
(39, 154, 5, 'Kreide', 4, 3, 0, 4, 0, 2, 0, 0, ''),
(40, 141, 1, 'Gerölllawine', 4, 3, 0, 4, 0, 3, 0, 0, ''),
(41, 127, 7, 'Donner', 5, 4, 0, 1, 2, 0, 0, 0, ''),
(42, 153, 1, 'Korrosion', 5, 4, 0, 1, 3, 0, 0, 0, ''),
(43, 177, 6, 'Smog', 5, 4, 0, 2, 3, 0, 0, 0, ''),
(44, 191, 7, 'Windstoß', 5, 4, 0, 1, 4, 0, 0, 0, ''),
(45, 131, 6, 'Erdloch', 5, 4, 0, 2, 4, 0, 0, 0, ''),
(46, 173, 7, 'Sandsturm', 5, 4, 0, 3, 4, 0, 0, 0, ''),
(47, 148, 2, 'Feuerhauch', 5, 2, 1, 0, 2, 0, 0, 0, ''),
(48, 134, 2, 'Feueratem', 5, 2, 1, 0, 3, 0, 0, 0, ''),
(49, 159, 6, 'Lichtwelle', 5, 2, 2, 0, 3, 0, 0, 0, ''),
(50, 140, 1, 'Funkenflug', 5, 2, 1, 0, 4, 0, 0, 0, ''),
(51, 193, 6, 'Wüstenwind', 5, 2, 2, 0, 4, 0, 0, 0, ''),
(52, 136, 1, 'Feuerschneise', 5, 2, 3, 0, 4, 0, 0, 0, ''),
(53, 126, 7, 'Böe', 5, 3, 0, 0, 2, 1, 0, 0, ''),
(54, 190, 7, 'Windhose', 5, 3, 0, 0, 3, 1, 0, 0, ''),
(55, 142, 7, 'Gewitter', 5, 3, 0, 0, 3, 2, 0, 0, ''),
(56, 181, 1, 'Tornado', 5, 3, 0, 0, 4, 1, 0, 0, ''),
(57, 166, 1, 'Orkan', 5, 3, 0, 0, 4, 2, 0, 0, ''),
(58, 128, 1, 'Eissturm', 5, 3, 0, 0, 4, 3, 0, 0, ''),
(59, 174, 5, 'Schlamm', 3, 4, 0, 1, 0, 2, 0, 0, ''),
(60, 170, 5, 'Salzwasser', 3, 4, 0, 1, 0, 3, 0, 0, ''),
(61, 146, 5, 'Gletscher', 3, 4, 0, 2, 0, 3, 0, 0, ''),
(62, 184, 1, 'Tsunami', 3, 4, 0, 1, 0, 4, 0, 0, ''),
(63, 187, 2, 'Wasserfall', 3, 5, 0, 0, 2, 3, 0, 0, ''),
(64, 163, 1, 'Muräne', 3, 4, 0, 3, 0, 4, 0, 0, ''),
(65, 168, 2, 'Regenschauer', 3, 5, 0, 0, 1, 2, 0, 0, ''),
(66, 188, 1, 'Wasserstrahl', 3, 5, 0, 0, 1, 3, 0, 0, ''),
(67, 189, 7, 'Wasserwelle', 3, 4, 0, 2, 0, 4, 0, 0, ''),
(68, 162, 1, 'Monsun', 3, 5, 0, 0, 1, 4, 0, 0, ''),
(69, 150, 1, 'Hurrikan', 3, 5, 0, 0, 2, 4, 0, 0, ''),
(70, 175, 6, 'Schneesturm', 3, 5, 0, 0, 3, 4, 0, 0, ''),
(71, 186, 6, 'Wasserdampf', 3, 2, 1, 0, 0, 2, 0, 0, ''),
(72, 164, 6, 'Nebel', 3, 2, 1, 0, 0, 3, 0, 0, ''),
(73, 167, 6, 'Regenbogen', 3, 2, 2, 0, 0, 3, 0, 0, ''),
(74, 192, 5, 'Wolke', 3, 2, 1, 0, 0, 4, 0, 0, ''),
(75, 149, 2, 'Heiße Quelle', 3, 2, 2, 0, 0, 4, 0, 0, ''),
(76, 143, 1, 'Geysir', 3, 2, 3, 0, 0, 4, 0, 0, '');

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
(1, 'Schaden', 0, ''),
(2, 'Heilung', 0, ''),
(5, 'Materieformung', 0, ''),
(6, 'Sicht', 0, ''),
(7, 'Verjagen', 0, ''),
(8, 'Verbergen', 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zauber_spieler`
--

DROP TABLE IF EXISTS `zauber_spieler`;
CREATE TABLE `zauber_spieler` (
  `id` int(10) NOT NULL,
  `spieler_id` int(10) NOT NULL,
  `zauber_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zuordnung Zauber zu Spieler' ROW_FORMAT=COMPACT;

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
-- Indizes für die Tabelle `level_bilder`
--
ALTER TABLE `level_bilder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_bilder_id_level_bilder` (`bilder_id`) USING BTREE,
  ADD KEY `FK_level_id_level_bilder` (`level_id`) USING BTREE,
  ADD KEY `FK_gattung_id_level_bilder` (`gattung_id`) USING BTREE;

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
  ADD KEY `FK_bilder_id_zauber` (`bilder_id`),
  ADD KEY `FK_zauberart_id_zauber` (`zauberart_id`),
  ADD KEY `FK_nebenelement_id_zauber` (`nebenelement_id`) USING BTREE,
  ADD KEY `FK_hauptelement_id_zauber` (`hauptelement_id`) USING BTREE;

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;
--
-- AUTO_INCREMENT für Tabelle `bilder`
--
ALTER TABLE `bilder`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT für Tabelle `items_spieler`
--
ALTER TABLE `items_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT für Tabelle `level`
--
ALTER TABLE `level`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `level_bilder`
--
ALTER TABLE `level_bilder`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT für Tabelle `npc`
--
ALTER TABLE `npc`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT für Tabelle `npc_gebiet`
--
ALTER TABLE `npc_gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;
--
-- AUTO_INCREMENT für Tabelle `npc_items`
--
ALTER TABLE `npc_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT für Tabelle `zauber`
--
ALTER TABLE `zauber`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT für Tabelle `zauberart`
--
ALTER TABLE `zauberart`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
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
-- Constraints der Tabelle `level_bilder`
--
ALTER TABLE `level_bilder`
  ADD CONSTRAINT `FK_bilder_id_level_bilder` FOREIGN KEY (`bilder_id`) REFERENCES `bilder` (`id`),
  ADD CONSTRAINT `FK_gattung_id_level_bilder` FOREIGN KEY (`gattung_id`) REFERENCES `gattung` (`id`),
  ADD CONSTRAINT `FK_level_id_level_bilder` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`);

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
  ADD CONSTRAINT `FK_hauptelement_id_zauber` FOREIGN KEY (`hauptelement_id`) REFERENCES `element` (`id`),
  ADD CONSTRAINT `FK_nebenelement_id_zauber` FOREIGN KEY (`nebenelement_id`) REFERENCES `element` (`id`),
  ADD CONSTRAINT `FK_zauberart_id_zauber` FOREIGN KEY (`zauberart_id`) REFERENCES `zauberart` (`id`);

--
-- Constraints der Tabelle `zauber_spieler`
--
ALTER TABLE `zauber_spieler`
  ADD CONSTRAINT `FK_spieler_id_zauber_spieler` FOREIGN KEY (`spieler_id`) REFERENCES `spieler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_zauber_id_zauber_spieler` FOREIGN KEY (`zauber_id`) REFERENCES `zauber` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
