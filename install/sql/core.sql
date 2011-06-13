-- Joostina CMS SQL dump 

--
-- Структура таблицы `jos_access`
--

CREATE TABLE `jos_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(25) NOT NULL,
  `subsection` varchar(25) NOT NULL,
  `action` varchar(25) NOT NULL,
  `action_label` varchar(255) NOT NULL,
  `accsess` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section` (`section`,`subsection`,`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_access`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_attached`
--

CREATE TABLE `jos_attached` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_ext` varchar(25) NOT NULL,
  `file_mime` varchar(50) NOT NULL,
  `file_size` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_attached`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_blog`
--

CREATE TABLE `jos_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `introtext` text,
  `fulltext` longtext,
  `params` text,
  `category_id` tinyint(2) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`slug`),
  KEY `state` (`state`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_blog`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_blog_category`
--

CREATE TABLE `jos_blog_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `params` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_blog_category`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_categories`
--

CREATE TABLE `jos_categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lft` mediumint(8) unsigned NOT NULL,
  `rgt` mediumint(8) unsigned NOT NULL,
  `level` mediumint(8) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `moved` tinyint(1) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_categories`
--

INSERT INTO `jos_categories` VALUES(1, 1, 2, 0, 0, 0, 'root', '', 'root', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_categories_details`
--

CREATE TABLE `jos_categories_details` (
  `cat_id` int(11) NOT NULL,
  `desc_short` mediumtext NOT NULL,
  `desc_full` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
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
-- Структура таблицы `jos_components`
--

CREATE TABLE `jos_components` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_components`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_content`
--

CREATE TABLE `jos_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `special` tinyint(1) NOT NULL,
  `attachments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_content`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_extrafields`
--

CREATE TABLE `jos_extrafields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `object` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `rules` text NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_extrafields`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_extrafields_data`
--

CREATE TABLE `jos_extrafields_data` (
  `field_id` int(11) NOT NULL,
  `obj_id` int(11) NOT NULL,
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

CREATE TABLE `jos_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(20) NOT NULL,
  `hit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`,`obj_task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_hits`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_menu`
--

CREATE TABLE `jos_menu` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_menu`
--

INSERT INTO `jos_menu` VALUES(1, 'mainmenu', 'Главная', 'Главнее не бывает', 'index.php?option=mainpage', 'components', 1, 0, 1, 0, 9999, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '');
INSERT INTO `jos_menu` VALUES(2, 'mainmenu', 'Странички', '', 'index.php?option=pages', 'components', 1, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '{ "page_id": "1" }');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_metainfo`
--

CREATE TABLE `jos_metainfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subgroup` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `obj_id` int(11) unsigned DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_metainfo`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules`
--

CREATE TABLE `jos_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(10) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) NOT NULL,
  `template` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `cache_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`state`),
  KEY `newsfeeds` (`module`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_modules`
--

INSERT INTO `jos_modules` VALUES(1, 'Админменю', '', 1, 'top', 1, 'adminmenu', '', '', 1, 0);
INSERT INTO `jos_modules` VALUES(2, 'Системные сообщения', '', 1, 'inset', 0, 'adminmsg', '', '', 1, 0);
INSERT INTO `jos_modules` VALUES(3, 'Главное меню', '', 2, 'left', 1, 'navigation', 'yax', 'null', 0, 32767);
INSERT INTO `jos_modules` VALUES(4, 'Авторизация', '', 1, 'header', 1, 'login', '', '{"param1":"","param2":""}', 0, 0);
INSERT INTO `jos_modules` VALUES(5, 'Кнопки быстрого доступа', '', 1, 'icon', 1, 'adminquickicons', '', '{ "use_cache": "0", "use_ext": "0" }', 1, 604800);
INSERT INTO `jos_modules` VALUES(6, 'Хлебные крошки', '', 0, 'pathway', 1, 'breadcrumbs', '', 'null', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules_pages`
--

CREATE TABLE `jos_modules_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `controller` varchar(25) NOT NULL DEFAULT '0',
  `method` varchar(50) NOT NULL,
  `rule` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_id` (`moduleid`,`controller`,`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_modules_pages`
--

INSERT INTO `jos_modules_pages` VALUES(2, 6, 'all', '', '');
INSERT INTO `jos_modules_pages` VALUES(3, 3, 'all', '', '');
INSERT INTO `jos_modules_pages` VALUES(5, 4, 'all', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_news`
--

CREATE TABLE `jos_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `special` tinyint(1) NOT NULL,
  `attachments` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `type_id` (`category_id`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_news`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_pages`
--

CREATE TABLE `jos_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_pages`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_params`
--

CREATE TABLE `jos_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `object` varchar(20) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `name` (`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_params`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_quickicons`
--

CREATE TABLE `jos_quickicons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `alt_text` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gid` int(3) DEFAULT '25',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_quickicons`
--

INSERT INTO `jos_quickicons` VALUES(1, 'Комментарии', 'Тыц-мегатыц', 'index2.php?option=comments', 'empathy.png', 5, 1, 0);
INSERT INTO `jos_quickicons` VALUES(2, 'Пользователи', '', 'index2.php?option=users', 'contact-new.png', 10, 1, 0);
INSERT INTO `jos_quickicons` VALUES(3, 'Странички', '', 'index2.php?option=pages', 'edit-select-all.png', 0, 1, 0);
INSERT INTO `jos_quickicons` VALUES(4, 'Блог', '', 'index2.php?option=blog', 'folder-documents.png', 0, 1, 0);
INSERT INTO `jos_quickicons` VALUES(5, 'Кодер', '', 'index2.php?option=coder', 'gnome-fs-bookmark-missing.png', 0, 1, 0);
INSERT INTO `jos_quickicons` VALUES(6, 'Новости', '', 'index2.php?option=news', 'xfce4-menueditor.png', 0, 1, 0);
INSERT INTO `jos_quickicons` VALUES(7, 'Корзина', '', 'index2.php?option=trash', 'emptytrash.png', 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_searched`
--

CREATE TABLE `jos_searched` (
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

CREATE TABLE `jos_session` (
  `username` varchar(50) DEFAULT '',
  `data` longtext,
  `time` varchar(14) DEFAULT '',
  `session_id` varchar(32) NOT NULL DEFAULT '0',
  `guest` tinyint(4) DEFAULT '1',
  `userid` int(11) DEFAULT '0',
  `groupname` varchar(50) DEFAULT NULL,
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_session`
--

CREATE TABLE `jos_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_template_positions`
--

CREATE TABLE `jos_template_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_template_positions`
--

INSERT INTO `jos_template_positions` VALUES(1, 'left', '');
INSERT INTO `jos_template_positions` VALUES(2, 'right', '');
INSERT INTO `jos_template_positions` VALUES(3, 'top', '');
INSERT INTO `jos_template_positions` VALUES(4, 'bottom', '');
INSERT INTO `jos_template_positions` VALUES(6, 'banner', '');
INSERT INTO `jos_template_positions` VALUES(7, 'header', '');
INSERT INTO `jos_template_positions` VALUES(8, 'footer', '');
INSERT INTO `jos_template_positions` VALUES(10, 'mainpage', '');
INSERT INTO `jos_template_positions` VALUES(11, 'breadcrumb', '');
INSERT INTO `jos_template_positions` VALUES(12, 'incomponen', '');
INSERT INTO `jos_template_positions` VALUES(14, 'user1', '');
INSERT INTO `jos_template_positions` VALUES(15, 'user2', '');
INSERT INTO `jos_template_positions` VALUES(16, 'user3', '');
INSERT INTO `jos_template_positions` VALUES(17, 'user4', '');
INSERT INTO `jos_template_positions` VALUES(18, 'user5', '');
INSERT INTO `jos_template_positions` VALUES(19, 'user6', '');
INSERT INTO `jos_template_positions` VALUES(20, 'user7', '');
INSERT INTO `jos_template_positions` VALUES(21, 'user8', '');
INSERT INTO `jos_template_positions` VALUES(22, 'user9', '');
INSERT INTO `jos_template_positions` VALUES(23, 'advert1', '');
INSERT INTO `jos_template_positions` VALUES(24, 'advert2', '');
INSERT INTO `jos_template_positions` VALUES(25, 'advert3', '');
INSERT INTO `jos_template_positions` VALUES(26, 'icon', '');
INSERT INTO `jos_template_positions` VALUES(27, 'debug', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_trash`
--

CREATE TABLE `jos_trash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_table` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `data` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_trash`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_users`
--

CREATE TABLE `jos_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `username_canonikal` varchar(100) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `openid` varchar(200) DEFAULT NULL,
  `password` varchar(100) NOT NULL DEFAULT '',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `groupname` varchar(50) DEFAULT NULL,
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `bad_auth_count` int(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idxemail` (`email`),
  KEY `block_id` (`state`,`id`),
  KEY `username_canonikal` (`username_canonikal`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` VALUES(1, 'admin', 'adm1n', '', 'admin@joostina.ru', '', '8946ff6c645d05bc6b1635ac03b80798:OgaubbtrYeZn1KJ3', 1, 8, 'SuperAdministrator', '2010-11-28 15:29:47', '2011-05-30 03:35:20', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_extra`
--

CREATE TABLE `jos_users_extra` (
  `user_id` int(11) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `about` longtext,
  `location` varchar(255) NOT NULL,
  `contacts` text NOT NULL,
  `birthdate` date DEFAULT '0000-00-00',
  `interests` text NOT NULL,
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

CREATE TABLE `jos_users_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `group_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_users_groups`
--

INSERT INTO `jos_users_groups` VALUES(1, 0, 'Public', 'Корень групп');
INSERT INTO `jos_users_groups` VALUES(2, 1, 'Registered', 'Зарегистрированные');
INSERT INTO `jos_users_groups` VALUES(3, 2, 'Author', 'Авторы');
INSERT INTO `jos_users_groups` VALUES(4, 3, 'Editor', 'Редакторы');
INSERT INTO `jos_users_groups` VALUES(5, 4, 'Publisher', 'Публикаторы');
INSERT INTO `jos_users_groups` VALUES(6, 1, 'Manager', 'Менеджеры');
INSERT INTO `jos_users_groups` VALUES(7, 6, 'Administrator', 'Администраторы');
INSERT INTO `jos_users_groups` VALUES(8, 7, 'SuperAdministrator', 'Супер Администраторы');


--
-- Структура таблицы `jos_comments`
--

CREATE TABLE `jos_comments` (
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
  `params` longtext,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obj_id` (`obj_id`,`obj_option`),
  KEY `state` (`state`),
  KEY `path` (`path`,`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_comments_counter`
--

CREATE TABLE `jos_comments_counter` (
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `last_user_id` int(11) unsigned NOT NULL,
  `last_comment_id` int(11) unsigned NOT NULL,
  `counter` int(11) unsigned NOT NULL,
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;