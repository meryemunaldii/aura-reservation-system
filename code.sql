CREATE DATABASE OfficeReservationDB;
USE OfficeReservationDB;

-- Users (Supertype) Table
CREATE TABLE Users (
    UserID INT PRIMARY KEY, -- PK [cite: 94]
    FirstName VARCHAR(50) NOT NULL, -- Required field [cite: 95]
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL, -- Unique constraint
    Phone VARCHAR(20), 
    Password VARCHAR(255) NOT NULL, -- To be stored with SHA hashing [cite: 156]
    UserType VARCHAR(20) CHECK (UserType IN ('Student', 'Academician'))
);

-- Rooms (Supertype) Table
CREATE TABLE Rooms (
    RoomID INT PRIMARY KEY, -- PK
    RoomNo VARCHAR(10) NOT NULL,
    Floor INT,
    Capacity INT,
    RoomType VARCHAR(20) CHECK (RoomType IN ('Group', 'Individual', 'Seminar')) -- For subtype differentiation
);

-- Subtype 1: Group Study Rooms
CREATE TABLE GroupRooms (
    RoomID INT PRIMARY KEY, -- Both PK and FK
    MinMemberCount INT DEFAULT 3,
    HasProjector CHAR(1) CHECK (HasProjector IN ('Y', 'N')),
    CONSTRAINT fk_group_room FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID)
);

-- Subtype 2: Individual Study Rooms
CREATE TABLE IndividualRooms (
    RoomID INT PRIMARY KEY, -- Both PK and FK
    IsQuietZone CHAR(1) CHECK (IsQuietZone IN ('Y', 'N')),
    HasDeskLamp CHAR(1) CHECK (HasDeskLamp IN ('Y', 'N')),
    CONSTRAINT fk_ind_room FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID)
);

-- Subtype 3: Seminar Rooms
CREATE TABLE SeminarRooms (
    RoomID INT PRIMARY KEY, -- Both PK and FK
    HasSoundSystem CHAR(1) CHECK (HasSoundSystem IN ('Y', 'N')),
    HasStage CHAR(1) CHECK (HasStage IN ('Y', 'N')),
    SeatType VARCHAR(50),
    CONSTRAINT fk_sem_room FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID)
);

-- Equipments Table
CREATE TABLE Equipments (
    EquipmentID INT PRIMARY KEY,
    EquipmentName VARCHAR(50) NOT NULL,
    Type VARCHAR(30)
);

-- Reservations Table (Relates Users and Rooms)
CREATE TABLE Reservations (
    ReservationID INT PRIMARY KEY,
    UserID INT NOT NULL, -- FK [cite: 123]
    RoomID INT NOT NULL, -- FK
    ReservationDate DATE NOT NULL,
    StartTime TIME NOT NULL,
    EndTime TIME NOT NULL,
    ApprovalStatus VARCHAR(20) DEFAULT 'Pending',
    CONSTRAINT fk_rez_user FOREIGN KEY (UserID) REFERENCES Users(UserID),
    CONSTRAINT fk_rez_room FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID)
);

-- Feedbacks Table
CREATE TABLE Feedbacks (
    FeedbackID INT PRIMARY KEY,
    UserID INT,
    RoomID INT,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    Comment TEXT,
    FeedbackDate DATE NOT NULL,
    CONSTRAINT fk_fb_user FOREIGN KEY (UserID) REFERENCES Users(UserID),
    CONSTRAINT fk_fb_room FOREIGN KEY (RoomID) REFERENCES Rooms(RoomID)
);

-- Violation and Fine Tracking Table
CREATE TABLE UserViolations (
    ViolationID INT PRIMARY KEY,
    UserID INT NOT NULL,
    Reason VARCHAR(255), -- Reason for the penalty (e.g., "Noise", "Late exit")
    FineAmount DECIMAL(10,2) DEFAULT 0.00, -- If there is a monetary fine
    StartDate DATE,
    EndDate DATE, -- The date until the user is banned from booking
    CONSTRAINT fk_violation_user FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Action History (Log) Tables
CREATE TABLE ActionHistory (
    LogID INT PRIMARY KEY,
    UserID INT,
    ActionType VARCHAR(50), -- Create, Update, Delete
    ActionTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_log_user FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- INSERT
-- 1. Users (Öğrenci ve Akademisyen)
INSERT INTO Users VALUES (1, 'Saliha Cansu', 'Pulat', 'salihacansupulat@uni.edu.tr', '05051112233', 'sha256_hash_1', 'Student');
INSERT INTO Users VALUES (2, 'Meryem', 'Ünaldı', 'meryem@uni.edu.tr', '05054445566', 'sha256_hash_2', 'Student');
INSERT INTO Users VALUES (3, 'İsranur', 'Elcenabi', 'isra@uni.edu.tr', '05057778899', 'sha256_hash_3', 'Academician');

-- 2. Rooms
INSERT INTO Rooms VALUES (101, 'A-101', 1, 4, 'Group');
INSERT INTO Rooms VALUES (102, 'B-205', 2, 1, 'Individual');
INSERT INTO Rooms VALUES (103, 'S-301', 3, 50, 'Seminar');

-- 3. Reservations (Örnek Kayıtlar)
INSERT INTO Reservations VALUES (1, 1, 101, '2026-04-20', '10:00:00', '12:00:00', 'Approved');
INSERT INTO Reservations VALUES (2, 2, 101, '2026-04-20', '13:00:00', '15:00:00', 'Pending');
INSERT INTO Reservations VALUES (3, 3, 103, '2026-04-21', '09:00:00', '17:00:00', 'Approved');

-- SORGULAR
-- Join Sorgusu
SELECT u.FirstName, u.LastName, r.RoomNo, res.ReservationDate
FROM Users u
JOIN Reservations res ON u.UserID = res.UserID
JOIN Rooms r ON res.RoomID = r.RoomID;

-- Subquery Sorgusu
SELECT RoomNo, Capacity 
FROM Rooms 
WHERE Capacity > (SELECT AVG(Capacity) FROM Rooms);

-- Group By & Aggregate Sorgusu
SELECT RoomType, COUNT(*) as TotalRooms
FROM Rooms
GROUP BY RoomType;

-- Date Sorgusu
SELECT * FROM Reservations 
WHERE ReservationDate = CURDATE();

-- Character Sorgusu
SELECT FirstName, LastName, Email 
FROM Users 
WHERE Email LIKE '%uni.edu.tr';

-- Görünüm Oluşturma
CREATE VIEW ApprovedReservationsView AS
SELECT u.FirstName, u.LastName, r.RoomNo, res.ReservationDate, res.StartTime
FROM Users u
JOIN Reservations res ON u.UserID = res.UserID
JOIN Rooms r ON res.RoomID = r.RoomID
WHERE res.ApprovalStatus = 'Approved';

-- Bunu görmek için:
SELECT * FROM ApprovedReservationsView;

-- Index Oluşturma
CREATE INDEX idx_user_email ON Users(Email);