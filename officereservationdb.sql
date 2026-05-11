-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 11 May 2026, 11:12:27
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `officereservationdb`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `actionhistory`
--

CREATE TABLE `actionhistory` (
  `LogID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ActionType` varchar(50) DEFAULT NULL,
  `ActionTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `actionhistory`
--

INSERT INTO `actionhistory` (`LogID`, `UserID`, `ActionType`, `ActionTime`) VALUES
(1, 476050, 'Rezervasyon İptal Edildi', '2026-05-01 16:26:10'),
(2, 476050, 'Sisteme Giriş Yapıldı', '2026-05-01 16:55:31'),
(3, 476050, 'Sisteme Giriş Yapıldı', '2026-05-01 16:56:15'),
(4, 476050, 'Sisteme Giriş Yapıldı', '2026-05-01 16:57:52'),
(5, 476050, 'Sisteme Giriş Yapıldı', '2026-05-01 16:58:04'),
(6, 476050, 'Sisteme Giriş Yapıldı', '2026-05-01 16:59:05'),
(7, 975078, 'Sisteme Giriş Yapıldı', '2026-05-01 16:59:57'),
(9, 975078, 'Rezervasyon İptal Edildi (ID: 18)', '2026-05-01 17:03:20'),
(10, 975078, 'Oda No: 103 için yeni rezervasyon oluşturuldu', '2026-05-01 17:48:58'),
(11, 935782, 'Sisteme Giriş Yapıldı', '2026-05-03 16:55:40'),
(12, 935782, 'Oda No: 102 için yeni rezervasyon oluşturuldu', '2026-05-03 16:56:14'),
(13, 975078, 'Sisteme Giriş Yapıldı', '2026-05-05 11:05:39'),
(14, 975078, 'Oda No: 2 için yeni rezervasyon oluşturuldu', '2026-05-05 11:05:58'),
(15, 975078, 'Sisteme Giriş Yapıldı', '2026-05-05 12:30:46'),
(16, 975078, 'Oda No: 2 için yeni rezervasyon oluşturuldu', '2026-05-05 12:31:07'),
(17, 975078, 'Rezervasyon İptal Edildi (ID: 31)', '2026-05-05 12:31:22'),
(18, 975078, 'Sisteme Giriş Yapıldı', '2026-05-06 20:39:53'),
(19, 975078, 'Oda No: 2 için yeni rezervasyon oluşturuldu', '2026-05-06 20:40:58');

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `approvedreservationsview`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `approvedreservationsview` (
`FirstName` varchar(50)
,`LastName` varchar(50)
,`RoomNo` varchar(10)
,`ReservationDate` date
,`StartTime` time
);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `equipments`
--

CREATE TABLE `equipments` (
  `EquipmentID` int(11) NOT NULL,
  `EquipmentName` varchar(50) NOT NULL,
  `Type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `RoomID` int(11) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL CHECK (`Rating` between 1 and 5),
  `Comment` text DEFAULT NULL,
  `FeedbackDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `UserID`, `RoomID`, `Rating`, `Comment`, `FeedbackDate`) VALUES
(3, 476050, 101, 3, 'zestxtvhknl', '2026-05-01'),
(7, 935782, 2, 3, 'oda soğuktu', '2026-05-03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `grouprooms`
--

CREATE TABLE `grouprooms` (
  `RoomID` int(11) NOT NULL,
  `MinMemberCount` int(11) DEFAULT 3,
  `HasProjector` char(1) DEFAULT NULL CHECK (`HasProjector` in ('Y','N'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `grouprooms`
--

INSERT INTO `grouprooms` (`RoomID`, `MinMemberCount`, `HasProjector`) VALUES
(2, 1, 'Y'),
(101, 4, 'Y');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `individualrooms`
--

CREATE TABLE `individualrooms` (
  `RoomID` int(11) NOT NULL,
  `IsQuietZone` char(1) DEFAULT NULL CHECK (`IsQuietZone` in ('Y','N')),
  `HasDeskLamp` char(1) DEFAULT NULL CHECK (`HasDeskLamp` in ('Y','N'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `individualrooms`
--

INSERT INTO `individualrooms` (`RoomID`, `IsQuietZone`, `HasDeskLamp`) VALUES
(2, 'Y', 'Y');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reservations`
--

CREATE TABLE `reservations` (
  `ReservationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `ReservationDate` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `ApprovalStatus` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reservations`
--

INSERT INTO `reservations` (`ReservationID`, `UserID`, `RoomID`, `ReservationDate`, `StartTime`, `EndTime`, `ApprovalStatus`) VALUES
(1, 975078, 2, '2026-05-22', '09:00:00', '10:00:00', 'Pending');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rooms`
--

CREATE TABLE `rooms` (
  `RoomID` int(11) NOT NULL,
  `RoomNo` varchar(10) NOT NULL,
  `Floor` int(11) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `RoomType` varchar(20) DEFAULT NULL CHECK (`RoomType` in ('Group','Individual','Seminar'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `rooms`
--

INSERT INTO `rooms` (`RoomID`, `RoomNo`, `Floor`, `Capacity`, `RoomType`) VALUES
(2, 'B-201', 2, 1, 'Individual'),
(101, 'A-101', 1, 4, 'Group'),
(102, 'B-205', 2, 1, 'Individual'),
(103, 'S-301', 3, 50, 'Seminar');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seminarrooms`
--

CREATE TABLE `seminarrooms` (
  `RoomID` int(11) NOT NULL,
  `HasSoundSystem` char(1) DEFAULT NULL CHECK (`HasSoundSystem` in ('Y','N')),
  `HasStage` char(1) DEFAULT NULL CHECK (`HasStage` in ('Y','N')),
  `SeatType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `UserType` varchar(20) DEFAULT NULL CHECK (`UserType` in ('Student','Academician'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Email`, `Phone`, `Password`, `UserType`) VALUES
(1, 'Saliha Cansu', 'Pulat', 'salihacansupulat@uni.edu.tr', '05051112233', 'sha256_hash_1', 'Student'),
(2, 'Meryem', 'Ünaldı', 'meryem@uni.edu.tr', '05054445566', 'sha256_hash_2', 'Student'),
(3, 'İsranur', 'Elcenabi', 'isra@uni.edu.tr', '05057778899', 'sha256_hash_3', 'Academician'),
(180174, 'mery', 'unal', 'mery@gmail.com', '05236874152', '419afefab43bb8e591e0cf981be9630b309fe1706c655f0e0315e20015f11724', 'Academician'),
(476050, 'mehmet', 'ünaldı', 'mehmet@gmail.com', '05241234758', 'c73c1f28e53040a8c45acf1d30b3797431d945b3f3be465cc4bb951586a10a0d', 'Student'),
(577165, 'ilhami', 'orak', 'orak@gmail.com', '05412568974', 'b65cdeeed6b146a6b24b1b63d2c3b301daa1a52fba08fe4b9631fcae8dae9ce3', 'Student'),
(935782, 'cansu', 'pulat', 'cansu@gmail.com', '05784658236', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'Student'),
(939260, 'cansusu', 'pulat', 'cansusu@gmail.com', '0547862', '84c16d403c7851ee974069b003fae19ec5d58dad1461584d02c88afa17bafae2', 'Academician'),
(975078, 'meryem', 'unaldi', 'meryemm@gmail.com', '05784652589', '1a0bd92cb5f6cb95c896a1df65051ba4d94c6e6a74bae82e53092f31e1147836', 'Student');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `userviolations`
--

CREATE TABLE `userviolations` (
  `ViolationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  `FineAmount` decimal(10,2) DEFAULT 0.00,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `userviolations`
--

INSERT INTO `userviolations` (`ViolationID`, `UserID`, `Reason`, `FineAmount`, `StartDate`, `EndDate`) VALUES
(1, 975078, 'geç anahtar teslim', 10.00, '2026-04-24', '2026-04-25'),
(2, 476050, 'nedensiz', 0.00, '2026-05-01', '2026-05-08'),
(5, 577165, 'Geç anahtar teslim', 50.00, '2026-05-07', '2026-05-14');

-- --------------------------------------------------------

--
-- Görünüm yapısı `approvedreservationsview`
--
DROP TABLE IF EXISTS `approvedreservationsview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `approvedreservationsview`  AS SELECT `u`.`FirstName` AS `FirstName`, `u`.`LastName` AS `LastName`, `r`.`RoomNo` AS `RoomNo`, `res`.`ReservationDate` AS `ReservationDate`, `res`.`StartTime` AS `StartTime` FROM ((`users` `u` join `reservations` `res` on(`u`.`UserID` = `res`.`UserID`)) join `rooms` `r` on(`res`.`RoomID` = `r`.`RoomID`)) WHERE `res`.`ApprovalStatus` = 'Approved' ;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `actionhistory`
--
ALTER TABLE `actionhistory`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `fk_log_user` (`UserID`);

--
-- Tablo için indeksler `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`EquipmentID`);

--
-- Tablo için indeksler `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `fk_fb_user` (`UserID`),
  ADD KEY `fk_fb_room` (`RoomID`);

--
-- Tablo için indeksler `grouprooms`
--
ALTER TABLE `grouprooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Tablo için indeksler `individualrooms`
--
ALTER TABLE `individualrooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Tablo için indeksler `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ReservationID`),
  ADD KEY `fk_rez_user` (`UserID`),
  ADD KEY `fk_rez_room` (`RoomID`);

--
-- Tablo için indeksler `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Tablo için indeksler `seminarrooms`
--
ALTER TABLE `seminarrooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `idx_user_email` (`Email`);

--
-- Tablo için indeksler `userviolations`
--
ALTER TABLE `userviolations`
  ADD PRIMARY KEY (`ViolationID`),
  ADD KEY `fk_violation_user` (`UserID`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `actionhistory`
--
ALTER TABLE `actionhistory`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Tablo için AUTO_INCREMENT değeri `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `actionhistory`
--
ALTER TABLE `actionhistory`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Tablo kısıtlamaları `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_fb_room` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`),
  ADD CONSTRAINT `fk_fb_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Tablo kısıtlamaları `grouprooms`
--
ALTER TABLE `grouprooms`
  ADD CONSTRAINT `fk_group_room` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`);

--
-- Tablo kısıtlamaları `individualrooms`
--
ALTER TABLE `individualrooms`
  ADD CONSTRAINT `fk_ind_room` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`);

--
-- Tablo kısıtlamaları `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_rez_room` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`),
  ADD CONSTRAINT `fk_rez_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Tablo kısıtlamaları `seminarrooms`
--
ALTER TABLE `seminarrooms`
  ADD CONSTRAINT `fk_sem_room` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`);

--
-- Tablo kısıtlamaları `userviolations`
--
ALTER TABLE `userviolations`
  ADD CONSTRAINT `fk_violation_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
