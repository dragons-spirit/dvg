-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Apr 2017 um 23:18
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
(56, 26, 10, '2017-04-15 20:11:53', '2017-04-15 20:13:53', 'abgeschlossen', 9, 0),
(57, 26, 2, '2017-04-15 20:15:13', '2017-04-15 20:15:23', 'abgeschlossen', 0, 0),
(58, 26, 2, '2017-04-15 20:15:35', '2017-04-15 20:15:45', 'abgeschlossen', 0, 0),
(59, 26, 10, '2017-04-15 20:15:52', '2017-04-15 20:17:52', 'abgeschlossen', 8, 0),
(60, 26, 2, '2017-04-26 16:03:14', '2017-04-26 16:03:24', 'abgeschlossen', 0, 0),
(61, 26, 2, '2017-04-26 17:35:03', '2017-04-26 17:35:13', 'abgeschlossen', 0, 0),
(62, 26, 2, '2017-04-26 17:36:22', '2017-04-26 17:36:32', 'abgeschlossen', 0, 0),
(63, 26, 2, '2017-04-26 17:37:46', '2017-04-26 17:37:56', 'abgeschlossen', 0, 0),
(64, 26, 2, '2017-04-26 17:38:41', '2017-04-26 17:38:51', 'abgeschlossen', 0, 0),
(65, 26, 2, '2017-04-26 17:43:55', '2017-04-26 17:44:05', 'abgeschlossen', 0, 0),
(66, 26, 2, '2017-04-26 17:47:43', '2017-04-26 17:47:53', 'abgeschlossen', 0, 0),
(67, 26, 2, '2017-04-26 17:49:16', '2017-04-26 17:49:26', 'abgeschlossen', 0, 0),
(68, 26, 10, '2017-04-26 17:49:35', '2017-04-26 17:51:35', 'abgeschlossen', 9, 0),
(69, 26, 2, '2017-04-26 17:53:32', '2017-04-26 17:53:42', 'abgeschlossen', 0, 0),
(70, 26, 2, '2017-04-26 17:53:50', '2017-04-26 17:54:00', 'abgeschlossen', 0, 0),
(71, 26, 10, '2017-04-26 17:56:48', '2017-04-26 17:56:58', 'abgeschlossen', 6, 0),
(72, 26, 2, '2017-04-26 18:08:08', '2017-04-26 18:08:18', 'abgeschlossen', 0, 0),
(73, 26, 2, '2017-04-26 18:09:40', '2017-04-26 18:09:45', 'abgeschlossen', 0, 0),
(74, 26, 2, '2017-04-26 18:10:00', '2017-04-26 18:10:05', 'abgeschlossen', 0, 0),
(75, 26, 2, '2017-04-26 18:10:50', '2017-04-26 18:10:55', 'abgeschlossen', 0, 0),
(76, 26, 2, '2017-04-26 18:15:29', '2017-04-26 18:15:34', 'abgeschlossen', 0, 0),
(77, 26, 10, '2017-04-26 18:15:39', '2017-04-26 18:15:49', 'abgeschlossen', 9, 0),
(78, 26, 2, '2017-04-26 18:15:57', '2017-04-26 18:16:02', 'abgeschlossen', 0, 0),
(79, 26, 2, '2017-04-26 18:17:47', '2017-04-26 18:17:52', 'abgeschlossen', 0, 0),
(80, 26, 2, '2017-04-26 18:18:25', '2017-04-26 18:18:30', 'abgeschlossen', 0, 0),
(81, 26, 2, '2017-04-26 18:19:45', '2017-04-26 18:19:50', 'abgeschlossen', 0, 0),
(82, 26, 2, '2017-04-26 18:19:58', '2017-04-26 18:20:03', 'abgeschlossen', 0, 0),
(83, 26, 2, '2017-04-26 18:22:23', '2017-04-26 18:22:28', 'abgeschlossen', 0, 0),
(84, 26, 2, '2017-04-26 18:22:44', '2017-04-26 18:22:49', 'abgeschlossen', 0, 0),
(85, 26, 2, '2017-04-27 17:16:48', '2017-04-27 17:16:53', 'abgeschlossen', 0, 0),
(86, 26, 2, '2017-04-27 17:17:33', '2017-04-27 17:17:38', 'abgeschlossen', 0, 0),
(87, 26, 2, '2017-04-27 17:18:05', '2017-04-27 17:18:10', 'abgeschlossen', 0, 0),
(88, 26, 2, '2017-04-27 17:31:42', '2017-04-27 17:31:47', 'abgeschlossen', 0, 0),
(89, 26, 2, '2017-04-28 19:10:34', '2017-04-28 19:10:39', 'abgeschlossen', 0, 0),
(90, 26, 10, '2017-04-28 19:10:45', '2017-04-28 19:10:55', 'abgeschlossen', 1, 0),
(91, 26, 2, '2017-04-28 19:11:04', '2017-04-28 19:11:09', 'abgeschlossen', 0, 0),
(92, 26, 10, '2017-04-28 19:12:35', '2017-04-28 19:12:45', 'abgeschlossen', 9, 0),
(93, 26, 2, '2017-04-28 19:13:27', '2017-04-28 19:13:32', 'abgeschlossen', 0, 0),
(94, 26, 10, '2017-04-28 19:13:37', '2017-04-28 19:13:47', 'abgeschlossen', 1, 0),
(95, 26, 10, '2017-04-28 20:14:44', '2017-04-28 20:14:54', 'abgeschlossen', 9, 0),
(96, 26, 2, '2017-04-28 20:15:15', '2017-04-28 20:15:20', 'abgeschlossen', 0, 0),
(97, 26, 2, '2017-04-28 20:20:53', '2017-04-28 20:20:58', 'abgeschlossen', 0, 0),
(98, 26, 7, '2017-04-28 20:21:01', '2017-04-28 20:22:01', 'abgeschlossen', 6, 0),
(99, 26, 2, '2017-04-28 20:27:37', '2017-04-28 20:27:42', 'abgeschlossen', 0, 0),
(100, 26, 6, '2017-04-28 20:27:46', '2017-04-28 20:27:56', 'abgeschlossen', 4, 0),
(101, 26, 2, '2017-04-28 20:29:26', '2017-04-28 20:29:31', 'abgeschlossen', 0, 0),
(102, 26, 6, '2017-04-28 20:29:33', '2017-04-28 20:29:43', 'abgeschlossen', 4, 0),
(103, 26, 2, '2017-04-28 20:30:19', '2017-04-28 20:30:24', 'abgeschlossen', 0, 0),
(104, 26, 6, '2017-04-28 20:30:27', '2017-04-28 20:30:37', 'abgeschlossen', 4, 0),
(105, 26, 2, '2017-04-28 20:31:31', '2017-04-28 20:31:36', 'abgeschlossen', 0, 0),
(106, 26, 6, '2017-04-28 20:31:38', '2017-04-28 20:31:48', 'abgeschlossen', 4, 0),
(107, 26, 2, '2017-04-28 20:32:42', '2017-04-28 20:32:47', 'abgeschlossen', 0, 0),
(108, 26, 7, '2017-04-28 20:32:49', '2017-04-28 20:32:59', 'abgeschlossen', 7, 0),
(109, 26, 2, '2017-04-28 20:35:13', '2017-04-28 20:35:18', 'abgeschlossen', 0, 0),
(110, 26, 7, '2017-04-28 20:35:20', '2017-04-28 20:35:25', 'abgeschlossen', 6, 0),
(111, 26, 2, '2017-04-28 20:36:54', '2017-04-28 20:36:59', 'abgeschlossen', 0, 0),
(112, 26, 7, '2017-04-28 20:37:00', '2017-04-28 20:37:05', 'abgeschlossen', 6, 0),
(113, 26, 10, '2017-04-28 20:37:14', '2017-04-28 20:37:24', 'abgeschlossen', 1, 0),
(114, 26, 10, '2017-04-28 20:39:30', '2017-04-28 20:39:40', 'abgeschlossen', 8, 0),
(115, 26, 2, '2017-04-28 20:44:16', '2017-04-28 20:44:21', 'abgeschlossen', 0, 0),
(116, 26, 6, '2017-04-28 20:46:13', '2017-04-28 20:46:18', 'abgeschlossen', 2, 0),
(117, 26, 10, '2017-04-28 20:46:29', '2017-04-28 20:46:39', 'abgeschlossen', 9, 0),
(118, 26, 10, '2017-04-28 20:48:10', '2017-04-28 20:48:20', 'abgeschlossen', 9, 0),
(119, 26, 10, '2017-04-28 20:48:22', '2017-04-28 20:48:32', 'abgeschlossen', 6, 0),
(120, 26, 2, '2017-04-28 20:48:57', '2017-04-28 20:49:02', 'abgeschlossen', 0, 0),
(121, 26, 2, '2017-04-28 20:49:10', '2017-04-28 20:49:15', 'abgeschlossen', 0, 0),
(122, 26, 2, '2017-04-28 20:49:18', '2017-04-28 20:49:23', 'abgeschlossen', 0, 0),
(123, 26, 2, '2017-04-28 20:50:12', '2017-04-28 20:50:17', 'abgeschlossen', 0, 0),
(124, 26, 7, '2017-04-28 20:50:21', '2017-04-28 20:50:26', 'abgeschlossen', 7, 0),
(125, 26, 10, '2017-04-28 20:50:32', '2017-04-28 20:50:42', 'abgeschlossen', 9, 0),
(126, 26, 10, '2017-04-28 20:52:18', '2017-04-28 20:52:28', 'abgeschlossen', 2, 0),
(127, 26, 10, '2017-04-28 20:52:36', '2017-04-28 20:52:46', 'abgeschlossen', 1, 0),
(128, 26, 2, '2017-04-28 20:52:58', '2017-04-28 20:53:03', 'abgeschlossen', 0, 0),
(129, 26, 2, '2017-04-28 20:55:58', '2017-04-28 20:56:03', 'abgeschlossen', 0, 0),
(130, 26, 2, '2017-04-28 20:56:07', '2017-04-28 20:56:12', 'abgeschlossen', 0, 0),
(131, 26, 6, '2017-04-28 20:56:15', '2017-04-28 20:56:20', 'abgeschlossen', 2, 0),
(132, 26, 2, '2017-04-28 21:00:06', '2017-04-28 21:00:11', 'abgeschlossen', 0, 0),
(133, 26, 2, '2017-04-28 21:00:16', '2017-04-28 21:00:21', 'abgeschlossen', 0, 0),
(134, 26, 10, '2017-04-28 21:00:23', '2017-04-28 21:00:33', 'abgeschlossen', 4, 0),
(135, 26, 2, '2017-04-28 21:00:40', '2017-04-28 21:00:45', 'abgeschlossen', 0, 0),
(136, 26, 6, '2017-04-28 21:00:48', '2017-04-28 21:00:53', 'abgeschlossen', 2, 0),
(137, 26, 2, '2017-04-28 21:04:29', '2017-04-28 21:04:34', 'abgeschlossen', 0, 0),
(138, 26, 10, '2017-04-28 21:04:42', '2017-04-28 21:04:52', 'abgeschlossen', 8, 0),
(139, 26, 2, '2017-04-28 21:04:55', '2017-04-28 21:05:00', 'abgeschlossen', 0, 0),
(140, 26, 6, '2017-04-28 21:05:02', '2017-04-28 21:05:07', 'abgeschlossen', 2, 0),
(141, 26, 2, '2017-04-28 21:05:11', '2017-04-28 21:05:16', 'abgeschlossen', 0, 0),
(142, 26, 7, '2017-04-28 21:05:19', '2017-04-28 21:05:24', 'abgeschlossen', 7, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Alle genutzen Bilder mit Speicherpfad, separatem Bildtitel und Beschreibung';

--
-- Daten für Tabelle `bilder`
--

INSERT INTO `bilder` (`id`, `titel`, `pfad`, `beschreibung`) VALUES
(1, 'blank', '', ''),
(2, 'Sumpf', '../Platzhalter_gebiete/Gross/sumpf.jpg', 'Nass, schmutzig und schauerlich. Ein Sumpf eben!'),
(3, 'Vulkan', '../Platzhalter_gebiete/Gross/vulkan.jpg', 'Das feurige Herz von Gaia. Vorsicht heiß!'),
(4, 'Eissee', '../Platzhalter_gebiete/Gross/eissee.jpg', 'Ein schöner großer See lädt euch zum Baden gehen ein. An die Hacke, fertig, los!'),
(5, 'Dschungel', '../Platzhalter_gebiete/Gross/dschungel.jpg', 'Gestrüpp soweit das Auge reicht. Was hier alles kreucht und fleucht, wollt ihr euch gar nicht erst ausmalen.'),
(6, 'Klippe', '../Platzhalter_gebiete/Gross/klippe.jpg', 'Ihr schaut in den Abgrund und sogleich juckt es euch in den Flügeln. Traut euch!'),
(7, 'Kristallhoehle', '../Platzhalter_gebiete/Gross/kristallhoehle.jpg', 'Funkelnde Steine weit und breit. Ein wahres Paradies ... wenn man es kalt, muffig und feucht mag.'),
(8, 'Wueste', '../Platzhalter_gebiete/Gross/wueste.jpg', 'Naaaa, wollt ihr etwas zu trinken? Hier ganz sicher nicht!'),
(9, 'Mammutbaum', '../Platzhalter_gebiete/Gross/mammutbaum.jpg', 'Ein mächtiger Stamm, gewaltiges Blattwerk und die schier endlose Höhe lassen auf einen Mammutbaum schließen.'),
(10, 'Wald', '../Platzhalter_gebiete/Gross/wald.jpg', 'Manchereins sieht den Wald vor lauter Bäumen nicht. Hinweis: Ihr steht gerade in einem!'),
(11, 'Oase', '../Platzhalter_gebiete/Gross/oase.jpg', 'Träumt ihr oder halluziniert ihr nur? Wasser und Grün mitten in der Wüste. Das kann doch nicht mit rechten Dingen zugehen.'),
(12, 'Steppe', '../Platzhalter_gebiete/Gross/steppe.jpg', 'Gras überall Gras. Ihr schlagt die Hände über dem Kopf zusammen und denkt: ''Wenn man es wenigstens rauchen könnte ...'''),
(13, 'Drache_laufend', '../Bilder/Drache_laeuft.gif', 'Animierter Drache (laufend)'),
(14, 'Drache_kaempfend', '../Bilder/Drache_kaempft.gif', 'Animierter Drache (kämpfend)'),
(15, 'Drache_wartend', '../Bilder/Drache_wartet.png', 'Drache (wartend)');

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
(11, 12, 'Steppe', 'Gras überall Gras. Ihr schlagt die Hände über dem Kopf zusammen und denkt: ''Wenn man es wenigstens rauchen könnte ...''');

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
  `typ` enum('Pflanze','Pilz','Werkzeug','Kleidung','Material') COLLATE utf8_unicode_ci NOT NULL,
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
(8, 'Knochen', 'Grau, staubig, schaurig ... ein Knochen wie er im Buche steht.', 'Material', 1);

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
(1, 3, 26, 3),
(2, 6, 26, 1),
(3, 7, 26, 1);

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
(1, 1, 1, 'Wymar (Name des Drachen von der Redaktion geändert', 'Drache', 75, 100, 50, 100, 20, 20, 20, 1000, 100, 'Wymar ist einer der ältesten bekannten Drachen und wird für seine Weisheit hoch geschätzt. Ihr tut gut daran, seinen Ratschlägen aufs genauste zu folgen.', 'ansprechbar'),
(2, 1, 3, 'Ratte', 'Nager', 3, 3, 0, 0, 5, 5, 0, 15, 10, 'Eklige Biester! Entweder kreischend davonrennen und den erstbesten Kammerjäger um Hilfe bitten oder einfach selbst Hand anlegen. ', 'angreifbar'),
(3, 1, 0, 'Zayinenkrieger', 'Zayine', 50, 50, 40, 25, 25, 25, 25, 1000, 750, 'Einen Krieger der Zayinen. Am besten ihr schleicht euch ungesehen an ihm vorbei, denn schon auf den ersten Blick könnt ihr erkennen, dass mit ihm nicht gut Kirschen essen sein wird.', 'angreifbar'),
(4, 1, 3, 'Fuchs', 'Fuchs', 10, 10, 0, 0, 2, 10, 5, 35, 50, 'Ein Fuchs, kräftig gebaut, jedoch scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er auf den ersten Blick ganz niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.', 'angreifbar'),
(5, 1, 3, 'Junger Fuchs', 'Fuchs', 5, 5, 0, 0, 1, 5, 3, 25, 40, 'Ein Fuchs, relativ klein, scheu und nicht sonderlich angriffslustig. Ihr solltet eurer Können jedoch nicht überstrapazieren. Auch wenn er klein und niedlich aussieht, so ist er doch sehr gerissen und weiß mit seinen Zähnen gut auszuteilen.', 'angreifbar'),
(6, 1, 2, 'Apfelbaum', 'Pflanze', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Ein stattlicher Apfelbaum mit einer Menge Äpfeln in der Krone. Verlockend!', 'sammelbar'),
(7, 1, 3, 'Steinpilz', 'Pilz', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Ein Pilz mit breitem Stiel und einem hellbraunen Hut. Das müsste ein Steinpilz sein.', 'sammelbar'),
(8, 1, 3, 'Knoblauch', 'Pflanze', 0, 0, 0, 0, 0, 0, 0, 10, 10, 'Eine schöne Knoblauchpflanze. Der unterirdische Teil ist ein wahrer Gaumenschmaus für Kenner. Der oberirdische Teil hingegen ist ein Haufen unnützes Grünzeug.', 'sammelbar');

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
(34, 8, 5, 100);

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
(12, 2, 8, 50, 1, 1);

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

--
-- Daten für Tabelle `quest_spieler`
--

INSERT INTO `quest_spieler` (`id`, `spieler_id`, `quest_id`, `status`, `start`, `ende`) VALUES
(1, 1, 1, 'gestartet', NULL, NULL);

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
(20, 11, 1, 3, 6, 11, 'Erdwurmi', 'M', 10, 5, 0, 1, 1, 5, 1, 60, 60, 8, 8, 0, '2016-12-28 18:45:52'),
(21, 11, 1, 1, 3, 7, 'Schneewitcher', 'M', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2016-12-28 18:45:44'),
(25, 10, 1, 1, 1, 7, 'trtet', 'W', 10, 5, 0, 5, 1, 1, 1, 60, 60, 8, 8, 0, '2017-01-28 16:41:06'),
(26, 10, 1, 2, 1, 8, 'Rashiel', 'W', 10, 5, 0, 1, 5, 1, 1, 60, 60, 8, 8, 0, '2017-04-28 21:04:52');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Zauber die ein Spieler wirken kann (z.B. im Kampf oder beim Erkunden)';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details zu Zaubern um verschiedenste Wirkungen abbilden zu können.';

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
-- Indizes für die Tabelle `aktion`
--
ALTER TABLE `aktion`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `aktion_spieler`
--
ALTER TABLE `aktion_spieler`
  ADD PRIMARY KEY (`id`);

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
-- Indizes für die Tabelle `gebiet`
--
ALTER TABLE `gebiet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `gebiet_gebiet`
--
ALTER TABLE `gebiet_gebiet`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `items_spieler`
--
ALTER TABLE `items_spieler`
  ADD PRIMARY KEY (`id`);

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
-- Indizes für die Tabelle `npc_gebiet`
--
ALTER TABLE `npc_gebiet`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `npc_items`
--
ALTER TABLE `npc_items`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT für Tabelle `aktion`
--
ALTER TABLE `aktion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `aktion_spieler`
--
ALTER TABLE `aktion_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;
--
-- AUTO_INCREMENT für Tabelle `bilder`
--
ALTER TABLE `bilder`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
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
-- AUTO_INCREMENT für Tabelle `gebiet`
--
ALTER TABLE `gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `gebiet_gebiet`
--
ALTER TABLE `gebiet_gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT für Tabelle `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT für Tabelle `items_spieler`
--
ALTER TABLE `items_spieler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `level`
--
ALTER TABLE `level`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `npc`
--
ALTER TABLE `npc`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT für Tabelle `npc_gebiet`
--
ALTER TABLE `npc_gebiet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT für Tabelle `npc_items`
--
ALTER TABLE `npc_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
