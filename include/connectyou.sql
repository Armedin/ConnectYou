-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2020 at 02:18 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `connectyou`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_banned_items`
--

CREATE TABLE `chat_banned_items` (
  `ID` int(11) NOT NULL,
  `setting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `val1` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `val2` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_banned_users`
--

CREATE TABLE `chat_banned_users` (
  `ID` int(255) NOT NULL,
  `userid` int(255) DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci,
  `time` int(255) DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_friends`
--

CREATE TABLE `chat_friends` (
  `ID` int(255) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `friend_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_friends`
--

INSERT INTO `chat_friends` (`ID`, `user_id`, `friend_id`) VALUES
(1, 46, 47),
(2, 46, 48);

-- --------------------------------------------------------

--
-- Table structure for table `chat_members`
--

CREATE TABLE `chat_members` (
  `ID` int(255) NOT NULL,
  `chat_room` int(255) DEFAULT NULL,
  `user_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `last_message_time` int(255) DEFAULT NULL,
  `last_load_time` int(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_members`
--

INSERT INTO `chat_members` (`ID`, `chat_room`, `user_name`, `user_id`, `status`, `last_message_time`, `last_load_time`) VALUES
(8, 4, 'Arbi', 47, 2, 1581420217, 1581418414),
(7, 4, 'Armedin', 46, 1, 1581420217, NULL),
(3, 2, 'Armela', 48, 1, 1580729370, NULL),
(4, 2, 'Armedin', 46, 6, 1580729370, 1581415643),
(9, 5, 'Armedin', 46, 6, 1581418127, NULL),
(6, 3, 'Arbi', 47, 1, 1581392144, NULL),
(10, 5, 'Arbi', 47, 0, 1581418127, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `ID` int(255) NOT NULL,
  `chat_room` int(255) DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci,
  `from_id` int(255) DEFAULT NULL,
  `from_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `time` int(255) DEFAULT NULL,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `mime` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `file_name` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`ID`, `chat_room`, `message`, `from_id`, `from_name`, `time`, `type`, `mime`, `file_name`) VALUES
(31, 2, 'Hello', 48, 'Armela', 1580719713, 'user', NULL, NULL),
(32, 2, 'yo', 46, 'Armedin', 1580719736, 'user', NULL, NULL),
(33, 2, 'hello', 46, 'Armedin', 1580729370, 'user', NULL, NULL),
(44, 4, 'hi', 46, 'Armedin', 1581420217, 'user', NULL, NULL),
(43, 4, 'Yeah sure. What time ?', 47, 'Arbi', 1581418115, 'user', NULL, NULL),
(42, 4, 'hmm?', 46, 'Armedin', 1581417375, 'user', NULL, NULL),
(41, 4, 'Hello guys? Shall we play basketball?', 46, 'Armedin', 1581417282, 'user', NULL, NULL),
(40, 3, 'Armedin has left.', 46, 'Armedin', 1581392189, 'system', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE `chat_room` (
  `ID` int(255) NOT NULL,
  `chat_name` text CHARACTER SET utf8 COLLATE utf8_turkish_ci,
  `id_hash` varchar(32) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL DEFAULT '0',
  `owner_id` int(255) DEFAULT NULL,
  `owner_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `last_message_time` int(255) DEFAULT NULL,
  `last_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci,
  `type` int(255) DEFAULT NULL,
  `group_pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `temp_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `time` int(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_room`
--

INSERT INTO `chat_room` (`ID`, `chat_name`, `id_hash`, `owner_id`, `owner_name`, `last_message_time`, `last_message`, `type`, `group_pic`, `temp_token`, `time`) VALUES
(5, 'Armedin|Arbi', '3dee6aa9272ac77252b06a8d079de67a', 46, 'Armedin', 1581418127, NULL, 1, NULL, NULL, 1581418127),
(2, 'Armela|Armedin', 'a56327bce99e890a9718a1c2358482ba', 48, 'Armela', 1580729370, 'hello', 1, NULL, NULL, 1580719711),
(4, 'Outdoor Physical Activity', 'b0f8ca85f473e7e970b9400ab870a1e3', 46, 'Armedin', 1581420217, 'hi', 0, NULL, NULL, 1581417234);

-- --------------------------------------------------------

--
-- Table structure for table `chat_settings`
--

CREATE TABLE `chat_settings` (
  `ID` int(255) NOT NULL,
  `setting` varchar(512) DEFAULT NULL,
  `value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_settings`
--

INSERT INTO `chat_settings` (`ID`, `setting`, `value`) VALUES
(1, 'MIN_USERNAME', '3'),
(2, 'MAX_USERNAME', '40'),
(3, 'MAX_EMAIL', '128'),
(4, 'MIN_PASSWORD', '4'),
(5, 'MAX_PASSWORD', '32'),
(6, 'GUEST_LOGIN', '0'),
(7, 'GUEST_NAME_PREFIX', 'GUEST'),
(8, 'GUEST_PASSWORD_LENGHT', '8'),
(9, 'ENABLE_GUEST_GROUPS', '1'),
(10, 'ENABLE_GUEST_PM', '1'),
(11, 'ENABLE_GUEST_BE_INVITED', '1'),
(12, 'SHOW_GUESTS_ON_ONLINE_USER_LIST', '1'),
(13, 'ENABLE_USER_GROUPS', '0'),
(14, 'ENABLE_USER_PM', '1'),
(15, 'SAVE_MESSAGES', '1'),
(16, 'ENABLE_EMOJI', '0'),
(17, 'MAX_GROUP_CAPACITY', '256'),
(18, 'MAX_LENGHT_GROUP_NAME', '50'),
(19, 'MIN_SEARCH', '3'),
(20, 'ENABLE_ONLINE_USERS', '1'),
(21, 'PROFILE_PIC_DESTINATION', './include/img/'),
(22, 'MAX_IMG_SIZE', '2M'),
(23, 'IMG_EXTENSIONS', 'png|jpg|jpeg|PNG|JPG|JPEG'),
(24, 'SHARE_PHOTO', '0'),
(25, 'SHARE_VIDEO', '0'),
(26, 'SHARE_FILE', '0'),
(27, 'SHARE_MUSIC', '0'),
(28, 'SHARE_ARCHIVE', '0'),
(29, 'SHARE_DESTINATION', './include/img/share/'),
(30, 'PHOTO_EXTENSIONS', 'png|PNG|jpg|JPG|jpeg|JPEG|gif|GIF'),
(31, 'PHOTO_MIME_TYPES', 'image/png|image/jpg|image/jpeg|image/gif'),
(32, 'VIDEO_EXTENSIONS', 'mp4|ogg|webm|flv'),
(33, 'VIDEO_MIME_TYPES', 'video/mp4|video/ogg|video/webm|application/octet-stream'),
(34, 'FILE_EXTENSIONS', 'zip|txt|rar'),
(35, 'MUSIC_EXTENSIONS', 'mp3|mpg|flac|aac|ogg|oga|wav'),
(36, 'MUSIC_MIME_TYPES', 'audio/aac|audio/mp3|audio/ogg|audio/wav'),
(37, 'MAX_PHOTO', '10'),
(38, 'MAX_VIDEO', '10'),
(39, 'MAX_FILE', '10'),
(40, 'MAX_PHOTO_SIZE', '2M'),
(41, 'MAX_VIDEO_SIZE', '2M'),
(42, 'MAX_FILE_SIZE', '2M'),
(43, 'MAX_MUSIC_SIZE', '2M'),
(44, 'TIMEZONE', 'Europe/Istanbul'),
(45, 'WEBSOCKET_URL', 'localhost:8080/ws/server.php'),
(46, 'SECRETKEY', '2365ab5366a8f296a007d40a8dacd227ef99c09f9f8021f59fd77cced933bc4c'),
(47, 'INSTALLED', '0'),
(48, 'URL', 'https://localhost:8000/chat/'),
(49, 'ENABLE_FRIEND_SYSTEM', '1'),
(50, 'ALLOW_GUEST_TO_ADD_FRIENDS', '1'),
(51, 'BAN_IP', '1'),
(52, 'MAX_STATUS', '1024'),
(53, 'DEFAULT_STATUS', 'Available'),
(54, 'VOICE_NOTES', '0'),
(55, 'MAX_VOICE_NOTE_SIZE', '2M'),
(56, 'GOOGLE_MAPS_API_KEY', ''),
(57, 'SHARE_LOCATION', '0'),
(58, 'ENABLE_USER_STATUS', '1'),
(59, 'USER_ACTIVATION', '0'),
(60, 'FORGOT_PASSWORD', '0');

-- --------------------------------------------------------

--
-- Table structure for table `chat_unread`
--

CREATE TABLE `chat_unread` (
  `ID` int(255) NOT NULL,
  `chat_room` int(255) DEFAULT NULL,
  `msg_id` int(255) DEFAULT NULL,
  `usr_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_user_activation`
--

CREATE TABLE `chat_user_activation` (
  `ID` int(255) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'account_activation',
  `user_id` int(255) DEFAULT NULL,
  `activation_code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `valid_time` int(64) DEFAULT NULL,
  `next_request` int(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `ID` int(11) NOT NULL,
  `interest` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`ID`, `interest`) VALUES
(1, '3D printing'),
(2, 'Acrobatics'),
(3, 'Acting'),
(4, 'Amateur radio'),
(5, 'Animation'),
(6, 'Aquascaping'),
(7, 'Astronomy'),
(8, 'Baking'),
(9, 'Baton twirling'),
(10, 'Beatboxing'),
(11, 'Blogging'),
(12, 'Building'),
(13, 'Board/tabletop games'),
(14, 'Book discussion clubs'),
(15, 'Bowling'),
(16, 'Brazilian jiu-jitsu'),
(17, 'Breadmaking'),
(18, 'Cabaret'),
(19, 'Calligraphy'),
(20, 'Candle making'),
(21, 'Car fixing & building'),
(22, 'Card games'),
(23, 'Cheesemaking'),
(24, 'Clothesmaking'),
(25, 'Coffee roasting'),
(26, 'Collecting'),
(27, 'Coloring'),
(28, 'Computer programming'),
(29, 'Confectionery'),
(30, 'Cooking'),
(31, 'Cosplaying'),
(32, 'Craft'),
(33, 'Creative writing'),
(34, 'Cross-stitch'),
(35, 'Crossword puzzles'),
(36, 'Cryptography'),
(37, 'Cue sports'),
(38, 'Dance'),
(39, 'Digital arts'),
(40, 'Do it yourself'),
(41, 'Drama'),
(42, 'Drawing'),
(43, 'Electronics'),
(44, 'Embroidery'),
(45, 'Experimenting'),
(46, 'Fashion'),
(47, 'Fishkeeping'),
(48, 'Language learning'),
(49, 'Furniture building'),
(50, 'Gaming'),
(51, 'Genealogy'),
(52, 'Gingerbread house making'),
(53, 'Graphic design'),
(54, 'Home improvement'),
(55, 'Hula hooping'),
(56, 'Hydroponics'),
(57, 'Ice skating'),
(58, 'Jigsaw puzzles'),
(59, 'Juggling'),
(60, 'Karaoke'),
(61, 'Karate'),
(62, 'Kendama'),
(63, 'Knitting'),
(64, 'Knot tying'),
(65, 'Kombucha brewing'),
(66, 'Lace making'),
(67, 'Leather crafting'),
(68, 'Lego building'),
(69, 'Listening to music'),
(70, 'Listening to podcasts'),
(71, 'Macrame'),
(72, 'Magic'),
(73, 'Makeup'),
(74, 'Metalworking'),
(75, 'Model building'),
(76, 'Model engineering'),
(77, 'Needlepoint'),
(78, 'Origami'),
(79, 'Pet adoption & fostering'),
(80, 'Philately'),
(81, 'Playing musical instruments'),
(82, 'Poi'),
(83, 'Pottery'),
(84, 'Puzzles'),
(85, 'Quilling'),
(86, 'Quizzes'),
(87, 'Rapping'),
(88, 'Refinishing'),
(89, 'Rubik\'s Cube'),
(90, 'Sculpting'),
(91, 'Sewing'),
(92, 'Shoemaking'),
(93, 'Singing'),
(94, 'Sketching'),
(95, 'Soapmaking'),
(96, 'Stand-up comedy'),
(97, 'Table tennis'),
(98, 'Video editing'),
(99, 'Video game developing'),
(100, 'Video gaming'),
(101, 'Watching movies'),
(102, 'Watching television'),
(103, 'Weaving'),
(104, 'Welding'),
(105, 'Whittling'),
(106, 'Winemaking'),
(107, 'Woodworking'),
(108, 'Worldbuilding'),
(109, 'Writing'),
(110, 'Yo-yoing'),
(111, 'Yoga'),
(112, 'Air sports'),
(113, 'Archery'),
(114, 'Astronomy'),
(115, 'Backpacking'),
(116, 'BASE jumping'),
(117, 'Baseball'),
(118, 'Basketball'),
(119, 'Beekeeping'),
(120, 'Birdwatching'),
(121, 'Blacksmithing'),
(122, 'BMX'),
(123, 'Board sports'),
(124, 'Bodybuilding'),
(125, 'Butterfly watching'),
(126, 'Camping'),
(127, 'Canoeing'),
(128, 'Canyoning'),
(129, 'Composting'),
(130, 'Dowsing'),
(131, 'Driving'),
(132, 'Fishing'),
(133, 'Flag football'),
(134, 'Flower growing'),
(135, 'Flying'),
(136, 'Flying disc'),
(137, 'Foraging'),
(138, 'Football'),
(139, 'Gardening'),
(140, 'Geocaching'),
(141, 'Graffiti'),
(142, 'Handball'),
(143, 'Herbalism'),
(144, 'Herping'),
(145, 'High-power rocketry'),
(146, 'Hiking'),
(147, 'Hooping'),
(148, 'Horseback riding'),
(149, 'Jogging'),
(150, 'Kayaking'),
(151, 'Kite flying'),
(152, 'Kitesurfing'),
(153, 'Lacrosse'),
(154, 'LARPing'),
(155, 'Letterboxing'),
(156, 'Longboarding'),
(157, 'Meteorology'),
(158, 'Motor sports'),
(159, 'Mountain biking'),
(160, 'Mountaineering'),
(161, 'Netball'),
(162, 'Nordic skating'),
(163, 'Orienteering'),
(164, 'Paintball'),
(165, 'Parkour'),
(166, 'Podcast hosting'),
(167, 'Polo'),
(168, 'Powerlifting'),
(169, 'Rafting'),
(170, 'Road biking'),
(171, 'Roller skating'),
(172, 'Running'),
(173, 'Sailing'),
(174, 'Sand art'),
(175, 'Scouting'),
(176, 'Rowing'),
(177, 'Shooting'),
(178, 'Shopping'),
(179, 'Skateboarding'),
(180, 'Skiing'),
(181, 'Skydiving'),
(182, 'Slacklining'),
(183, 'Snowboarding'),
(184, 'Soccer'),
(185, 'Surfing'),
(186, 'Swimming'),
(187, 'Taekwondo'),
(188, 'Tai chi'),
(189, 'Topiary'),
(190, 'Urban exploration'),
(191, 'Vacation'),
(192, 'Vegetable farming'),
(193, 'Vehicle restoration'),
(194, 'Walking'),
(195, 'Water sports'),
(196, 'Action figure'),
(197, 'Antiquing'),
(198, 'Die-cast toy'),
(199, 'Dolls'),
(200, 'Perfume'),
(201, 'Phillumeny'),
(202, 'modelling'),
(203, 'Rock tumbling'),
(204, 'Scutelliphily'),
(205, 'Shoes'),
(206, 'Sports memorabilia'),
(207, 'Toys'),
(208, 'Vintage cars'),
(209, 'Vintage clothing'),
(210, 'Antiquities'),
(211, 'Rock balancing'),
(212, 'Animal fancy'),
(213, 'Badminton'),
(214, 'Beauty pageants'),
(215, 'Billiards'),
(216, 'Bowling'),
(217, 'Boxing'),
(218, 'Bridge'),
(219, 'Cheerleading'),
(220, 'Chess'),
(221, 'Color guard'),
(222, 'Curling'),
(223, 'Dancing'),
(224, 'Darts'),
(225, 'Debate'),
(226, 'Eating'),
(227, 'Esports'),
(228, 'Fencing'),
(229, 'Go'),
(230, 'Gymnastics'),
(231, 'Ice hockey'),
(232, 'Ice skating'),
(233, 'Judo'),
(234, 'Jujitsu'),
(235, 'Kabaddi'),
(236, 'Laser tag'),
(237, 'Longboarding'),
(238, 'Mahjong'),
(239, 'Marbles'),
(240, 'Martial arts'),
(241, 'Poker'),
(242, 'Shogi'),
(243, 'Table football'),
(244, 'Volleyball'),
(245, 'Weightlifting'),
(246, 'Wrestling'),
(247, 'Airsoft'),
(248, 'American football'),
(249, 'Archery'),
(250, 'Association football'),
(251, 'Auto racing'),
(252, 'Beach volleyball'),
(253, 'Breakdancing'),
(254, 'Climbing'),
(255, 'Cricket'),
(256, 'Cycling'),
(257, 'Disc golf'),
(258, 'Equestrianism'),
(259, 'Field hockey'),
(260, 'Figure skating'),
(261, 'Fishing'),
(262, 'Footbag'),
(263, 'Golfing'),
(264, 'Handball'),
(265, 'Horseback riding'),
(266, 'Jukskei'),
(267, 'Kart racing'),
(268, 'Lacrosse'),
(269, 'Longboarding'),
(270, 'Model aircraft'),
(271, 'Racquetball'),
(272, 'Racing'),
(273, 'Rugby'),
(274, 'Rowing'),
(275, 'Skateboarding'),
(276, 'Softball'),
(277, 'Speed skating'),
(278, 'Squash'),
(279, 'Surfing'),
(280, 'Swimming'),
(281, 'Tennis'),
(282, 'Tennis polo'),
(283, 'Tour skating'),
(284, 'Triathlon'),
(285, 'Frisbee'),
(286, 'Water polo'),
(287, 'Fishkeeping'),
(288, 'Learning'),
(289, 'Meditation'),
(290, 'Microscopy'),
(291, 'Reading'),
(292, 'Audiophile'),
(293, 'Videophilia'),
(294, 'Gongoozling'),
(295, 'Hiking/backpacking'),
(296, 'Satellite watching'),
(297, 'Traveling'),
(298, 'Whale watching'),
(299, 'Religion'),
(300, 'Buddhism'),
(301, 'Judaism'),
(302, 'Christianity'),
(303, 'Islam'),
(304, 'Taoism'),
(305, 'Confucianism'),
(306, 'Hinduism'),
(307, 'Jainism'),
(308, 'Sikhism'),
(309, 'Theism'),
(310, 'Atheism'),
(311, 'Philosophy'),
(312, 'Anarchism'),
(313, 'Feminism'),
(314, 'Libertarianism'),
(315, 'Theology'),
(316, 'Aesthetics'),
(317, 'Epistemology'),
(318, 'Ethics'),
(319, 'Animal rights'),
(320, 'Men rights'),
(321, 'Women rights'),
(322, 'LGBT+ rights'),
(323, 'LGBT'),
(324, 'Logic'),
(325, 'Metaphysics'),
(326, 'Teleology'),
(327, 'Ontology'),
(328, 'Earth Science'),
(329, 'Mythology'),
(330, 'Esotericism'),
(331, 'Taro cards'),
(332, 'Culture'),
(333, 'Law'),
(334, 'Economics'),
(335, 'Psychology'),
(336, 'Law'),
(337, 'Criminal Justice'),
(338, 'Jurisprudence'),
(339, 'Management'),
(340, 'Marketing'),
(341, 'Physics'),
(342, 'Chemistry'),
(343, 'Biology'),
(344, 'Biochemistry'),
(345, 'Social Work'),
(346, 'Sociology'),
(347, 'Geography'),
(348, 'Anthropology'),
(349, 'Archaeology'),
(350, 'Space Sciences'),
(351, 'Computer Science'),
(352, 'Mathematics'),
(353, 'Statistics'),
(354, 'Business'),
(355, 'Engineering'),
(356, 'Medicine'),
(357, 'Health'),
(358, 'Ballroom dancing'),
(359, 'Art'),
(360, 'Music'),
(361, 'Musicology'),
(362, 'Recording'),
(363, 'Choreography'),
(364, 'Theatre'),
(365, 'Directing'),
(366, 'Dramaturgy'),
(367, 'History'),
(368, 'Playwriting'),
(369, 'Puppetry'),
(370, 'Scenography'),
(371, 'Film'),
(372, 'Animation'),
(373, 'Film criticism'),
(374, 'Filmmaking'),
(375, 'Fine arts'),
(376, 'Graphic arts'),
(377, 'Drawing'),
(378, 'Painting'),
(379, 'Photography'),
(380, 'Sculpture'),
(381, 'Calligraphy'),
(382, 'Social Media'),
(383, 'Printmaking'),
(384, 'Architecture'),
(385, 'Design'),
(386, 'Typography'),
(387, 'Egronomics'),
(388, 'Game design'),
(389, 'History'),
(390, 'Politics'),
(391, 'Literature'),
(392, 'Linguistics'),
(393, 'Grammar'),
(394, 'Lexicology'),
(395, 'Philology'),
(396, 'Rhetorics'),
(397, 'Creative writing'),
(398, 'Poetics'),
(399, 'Poetry'),
(400, 'Innovation'),
(401, 'Business'),
(402, 'Research'),
(403, 'Motivation'),
(404, 'Leadership'),
(405, 'Consulting'),
(406, 'Analytics'),
(407, 'Project'),
(408, 'Sustainability'),
(409, 'Environment'),
(410, 'Climate Change'),
(411, 'Cinematography'),
(412, 'Ecology'),
(413, 'Capoeira'),
(414, 'Harry Potter'),
(415, 'Sci-Fi'),
(416, 'Star Wars'),
(417, 'Lord of the Rings'),
(418, 'World of Warcraft'),
(419, 'Overwatch'),
(420, 'YouTube'),
(421, 'Twitch'),
(422, 'Food'),
(423, 'Vegan'),
(424, 'Vegetarian'),
(425, 'Sneakers'),
(426, 'Coding'),
(427, 'Entrepreneurship'),
(428, 'Pole Dancing'),
(429, 'Society'),
(430, 'Military'),
(431, 'Investment'),
(432, 'Personal Growth');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `ID` int(10) NOT NULL,
  `username` varchar(56) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_date` int(50) DEFAULT NULL,
  `online` int(1) NOT NULL DEFAULT '0',
  `user_status` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activation` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`ID`, `username`, `email`, `password`, `salt`, `token`, `registration_date`, `online`, `user_status`, `profile_pic`, `activation`) VALUES
(46, 'Armedin', 'ak2716@gmail.com', '$2y$10$FY1a14A5/gmL5nd6Se9Iuebo3IlzEoEz7Y/ZHma9uudoBB37ltbKy', NULL, 'a8011be9232261a8274f6a5a7828a59f59ce8158d7c09f044117420019217cf4', 1577025634, 0, 'No life programmer!', '', 1),
(47, 'Arbi', 'arbi@gmail.com', '$2y$10$gzKIFmCd5mZ//inZFhOOQ.46djrvaE.OWhzkN7GWYaOPBlNDUk7Vm', NULL, '1657186b2b05b52a915f649a0d978e9df742175a45537d08d322aaadaa3241f2', NULL, 1, NULL, NULL, 1),
(48, 'Armela', 'ak2716j@gmail.com', '$2y$10$qFO1Wl/k3Pe6nQ6gMSmuLuovAXapXCN6p4UGh2Jot9ptn.oUzesV.', NULL, '23977e9c106c379ff37349dc25b5ee5f6e91ddd5a85fcd236ab37f7651ec65f0', 1580719652, 0, NULL, '5e3968da304ac.png', 1),
(49, 'armeidn', 'ar@gmail.com', '$2y$10$sNXuc4g9mnsqaQr0N2PrI.zTFGCOLBb.LU1oSNHcGWAX2gfYMi2/e', NULL, NULL, 1580727601, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

CREATE TABLE `university` (
  `ID` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `logo` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `university`
--

INSERT INTO `university` (`ID`, `name`, `logo`) VALUES
(1, 'University of Bath', 'university_of_bath.png'),
(2, 'University of Cambridge', 'university_of_cambridge.png'),
(3, 'University of Oxford', 'university_of_oxford.png'),
(4, 'University College London', 'university_college_london.png'),
(5, 'Imperial College London', 'imperial_college_london.png'),
(6, 'University of Birmingham', 'university_of_birmingham.png'),
(7, 'University of Bristol', 'university_of_bristol.png'),
(8, 'University of Aberdeen', 'university_of_aberdeen.png'),
(9, 'Abertay University', 'abertay_university.png'),
(10, 'Aberystwyth University', 'aberystwyth_university.png'),
(11, 'Anglia Ruskin University', 'anglia_ruskin_university.png'),
(12, 'Arden University', 'arden_university.png'),
(13, 'Aston University', 'aston_university.png'),
(14, 'Bangor University', 'bangor_university.png'),
(15, 'Bath Spa University', 'bath_spa_university.png'),
(16, 'University of Bedfordshire', 'university_of_bedfordshire.png\r\n'),
(17, 'Birmingham City University\r\n', 'birmingham_city_university.png'),
(18, 'University College Birmingham', 'university_college_birmingham.png'),
(19, 'Bishop Grosseteste University', 'bishop_grosseteste_university.png\r\n'),
(20, 'University of Bolton\r\n', 'university_of_bolton.png'),
(21, 'Arts University Bournemouth', 'arts_university_bournemouth.png'),
(22, 'Bournemouth University\r\n', 'bournemouth_university.png'),
(23, 'BPP University\r\n', 'BPP_university.png\r\n'),
(24, 'University of Bradford\r\n', 'university_of_bradford.png\r\n'),
(25, 'University of Brighton', 'university_of_brighton.png\r\n'),
(26, 'Brunel University London\r\n', 'brunel_university_london.png\r\n'),
(27, 'University of Buckingham\r\n', 'university_of_buckingham.png\r\n'),
(28, 'Buckinghamshire New University\r\n', 'buckinghamshire_new_university.png'),
(29, 'Canterbury Christ Church University', 'canterbury_christ_church_university.png'),
(30, 'Cardiff Metropolitan University\r\n', 'cardiff_metropolitan_university.png\r\n'),
(31, 'Cardiff University', 'cardiff_university.png'),
(32, 'University of Chester', 'university_of_chester.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_activation`
--

CREATE TABLE `user_activation` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activation_key` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `valid_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `ID` int(11) NOT NULL,
  `userID` int(10) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `age` int(3) NOT NULL,
  `university` varchar(256) NOT NULL,
  `department` varchar(256) NOT NULL,
  `interests` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`ID`, `userID`, `firstname`, `lastname`, `age`, `university`, `department`, `interests`) VALUES
(7, 46, 'Armedin', 'Kuka', 19, 'University of Bath', 'Computer Science', 'Basketball,Computer programming,Walking,Chess'),
(8, 48, 'Armela', 'Kuka', 20, 'University of Bath', 'Law', 'Volleyball,Law,BASE jumping,Cooking,Board/tabletop games');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_banned_items`
--
ALTER TABLE `chat_banned_items`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_banned_users`
--
ALTER TABLE `chat_banned_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_friends`
--
ALTER TABLE `chat_friends`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_members`
--
ALTER TABLE `chat_members`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_settings`
--
ALTER TABLE `chat_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_unread`
--
ALTER TABLE `chat_unread`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `chat_user_activation`
--
ALTER TABLE `chat_user_activation`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `university`
--
ALTER TABLE `university`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_activation`
--
ALTER TABLE `user_activation`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_banned_items`
--
ALTER TABLE `chat_banned_items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_banned_users`
--
ALTER TABLE `chat_banned_users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_friends`
--
ALTER TABLE `chat_friends`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat_members`
--
ALTER TABLE `chat_members`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chat_settings`
--
ALTER TABLE `chat_settings`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `chat_unread`
--
ALTER TABLE `chat_unread`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_user_activation`
--
ALTER TABLE `chat_user_activation`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `university`
--
ALTER TABLE `university`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_activation`
--
ALTER TABLE `user_activation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
