-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 17 2011 г., 00:55
-- Версия сервера: 5.1.49
-- Версия PHP: 5.3.3-1ubuntu9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `joostina`
--

-- --------------------------------------------------------

--
-- Структура таблицы `jos_access`
--

CREATE TABLE IF NOT EXISTS `jos_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(25) NOT NULL,
  `subsection` varchar(25) NOT NULL,
  `action` varchar(25) NOT NULL,
  `action_label` varchar(255) NOT NULL,
  `accsess` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section` (`section`,`subsection`,`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_access`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_attached`
--

CREATE TABLE IF NOT EXISTS `jos_attached` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_ext` varchar(25) NOT NULL,
  `file_mime` varchar(50) NOT NULL,
  `file_size` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_attached`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_blog`
--

CREATE TABLE IF NOT EXISTS `jos_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `introtext` text,
  `fulltext` longtext,
  `params` text,
  `category_id` tinyint(2) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`slug`),
  KEY `state` (`state`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_blog`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_blog_category`
--

CREATE TABLE IF NOT EXISTS `jos_blog_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `params` text NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`slug`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_blog_category`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_categories`
--

CREATE TABLE IF NOT EXISTS `jos_categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lft` mediumint(8) unsigned NOT NULL,
  `rgt` mediumint(8) unsigned NOT NULL,
  `level` mediumint(8) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `moved` tinyint(1) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_categories`
--

INSERT INTO `jos_categories` (`id`, `lft`, `rgt`, `level`, `parent_id`, `moved`, `name`, `group`, `slug`, `state`) VALUES
(1, 1, 2, 0, 0, 0, 'root', '', 'root', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_categories_details`
--

CREATE TABLE IF NOT EXISTS `jos_categories_details` (
  `cat_id` int(11) unsigned NOT NULL,
  `desc_short` mediumtext NOT NULL,
  `desc_full` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `attachments` text NOT NULL,
  `video` varchar(300) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_categories_details`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_comments`
--

CREATE TABLE IF NOT EXISTS `jos_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `level` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `obj_id` int(11) unsigned NOT NULL DEFAULT '0',
  `obj_option` varchar(30) NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `comment_text` mediumtext NOT NULL,
  `created_at` datetime NOT NULL,
  `params` longtext,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obj_id` (`obj_id`,`obj_option`),
  KEY `state` (`state`),
  KEY `path` (`path`,`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_comments`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_comments_counter`
--

CREATE TABLE IF NOT EXISTS `jos_comments_counter` (
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `last_user_id` int(11) unsigned NOT NULL,
  `last_comment_id` int(11) unsigned NOT NULL,
  `counter` int(11) unsigned NOT NULL,
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_comments_counter`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_content`
--

CREATE TABLE IF NOT EXISTS `jos_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ordering` int(11) unsigned NOT NULL DEFAULT '1',
  `special` tinyint(1) unsigned NOT NULL,
  `attachments` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_content`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_extrafields`
--

CREATE TABLE IF NOT EXISTS `jos_extrafields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `object` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `rules` text NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_extrafields`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_extrafields_data`
--

CREATE TABLE IF NOT EXISTS `jos_extrafields_data` (
  `field_id` int(11) unsigned NOT NULL,
  `obj_id` int(11) unsigned NOT NULL,
  `value` tinytext NOT NULL,
  UNIQUE KEY `field_id` (`field_id`,`obj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_extrafields_data`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_hits`
--

CREATE TABLE IF NOT EXISTS `jos_hits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(20) NOT NULL,
  `hit` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`,`obj_task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_hits`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_metainfo`
--

CREATE TABLE IF NOT EXISTS `jos_metainfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subgroup` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `obj_id` int(11) unsigned DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group` (`group`,`subgroup`,`obj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_metainfo`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules`
--

CREATE TABLE IF NOT EXISTS `jos_modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) unsigned NOT NULL DEFAULT '0',
  `position` varchar(10) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `module` varchar(50) NOT NULL,
  `template` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `client_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `published` (`state`),
  KEY `newsfeeds` (`module`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `jos_modules`
--

INSERT INTO `jos_modules` (`id`, `title`, `content`, `ordering`, `position`, `state`, `module`, `template`, `params`, `client_id`) VALUES
(1, 'Админменю', '', 1, 'top', 1, 'adminmenu', '', '', 1),
(2, 'Системные сообщения', '', 1, 'inset', 0, 'adminmsg', '', '', 1),
(3, 'Главное меню', '', 2, 'left', 1, 'navigation', '', 'null', 0),
(4, 'Авторизация', '', 1, 'header', 1, 'login', '', '{"param1":"","param2":""}', 0),
(5, 'Кнопки быстрого доступа', '', 1, 'icon', 1, 'adminquickicons', '', '', 1),
(6, 'Хлебные крошки', '', 0, 'pathway', 1, 'breadcrumbs', '', '', 0),
(7, 'Про нас', '', 0, 'pathway', 1, 'about', '', '', 0),
(8, 'Новое в блоге', '', 0, 'right', 1, 'blogs', '', '""', 0),
(9, 'Категории', '', 0, 'pathway', 1, 'categories', '', '', 0),
(10, 'Меню категорий', '', 0, 'pathway', 1, 'categories_menu', '', '', 0),
(11, 'Новости', '', 0, 'pathway', 1, 'news', '', '', 0),
(12, 'Промо - текст', '', 0, 'pathway', 1, 'promo', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules_pages`
--

CREATE TABLE IF NOT EXISTS `jos_modules_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) unsigned NOT NULL DEFAULT '0',
  `controller` varchar(25) NOT NULL DEFAULT '0',
  `method` varchar(50) NOT NULL,
  `rule` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_id` (`moduleid`,`controller`,`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `jos_modules_pages`
--

INSERT INTO `jos_modules_pages` (`id`, `moduleid`, `controller`, `method`, `rule`) VALUES
(2, 6, 'all', '', ''),
(3, 3, 'all', '', ''),
(5, 4, 'all', '', ''),
(15, 8, 'mainpage', 'index', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_news`
--

CREATE TABLE IF NOT EXISTS `jos_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `special` tinyint(1) unsigned NOT NULL,
  `attachments` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `type_id` (`category_id`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_news`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_pages`
--

CREATE TABLE IF NOT EXISTS `jos_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_pages`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_params`
--

CREATE TABLE IF NOT EXISTS `jos_params` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `object` varchar(20) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `name` (`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_params`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_quickicons`
--

CREATE TABLE IF NOT EXISTS `jos_quickicons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `alt_text` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `ordering` tinyint(3) unsigned NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `gid` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_quickicons`
--

INSERT INTO `jos_quickicons` (`id`, `title`, `alt_text`, `href`, `icon`, `ordering`, `state`, `gid`) VALUES
(1, 'Комментарии', 'Тыц-мегатыц', 'index2.php?option=comments', 'empathy.png', 5, 1, 0),
(2, 'Пользователи', '', 'index2.php?option=users', 'contact-new.png', 10, 1, 0),
(3, 'Странички', '', 'index2.php?option=pages', 'edit-select-all.png', 0, 1, 0),
(4, 'Файловый манипулятор', '', 'index2.php?option=finder', 'folder-move.png', 0, 1, 0),
(5, 'Блог', '', 'index2.php?option=blog', 'folder-documents.png', 0, 1, 0),
(6, 'Кодер', '', 'index2.php?option=coder', 'gnome-fs-bookmark-missing.png', 0, 1, 0),
(7, 'Новости', '', 'index2.php?option=news', 'xfce4-menueditor.png', 0, 1, 0),
(8, 'Корзина', '', 'index2.php?option=trash', 'emptytrash.png', 0, 1, 0),
(9, 'Группы пользователей', 'Группы пользователей', 'index2.php?option=usergroups', 'gwibber.png', 0, 1, 0),
(10, 'Расширения', 'Расширения', 'index2.php?option=exts', 'gnome-power-manager.png', 0, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_searched`
--

CREATE TABLE IF NOT EXISTS `jos_searched` (
  `word` varchar(255) NOT NULL,
  `hit` int(11) unsigned NOT NULL,
  UNIQUE KEY `word` (`word`),
  KEY `hit` (`hit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_searched`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_templates`
--

CREATE TABLE IF NOT EXISTS `jos_templates` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_templates`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_template_positions`
--

CREATE TABLE IF NOT EXISTS `jos_template_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `jos_template_positions`
--

INSERT INTO `jos_template_positions` (`id`, `position`, `description`) VALUES
(1, 'left', ''),
(2, 'right', ''),
(3, 'top', ''),
(4, 'bottom', ''),
(6, 'banner', ''),
(7, 'header', ''),
(8, 'footer', ''),
(10, 'mainpage', ''),
(11, 'breadcrumb', ''),
(12, 'incomponen', ''),
(14, 'user1', ''),
(15, 'user2', ''),
(16, 'user3', ''),
(17, 'user4', ''),
(18, 'user5', ''),
(19, 'user6', ''),
(20, 'user7', ''),
(21, 'user8', ''),
(22, 'user9', ''),
(23, 'advert1', ''),
(24, 'advert2', ''),
(25, 'advert3', ''),
(26, 'icon', ''),
(27, 'debug', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_trash`
--

CREATE TABLE IF NOT EXISTS `jos_trash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_table` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `data` longtext NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_trash`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_users`
--

CREATE TABLE IF NOT EXISTS `jos_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL DEFAULT '',
  `user_name_canonikal` varchar(100) NOT NULL,
  `real_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `openid` varchar(200) DEFAULT NULL,
  `password` varchar(100) NOT NULL DEFAULT '',
  `state` tinyint(1) unsigned NOT NULL,
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(25) NOT NULL,
  `register_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `bad_auth_count` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`),
  KEY `idxemail` (`email`),
  KEY `block_id` (`state`,`id`),
  KEY `username_canonikal` (`user_name_canonikal`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` (`id`, `user_name`, `user_name_canonikal`, `real_name`, `email`, `openid`, `password`, `state`, `group_id`, `group_name`, `register_date`, `lastvisit_date`, `activation`, `bad_auth_count`) VALUES
(1, 'admin', 'adm1n', 'Кооолька!', 'admin@joostina.ru', '', '8946ff6c645d05bc6b1635ac03b80798:OgaubbtrYeZn1KJ3', 1, 8, 'SuperAdministrator', '2010-11-28 15:29:47', '2011-05-30 03:35:20', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_extra`
--

CREATE TABLE IF NOT EXISTS `jos_users_extra` (
  `user_id` int(11) unsigned NOT NULL,
  `gender` varchar(10) NOT NULL,
  `about` longtext,
  `location` varchar(255) NOT NULL,
  `contacts` text NOT NULL,
  `birth_date` date DEFAULT '0000-00-00',
  `interests` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_users_extra`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_groups`
--

CREATE TABLE IF NOT EXISTS `jos_users_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `group_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `jos_users_groups`
--

INSERT INTO `jos_users_groups` (`id`, `parent_id`, `title`, `group_title`) VALUES
(1, 0, 'Public', 'Корень групп'),
(2, 1, 'Registered', 'Зарегистрированные'),
(3, 2, 'Author', 'Авторы'),
(4, 3, 'Editor', 'Редакторы'),
(5, 4, 'Publisher', 'Публикаторы'),
(6, 1, 'Manager', 'Менеджеры'),
(7, 6, 'Administrator', 'Администраторы'),
(8, 7, 'SuperAdministrator', 'Супер Администраторы');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_session`
--

CREATE TABLE IF NOT EXISTS `jos_users_session` (
  `session_id` varchar(32) NOT NULL DEFAULT '0',
  `user_name` varchar(50) DEFAULT NULL,
  `data` longtext,
  `time` varchar(14) DEFAULT '',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `user_id` int(11) unsigned DEFAULT '0',
  `group_name` varchar(50) DEFAULT NULL,
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `session_id` (`session_id`),
  KEY `userid` (`user_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_users_session`
--

INSERT INTO `jos_users_session` (`session_id`, `user_name`, `data`, `time`, `guest`, `user_id`, `group_name`, `group_id`, `is_admin`) VALUES
('17148907c13ff0ce00c59e969105621d', 'admin', NULL, '1321473304', 0, 1, 'SuperAdministrator', 8, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_votes_blog`
--

CREATE TABLE IF NOT EXISTS `jos_votes_blog` (
  `obj_id` int(11) unsigned NOT NULL,
  `action_id` int(11) unsigned NOT NULL,
  `action_name` varchar(50) NOT NULL,
  `action_obj_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `vote` tinyint(5) NOT NULL,
  `created_at` datetime NOT NULL,
  UNIQUE KEY `obj_id` (`obj_id`,`action_id`,`action_obj_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_votes_blog`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_votes_blog_results`
--

CREATE TABLE IF NOT EXISTS `jos_votes_blog_results` (
  `obj_id` int(11) unsigned NOT NULL,
  `votes_count` int(11) NOT NULL,
  `voters_count` int(11) unsigned NOT NULL,
  `votes_average` float NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`obj_id`),
  KEY `votes_average` (`votes_average`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_votes_blog_results`
--

CREATE TABLE IF NOT EXISTS `jos_users_tokens` (
  `token` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_related` varchar(50) NOT NULL,
  UNIQUE KEY `token` (`token`),
  KEY `updated_at` (`updated_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
