-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 23. Apr 2019 um 08:54
-- Server-Version: 10.1.38-MariaDB
-- PHP-Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `minska`
--
CREATE DATABASE IF NOT EXISTS `minska` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `minska`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `calorie`
--

CREATE TABLE `calorie` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Title` varchar(45) NOT NULL,
  `Calories_per_100` double NOT NULL,
  `Amount` double NOT NULL,
  `Stamp_Consumed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Stamp_Insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `template`
--

CREATE TABLE `template` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Title` varchar(45) NOT NULL,
  `Calories_per_100` double NOT NULL,
  `Default_Amount` double NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Stamp_Last_Used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Stamp_Insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Stamp_Update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Email` varchar(89) NOT NULL,
  `Email_Confirmed` tinyint(1) DEFAULT NULL,
  `Lang` enum('de','en') NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Stamp_Insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Stamp_Update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Stamp_Last_Login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_detail`
--

CREATE TABLE `user_detail` (
  `User_ID` int(11) NOT NULL,
  `Gender` enum('male','female') NOT NULL,
  `Height` double NOT NULL,
  `Birthdate` date NOT NULL,
  `Aim_Weight` double NOT NULL,
  `Aim_Date` date NOT NULL,
  `Stamp_Insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Stamp_Update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_email_confirm`
--

CREATE TABLE `user_email_confirm` (
  `User_ID` int(11) NOT NULL,
  `Confirm_Code` varchar(255) NOT NULL,
  `Stamp_Insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Stamp_Confirmed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_license`
--

CREATE TABLE `user_license` (
  `User_ID` int(11) NOT NULL,
  `License_Code` varchar(255) NOT NULL,
  `Remove_Ads` tinyint(1) DEFAULT NULL,
  `Valid_From` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Valid_Till` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `view_mailconfirm`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `view_mailconfirm` (
`Email` varchar(89)
,`Confirmed` tinyint(1)
,`Code` varchar(255)
,`Inserted` timestamp
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `view_usertoken`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `view_usertoken` (
`ID` int(11)
,`Firstname` varchar(255)
,`Lastname` varchar(255)
,`Language` enum('de','en')
,`Email` varchar(89)
,`Email_Confirmed` tinyint(1)
,`Gender` enum('male','female')
,`Height` double
,`Birthdate` date
,`Aim_Weight` double
,`Aim_Date` date
);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `weight`
--

CREATE TABLE `weight` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Weight` double NOT NULL,
  `Date_Weighed` date NOT NULL,
  `Stamp_Insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur des Views `view_mailconfirm`
--
DROP TABLE IF EXISTS `view_mailconfirm`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_mailconfirm`  AS  select `us`.`Email` AS `Email`,`us`.`Email_Confirmed` AS `Confirmed`,`co`.`Confirm_Code` AS `Code`,`co`.`Stamp_Insert` AS `Inserted` from (`user` `us` left join `user_email_confirm` `co` on((`co`.`User_ID` = `us`.`ID`))) ;

-- --------------------------------------------------------

--
-- Struktur des Views `view_usertoken`
--
DROP TABLE IF EXISTS `view_usertoken`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_usertoken`  AS  select `us`.`ID` AS `ID`,`us`.`Firstname` AS `Firstname`,`us`.`Lastname` AS `Lastname`,`us`.`Lang` AS `Language`,`us`.`Email` AS `Email`,`us`.`Email_Confirmed` AS `Email_Confirmed`,`de`.`Gender` AS `Gender`,`de`.`Height` AS `Height`,`de`.`Birthdate` AS `Birthdate`,`de`.`Aim_Weight` AS `Aim_Weight`,`de`.`Aim_Date` AS `Aim_Date` from (`user` `us` left join `user_detail` `de` on((`de`.`User_ID` = `us`.`ID`))) ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `calorie`
--
ALTER TABLE `calorie`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indizes für die Tabelle `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_Email` (`Email`);

--
-- Indizes für die Tabelle `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indizes für die Tabelle `user_email_confirm`
--
ALTER TABLE `user_email_confirm`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `UNIQUE_Confirm_Code` (`Confirm_Code`);

--
-- Indizes für die Tabelle `user_license`
--
ALTER TABLE `user_license`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `UNIQUE_License_Code` (`License_Code`);

--
-- Indizes für die Tabelle `weight`
--
ALTER TABLE `weight`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `calorie`
--
ALTER TABLE `calorie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `template`
--
ALTER TABLE `template`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `weight`
--
ALTER TABLE `weight`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `calorie`
--
ALTER TABLE `calorie`
  ADD CONSTRAINT `calorie_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `template`
--
ALTER TABLE `template`
  ADD CONSTRAINT `template_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `user_detail`
--
ALTER TABLE `user_detail`
  ADD CONSTRAINT `user_detail_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `user_email_confirm`
--
ALTER TABLE `user_email_confirm`
  ADD CONSTRAINT `user_email_confirm_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `user_license`
--
ALTER TABLE `user_license`
  ADD CONSTRAINT `user_license_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `weight`
--
ALTER TABLE `weight`
  ADD CONSTRAINT `weight_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
