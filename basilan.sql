-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 05:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basilan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'admin', '$2y$10$o2MxGnKiEUF9UdFr7/rdTOiWKjBT1ttHkermLBdy5HiAa92KLCUfe', '2024-12-14 23:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `contributors`
--

CREATE TABLE `contributors` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributors`
--

INSERT INTO `contributors` (`id`, `full_name`, `username`, `password`, `created_at`) VALUES
(1, 'Juan Dela Cruz', 'contributor', '$2y$10$gFresnKLVnGhOqjdySvCY.R6MoaFjbYBgjw0X9BzURMTxyodS1pXe', '2025-01-24 22:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `dictionary_entries`
--

CREATE TABLE `dictionary_entries` (
  `id` int(11) NOT NULL,
  `yakan_word` varchar(255) NOT NULL,
  `pilipino_word` varchar(255) NOT NULL,
  `english_word` varchar(255) NOT NULL,
  `synonyms` text DEFAULT NULL,
  `examples` text DEFAULT NULL,
  `submitted_by_contributor_id` int(11) NOT NULL,
  `validated_by_validator_id` int(11) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `validation_timestamp` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dictionary_entries`
--

INSERT INTO `dictionary_entries` (`id`, `yakan_word`, `pilipino_word`, `english_word`, `synonyms`, `examples`, `submitted_by_contributor_id`, `validated_by_validator_id`, `is_validated`, `validation_timestamp`, `created_at`) VALUES
(1, 'Luma', 'Bahay', 'House', 'Home, Dwelling', 'Ang luma ay malaki. (The house is big.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(2, 'A-ah', 'Tao', 'Person', 'Human, Individual', 'Ang tawhan ay mabait. (The person is kind.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(3, 'Tahun', 'Edad', 'Age', NULL, 'Ang gulang niya ay dalawampu. (Their age is twenty.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(4, 'Saye', 'Saya', 'Happy', 'Joyful, Cheerful', 'Ang bata ay sayu. (The child is happy.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(5, 'Dibuhi', 'Kahapon', 'Yesterday', 'Previous day', 'Kahapang ako ay pumunta sa merkado. (Yesterday I went to the market.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(6, 'Nulat', 'Sulat', 'Letter', 'Message, Note', 'Nagpadala siya ng sulat. (They sent a letter.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(7, 'Iskul', 'Paaralan', 'School', 'Academy, Institution', 'Ang mga bata ay nasa iskul. (The children are in school.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(8, 'Anda', 'Asawa', 'Spouse', 'Husband, Wife', 'Ang asawa niya ay maganda. (Their spouse is beautiful.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(9, 'Mangan', 'Kain', 'Eat', 'Consume, Devour', 'Gusto ko kaon ng mangga. (I want to eat mango.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(10, 'Kemon', 'Lahat', 'All', 'Everything, Everyone', 'Ang tanan ay narito. (Everyone is here.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(11, 'Bohe', 'Tubig', 'Water', 'H2O, Liquid', 'Kailangan natin ng tubig. (We need water.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(12, 'Pamilyah', 'Pamilya', 'Family', 'Relatives, Kin', 'Mahilig sa pamilya ang Yakan. (Yakan people value family.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(13, 'Saweh', 'Kaibigan', 'Friend', 'Buddy, Companion', 'Ang kaibigan ko ay mabait. (My friend is kind.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(14, 'Sayul', 'Gulay', 'Vegetable', 'Veggies, Produce', 'Mahilig sila sa gulay. (They love vegetables.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(15, 'Tabuh', 'Palengke', 'Market', 'Bazaar, Marketplace', 'Pumunta kami sa merkado. (We went to the market.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(16, 'Gaddung', 'Bilog', 'Round', 'Circular, Spherical', 'Ang bola ay bilog. (The ball is round.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(17, 'Lomboy', 'Mahina', 'Weak', 'Fragile, Feeble', 'Ang mahina ay madaling masaktan. (The weak are easily hurt.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(18, 'Basag', 'Malakas', 'Strong', 'Powerful, Sturdy', 'Ang malakas na hangin ay dumaan. (The strong wind passed by.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(19, 'Halga', 'Mahal', 'Expensive', 'Costly, Pricey', 'Ang mga gamit na ito ay mahal. (These items are expensive.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(20, 'Mura', 'Mura', 'Cheap', 'Inexpensive, Low-cost', 'Ang mga gulay ay mura sa merkado. (The vegetables are cheap at the market.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(21, 'Bata', 'Bata', 'Child', 'Kid, Youngster', 'Ang bata ay masaya. (The child is happy.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(22, 'Matanda', 'Matanda', 'Old', 'Elderly, Aged', 'Ang matanda ay mahina. (The old person is frail.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(23, 'Buhay', 'Buhay', 'Life', 'Existence, Living', 'Ang buhay ay maganda. (Life is beautiful.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(24, 'Patay', 'Patay', 'Dead', 'Deceased, Gone', 'Ang patay ay hindi na makakapagsalita. (The dead cannot speak anymore.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(25, 'Matamis', 'Matamis', 'Sweet', 'Sugary, Pleasant', 'Ang mangga ay matamis. (The mango is sweet.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(26, 'Maasim', 'Maasim', 'Sour', 'Tart, Acidic', 'Ang kalamansi ay maasim. (The calamansi is sour.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(27, 'Mainit', 'Mainit', 'Hot', 'Warm, Scorching', 'Mainit ang panahon ngayon. (The weather is hot today.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(28, 'Malalamig', 'Malalamig', 'Cold', 'Chilly, Freezing', 'Malalamig ang hangin sa umaga. (The air is cold in the morning.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(29, 'Mataas', 'Mataas', 'High', 'Tall, Elevated', 'Mataas ang bundok. (The mountain is high.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(30, 'Mababa', 'Mababa', 'Low', 'Short, Below', 'Ang tubig ay mababa. (The water is low.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(31, 'Salamat', 'Salamat', 'Thank you', 'Gratitude, Appreciation', 'Salamat sa tulong mo. (Thank you for your help.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(32, 'Kaagap', 'Kaagapay', 'Partner', 'Ally, Collaborator', 'Ang kaagap ko sa negosyo ay matalino. (My partner in business is smart.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(33, 'Aki', 'Anak', 'Child', 'Offspring, Kid', 'Aki ko ay magaling mag-aral. (My child is good at studying.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(34, 'Ubi', 'Kamote', 'Sweet Potato', 'Yam', 'Gusto ko ng ubing prito. (I want fried sweet potatoes.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53'),
(35, 'Kilat', 'Kidlat', 'Lightning', 'Thunderbolt, Flash', 'Nagkaroon ng kilat sa kalangitan. (There was lightning in the sky.)', 1, 1, 1, '2025-01-25 04:20:53', '2025-01-25 04:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `english_sentences`
--

CREATE TABLE `english_sentences` (
  `id` int(11) NOT NULL,
  `english_sentence` text NOT NULL,
  `submitted_by_contributor_id` int(11) NOT NULL,
  `validated_by_validator_id` int(11) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `validation_timestamp` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `english_sentences`
--

INSERT INTO `english_sentences` (`id`, `english_sentence`, `submitted_by_contributor_id`, `validated_by_validator_id`, `is_validated`, `validation_timestamp`, `created_at`, `category`) VALUES
(5, 'where are you?', 1, 1, 1, '2025-01-25 04:07:15', '2025-01-25 00:02:01', NULL),
(6, 'hi', 1, 1, 1, '2025-01-25 00:24:29', '2025-01-25 00:24:13', 'School'),
(7, 'where are you?', 1, 1, 1, '2025-01-25 04:07:14', '2025-01-25 04:05:10', 'Police Station'),
(8, 'what is your name?', 1, 1, 1, '2025-01-25 04:07:08', '2025-01-25 04:06:35', 'City Hall');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `message`, `submitted_at`) VALUES
(3, 7, 'Bulok System', '2024-12-15 06:03:26'),
(4, 6, 'bulok ang system mo', '2025-01-25 05:19:38');

-- --------------------------------------------------------

--
-- Table structure for table `media_history`
--

CREATE TABLE `media_history` (
  `id` int(11) NOT NULL,
  `language` enum('Yakan','English') NOT NULL,
  `media_type` enum('image','video') NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `caption` text NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media_history`
--

INSERT INTO `media_history` (`id`, `language`, `media_type`, `media_path`, `caption`, `uploaded_at`) VALUES
(7, 'Yakan', 'image', 'IslandEscape_1734229863.png', 'Yakan refers to the majority Muslim group in Basilan, an island province just south of Zamboanga peninsula. “Basilan” may mean “the waterway into the sea” or may derive from the Yakan word for “the way to the iron” because of the presence of minerals in the island. It measures 1,358 square kilometers, the largest in the Sulu archipelago. Located at the northern end of the Sulu archipelago, it is bounded in the north by Zamboanga City; in the south by the Sulu archipelago, with Jolo as the major island; in the east by  Mindanao; and in the west by the Sulu Sea and Sabah (North Borneo). Basilan enjoys good weather because it is located below the typhoon belt. Abundant rainfall throughout the year keeps the soil wet and fertile.', '2024-12-15 02:31:03'),
(8, 'Yakan', 'image', 'IslandEscape_1735354895.png', 'Yakan refers to the majority Muslim group in Basilan, an island province just south of Zamboanga peninsula. “Basilan” may mean “the waterway into the sea” or may derive from the Yakan word for “the way to the iron” because of the presence of minerals in the island. It measures 1,358 square kilometers, the largest in the Sulu archipelago. Located at the northern end of the Sulu archipelago, it is bounded in the north by Zamboanga City; in the south by the Sulu archipelago, with Jolo as the major island; in the east by  Mindanao; and in the west by the Sulu Sea and Sabah (North Borneo). Basilan enjoys good weather because it is located below the typhoon belt. Abundant rainfall throughout the year keeps the soil wet and fertile.', '2024-12-28 03:01:35');

-- --------------------------------------------------------

--
-- Table structure for table `recent_dictionary_searches`
--

CREATE TABLE `recent_dictionary_searches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dictionary_entry_id` int(11) NOT NULL,
  `search_timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recent_dictionary_searches`
--

INSERT INTO `recent_dictionary_searches` (`id`, `user_id`, `dictionary_entry_id`, `search_timestamp`) VALUES
(16, 6, 8, '2025-01-25 12:21:32'),
(17, 6, 9, '2025-01-25 12:21:45'),
(18, 6, 9, '2025-01-25 12:21:49'),
(19, 6, 8, '2025-01-25 12:21:50'),
(20, 6, 8, '2025-01-25 13:19:04'),
(21, 6, 9, '2025-01-25 13:19:13'),
(22, 6, 9, '2025-01-25 13:19:23'),
(23, 6, 8, '2025-01-26 22:30:32'),
(24, 6, 22, '2025-01-26 22:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `recent_translated_words`
--

CREATE TABLE `recent_translated_words` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `translated_word` varchar(255) NOT NULL,
  `translation` text NOT NULL,
  `direction` enum('yakan-to-english','english-to-yakan') NOT NULL,
  `translation_timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(6, 'intong', '$2y$10$yu5y4dU5zErk2HA/TY2/e.G2PD8i8zm3igL4p39qSzQ/UK2VZYuDS'),
(7, 'bruce wayne', '$2y$10$WgYDouFiGxt1AbXtF5cJ1evPX3rfy0JlJmG0ANzaroLTesS4CwvMW');

-- --------------------------------------------------------

--
-- Table structure for table `validators`
--

CREATE TABLE `validators` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `validators`
--

INSERT INTO `validators` (`id`, `full_name`, `username`, `password`, `created_at`) VALUES
(1, 'Jose Rizal', 'validator', '$2y$10$bzS7E9w/ElAM54ATbKGaquXod/JVoebqeEWmYGKC2sE2MgyxaPahm', '2025-01-24 22:30:10');

-- --------------------------------------------------------

--
-- Table structure for table `yakan_sentences`
--

CREATE TABLE `yakan_sentences` (
  `id` int(11) NOT NULL,
  `yakan_sentence` text NOT NULL,
  `audio_path` varchar(255) DEFAULT NULL,
  `submitted_by_contributor_id` int(11) NOT NULL,
  `validated_by_validator_id` int(11) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `validation_timestamp` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `english_sentence_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `yakan_sentences`
--

INSERT INTO `yakan_sentences` (`id`, `yakan_sentence`, `audio_path`, `submitted_by_contributor_id`, `validated_by_validator_id`, `is_validated`, `validation_timestamp`, `created_at`, `english_sentence_id`) VALUES
(4, 'intag ne kew?', 'uploads/2.m4a', 1, 1, 1, '2025-01-25 04:07:15', '2025-01-25 00:02:01', 5),
(5, 'salam', 'uploads/2.m4a', 1, 1, 1, '2025-01-25 00:24:29', '2025-01-25 00:24:13', 6),
(6, 'intag ne kew?', 'uploads/1.m4a', 1, 1, 1, '2025-01-25 04:07:14', '2025-01-25 04:05:10', 7),
(7, 'ine alen nuh?', 'uploads/2.m4a', 1, 1, 1, '2025-01-25 04:07:08', '2025-01-25 04:06:35', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contributors`
--
ALTER TABLE `contributors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dictionary_entries`
--
ALTER TABLE `dictionary_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_submitted_by_contributor_id` (`submitted_by_contributor_id`),
  ADD KEY `fk_validated_by_validator_id` (`validated_by_validator_id`);

--
-- Indexes for table `english_sentences`
--
ALTER TABLE `english_sentences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitted_by_contributor_id` (`submitted_by_contributor_id`),
  ADD KEY `validated_by_validator_id` (`validated_by_validator_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `media_history`
--
ALTER TABLE `media_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recent_dictionary_searches`
--
ALTER TABLE `recent_dictionary_searches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_dictionary_search` (`user_id`),
  ADD KEY `fk_dictionary_entry_search` (`dictionary_entry_id`);

--
-- Indexes for table `recent_translated_words`
--
ALTER TABLE `recent_translated_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_translated_word` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `validators`
--
ALTER TABLE `validators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yakan_sentences`
--
ALTER TABLE `yakan_sentences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitted_by_contributor_id` (`submitted_by_contributor_id`),
  ADD KEY `validated_by_validator_id` (`validated_by_validator_id`),
  ADD KEY `fk_english_sentence_id` (`english_sentence_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contributors`
--
ALTER TABLE `contributors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dictionary_entries`
--
ALTER TABLE `dictionary_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `english_sentences`
--
ALTER TABLE `english_sentences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `media_history`
--
ALTER TABLE `media_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recent_dictionary_searches`
--
ALTER TABLE `recent_dictionary_searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `recent_translated_words`
--
ALTER TABLE `recent_translated_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `validators`
--
ALTER TABLE `validators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `yakan_sentences`
--
ALTER TABLE `yakan_sentences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dictionary_entries`
--
ALTER TABLE `dictionary_entries`
  ADD CONSTRAINT `fk_submitted_by_contributor_id` FOREIGN KEY (`submitted_by_contributor_id`) REFERENCES `contributors` (`id`),
  ADD CONSTRAINT `fk_validated_by_validator_id` FOREIGN KEY (`validated_by_validator_id`) REFERENCES `validators` (`id`);

--
-- Constraints for table `english_sentences`
--
ALTER TABLE `english_sentences`
  ADD CONSTRAINT `english_sentences_ibfk_1` FOREIGN KEY (`submitted_by_contributor_id`) REFERENCES `contributors` (`id`),
  ADD CONSTRAINT `english_sentences_ibfk_2` FOREIGN KEY (`validated_by_validator_id`) REFERENCES `validators` (`id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recent_dictionary_searches`
--
ALTER TABLE `recent_dictionary_searches`
  ADD CONSTRAINT `fk_dictionary_entry_search` FOREIGN KEY (`dictionary_entry_id`) REFERENCES `dictionary_entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_dictionary_search` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recent_translated_words`
--
ALTER TABLE `recent_translated_words`
  ADD CONSTRAINT `fk_user_translated_word` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `yakan_sentences`
--
ALTER TABLE `yakan_sentences`
  ADD CONSTRAINT `fk_english_sentence_id` FOREIGN KEY (`english_sentence_id`) REFERENCES `english_sentences` (`id`),
  ADD CONSTRAINT `yakan_sentences_ibfk_1` FOREIGN KEY (`submitted_by_contributor_id`) REFERENCES `contributors` (`id`),
  ADD CONSTRAINT `yakan_sentences_ibfk_2` FOREIGN KEY (`validated_by_validator_id`) REFERENCES `validators` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
