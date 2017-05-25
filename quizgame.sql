-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2017 at 04:27 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quizgame`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_games`
--

CREATE TABLE IF NOT EXISTS `active_games` (
`id` int(11) NOT NULL,
  `game_id` varchar(64) NOT NULL,
  `created` varchar(64) NOT NULL,
  `updated` varchar(64) DEFAULT NULL,
  `history` text,
  `starting_game` varchar(64) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `active_games`
--

INSERT INTO `active_games` (`id`, `game_id`, `created`, `updated`, `history`, `starting_game`) VALUES
(2, '5925da44be53f5925da44be546', '1495652932', '1495653117', '{"creator":"Zombo","users":"[{\\"name\\":\\"Zombo\\",\\"points\\":2}]"}', '1495652952');

-- --------------------------------------------------------

--
-- Table structure for table `closed_games`
--

CREATE TABLE IF NOT EXISTS `closed_games` (
`id` int(11) NOT NULL,
  `game_id` varchar(64) NOT NULL,
  `created` varchar(64) NOT NULL,
  `updated` varchar(64) DEFAULT NULL,
  `history` text
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `closed_games`
--

INSERT INTO `closed_games` (`id`, `game_id`, `created`, `updated`, `history`) VALUES
(1, '5925e1c8436ca5925e1c8436da', '1495654856', '1495654873', '{"creator":"Zombo","users":"[{\\"name\\":\\"Zombo\\",\\"points\\":0}]"}');

-- --------------------------------------------------------

--
-- Table structure for table `hubchat`
--

CREATE TABLE IF NOT EXISTS `hubchat` (
`id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `game_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `hubchat`
--

INSERT INTO `hubchat` (`id`, `username`, `time`, `message`, `game_id`) VALUES
(2, 'test', '18/4/2017 23:54:54', 'test test', NULL),
(3, 'test', '19/4/2017 00:09:46', '<script>alert("I am an alert box!");</script>', NULL),
(7, 'test', '19/4/2017 19:15:57', 'asd', NULL),
(8, 'minad', '20/5/2017 12:31:12', 'wat', NULL),
(9, 'minad', '20/5/2017 15:57:50', 'asd', '59203c90a238159203c90a2389'),
(10, 'minad', '20/5/2017 16:28:15', 'sgdg', '592052766790f5920527667916'),
(11, 'minad', '20/5/2017 16:31:36', 'jhsdhksd\r\n', '5920533ea75a35920533ea75ae'),
(12, 'minad', '20/5/2017 18:10:55', 'sxcghvhj', '59206a487f4ac59206a487f4b7'),
(13, 'minad', '20/5/2017 18:11:04', 'fgt', '59206a487f4ac59206a487f4b7'),
(15, 'adm', '23/5/2017 00:41:29', 'zxcbnb', '592368b59c4d1592368b59c4d3'),
(16, 'adm', '23/5/2017 00:41:32', 'sdfgdhjh', '592368b59c4d1592368b59c4d3'),
(17, 'adm', '23/5/2017 10:43:01', 'Hello, and welcome to zombo.com', NULL),
(18, 'adm', '23/5/2017 10:43:48', 'zombo.com', NULL),
(19, 'adm', '23/5/2017 11:37:43', 'jhsdmndfd', NULL),
(22, 'Zombo', '24/5/2017 12:08:02', 'dfghfjkhjlkÃ¦ljj', NULL),
(24, 'Zombo', '24/5/2017 12:14:55', 'asdfghgjh', '59255d04c35da59255d04c35e3'),
(25, 'Zombo', '24/5/2017 14:00:23', 'vfdsdc', '592575cc5d701592575cc5d70d'),
(26, 'Zombo', '24/5/2017 14:01:24', 'sgfhj', '592576024d905592576024d90f'),
(27, 'Zombo', '24/5/2017 14:01:50', 'sdfgdhgj', '592576290c32a592576290c332'),
(28, 'Zombo', '24/5/2017 14:11:27', 'sdfdd', '59257636ea30359257636ea30b'),
(29, 'Zombo', '24/5/2017 14:13:12', 'yghhgfg', '59257636ea30359257636ea30b'),
(30, 'Zombo', '24/5/2017 21:41:10', 'asddd', '5925e1c8436ca5925e1c8436da');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`user_id`, `time`) VALUES
(0, ':time'),
(0, ':time'),
(0, ':time'),
(0, ':time');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
`id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` binary(60) NOT NULL,
  `rank` int(3) NOT NULL DEFAULT '1',
  `ppid` int(11) DEFAULT NULL,
  `activation_status` int(11) NOT NULL DEFAULT '0',
  `activation_code` text
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `username`, `email`, `password`, `rank`, `ppid`, `activation_status`, `activation_code`) VALUES
(1, 'test_user', 'test@example.com', 0x2432792431302449727a594a6931306a334a792f4b366a7a534c51744f4c69663177455a715452516f4b33446353336a646e4645684c3466574d3447, 1, NULL, 1, NULL),
(2, 'test', 'significantowlowl@gmail.com', 0x2432792431302467576b45454a4a766375655651494858484236554f757448772f432e5152734d42435a7678456b78783735763339654d4955715343, 999, NULL, 1, NULL),
(3, 'minad', 'min@ad.dk', 0x2432792431302435366947422e4864684778494c4650316d487a46502e5a4a5a3665356236453249306744506976355a575452326c624b6e636a4d75, 999, NULL, 1, NULL),
(4, 'adm', 'ad@m.dk', 0x24327924313024495862313462354c6c6e49566a546c4a34793358734f465345322e6f723053796a2f6d6632745a7432715273694161676a3670326d, 1, 3, 1, NULL),
(5, 'Zombo', 'zom@bo.dk', 0x2432792431302448526f5a41696e44366549745368306a536a553666656d51427036354c34456c6c473474675132516c582e5a30374f61392f517a4b, 999, 17, 1, NULL),
(7, 'J', 'johannes.skjaerbaek@gmail.com', 0x24327924313024566c4d553868663055486753583056784a39584c6d2e5274375269435a6875417443393254754d79587a78774263695574382e7961, 1, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
`id` int(11) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `answers` text NOT NULL,
  `correct_answer` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '3'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `answers`, `correct_answer`, `status`) VALUES
(1, 'What is love?', '{"answers":["A chemical reaction in the brain.","Baby don''t hurt me", "Don''t hurt me", "No more"]}', 2, 1),
(3, 'How fast are turtles?', '{"answers":["30 speed","9001"]}', 1, 1),
(4, 'Best weeb media?', '{"answers":["Animoo","Mangos"]}', 1, 1),
(6, 'can''t', '{"answers":["uneven","even"]}', 2, 1),
(7, 'fgfhnjmhmhgfds', '{"answers":["dfghj","asfdg"]}', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE IF NOT EXISTS `uploads` (
`id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `original_name` varchar(256) NOT NULL,
  `mime_type` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `name`, `original_name`, `mime_type`) VALUES
(3, 'test.jpg', '2 Ducks, 1 Cup.jpg', 'image/jpeg'),
(4, 'test.jpg', '2 Ducks, 1 Cup.jpg', 'image/jpeg'),
(5, 'test.jpg', '2 Ducks, 1 Cup.jpg', 'image/jpeg'),
(6, '3497.jpg', '3497.jpg', 'image/jpeg'),
(7, '4a462e52b50b55564dd127bfb85319b68c442e17.jpg', '4a462e52b50b55564dd127bfb85319b68c442e17.jpg', 'image/jpeg'),
(8, '258ny8l.jpg', '258ny8l.jpg', 'image/jpeg'),
(9, '3497.jpg', '3497.jpg', 'image/jpeg'),
(10, '5qQCJ.gif', '5qQCJ.gif', 'image/gif'),
(11, '87477.jpg', '87477.jpg', 'image/jpeg'),
(12, '1258374983233.jpg', '1258374983233.jpg', 'image/jpeg'),
(13, '59249e314901c59249e314902c125229511659249e314904f59249e3149057.png', '2bgTi.png', 'image/png'),
(14, '59249e671ed9859249e671eda7204640804259249e671edbd59249e671edc6.jpg', '1257964369824.jpg', 'image/jpeg'),
(15, '5924b21f9717b5924b21f9717d3740129075924b21f971825924b21f97183.jpg', 'i heard you was talking shit.jpg', 'image/jpeg'),
(16, '5924b2390d3905924b2390d3927690635545924b2390d3975924b2390d398.gif', 'oh shit nigger what are you doing.gif', 'image/gif'),
(17, '5924b244b8bb25924b244b8bb56131162725924b244b8bb95924b244b8bba.jpg', 'you think this is a motherfucking game.jpg', 'image/jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_games`
--
ALTER TABLE `active_games`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `closed_games`
--
ALTER TABLE `closed_games`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubchat`
--
ALTER TABLE `hubchat`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_games`
--
ALTER TABLE `active_games`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `closed_games`
--
ALTER TABLE `closed_games`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hubchat`
--
ALTER TABLE `hubchat`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
