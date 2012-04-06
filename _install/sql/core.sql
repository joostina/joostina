-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 07 2012 г., 03:32
-- Версия сервера: 5.1.49
-- Версия PHP: 5.3.3-1ubuntu9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `joostina`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_hits`
--

INSERT INTO `jos_hits` (`id`, `obj_id`, `obj_option`, `obj_task`, `hit`) VALUES
(1, 1, 'pages', 'view', 4);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_pages`
--

INSERT INTO `jos_pages` (`id`, `title`, `slug`, `text`, `meta_keywords`, `meta_description`, `created_at`, `state`) VALUES
(1, 'Главная страничка с текстом, тадааа! eqwewqe', 'glavnaya-stranichka-s-tekstom-tadaaa', '<div>Energistically evisculate end-to-end supply chains after web-enabled services. Objectively mesh open-source infrastructures without B2C opportunities. Efficiently envisioneer goal-oriented technologies before premium experiences. Appropriately restore strategic internal or "organic" sources through turnkey information. Conveniently utilize business human capital whereas functional potentialities.</div><div><br></div><div>Objectively engineer unique metrics rather than client-centered experiences. Dramatically productivate prospective channels with front-end initiatives. Monotonectally cultivate accurate materials through enterprise-wide supply chains. Professionally grow unique outsourcing with pandemic deliverables. Rapidiously extend go forward models without extensible materials.</div><div><br></div><div>Progressively deploy e-business alignments through stand-alone partnerships. Proactively redefine resource maximizing partnerships with quality methods of empowerment. Assertively fashion installed base quality vectors after wireless channels. Assertively recaptiualize one-to-one bandwidth rather than resource-leveling e-commerce. Compellingly leverage existing exceptional functionalities through just in time resources.</div><div><br></div><div>Interactively target stand-alone core competencies and standards compliant experiences. Enthusiastically whiteboard reliable process improvements after 2.0 scenarios. Progressively engage 2.0 interfaces after sticky core competencies. Credibly provide access to web-enabled manufactured products and professional services. Competently communicate team driven internal or "organic" sources and user friendly internal or "organic" sources.</div><div><br></div><div>Collaboratively brand cutting-edge manufactured products whereas alternative alignments. Credibly formulate top-line schemas whereas cross-unit process improvements. Dynamically deploy synergistic web-readiness without 24/365 infomediaries. Competently matrix multifunctional results before granular applications. Phosfluorescently fabricate prospective metrics via interoperable content.</div><div><br></div><div>Efficiently strategize customer directed web services rather than frictionless scenarios. Rapidiously initiate covalent best practices whereas excellent resources. Credibly formulate client-centered action items after compelling e-markets. Globally syndicate scalable resources rather than adaptive outsourcing. Holisticly syndicate B2C methods of empowerment after efficient solutions.</div><div><br></div><div>Professionally re-engineer multimedia based expertise and 2.0 customer service. Appropriately engage leading-edge "outside the box" thinking without cutting-edge intellectual capital. Credibly recaptiualize synergistic catalysts for change for seamless resources. Monotonectally incentivize exceptional customer service and enterprise-wide best practices. Objectively network fully tested portals through client-focused potentialities.</div><div><br></div><div>Enthusiastically integrate distributed human capital whereas leading-edge catalysts for change. Credibly integrate robust models through principle-centered materials. Progressively evisculate one-to-one growth strategies through proactive paradigms. Progressively scale world-class e-services via standardized infomediaries. Professionally evisculate cross-platform total linkage without performance based potentialities.</div>  ', '', '', '2012-01-03 22:58:52', 1);

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
(3, 'Странички', '', 'index2.php?option=pages', 'edit-select-all.png', 0, 0, 0),
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
  `activation` varchar(100) NOT NULL DEFAULT '',
  `bad_auth_count` tinyint(2) unsigned DEFAULT NULL,
  `register_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`),
  KEY `idxemail` (`email`),
  KEY `block_id` (`state`,`id`),
  KEY `username_canonikal` (`user_name_canonikal`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` (`id`, `user_name`, `user_name_canonikal`, `real_name`, `email`, `openid`, `password`, `state`, `group_id`, `group_name`, `activation`, `bad_auth_count`, `register_date`, `lastvisit_date`) VALUES
(1, 'admin', 'atm1n', 'Реально нереальный админ!', 'admin@joostina.ru', '', '1e72fb95f1710557274dcfd23a4582fd:DAj5Qf4Y1ktEenhE', 1, 8, 'SuperAdministrator', '', 0, '2010-11-28 15:29:47', '2012-04-05 23:38:40');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_acl_groups`
--

CREATE TABLE IF NOT EXISTS `jos_users_acl_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `jos_users_acl_groups`
--

INSERT INTO `jos_users_acl_groups` (`id`, `title`, `name`, `created_at`, `modified_at`) VALUES
(6, 'Администраторы', 'admin', '2012-02-18 18:13:40', '2012-04-06 18:04:03'),
(7, 'Зарегистрированные', 'Registered', '0000-00-00 00:00:00', '2012-04-07 00:37:32'),
(11, 'Модераторы блогов', 'Blog Moderators', '2012-04-07 01:30:45', '0000-00-00 00:00:00'),
(12, 'Модераторы новостей', 'New Moderators', '2012-04-07 01:31:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_acl_groups_users`
--

CREATE TABLE IF NOT EXISTS `jos_users_acl_groups_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` tinyint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Соотношение пользователей и групп' AUTO_INCREMENT=69 ;

--
-- Дамп данных таблицы `jos_users_acl_groups_users`
--

INSERT INTO `jos_users_acl_groups_users` (`id`, `user_id`, `group_id`) VALUES
(65, 1, 10),
(64, 1, 9),
(63, 1, 8),
(62, 1, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_acl_rules`
--

CREATE TABLE IF NOT EXISTS `jos_users_acl_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `acl_group` varchar(25) NOT NULL,
  `acl_name` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acl_group` (`acl_group`,`acl_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Список правил доступа' AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_users_acl_rules`
--

INSERT INTO `jos_users_acl_rules` (`id`, `title`, `acl_group`, `acl_name`, `created_at`, `modified_at`) VALUES
(1, 'Добавление записей в блог', 'blog', 'create', '2012-02-18 18:21:42', '2012-04-07 00:39:29'),
(2, 'Панель управления', 'admin_panel', 'init', '0000-00-00 00:00:00', '2012-04-07 00:38:24'),
(3, 'Комментирование записей в блоге', 'blog', 'comment', '2012-04-07 00:40:06', '0000-00-00 00:00:00'),
(4, 'Редактирование любых записей в блоге', 'blog', 'all-edit', '2012-04-07 00:40:29', '2012-04-07 00:43:56'),
(5, 'Удаление любых записей в блоге', 'blog', 'all-remove', '2012-04-07 00:41:05', '2012-04-07 00:43:49'),
(6, 'Добавление новостей', 'news', 'create', '2012-04-07 00:41:38', '0000-00-00 00:00:00'),
(7, 'Редактирование новостей', 'news', 'edit', '2012-04-07 00:41:52', '0000-00-00 00:00:00'),
(8, 'Удаление новостей', 'news', 'remove', '2012-04-07 00:42:09', '0000-00-00 00:00:00'),
(9, 'Редактирование своих записей в блоге', 'blog', 'my-edit', '2012-04-07 00:43:30', '0000-00-00 00:00:00'),
(10, 'Удаление любых записей в новостях', 'news', 'all-remove', '2012-04-07 00:44:16', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_acl_rules_groups`
--

CREATE TABLE IF NOT EXISTS `jos_users_acl_rules_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Соотношение правил и групп' AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `jos_users_acl_rules_groups`
--

INSERT INTO `jos_users_acl_rules_groups` (`id`, `group_id`, `task_id`) VALUES
(1, 6, 1),
(2, 8, 2),
(3, 9, 1),
(4, 10, 2),
(5, 6, 2),
(6, 6, 4),
(7, 7, 4),
(8, 6, 5),
(9, 7, 6),
(10, 7, 5),
(11, 6, 6),
(12, 7, 7),
(13, 7, 2),
(14, 6, 3),
(15, 7, 3),
(18, 6, 7),
(19, 7, 8),
(20, 6, 9),
(21, 7, 10);

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

INSERT INTO `jos_users_extra` (`user_id`, `gender`, `about`, `location`, `contacts`, `birth_date`, `interests`) VALUES
(1, '', NULL, '', '', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_session`
--

CREATE TABLE IF NOT EXISTS `jos_users_session` (
  `session_id` varchar(32) NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned DEFAULT '0',
  `user_name` varchar(50) DEFAULT NULL,
  `time` varchar(14) DEFAULT '',
  `guest` tinyint(1) unsigned DEFAULT '1',
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


-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_tokens`
--

CREATE TABLE IF NOT EXISTS `jos_users_tokens` (
  `token` varchar(32) NOT NULL,
  `user_related` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `token` (`token`),
  KEY `updated_at` (`updated_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_users_tokens`
--


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

