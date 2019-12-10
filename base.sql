-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 10 2019 г., 23:00
-- Версия сервера: 10.1.43-MariaDB-0ubuntu0.18.04.1
-- Версия PHP: 7.2.24-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `createhost`
--

-- --------------------------------------------------------

--
-- Структура таблицы `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `server_id` int(10) DEFAULT NULL,
  `expense_rplan` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `expense_sum` decimal(10,2) DEFAULT NULL,
  `expense_status` int(1) DEFAULT NULL,
  `expense_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `image_id` int(10) NOT NULL,
  `image_os` varchar(16) DEFAULT NULL,
  `image_name` varchar(64) DEFAULT NULL,
  `image_text` varchar(64) DEFAULT NULL,
  `image_ver` varchar(32) DEFAULT NULL,
  `image_status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`image_id`, `image_os`, `image_name`, `image_text`, `image_ver`, `image_status`) VALUES
(1, 'ubuntu', 'ubuntu_18.04_64_001_master', 'Ubuntu 18.04 64bit', '18.04 64bit', 1),
(2, 'ubuntu', 'ubuntu_16.04_64_001_master', 'Ubuntu 16.04 64bit', '16.04 64bit', 1),
(3, 'ubuntu', 'ubuntu_14.04_64_002_master', 'Ubuntu 14.04 64bit', '14.04 64bit', 1),
(4, 'debian', 'debian_10_64_001_master', 'Debian 10 64bit', '10 64bit', 1),
(5, 'debian', 'debian_9_64_001_master', 'Debian 9 64bit', '9 64bit', 1),
(6, 'debian', 'debian_8_64_001_master', 'Debian 8 64bit', '8 64bit', 1),
(7, 'centos', 'centos_8_64_001_master', 'CentOS 8 64bit', '8 64bit', 1),
(8, 'centos', 'centos_7_64_001_master', 'CentOS 7 64bit', '7 64bit', 1),
(9, 'centos', 'centos_6_64_001_master', 'CentOS 6 64bit', '6 64bit', 1),
(10, 'fedora', 'fedora_27_64_001_master', 'Fedora 27 64bit', '27 64bit', 1),
(11, 'opensuse', 'opensuse_42.3_64_001_master', 'OpenSUSE 42.3 64bit', '42.3 64bit', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `payment_sum` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(10) DEFAULT NULL,
  `payment_invoice` int(10) DEFAULT NULL,
  `payment_resurl` varchar(255) DEFAULT NULL,
  `payment_sucurl` varchar(255) DEFAULT NULL,
  `payment_status` int(1) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `payment_sum`, `payment_method`, `payment_invoice`, `payment_resurl`, `payment_sucurl`, `payment_status`, `payment_date`) VALUES
(1, 1, '100.00', 'card', NULL, NULL, NULL, 0, '2019-11-13 23:10:19');

-- --------------------------------------------------------

--
-- Структура таблицы `servers`
--

CREATE TABLE `servers` (
  `server_id` int(10) NOT NULL,
  `server_ctid` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `image_id` int(10) DEFAULT NULL,
  `server_name` varchar(32) DEFAULT NULL,
  `server_rplan` varchar(10) DEFAULT NULL,
  `server_password` varchar(10) DEFAULT NULL,
  `server_ip` varchar(15) DEFAULT NULL,
  `server_status` int(1) DEFAULT NULL,
  `server_active` int(1) DEFAULT NULL,
  `server_lock` int(1) DEFAULT NULL,
  `server_date_create` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `ticket_subject` varchar(128) DEFAULT NULL,
  `ticket_header` varchar(128) DEFAULT NULL,
  `ticket_text` text,
  `ticket_status` int(1) DEFAULT NULL,
  `ticket_close` int(1) DEFAULT NULL,
  `ticket_date_close` datetime DEFAULT NULL,
  `ticket_date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets_messages`
--

CREATE TABLE `tickets_messages` (
  `ticket_message_id` int(10) NOT NULL,
  `ticket_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `ticket_message` text,
  `ticket_message_date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_email` varchar(96) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_firstname` varchar(32) DEFAULT NULL,
  `user_lastname` varchar(32) DEFAULT NULL,
  `user_token` varchar(32) DEFAULT NULL,
  `user_phone` varchar(11) DEFAULT NULL,
  `user_geo` varchar(32) DEFAULT NULL,
  `user_status` int(1) DEFAULT NULL,
  `user_balance` decimal(10,2) DEFAULT NULL,
  `user_access_level` int(1) DEFAULT NULL,
  `user_date_reg` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_password`, `user_firstname`, `user_lastname`, `user_token`, `user_phone`, `user_geo`, `user_status`, `user_balance`, `user_access_level`, `user_date_reg`) VALUES
(1, 'mzhidkoff@ya.ru', '$2y$10$g8fn/cMX9c0VneW/lofaeubphVYV8ncTbfpgagavg.f6D41WYYm9K', NULL, NULL, 'usertoken', NULL, NULL, 1, '-0.37', 1, '2019-11-12 15:44:07');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Индексы таблицы `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`server_id`),
  ADD UNIQUE KEY `server_ctid` (`server_ctid`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Индексы таблицы `tickets_messages`
--
ALTER TABLE `tickets_messages`
  ADD PRIMARY KEY (`ticket_message_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `servers`
--
ALTER TABLE `servers`
  MODIFY `server_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tickets_messages`
--
ALTER TABLE `tickets_messages`
  MODIFY `ticket_message_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
