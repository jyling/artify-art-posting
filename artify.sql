-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2019 at 02:40 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `artify`
--
CREATE DATABASE IF NOT EXISTS `artify` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `artify`;

-- --------------------------------------------------------

--
-- Table structure for table `usr`
--

CREATE TABLE `usr` (
  `usr_id` int(11) NOT NULL,
  `usrnm` varchar(32) NOT NULL,
  `pwd` varchar(64) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `joined` datetime NOT NULL,
  `score` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usr`
--

INSERT INTO `usr` (`usr_id`, `usrnm`, `pwd`, `nickname`, `email`, `joined`, `score`) VALUES
(6, 'jyling16', '$2y$10$RuuSMcTtDZLVSB.MNLzGTeigSqn6aQVy3bpEmzs6pmUQhY9OS3ftq', 'jack', 's.gm@a.com', '2019-03-02 04:31:29', 0.9),
(7, 'Jack Desu', '$2y$10$Etc6ZRqrSsgwlrN7RPyUAeBH.9mbROnFfnY1nX7KIpb0qBAcRiByu', 'JacK', 'sa.gm@a.com', '2019-03-02 05:32:47', 0.9);

-- --------------------------------------------------------

--
-- Table structure for table `usr_group`
--

CREATE TABLE `usr_group` (
  `usr_id` int(11) NOT NULL,
  `permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usr_group`
--

INSERT INTO `usr_group` (`usr_id`, `permission`) VALUES
(4, '\r\n {\r\n \"usr\": {\r\n \"accType\": \"normal\",\r\n \"permission\": {\r\n \"post\": true,\r\n \"delete\": true,\r\n \"ban\": false,\r\n \"owner\": false\r\n }\r\n }\r\n }'),
(5, '\r\n {\r\n \"usr\": {\r\n \"accType\": \"normal\",\r\n \"permission\": {\r\n \"post\": true,\r\n \"delete\": true,\r\n \"ban\": false,\r\n \"owner\": false\r\n }\r\n }\r\n }'),
(6, '\r\n {\r\n \"usr\": {\r\n \"accType\": \"normal\",\r\n \"permission\": {\r\n \"post\": false,\r\n \"delete\": true,\r\n \"ban\": false,\r\n \"owner\": false\r\n }\r\n }\r\n }'),
(7, '\r\n                        {\r\n                          \"usr\": {\r\n                            \"accType\" : \"admin\",\r\n                            \"permission\": {\r\n                              \"post\": true,\r\n                              \"delete\": false,\r\n                              \"ban\": false,\r\n                              \"owner\": false\r\n                            }\r\n                          }\r\n                        }');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `usr`
--
ALTER TABLE `usr`
  ADD PRIMARY KEY (`usr_id`);

--
-- Indexes for table `usr_group`
--
ALTER TABLE `usr_group`
  ADD UNIQUE KEY `usr_id` (`usr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `usr`
--
ALTER TABLE `usr`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
