DROP TABLE IF EXISTS jos_attached;
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


DROP TABLE IF EXISTS jos_bookmarks;
CREATE TABLE `jos_bookmarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`obj_id`,`obj_option`,`obj_task`),
  KEY `user_id_2` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS jos_bookmarks_counter;
CREATE TABLE `jos_bookmarks_counter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(30) NOT NULL,
  `counter` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`,`obj_task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS jos_comments;
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
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obj_id` (`obj_id`,`obj_option`),
  KEY `state` (`state`),
  KEY `path` (`path`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
INSERT INTO jos_comments VALUES("1","0","0","0","1","Pages","0","Гость","","127.0.0.1","er tert","2010-09-05 18:05:57","1");
INSERT INTO jos_comments VALUES("2","0","0","0","1","Pages","0","Гость","","127.0.0.1","er tert","2010-09-05 18:06:00","1");
INSERT INTO jos_comments VALUES("3","0","0","0","1","Pages","0","Гость","","127.0.0.1","er tert","2010-09-05 18:07:01","1");
INSERT INTO jos_comments VALUES("4","2","0,2","1","1","Pages","0","Гость","","127.0.0.1","qwewqe","2010-09-05 18:07:43","1");
INSERT INTO jos_comments VALUES("5","4","0,2,4","2","1","Pages","0","Гость","","127.0.0.1","1111","2010-09-05 18:11:43","1");
INSERT INTO jos_comments VALUES("6","0","0","0","1","Pages","0","Гость","","127.0.0.1","qwew","2010-09-05 18:19:01","1");


DROP TABLE IF EXISTS jos_comments_counter;
CREATE TABLE `jos_comments_counter` (
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `last_user_id` int(11) unsigned NOT NULL,
  `last_comment_id` int(11) unsigned NOT NULL,
  `counter` int(11) unsigned NOT NULL,
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO jos_comments_counter VALUES("1","Pages","0","1","2");


DROP TABLE IF EXISTS jos_components;
CREATE TABLE `jos_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `menuid` int(11) unsigned NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_menu_link` varchar(255) NOT NULL DEFAULT '',
  `admin_menu_alt` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(50) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `admin_menu_img` varchar(255) NOT NULL DEFAULT '',
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
INSERT INTO jos_components VALUES("1","Странички","option=com_pages","0","0","option=com_pages","Странички","com_pages","0","administrator/images/menu/icon-16-featured.png","0","");
INSERT INTO jos_components VALUES("2","Авторизация","option=com_login","0","0","option=com_login","wqewe","com_login","0","administrator/images/menu/icon-16-clear.png","0","");


DROP TABLE IF EXISTS jos_config;
CREATE TABLE `jos_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS jos_groups;
CREATE TABLE `jos_groups` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO jos_groups VALUES("0","Общий");
INSERT INTO jos_groups VALUES("1","Участники");
INSERT INTO jos_groups VALUES("2","Специальный");


DROP TABLE IF EXISTS jos_hits;
CREATE TABLE `jos_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) unsigned NOT NULL,
  `obj_option` varchar(30) NOT NULL,
  `obj_task` varchar(20) NOT NULL,
  `hit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj_id` (`obj_id`,`obj_option`,`obj_task`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO jos_hits VALUES("1","1","pages","view","137");


DROP TABLE IF EXISTS jos_menu;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
INSERT INTO jos_menu VALUES("1","mainmenu","Главная","Главнее не бывает","index.php?option=com_mainpage","components","1","0","1","0","9999","0","0000-00-00 00:00:00","0","0","0","0","");
INSERT INTO jos_menu VALUES("2","mainmenu","Страницке","","index.php?option=com_pages","components","1","0","1","0","0","0","0000-00-00 00:00:00","0","0","0","0","{ \"page_id\": \"1\" }");


DROP TABLE IF EXISTS jos_metainfo;
CREATE TABLE `jos_metainfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component` varchar(255) NOT NULL,
  `task` varchar(255) NOT NULL,
  `obj_id` int(11) unsigned DEFAULT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(500) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS jos_modules;
CREATE TABLE `jos_modules` (
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
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
INSERT INTO jos_modules VALUES("3","Главное меню","","2","left","0","0000-00-00 00:00:00","1","mod_menu","0","0","0","","0","0","32767");
INSERT INTO jos_modules VALUES("4","Авторизация","","1","left","0","0000-00-00 00:00:00","1","mod_login","0","0","0","\"\"","0","0","0");
INSERT INTO jos_modules VALUES("19","Компоненты","","2","cpanel","0","0000-00-00 00:00:00","1","mod_components","0","99","1","","0","1","0");
INSERT INTO jos_modules VALUES("23","Последние зарегистрированные пользователи","","4","advert2","0","0000-00-00 00:00:00","0","mod_latest_users","0","99","1","","0","1","0");
INSERT INTO jos_modules VALUES("26","Полное меню","","1","top","0","0000-00-00 00:00:00","1","mod_fullmenu","0","99","1","","0","1","0");
INSERT INTO jos_modules VALUES("27","Путь","","1","pathway","0","0000-00-00 00:00:00","1","mod_pathway","0","99","1","","0","1","0");
INSERT INTO jos_modules VALUES("28","Панель инструментов","","1","toolbar","0","0000-00-00 00:00:00","1","mod_toolbar","0","99","1","","0","1","0");
INSERT INTO jos_modules VALUES("29","Системные сообщения","","1","inset","0","0000-00-00 00:00:00","1","mod_mosmsg","0","99","1","","0","1","0");
INSERT INTO jos_modules VALUES("30","Кнопки быстрого доступа","","1","icon","0","0000-00-00 00:00:00","1","mod_quickicons","0","99","1","{ \"use_cache\": \"0\", \"use_ext\": \"0\" }","0","1","604800");


DROP TABLE IF EXISTS jos_modules_menu;
CREATE TABLE `jos_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO jos_modules_menu VALUES("1","0");
INSERT INTO jos_modules_menu VALUES("30","0");


DROP TABLE IF EXISTS jos_pages;
CREATE TABLE `jos_pages` (
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
INSERT INTO jos_pages VALUES("1","йцуцйуцйу","цйцйуцйу","цйуцй","qw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqewqw ewqe wq ewqew","","","2010-09-04 03:51:46","1");
INSERT INTO jos_pages VALUES("2","йцуцйуцйу","цйцйуцйу","цйуцй","","","","2010-09-04 03:53:21","1");
INSERT INTO jos_pages VALUES("3","йцуцйуцйу","цйцйуцйу","цйуцй","","","","2010-09-04 03:53:30","1");


DROP TABLE IF EXISTS jos_quickicons;
CREATE TABLE `jos_quickicons` (
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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
INSERT INTO jos_quickicons VALUES("1","Комментарии","index2.php?option=com_comments","/administrator/images/quickicons/USB-Connection.png","5","0","1","Управление комментариями","0","0","8");
INSERT INTO jos_quickicons VALUES("2","Редактор меню","index2.php?option=com_menumanager","/administrator/images/quickicons/Photo-Settings.png","9","0","1","Управление объектами меню","0","0","8");
INSERT INTO jos_quickicons VALUES("3","Пользователи","index2.php?option=com_users","/administrator/images/quickicons/Power-Save-Settings.png","10","0","1","Управление пользователями","0","0","8");
INSERT INTO jos_quickicons VALUES("4","Странички","index2.php?option=com_pages","/administrator/images/quickicons/Online-Instruction-Manuals.png","0","0","1","","0","0","8");
INSERT INTO jos_quickicons VALUES("5","Редактор меню","index2.php?option=com_menumanager","/administrator/templates/joostfree/images/cpanel_ico/menu.png","9","0","1","Управление объектами меню","0","0","8");
INSERT INTO jos_quickicons VALUES("6","Глобальная конфигурация","index2.php?option=com_config&hidemainmenu=1","/administrator/templates/joostfree/images/cpanel_ico/config.png","13","0","1","Глобальная конфигурация сайта","0","0","8");
INSERT INTO jos_quickicons VALUES("7","Очистить весь кэш","index2.php?option=com_admin&task=clean_all_cache","/administrator/templates/joostfree/images/cpanel_ico/clear.png","14","0","1","Очистить весь кэш сайта","0","0","8");


DROP TABLE IF EXISTS jos_searched;
CREATE TABLE `jos_searched` (
  `word` varchar(255) NOT NULL,
  `hit` int(11) unsigned NOT NULL,
  UNIQUE KEY `word` (`word`),
  KEY `hit` (`hit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS jos_session;
CREATE TABLE `jos_session` (
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
INSERT INTO jos_session VALUES("admin","1283717665","5e19490bcb548b12f2db4bf36102413f","1","1","SuperAdministrator","0");


DROP TABLE IF EXISTS jos_tags;
CREATE TABLE `jos_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) NOT NULL,
  `obj_option` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `obj_id` (`obj_id`,`obj_option`),
  KEY `tag_text` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS jos_template_positions;
CREATE TABLE `jos_template_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
INSERT INTO jos_template_positions VALUES("1","left","");
INSERT INTO jos_template_positions VALUES("2","right","");
INSERT INTO jos_template_positions VALUES("3","top","");
INSERT INTO jos_template_positions VALUES("4","bottom","");
INSERT INTO jos_template_positions VALUES("5","inset","");
INSERT INTO jos_template_positions VALUES("6","banner","");
INSERT INTO jos_template_positions VALUES("7","header","");
INSERT INTO jos_template_positions VALUES("8","footer","");
INSERT INTO jos_template_positions VALUES("9","newsflash","");
INSERT INTO jos_template_positions VALUES("10","legals","");
INSERT INTO jos_template_positions VALUES("11","pathway","");
INSERT INTO jos_template_positions VALUES("12","toolbar","");
INSERT INTO jos_template_positions VALUES("13","cpanel","");
INSERT INTO jos_template_positions VALUES("14","user1","");
INSERT INTO jos_template_positions VALUES("15","user2","");
INSERT INTO jos_template_positions VALUES("16","user3","");
INSERT INTO jos_template_positions VALUES("17","user4","");
INSERT INTO jos_template_positions VALUES("18","user5","");
INSERT INTO jos_template_positions VALUES("19","user6","");
INSERT INTO jos_template_positions VALUES("20","user7","");
INSERT INTO jos_template_positions VALUES("21","user8","");
INSERT INTO jos_template_positions VALUES("22","user9","");
INSERT INTO jos_template_positions VALUES("23","advert1","");
INSERT INTO jos_template_positions VALUES("24","advert2","");
INSERT INTO jos_template_positions VALUES("25","advert3","");
INSERT INTO jos_template_positions VALUES("26","icon","");
INSERT INTO jos_template_positions VALUES("27","debug","");


DROP TABLE IF EXISTS jos_templates_menu;
CREATE TABLE `jos_templates_menu` (
  `template` varchar(50) NOT NULL DEFAULT '',
  `menuid` int(11) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`template`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO jos_templates_menu VALUES("newline2","0","0");
INSERT INTO jos_templates_menu VALUES("joostfree","0","1");


DROP TABLE IF EXISTS jos_trash;
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


DROP TABLE IF EXISTS jos_users;
CREATE TABLE `jos_users` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO jos_users VALUES("1","admin","bost56@gmail.com","","8b20caaf861da2cbcfb2b97aee923cfe:d3QMCcJSSYuH9oSI","1","8","SuperAdministrator","2010-05-20 02:45:14","2010-09-04 16:42:38","0","0");


DROP TABLE IF EXISTS jos_users_extra;
CREATE TABLE `jos_users_extra` (
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


DROP TABLE IF EXISTS jos_users_groups;
CREATE TABLE `jos_users_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
INSERT INTO jos_users_groups VALUES("1","0","Public");
INSERT INTO jos_users_groups VALUES("2","1","Registered");
INSERT INTO jos_users_groups VALUES("3","2","Author");
INSERT INTO jos_users_groups VALUES("4","3","Editor");
INSERT INTO jos_users_groups VALUES("5","4","Publisher");
INSERT INTO jos_users_groups VALUES("6","1","Manager");
INSERT INTO jos_users_groups VALUES("7","6","Administrator");
INSERT INTO jos_users_groups VALUES("8","7","SuperAdministrator");


DROP TABLE IF EXISTS jos_votes_comment;
CREATE TABLE `jos_votes_comment` (
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


DROP TABLE IF EXISTS jos_votes_comment_results;
CREATE TABLE `jos_votes_comment_results` (
  `obj_id` int(11) unsigned NOT NULL,
  `votes_count` int(11) NOT NULL,
  `voters_count` int(11) NOT NULL,
  `votes_average` float NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`obj_id`),
  KEY `votes_average` (`votes_average`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


