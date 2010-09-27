-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 21 2010 г., 03:06
-- Версия сервера: 5.1.41
-- Версия PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `joostina20`
--

-- --------------------------------------------------------

--
-- Структура таблицы `jos_attached`
--

CREATE TABLE IF NOT EXISTS `jos_attached` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_ext` varchar(25) NOT NULL,
  `file_mime` varchar(50) NOT NULL,
  `file_size` int(11) unsigned NOT NULL,
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
  `title` varchar(200) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` longtext NOT NULL,
  `params` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`slug`),
  KEY `state` (`state`)
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_blog_category`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_bookmarks`
--

CREATE TABLE IF NOT EXISTS `jos_bookmarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`obj_id`,`obj_option`,`obj_task`),
  KEY `user_id_2` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_bookmarks`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_bookmarks_counter`
--

CREATE TABLE IF NOT EXISTS `jos_bookmarks_counter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(30) NOT NULL,
  `counter` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`,`obj_task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_bookmarks_counter`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_comments`
--

CREATE TABLE IF NOT EXISTS `jos_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `obj_id` int(11) unsigned NOT NULL DEFAULT '0',
  `obj_option` varchar(30) NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `comment_text` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obj_id` (`obj_id`,`obj_option`),
  KEY `state` (`state`),
  KEY `path` (`path`,`level`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `jos_comments`
--

INSERT INTO `jos_comments` (`id`, `parent_id`, `path`, `level`, `obj_id`, `obj_option`, `user_id`, `user_name`, `user_email`, `user_ip`, `comment_text`, `created_at`, `state`) VALUES
(1, 0, '0', 0, 1, 'Pages', 0, 'Гость', '', '127.0.0.1', 'Рас камент', '2010-09-08 03:17:36', 1),
(2, 1, '0,1', 1, 1, 'Pages', 0, 'Гость', '', '127.0.0.1', 'Два камент', '2010-09-08 03:17:41', 1),
(3, 2, '0,1,2', 2, 1, 'Pages', 0, 'Гость', '', '127.0.0.1', 'Оп оп', '2010-09-08 03:17:46', 1),
(4, 0, '0', 0, 1, 'Pages', 0, 'Гость', '', '127.0.0.1', 'Упа как!', '2010-09-08 03:18:01', 1);

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

INSERT INTO `jos_comments_counter` (`obj_id`, `obj_option`, `last_user_id`, `last_comment_id`, `counter`) VALUES
(1, 'Pages', 0, 4, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_components`
--

CREATE TABLE IF NOT EXISTS `jos_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `version` float NOT NULL,
  `title` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL DEFAULT '',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_menu_img` varchar(255) NOT NULL,
  `params` text,
  `client_id` tinyint(1) NOT NULL,
  `update_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_components`
--

INSERT INTO `jos_components` (`id`, `author`, `version`, `title`, `link`, `parent`, `admin_menu_img`, `params`, `client_id`, `update_url`) VALUES
(1, '', 0, 'Странички', 'pages', 0, 'administrator/images/menu/icon-16-featured.png', '', 0, ''),
(2, '', 0, 'Авторизация', 'login', 0, 'administrator/images/menu/icon-16-clear.png', NULL, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_config`
--

CREATE TABLE IF NOT EXISTS `jos_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_config`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_hits`
--

CREATE TABLE IF NOT EXISTS `jos_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(20) NOT NULL,
  `hit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`,`obj_task`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_hits`
--

INSERT INTO `jos_hits` (`id`, `obj_id`, `obj_option`, `obj_task`, `hit`) VALUES
(1, 1, 'pages', 'view', 55),
(2, 0, 'tags', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_menu`
--

CREATE TABLE IF NOT EXISTS `jos_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(25) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `link_title` varchar(200) NOT NULL,
  `link` text,
  `type` varchar(50) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `componentid` int(11) unsigned NOT NULL DEFAULT '0',
  `sublevel` int(11) DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pollid` int(11) NOT NULL DEFAULT '0',
  `browserNav` tinyint(4) DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `utaccess` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `componentid` (`componentid`,`menutype`,`published`,`access`),
  KEY `menutype` (`menutype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_menu`
--

INSERT INTO `jos_menu` (`id`, `menutype`, `name`, `link_title`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) VALUES
(1, 'mainmenu', 'Главная', 'Главнее не бывает', 'index.php?option=com_mainpage', 'components', 1, 0, 1, 0, 9999, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, ''),
(2, 'mainmenu', 'Страницке', '', 'index.php?option=pages', 'components', 1, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '{ "page_id": "1" }');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules`
--

CREATE TABLE IF NOT EXISTS `jos_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(10) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `numnews` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text,
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `cache_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Дамп данных таблицы `jos_modules`
--

INSERT INTO `jos_modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`, `cache_time`) VALUES
(3, 'Главное меню', '', 2, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_menu', 0, 0, 0, '', 0, 0, 32767),
(4, 'Авторизация', '', 1, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_login', 0, 0, 0, '""', 0, 0, 0),
(19, 'Компоненты', '', 2, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_components', 0, 99, 1, '', 0, 1, 0),
(23, 'Последние зарегистрированные пользователи', '', 4, 'advert2', 0, '0000-00-00 00:00:00', 1, 'mod_latest_users', 0, 99, 1, '', 0, 1, 0),
(26, 'Полное меню', '', 1, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_fullmenu', 0, 99, 1, '', 0, 1, 0),
(27, 'Путь', '', 1, 'pathway', 0, '0000-00-00 00:00:00', 1, 'mod_pathway', 0, 99, 1, '', 0, 1, 0),
(28, 'Панель инструментов', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 99, 1, '', 0, 1, 0),
(29, 'Системные сообщения', '', 1, 'inset', 0, '0000-00-00 00:00:00', 1, 'mod_mosmsg', 0, 99, 1, '', 0, 1, 0),
(30, 'Кнопки быстрого доступа', '', 1, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicons', 0, 99, 1, '{ "use_cache": "0", "use_ext": "0" }', 0, 1, 604800);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules_menu`
--

CREATE TABLE IF NOT EXISTS `jos_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_modules_menu`
--

INSERT INTO `jos_modules_menu` (`moduleid`, `menuid`) VALUES
(1, 0),
(30, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_pages`
--

CREATE TABLE IF NOT EXISTS `jos_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `title_page` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `jos_pages`
--

INSERT INTO `jos_pages` (`id`, `title`, `title_page`, `slug`, `text`, `meta_keywords`, `meta_description`, `created_at`, `state`) VALUES
(1, 'Вот страничка!', 'Этот титл, он может без Б отличаться от заголовка, хехе', 'это ссылка, но её пока нет', '<p>Тут можно текст, с <strong>форматированием</strong>!</p><p>И, не поверите, но даже так!</p><p><br></p>', 'META::keywords', 'META::description', '2010-09-04 03:53:30', 1),
(2, 'Страница номер два', '', '', 'Опачкикракарушки!', '', '', '2010-09-08 02:46:35', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_quickicons`
--

CREATE TABLE IF NOT EXISTS `jos_quickicons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(64) NOT NULL DEFAULT '',
  `target` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  `new_window` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `gid` int(3) DEFAULT '25',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `jos_quickicons`
--

INSERT INTO `jos_quickicons` (`id`, `text`, `target`, `icon`, `ordering`, `new_window`, `published`, `title`, `display`, `access`, `gid`) VALUES
(1, 'Комментарии', 'index2.php?option=com_comments', '/administrator/images/quickicons/USB-Connection.png', 5, 0, 1, 'Управление комментариями', 0, 0, 8),
(2, 'Редактор меню', 'index2.php?option=com_menumanager', '/administrator/images/quickicons/Photo-Settings.png', 9, 0, 1, 'Управление объектами меню', 0, 0, 8),
(3, 'Пользователи', 'index2.php?option=com_users', '/administrator/images/quickicons/Power-Save-Settings.png', 10, 0, 1, 'Управление пользователями', 0, 0, 8),
(4, 'Странички', 'index2.php?option=com_pages', '/administrator/images/quickicons/Online-Instruction-Manuals.png', 0, 0, 1, '', 0, 0, 8),
(5, 'Редактор меню', 'index2.php?option=com_menumanager', '/administrator/templates/joostfree/images/cpanel_ico/menu.png', 9, 0, 1, 'Управление объектами меню', 0, 0, 8),
(6, 'Глобальная конфигурация', 'index2.php?option=com_config&hidemainmenu=1', '/administrator/templates/joostfree/images/cpanel_ico/config.png', 13, 0, 1, 'Глобальная конфигурация сайта', 0, 0, 8),
(7, 'Очистить весь кэш', 'index2.php?option=com_admin&task=clean_all_cache', '/administrator/templates/joostfree/images/cpanel_ico/clear.png', 14, 0, 1, 'Очистить весь кэш сайта', 0, 0, 8);

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
-- Структура таблицы `jos_session`
--

CREATE TABLE IF NOT EXISTS `jos_session` (
  `username` varchar(50) DEFAULT '',
  `time` varchar(14) DEFAULT '',
  `session_id` varchar(200) NOT NULL DEFAULT '0',
  `guest` tinyint(4) DEFAULT '1',
  `userid` int(11) DEFAULT '0',
  `groupname` varchar(50) DEFAULT NULL,
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`(64)),
  KEY `whosonline` (`guest`,`groupname`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_session`
--

INSERT INTO `jos_session` (`username`, `time`, `session_id`, `guest`, `userid`, `groupname`, `gid`) VALUES
('admin', '1284927775', 'ac2746926634f9fdd20c0173b3aa60f7', 1, 1, 'SuperAdministrator', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_tags`
--

CREATE TABLE IF NOT EXISTS `jos_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) NOT NULL,
  `obj_option` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obj_id` (`obj_id`,`obj_option`),
  KEY `tag_text` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `jos_tags`
--

INSERT INTO `jos_tags` (`id`, `obj_id`, `obj_option`, `tag`) VALUES
(8, 1, 'pages', 'дватэг'),
(7, 1, 'pages', 'рас тэг'),
(9, 1, 'pages', '3123');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_template_positions`
--

CREATE TABLE IF NOT EXISTS `jos_template_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
(5, 'inset', ''),
(6, 'banner', ''),
(7, 'header', ''),
(8, 'footer', ''),
(9, 'newsflash', ''),
(10, 'legals', ''),
(11, 'pathway', ''),
(12, 'toolbar', ''),
(13, 'cpanel', ''),
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
  `user_id` int(11) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `openid` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL DEFAULT '',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `groupname` varchar(50) NOT NULL,
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `bad_auth_count` int(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idxemail` (`email`),
  KEY `block_id` (`state`,`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` (`id`, `username`, `email`, `openid`, `password`, `state`, `gid`, `groupname`, `registerDate`, `lastvisitDate`, `activation`, `bad_auth_count`) VALUES
(1, 'admin', 'bost56@gmail.com', '', 'cfd23caa58ec2d7d07fc5a293d75f43e:Z21Rlz9aN0pDwGIg', 1, 8, 'SuperAdministrator', '2010-05-20 02:45:14', '2010-09-08 01:05:27', '0', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_extra`
--

CREATE TABLE IF NOT EXISTS `jos_users_extra` (
  `user_id` int(11) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `about` tinytext,
  `location` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `icq` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `jabber` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `yahoo` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `mobil` varchar(255) NOT NULL,
  `birthdate` date DEFAULT '0000-00-00',
  `cache_bookmarks` text NOT NULL,
  `cache_votes` text NOT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `jos_users_groups`
--

INSERT INTO `jos_users_groups` (`id`, `parent_id`, `title`) VALUES
(1, 0, 'Public'),
(2, 1, 'Registered'),
(3, 2, 'Author'),
(4, 3, 'Editor'),
(5, 4, 'Publisher'),
(6, 1, 'Manager'),
(7, 6, 'Administrator'),
(8, 7, 'SuperAdministrator');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_votes_comment`
--

CREATE TABLE IF NOT EXISTS `jos_votes_comment` (
  `obj_id` int(11) unsigned NOT NULL,
  `action_id` int(11) NOT NULL,
  `action_name` varchar(50) NOT NULL,
  `action_obj_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `vote` tinyint(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `obj_id` (`obj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_votes_comment`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_votes_comment_results`
--

CREATE TABLE IF NOT EXISTS `jos_votes_comment_results` (
  `obj_id` int(11) unsigned NOT NULL,
  `votes_count` int(11) NOT NULL,
  `voters_count` int(11) NOT NULL,
  `votes_average` float NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`obj_id`),
  KEY `votes_average` (`votes_average`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_votes_comment_results`
--

