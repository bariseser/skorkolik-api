-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: mysql_db:3306
-- Üretim Zamanı: 10 Haz 2025, 15:06:52
-- Sunucu sürümü: 9.2.0
-- PHP Sürümü: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `skorkolik`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coaches`
--

CREATE TABLE `coaches` (
  `id` bigint NOT NULL,
  `provider_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `countries`
--

CREATE TABLE `countries` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `flag` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `order` bigint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `leagues`
--

CREATE TABLE `leagues` (
  `id` bigint NOT NULL,
  `provider_id` bigint DEFAULT NULL,
  `country_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `order` bigint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `league_seasons`
--

CREATE TABLE `league_seasons` (
  `id` bigint NOT NULL,
  `league_id` bigint DEFAULT NULL,
  `year` bigint DEFAULT NULL,
  `current` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `standings` tinyint(1) DEFAULT NULL,
  `players` tinyint(1) DEFAULT NULL,
  `events` tinyint(1) DEFAULT NULL,
  `lineups` tinyint(1) DEFAULT NULL,
  `statistics_fixtures` tinyint(1) DEFAULT NULL,
  `statistics_players` tinyint(1) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `prediction` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `matches`
--

CREATE TABLE `matches` (
  `id` bigint NOT NULL,
  `provider_id` bigint DEFAULT NULL,
  `league_id` bigint DEFAULT NULL,
  `season_id` bigint DEFAULT NULL,
  `round_id` bigint DEFAULT NULL,
  `home_team_id` bigint DEFAULT NULL,
  `away_team_id` bigint DEFAULT NULL,
  `status` enum('not_started','playing','suspended','played','postponed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'not_started',
  `period` enum('first_half','second_half','half_time','extra_time','extra_half_time','penalty','unknown') COLLATE utf8mb4_general_ci DEFAULT 'unknown',
  `home_goals` tinyint DEFAULT NULL,
  `away_goals` tinyint DEFAULT NULL,
  `halftime_home` tinyint DEFAULT NULL,
  `halftime_away` tinyint DEFAULT NULL,
  `fulltime_home` tinyint DEFAULT NULL,
  `fulltime_away` tinyint DEFAULT NULL,
  `extra_home` tinyint DEFAULT NULL,
  `extra_away` tinyint DEFAULT NULL,
  `penalty_home` tinyint DEFAULT NULL,
  `penalty_away` tinyint DEFAULT NULL,
  `home_winner` tinyint(1) DEFAULT NULL,
  `away_winner` tinyint(1) DEFAULT NULL,
  `timestamp` bigint DEFAULT NULL,
  `event_imported` tinyint(1) DEFAULT NULL,
  `lineup_imported` tinyint(1) DEFAULT NULL,
  `team_stats_imported` tinyint(1) DEFAULT NULL,
  `player_stats_imported` tinyint(1) DEFAULT NULL,
  `match_date` datetime DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `match_events`
--

CREATE TABLE `match_events` (
  `id` bigint NOT NULL,
  `match_id` bigint DEFAULT NULL,
  `team_id` bigint DEFAULT NULL,
  `player_id` bigint DEFAULT NULL,
  `assist_id` bigint DEFAULT NULL,
  `elapsed` bigint DEFAULT NULL,
  `extra` bigint DEFAULT NULL,
  `event` enum('G','C','V','S','UNKNOWN') COLLATE utf8mb4_general_ci DEFAULT 'UNKNOWN',
  `detail` enum('G','PG','OW','MP','YC','RC','GC','PC','SB','SB-1','SB-2','SB-3','SB-4','SB-5','SB-6','SB-7','SB-8','SB-9','SB-10','UNKNOWN') COLLATE utf8mb4_general_ci DEFAULT 'UNKNOWN',
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `match_lineups`
--

CREATE TABLE `match_lineups` (
  `id` bigint NOT NULL,
  `match_id` bigint DEFAULT NULL,
  `team_id` bigint DEFAULT NULL,
  `coach_id` bigint DEFAULT NULL,
  `formation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `match_lineup_players`
--

CREATE TABLE `match_lineup_players` (
  `id` bigint NOT NULL,
  `lineup_id` bigint DEFAULT NULL,
  `player_id` bigint DEFAULT NULL,
  `number` bigint DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `grid` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_starter` tinyint(1) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `match_player_statistics`
--

CREATE TABLE `match_player_statistics` (
  `id` bigint NOT NULL,
  `match_id` bigint DEFAULT NULL,
  `player_id` bigint DEFAULT NULL,
  `team_id` bigint DEFAULT NULL,
  `stats` json DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `match_team_statistics`
--

CREATE TABLE `match_team_statistics` (
  `id` bigint NOT NULL,
  `match_id` bigint DEFAULT NULL,
  `team_id` bigint DEFAULT NULL,
  `stats` json DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `players`
--

CREATE TABLE `players` (
  `id` bigint NOT NULL,
  `provider_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birth_date` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `weight` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number` tinyint DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `detail` tinyint(1) DEFAULT NULL,
  `stats` tinyint(1) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `predictions`
--

CREATE TABLE `predictions` (
  `id` bigint NOT NULL,
  `match_id` bigint DEFAULT NULL,
  `prediction` json DEFAULT NULL,
  `teams` json DEFAULT NULL,
  `comparison` json DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rounds`
--

CREATE TABLE `rounds` (
  `id` bigint NOT NULL,
  `league_id` bigint DEFAULT NULL,
  `season_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `order` tinyint DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `standings`
--

CREATE TABLE `standings` (
  `id` bigint NOT NULL,
  `league_id` bigint DEFAULT NULL,
  `season_id` bigint DEFAULT NULL,
  `team_id` bigint DEFAULT NULL,
  `rank` bigint DEFAULT NULL,
  `point` bigint DEFAULT NULL,
  `goals_diff` bigint DEFAULT NULL,
  `form` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `played` bigint DEFAULT NULL,
  `win` bigint DEFAULT NULL,
  `draw` bigint DEFAULT NULL,
  `lose` bigint DEFAULT NULL,
  `goals_for` bigint DEFAULT NULL,
  `goals_against` bigint DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `teams`
--

CREATE TABLE `teams` (
  `id` bigint NOT NULL,
  `provider_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `founded` bigint DEFAULT NULL,
  `national` tinyint(1) DEFAULT NULL,
  `detail` tinyint(1) DEFAULT NULL,
  `stats` tinyint(1) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `team_stats`
--

CREATE TABLE `team_stats` (
  `id` bigint NOT NULL,
  `league_id` bigint DEFAULT NULL,
  `season_id` bigint DEFAULT NULL,
  `team_id` bigint DEFAULT NULL,
  `stats` json DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_coaches_provider_id` (`provider_id`),
  ADD KEY `idx_provider` (`provider_id`);

--
-- Tablo için indeksler `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `slug` (`slug`),
  ADD KEY `active` (`active`),
  ADD KEY `order` (`order`);

--
-- Tablo için indeksler `leagues`
--
ALTER TABLE `leagues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_provider_id` (`provider_id`),
  ADD KEY `idx_leagues_country` (`country_id`),
  ADD KEY `active` (`active`),
  ADD KEY `order` (`order`),
  ADD KEY `slug` (`slug`),
  ADD KEY `type` (`type`);

--
-- Tablo için indeksler `league_seasons`
--
ALTER TABLE `league_seasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_league` (`league_id`),
  ADD KEY `idx_year` (`year`),
  ADD KEY `current` (`current`),
  ADD KEY `standings` (`standings`),
  ADD KEY `players` (`players`),
  ADD KEY `events` (`events`),
  ADD KEY `lineups` (`lineups`),
  ADD KEY `statistics_fixtures` (`statistics_fixtures`),
  ADD KEY `statistics_players` (`statistics_players`),
  ADD KEY `prediction` (`prediction`);

--
-- Tablo için indeksler `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_provider` (`provider_id`),
  ADD KEY `idx_league` (`league_id`),
  ADD KEY `idx_season` (`season_id`),
  ADD KEY `idx_round` (`round_id`),
  ADD KEY `idx_home_team` (`home_team_id`),
  ADD KEY `idx_away_team` (`away_team_id`),
  ADD KEY `status` (`status`),
  ADD KEY `period` (`period`),
  ADD KEY `timestamp` (`timestamp`),
  ADD KEY `event_imported` (`event_imported`),
  ADD KEY `lineup_imported` (`lineup_imported`),
  ADD KEY `team_stats_imported` (`team_stats_imported`),
  ADD KEY `player_stats_imported` (`player_stats_imported`),
  ADD KEY `match_date` (`match_date`);

--
-- Tablo için indeksler `match_events`
--
ALTER TABLE `match_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_match` (`match_id`),
  ADD KEY `idx_team` (`team_id`),
  ADD KEY `idx_player` (`player_id`),
  ADD KEY `idx_assist` (`assist_id`),
  ADD KEY `detail` (`detail`),
  ADD KEY `event` (`event`);

--
-- Tablo için indeksler `match_lineups`
--
ALTER TABLE `match_lineups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_match_id` (`match_id`),
  ADD KEY `idx_team` (`team_id`),
  ADD KEY `idx_coach` (`coach_id`);

--
-- Tablo için indeksler `match_lineup_players`
--
ALTER TABLE `match_lineup_players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lineup` (`lineup_id`),
  ADD KEY `idx_player` (`player_id`),
  ADD KEY `is_starter` (`is_starter`);

--
-- Tablo için indeksler `match_player_statistics`
--
ALTER TABLE `match_player_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_match` (`match_id`),
  ADD KEY `idx_player` (`player_id`),
  ADD KEY `idx_team` (`team_id`);

--
-- Tablo için indeksler `match_team_statistics`
--
ALTER TABLE `match_team_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_match` (`match_id`),
  ADD KEY `idx_team` (`team_id`);

--
-- Tablo için indeksler `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_players_provider_id` (`provider_id`),
  ADD KEY `idx_provider` (`provider_id`),
  ADD KEY `detail` (`detail`),
  ADD KEY `stats` (`stats`),
  ADD KEY `position` (`position`);

--
-- Tablo için indeksler `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_match_id` (`match_id`);

--
-- Tablo için indeksler `rounds`
--
ALTER TABLE `rounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_league` (`league_id`),
  ADD KEY `idx_season` (`season_id`),
  ADD KEY `idx_order` (`order`);

--
-- Tablo için indeksler `standings`
--
ALTER TABLE `standings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_league_id` (`league_id`),
  ADD KEY `idx_season_id` (`season_id`),
  ADD KEY `idx_team_id` (`team_id`);

--
-- Tablo için indeksler `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_provider` (`provider_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `code` (`code`),
  ADD KEY `slug` (`slug`),
  ADD KEY `detail` (`detail`),
  ADD KEY `stats` (`stats`);

--
-- Tablo için indeksler `team_stats`
--
ALTER TABLE `team_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_league` (`league_id`),
  ADD KEY `idx_season` (`season_id`),
  ADD KEY `idx_team` (`team_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `coaches`
--
ALTER TABLE `coaches`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `leagues`
--
ALTER TABLE `leagues`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `league_seasons`
--
ALTER TABLE `league_seasons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `match_events`
--
ALTER TABLE `match_events`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `match_lineups`
--
ALTER TABLE `match_lineups`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `match_lineup_players`
--
ALTER TABLE `match_lineup_players`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `match_player_statistics`
--
ALTER TABLE `match_player_statistics`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `match_team_statistics`
--
ALTER TABLE `match_team_statistics`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `players`
--
ALTER TABLE `players`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `rounds`
--
ALTER TABLE `rounds`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `standings`
--
ALTER TABLE `standings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `team_stats`
--
ALTER TABLE `team_stats`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `leagues`
--
ALTER TABLE `leagues`
  ADD CONSTRAINT `fk_leagues_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Tablo kısıtlamaları `league_seasons`
--
ALTER TABLE `league_seasons`
  ADD CONSTRAINT `fk_league_seasons_league` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`);

--
-- Tablo kısıtlamaları `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `fk_matches_away_team` FOREIGN KEY (`away_team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `fk_matches_home_team` FOREIGN KEY (`home_team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `fk_matches_league` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`),
  ADD CONSTRAINT `fk_matches_season` FOREIGN KEY (`season_id`) REFERENCES `league_seasons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
