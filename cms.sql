

--
-- База данных: `cms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `irb_comments`
--

CREATE TABLE IF NOT EXISTS `irb_comments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_parent` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `owner` enum('category','page','users','user') NOT NULL,
  `author` varchar(20) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `irb_comments`
--


-- --------------------------------------------------------

--
-- Структура таблицы `irb_pages`
--

CREATE TABLE IF NOT EXISTS `irb_pages` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_parent` int(5) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `irb_pages`
--


-- --------------------------------------------------------

--
-- Структура таблицы `irb_pages_category`
--

CREATE TABLE IF NOT EXISTS `irb_pages_category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_parent` int(5) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `irb_pages_category`
--

INSERT INTO `irb_pages_category` (`id`, `id_parent`, `name`) VALUES
(1, 0, 'Категория 1'),
(2, 1, 'Категория 1.1'),
(3, 1, 'Категория 1.2'),
(4, 0, 'Категория 2'),
(5, 4, 'Категория 2.1'),
(6, 4, 'Категория 2.2');

-- --------------------------------------------------------

--
-- Структура таблицы `irb_users`
--

CREATE TABLE IF NOT EXISTS `irb_users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL,
  `role` enum('all','trusted') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `irb_users`
--

INSERT INTO `irb_users` (`id`, `login`, `password`, `role`) VALUES
(1, 'юзер 1', '12345', 'all'),
(2, 'юзер 2', '12345', 'trusted');