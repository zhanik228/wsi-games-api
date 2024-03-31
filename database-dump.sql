-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 31 2024 г., 14:24
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `games`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin1', '$2y$12$NP.TA5tMtdRkk6hK6WFMNuCHHdJ4VDXEVoiSLg/EqFcwICbNIgxHC', '2024-03-31 08:24:05', '2024-03-31 08:24:05'),
(2, 'admin2', '$2y$12$9TCFfRLGeAy9JI5/ElFGNeHyGYd8DL1mwBLjoAqtVswClutVgLiG2', '2024-03-31 08:24:06', '2024-03-31 08:24:06');

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`id`, `author_id`, `title`, `slug`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'Demo Game 1', 'demo-game-1', 'This is demo game 1', '2024-03-31 08:24:06', NULL, NULL),
(2, 4, 'Demo Game 2', 'demo-game-2', 'This is demo game 2', '2024-03-29 08:24:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `game_versions`
--

CREATE TABLE `game_versions` (
  `id` bigint UNSIGNED NOT NULL,
  `game_id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `game_versions`
--

INSERT INTO `game_versions` (`id`, `game_id`, `version`, `path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'v1', 'games/1/v1', '2024-03-29 08:24:06', '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(2, 1, 'v2', 'games/1/v2', '2024-03-29 08:24:06', '2024-03-31 08:24:06', NULL),
(3, 2, 'v1', 'games/2/v1', '2024-03-28 08:24:06', '2024-03-31 08:24:06', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2024_03_30_081109_create_admins_table', 1),
(4, '2024_03_30_081318_create_games_table', 1),
(5, '2024_03_30_081626_create_game_versions_table', 1),
(6, '2024_03_30_081811_create_scores_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `scores`
--

CREATE TABLE `scores` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `game_version_id` bigint UNSIGNED NOT NULL,
  `score` double(6,1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `game_version_id`, `score`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(2, 1, 1, 15.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(3, 1, 2, 12.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(4, 2, 3, 20.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(5, 2, 3, 30.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(6, 3, 2, 1000.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(7, 3, 2, -300.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(8, 4, 2, 5.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06'),
(9, 4, 3, 200.0, '2024-03-31 08:24:06', '2024-03-31 08:24:06');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `delete_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `updated_at`, `deleted_at`, `delete_reason`) VALUES
(1, 'player1', '$2y$12$51Ag0OVn/3eZ16UkpziCMuOjxF4LCR1XLlFrYKKJnuNFL2G1zTIJu', '2024-03-31 08:24:06', '2024-03-31 08:24:06', NULL, NULL),
(2, 'player2', '$2y$12$mdt.f.kHxabrdAxuPGtBxOVrNYDgjmXTwABA/okWjwGwbbj3b8E6C', '2024-03-31 08:24:06', '2024-03-31 08:24:06', NULL, NULL),
(3, 'dev1', '$2y$12$Cv4kg8GBAqmN59xqa74PPeT/ssTtQhG8yQxF9WT5RQTglNzNvuEuy', '2024-03-31 08:24:06', '2024-03-31 08:24:06', NULL, NULL),
(4, 'dev2', '$2y$12$coCQciGGZErM2VFIy62LJOhozE/AUgBKrfEMZJGvREspQ7rzTBCza', '2024-03-31 08:24:06', '2024-03-31 08:24:06', NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `games_author_id_foreign` (`author_id`);

--
-- Индексы таблицы `game_versions`
--
ALTER TABLE `game_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_versions_game_id_foreign` (`game_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scores_user_id_foreign` (`user_id`),
  ADD KEY `scores_game_version_id_foreign` (`game_version_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `game_versions`
--
ALTER TABLE `game_versions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `scores`
--
ALTER TABLE `scores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `game_versions`
--
ALTER TABLE `game_versions`
  ADD CONSTRAINT `game_versions_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);

--
-- Ограничения внешнего ключа таблицы `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_game_version_id_foreign` FOREIGN KEY (`game_version_id`) REFERENCES `game_versions` (`id`),
  ADD CONSTRAINT `scores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
