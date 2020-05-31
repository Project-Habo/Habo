-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2020 at 05:31 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `habo`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `requirements` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`, `category`, `requirements`) VALUES
(1, 'Running - 1km', 'Sports', 0),
(2, 'Running - 2km', 'Sports', 0);

-- --------------------------------------------------------

--
-- Table structure for table `activities_log`
--

CREATE TABLE `activities_log` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activities_log`
--

INSERT INTO `activities_log` (`id`, `activity_id`, `user_id`) VALUES
(18, 1, 1),
(19, 2, 1),
(20, 1, 2),
(21, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(7) NOT NULL,
  `admin` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `type`, `admin`) VALUES
(1, 'Test group 1', 'Public', 'test_user_3'),
(2, 'Test group 2', 'Private', 'test_user_3'),
(4, 'Habo group', 'Private', 'test_user_3'),
(5, 'Test group 3', 'Public', 'test_user_3');

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE `objectives` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(7) NOT NULL,
  `goal` varchar(100) NOT NULL,
  `category` varchar(25) NOT NULL,
  `progress` varchar(100) NOT NULL,
  `is_complete` varchar(3) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `objectives`
--

INSERT INTO `objectives` (`id`, `name`, `type`, `goal`, `category`, `progress`, `is_complete`, `user_id`) VALUES
(8, 'Test objective', 'Daily', 'kappa', 'Category 1', '0', 'no', 1),
(9, 'Test objective 2', 'Monthly', 'kappa', 'Category 2', '0', 'no', 1),
(10, 'Test objective 3', 'Yearly', 'kappa', 'Sports', '0', 'no', 1),
(11, 'Test objective', 'Daily', '3000', 'Sports', '0', 'no', 8);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `to` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `num_likes` int(11) NOT NULL,
  `num_comments` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `author`, `to`, `date_added`, `num_likes`, `num_comments`, `user_id`) VALUES
(3, 'test', 'test_user_3', 'none', '2020-05-28 16:56:20', 0, 0, 8),
(4, 'profile post test', 'test_user_3', 'none', '2020-05-30 15:24:46', 0, 1, 8),
(5, 'a', 'test_user_3', 'none', '2020-05-30 15:25:20', 0, 2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`id`, `body`, `author`, `date_added`, `user_id`, `post_id`) VALUES
(2, 'a comment', 'test_user_3', '2020-05-30 16:14:04', 8, 5),
(3, 'comment', 'test_user_3', '2020-05-30 18:21:21', 8, 5),
(4, 'comment in post with id 4', 'test_user_3', '2020-05-30 18:21:54', 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `from_country` varchar(50) DEFAULT NULL,
  `from_city` varchar(50) DEFAULT NULL,
  `in_country` varchar(50) DEFAULT NULL,
  `in_city` varchar(50) DEFAULT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `num_friends` int(11) NOT NULL,
  `friends_list` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `profile_pic`, `date_of_birth`, `age`, `gender`, `from_country`, `from_city`, `in_country`, `in_city`, `num_posts`, `num_likes`, `num_friends`, `friends_list`, `user_id`) VALUES
(2, 'assets/images/profile_pics/defaults/head_deep_blue.png', '2020-01-01', 0, 'Female', '', '', '', '', 0, 0, 0, ',', 2),
(7, 'assets/images/profile_pics/defaults/head_carrot.png', '2020-01-01', 0, 'Male', 'Greece', 'Lamia', 'Greece', 'Patras', 3, 0, 0, ',', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `signup_date` date NOT NULL,
  `user_admin` varchar(3) NOT NULL,
  `user_premium` varchar(3) NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `user_suspended` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `username`, `signup_date`, `user_admin`, `user_premium`, `user_closed`, `user_suspended`) VALUES
(2, 'Test', 'User', 'test@email.com', '827ccb0eea8a706c4c34a16891f84e7b', 'test_user', '2020-05-27', 'no', 'yes', 'no', 'no'),
(5, 'Test', 'User', 'test4@email.com', '827ccb0eea8a706c4c34a16891f84e7b', 'test_user_2', '2020-05-27', 'no', 'no', 'no', 'yes'),
(8, 'Konstantinos', 'Panos', 'test10@email.com', '827ccb0eea8a706c4c34a16891f84e7b', 'test_user_3', '2020-05-27', 'no', 'no', 'no', 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_log`
--
ALTER TABLE `activities_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `activities_log`
--
ALTER TABLE `activities_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `objectives`
--
ALTER TABLE `objectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
