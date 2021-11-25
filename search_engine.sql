-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2020 at 11:08 PM
-- Server version: 10.1.39-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutorials`
--

-- --------------------------------------------------------

--
-- Table structure for table `search_engine`
--

CREATE TABLE `search_engine` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `blurb` text NOT NULL,
  `url` text NOT NULL,
  `keywords` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `search_engine`
--

INSERT INTO `search_engine` (`id`, `title`, `blurb`, `url`, `keywords`) VALUES
(1, 'HeyTuts.com', 'Come learn something', 'https://www.HeyTuts.com', 'heytuts nickfrosty learn stuff '),
(2, 'Google', 'the best search engine', 'https://www.google.com', 'search engine google'),
(3, 'Bing?', 'another search engine that maybe people use?', 'https://bing.com', 'search engine bing'),
(4, 'youtube', 'videos on learning to code and stuff', 'https://www.youtube.com/HeyTuts', 'youtube heytuts nickfrosty videos');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `search_engine`
--
ALTER TABLE `search_engine`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `search_engine`
--
ALTER TABLE `search_engine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
