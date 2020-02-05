-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 Ara 2016, 23:23:18
-- Sunucu sürümü: 10.1.8-MariaDB
-- PHP Sürümü: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `chat`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_banned_items`
--

CREATE TABLE `chat_banned_items` (
  `ID` int(11) NOT NULL,
  `setting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `val1` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `val2` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_banned_users`
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
-- Tablo için tablo yapısı `chat_friends`
--

CREATE TABLE `chat_friends` (
  `ID` int(255) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `friend_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_members`
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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_messages`
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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_room`
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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_settings`
--

CREATE TABLE `chat_settings` (
  `ID` int(255) NOT NULL,
  `setting` varchar(512) DEFAULT NULL,
  `value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `chat_settings`
--

INSERT INTO `chat_settings` (`ID`, `setting`, `value`) VALUES
(1, 'MIN_USERNAME', '3'),
(2, 'MAX_USERNAME', '40'),
(3, 'MAX_EMAIL', '128'),
(4, 'MIN_PASSWORD', '4'),
(5, 'MAX_PASSWORD', '32'),
(6, 'GUEST_LOGIN', '1'),
(7, 'GUEST_NAME_PREFIX', 'GUEST'),
(8, 'GUEST_PASSWORD_LENGHT', '8'),
(9, 'ENABLE_GUEST_GROUPS', '1'),
(10, 'ENABLE_GUEST_PM', '1'),
(11, 'ENABLE_GUEST_BE_INVITED', '1'),
(12, 'SHOW_GUESTS_ON_ONLINE_USER_LIST', '1'),
(13, 'ENABLE_USER_GROUPS', '1'),
(14, 'ENABLE_USER_PM', '1'),
(15, 'SAVE_MESSAGES', '1'),
(16, 'ENABLE_EMOJI', '1'),
(17, 'MAX_GROUP_CAPACITY', '256'),
(18, 'MAX_LENGHT_GROUP_NAME', '50'),
(19, 'MIN_SEARCH', '3'),
(20, 'ENABLE_ONLINE_USERS', '1'),
(21, 'PROFILE_PIC_DESTINATION', './include/img/'),
(22, 'MAX_IMG_SIZE', '2M'),
(23, 'IMG_EXTENSIONS', 'png|jpg|jpeg|PNG|JPG|JPEG'),
(24, 'SHARE_PHOTO', '1'),
(25, 'SHARE_VIDEO', '1'),
(26, 'SHARE_FILE', '1'),
(27, 'SHARE_MUSIC', '1'),
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
(45, 'WEBSOCKET_URL', 'localhost:9000/ws/server.php'),
(46, 'SECRETKEY', '2365ab5366a8f296a007d40a8dacd227ef99c09f9f8021f59fd77cced933bc4c'),
(47, 'INSTALLED', '0'),
(48, 'URL', 'http://localhost'),
(49, 'ENABLE_FRIEND_SYSTEM', '1'),
(50, 'ALLOW_GUEST_TO_ADD_FRIENDS', '1'),
(51, 'BAN_IP', '1'),
(52, 'MAX_STATUS', '1024'),
(53, 'DEFAULT_STATUS', 'Available'),
(54, 'VOICE_NOTES', '1'),
(55, 'MAX_VOICE_NOTE_SIZE', '2M'),
(56, 'GOOGLE_MAPS_API_KEY', ''),
(57, 'SHARE_LOCATION', '1'),
(58, 'ENABLE_USER_STATUS', '1'),
(59, 'USER_ACTIVATION', '0'),
(60, 'FORGOT_PASSWORD', '0');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_unread`
--

CREATE TABLE `chat_unread` (
  `ID` int(255) NOT NULL,
  `chat_room` int(255) DEFAULT NULL,
  `msg_id` int(255) DEFAULT NULL,
  `usr_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_user_activation`
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
-- Tablo için tablo yapısı `members`
--

CREATE TABLE `members` (
  `ID` int(255) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `salt` varchar(1024) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `registration_date` int(50) DEFAULT NULL,
  `token` varchar(512) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `profile_pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `guest_confirmation` int(1) NOT NULL DEFAULT '1',
  `temp_pass` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `guest` int(1) NOT NULL DEFAULT '0',
  `online` int(1) NOT NULL DEFAULT '0',
  `admin` int(1) NOT NULL DEFAULT '0',
  `user_status` varchar(4096) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `activation` int(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `chat_banned_items`
--
ALTER TABLE `chat_banned_items`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_banned_users`
--
ALTER TABLE `chat_banned_users`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_friends`
--
ALTER TABLE `chat_friends`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_members`
--
ALTER TABLE `chat_members`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_settings`
--
ALTER TABLE `chat_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `chat_unread`
--
ALTER TABLE `chat_unread`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Tablo için indeksler `chat_user_activation`
--
ALTER TABLE `chat_user_activation`
  ADD PRIMARY KEY (`ID`);

--
-- Tablo için indeksler `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`ID`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `chat_banned_items`
--
ALTER TABLE `chat_banned_items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_banned_users`
--
ALTER TABLE `chat_banned_users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_friends`
--
ALTER TABLE `chat_friends`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_members`
--
ALTER TABLE `chat_members`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_settings`
--
ALTER TABLE `chat_settings`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- Tablo için AUTO_INCREMENT değeri `chat_unread`
--
ALTER TABLE `chat_unread`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `chat_user_activation`
--
ALTER TABLE `chat_user_activation`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `members`
--
ALTER TABLE `members`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
