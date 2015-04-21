-- phpMyAdmin SQL Dump
-- version 4.2.6deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 17 2015 г., 20:21
-- Версия сервера: 5.5.41-0ubuntu0.14.10.1
-- Версия PHP: 5.5.12-2ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `pulscen`
--

-- --------------------------------------------------------

--
-- Структура таблицы `advs`
--

CREATE TABLE IF NOT EXISTS `advs` (
`id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','hidden','template') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `advs`
--

INSERT INTO `advs` (`id`, `owner`, `added`, `status`) VALUES
(1, 1, '2015-04-17 16:50:28', 'template'),
(2, 1, '2015-04-17 17:08:30', 'active'),
(3, 1, '2015-04-17 17:08:35', 'active'),
(4, 1, '2015-04-17 17:08:37', 'active'),
(5, 1, '2015-04-17 17:10:49', 'active'),
(6, 1, '2015-04-17 17:11:18', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `adv_props`
--

CREATE TABLE IF NOT EXISTS `adv_props` (
`id` int(11) NOT NULL,
  `adv` int(11) NOT NULL,
  `prop` int(11) NOT NULL,
  `val` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Дамп данных таблицы `adv_props`
--

INSERT INTO `adv_props` (`id`, `adv`, `prop`, `val`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 9),
(3, 1, 3, 10),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 6),
(9, 1, 10, 6),
(16, 2, 1, 1),
(17, 2, 2, 9),
(18, 2, 3, 10),
(19, 2, 4, 1),
(20, 2, 5, 1),
(21, 2, 6, 6),
(22, 2, 10, 6),
(23, 3, 1, 1),
(24, 3, 2, 9),
(25, 3, 3, 10),
(26, 3, 4, 1),
(27, 3, 5, 1),
(28, 3, 6, 6),
(29, 3, 10, 6),
(30, 4, 1, 1),
(31, 4, 2, 9),
(32, 4, 3, 10),
(33, 4, 4, 1),
(34, 4, 5, 1),
(35, 4, 6, 6),
(36, 4, 10, 6),
(37, 5, 1, 1),
(38, 5, 2, 9),
(39, 5, 3, 10),
(40, 5, 4, 1),
(41, 5, 5, 1),
(42, 5, 6, 6),
(43, 5, 10, 6),
(44, 6, 1, 1),
(45, 6, 2, 9),
(46, 6, 3, 10),
(47, 6, 4, 1),
(48, 6, 5, 1),
(49, 6, 6, 6),
(50, 6, 10, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `fields_float`
--

CREATE TABLE IF NOT EXISTS `fields_float` (
`id` int(11) NOT NULL,
  `adv` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `val` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `fields_int`
--

CREATE TABLE IF NOT EXISTS `fields_int` (
`id` int(11) NOT NULL,
  `adv` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `val` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `fields_text`
--

CREATE TABLE IF NOT EXISTS `fields_text` (
`id` int(11) NOT NULL,
  `adv` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `val` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `field_names`
--

CREATE TABLE IF NOT EXISTS `field_names` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('int','float','text') NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '100'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `field_names`
--

INSERT INTO `field_names` (`id`, `name`, `type`, `sort`) VALUES
(1, 'Цена', 'int', 100),
(2, 'Описание', 'text', 100);

-- --------------------------------------------------------

--
-- Структура таблицы `props_float`
--

CREATE TABLE IF NOT EXISTS `props_float` (
`id` int(11) NOT NULL,
  `prop` int(11) NOT NULL,
  `val` float NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `props_float`
--

INSERT INTO `props_float` (`id`, `prop`, `val`, `sort`) VALUES
(1, 5, 0.4, 0),
(2, 5, 6, 0),
(3, 5, 10, 0),
(4, 5, 16, 0),
(5, 6, 10, 0),
(6, 6, 16, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `props_int`
--

CREATE TABLE IF NOT EXISTS `props_int` (
`id` int(11) NOT NULL,
  `prop` int(11) NOT NULL,
  `val` int(11) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `props_int`
--

INSERT INTO `props_int` (`id`, `prop`, `val`, `sort`) VALUES
(1, 4, 16, 0),
(2, 4, 25, 0),
(3, 4, 40, 0),
(4, 4, 63, 0),
(5, 4, 100, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `props_text`
--

CREATE TABLE IF NOT EXISTS `props_text` (
`id` int(11) NOT NULL,
  `prop` int(11) NOT NULL,
  `val` text NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `props_text`
--

INSERT INTO `props_text` (`id`, `prop`, `val`, `sort`) VALUES
(1, 1, 'Екатеринбург', 100),
(2, 1, 'Москва', 100),
(3, 1, 'Волгоград', 100),
(4, 1, 'Марс', 100),
(5, 10, 'Покупается', 100),
(6, 10, 'Продается', 100),
(7, 2, 'завод раз', 100),
(8, 2, 'завод два', 101),
(9, 2, 'и обчелся', 102),
(10, 3, 'а модель только одна', 100);

-- --------------------------------------------------------

--
-- Структура таблицы `prop_names`
--

CREATE TABLE IF NOT EXISTS `prop_names` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('int','float','text') NOT NULL,
  `kind` enum('props','fields') NOT NULL,
  `sort_disp` int(11) NOT NULL DEFAULT '100',
  `sort_filter` int(11) NOT NULL DEFAULT '100',
  `widget_type` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `prop_names`
--

INSERT INTO `prop_names` (`id`, `name`, `type`, `kind`, `sort_disp`, `sort_filter`, `widget_type`) VALUES
(1, 'Город', 'text', 'props', 100, 100, 'select'),
(2, 'Завод производитель', 'text', 'props', 100, 100, 'select'),
(3, 'Модель', 'text', 'props', 100, 100, 'select'),
(4, 'Мощность', 'int', 'props', 100, 100, 'select'),
(5, 'Нижнее напряжение', 'float', 'props', 100, 100, 'select'),
(6, 'Верхнее напряжение', 'float', 'props', 100, 100, 'select'),
(7, 'Цена', 'int', 'fields', 100, 100, 'integer'),
(8, 'Описание', 'text', 'fields', 100, 0, 'bigtext'),
(10, 'Состояние', 'text', 'props', 100, 100, 'select');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `salt` varchar(40) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `pass`, `salt`) VALUES
(1, 'nikandfor', 'nikifor', '06d3896f08b50eff8bc1457e86c012ea', 'oolp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advs`
--
ALTER TABLE `advs`
 ADD PRIMARY KEY (`id`), ADD KEY `owner` (`owner`), ADD KEY `owner_2` (`owner`);

--
-- Indexes for table `adv_props`
--
ALTER TABLE `adv_props`
 ADD PRIMARY KEY (`id`), ADD KEY `adv` (`adv`,`prop`), ADD KEY `prop` (`prop`);

--
-- Indexes for table `fields_float`
--
ALTER TABLE `fields_float`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fields_int`
--
ALTER TABLE `fields_int`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fields_text`
--
ALTER TABLE `fields_text`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `field_names`
--
ALTER TABLE `field_names`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `props_float`
--
ALTER TABLE `props_float`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `props_int`
--
ALTER TABLE `props_int`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `props_text`
--
ALTER TABLE `props_text`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prop_names`
--
ALTER TABLE `prop_names`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advs`
--
ALTER TABLE `advs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `adv_props`
--
ALTER TABLE `adv_props`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `fields_float`
--
ALTER TABLE `fields_float`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fields_int`
--
ALTER TABLE `fields_int`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fields_text`
--
ALTER TABLE `fields_text`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `field_names`
--
ALTER TABLE `field_names`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `props_float`
--
ALTER TABLE `props_float`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `props_int`
--
ALTER TABLE `props_int`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `props_text`
--
ALTER TABLE `props_text`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `prop_names`
--
ALTER TABLE `prop_names`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `adv_props`
--
ALTER TABLE `adv_props`
ADD CONSTRAINT `adv_props_ibfk_1` FOREIGN KEY (`adv`) REFERENCES `advs` (`id`),
ADD CONSTRAINT `adv_props_ibfk_2` FOREIGN KEY (`prop`) REFERENCES `prop_names` (`id`);

--
-- Ограничения внешнего ключа таблицы `fields_int`
--
ALTER TABLE `fields_int`
ADD CONSTRAINT `fields_int_ibfk_1` FOREIGN KEY (`id`) REFERENCES `advs` (`owner`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
