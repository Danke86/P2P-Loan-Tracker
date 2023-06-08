-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2023 at 08:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `p2p`
--
CREATE DATABASE IF NOT EXISTS `p2p` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `p2p`;

-- --------------------------------------------------------

--
-- Table structure for table `befriends`
--

CREATE TABLE `befriends` (
  `userid` int(3) NOT NULL,
  `friendid` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `befriends`:
--   `friendid`
--       `users` -> `userid`
--   `userid`
--       `users` -> `userid`
--

--
-- Truncate table before insert `befriends`
--

TRUNCATE TABLE `befriends`;
-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenseid` int(3) NOT NULL,
  `expense_type` varchar(6) NOT NULL,
  `expensename` varchar(20) NOT NULL,
  `date_incurred` datetime NOT NULL,
  `original_amount` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payerid` int(3) NOT NULL,
  `groupid` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `expenses`:
--   `groupid`
--       `groups` -> `groupid`
--   `payerid`
--       `users` -> `userid`
--

--
-- Truncate table before insert `expenses`
--

TRUNCATE TABLE `expenses`;
-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `groupid` int(3) NOT NULL,
  `groupname` varchar(20) NOT NULL,
  `member_count` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `groups`:
--

--
-- Truncate table before insert `groups`
--

TRUNCATE TABLE `groups`;
-- --------------------------------------------------------

--
-- Table structure for table `is_member_of`
--

CREATE TABLE `is_member_of` (
  `userid` int(3) NOT NULL,
  `groupid` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `is_member_of`:
--   `groupid`
--       `groups` -> `groupid`
--   `userid`
--       `users` -> `userid`
--

--
-- Truncate table before insert `is_member_of`
--

TRUNCATE TABLE `is_member_of`;
-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentid` int(3) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_incurred` datetime NOT NULL,
  `userid` int(3) NOT NULL,
  `expenseid` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `payments`:
--   `expenseid`
--       `expenses` -> `expenseid`
--   `userid`
--       `users` -> `userid`
--

--
-- Truncate table before insert `payments`
--

TRUNCATE TABLE `payments`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(3) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `upassword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
-- --------------------------------------------------------

--
-- Table structure for table `user_incurs_expense`
--

CREATE TABLE `user_incurs_expense` (
  `userid` int(3) NOT NULL,
  `expenseid` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `user_incurs_expense`:
--   `expenseid`
--       `expenses` -> `expenseid`
--   `userid`
--       `users` -> `userid`
--

--
-- Truncate table before insert `user_incurs_expense`
--

TRUNCATE TABLE `user_incurs_expense`;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `befriends`
--
ALTER TABLE `befriends`
  ADD KEY `befriends_userid_fk` (`userid`),
  ADD KEY `befriends_friendid_fk` (`friendid`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenseid`),
  ADD KEY `expenses_userid_fk` (`payerid`),
  ADD KEY `expenses_groupid_fk` (`groupid`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupid`);

--
-- Indexes for table `is_member_of`
--
ALTER TABLE `is_member_of`
  ADD UNIQUE KEY `userid` (`userid`,`groupid`),
  ADD KEY `is_member_of_groupid_fk` (`groupid`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentid`),
  ADD KEY `payments_userid_fk` (`userid`),
  ADD KEY `payments_expenseid_fk` (`expenseid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `user_incurs_expense`
--
ALTER TABLE `user_incurs_expense`
  ADD UNIQUE KEY `userid` (`userid`,`expenseid`),
  ADD KEY `userincurs_expenseid_fk` (`expenseid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenseid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `groupid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(3) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `befriends`
--
ALTER TABLE `befriends`
  ADD CONSTRAINT `befriends_friendid_fk` FOREIGN KEY (`friendid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `befriends_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_groupid_fk` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`),
  ADD CONSTRAINT `expenses_userid_fk` FOREIGN KEY (`payerid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `is_member_of`
--
ALTER TABLE `is_member_of`
  ADD CONSTRAINT `is_member_of_groupid_fk` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`),
  ADD CONSTRAINT `is_member_of_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_expenseid_fk` FOREIGN KEY (`expenseid`) REFERENCES `expenses` (`expenseid`),
  ADD CONSTRAINT `payments_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `user_incurs_expense`
--
ALTER TABLE `user_incurs_expense`
  ADD CONSTRAINT `userincurs_expenseid_fk` FOREIGN KEY (`expenseid`) REFERENCES `expenses` (`expenseid`),
  ADD CONSTRAINT `userincurs_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
