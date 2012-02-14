-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 14 2012 г., 16:41
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
-- Структура таблицы `jos_acl_access`
--

CREATE TABLE IF NOT EXISTS `jos_acl_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `jos_acl_access`
--

INSERT INTO `jos_acl_access` (`id`, `group_id`, `task_id`, `created_at`, `modified_at`) VALUES
(1, 1, 1, '2011-12-12 14:57:52', '0000-00-00 00:00:00'),
(2, 2, 2, '2011-12-12 14:57:53', '0000-00-00 00:00:00'),
(3, 4, 2, '2011-12-12 14:57:54', '0000-00-00 00:00:00'),
(4, 3, 1, '2011-12-12 14:57:55', '0000-00-00 00:00:00'),
(5, 3, 2, '2011-12-12 14:57:56', '0000-00-00 00:00:00'),
(6, 5, 1, '2011-12-12 14:57:57', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_acl_groups`
--

CREATE TABLE IF NOT EXISTS `jos_acl_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `jos_acl_groups`
--

INSERT INTO `jos_acl_groups` (`id`, `title`, `name`, `created_at`, `modified_at`) VALUES
(1, 'цкцукц кцук цук', '42423', '2011-12-12 14:57:01', '0000-00-00 00:00:00'),
(2, 'к234', 'кцук324', '2011-12-12 14:57:05', '0000-00-00 00:00:00'),
(3, '2423кцу', 'цукцук234', '2011-12-12 14:57:08', '0000-00-00 00:00:00'),
(4, '234укцук', 'цк234234', '2011-12-12 14:57:11', '0000-00-00 00:00:00'),
(5, '234кцукцук', '234цукуку', '2011-12-12 14:57:13', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_acl_list`
--

CREATE TABLE IF NOT EXISTS `jos_acl_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `acl_group` varchar(25) NOT NULL,
  `acl_name` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acl_group` (`acl_group`,`acl_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=152 ;

--
-- Дамп данных таблицы `jos_acl_list`
--

INSERT INTO `jos_acl_list` (`id`, `title`, `acl_group`, `acl_name`, `created_at`, `modified_at`) VALUES
(1, 'actionsAdminAcls::action_before', 'actionsAdminAcls', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(2, 'actionsAdminAcls::index', 'actionsAdminAcls', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(3, 'actionsAdminAcls::create', 'actionsAdminAcls', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(4, 'actionsAdminAcls::edit', 'actionsAdminAcls', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(5, 'actionsAdminAcls::save', 'actionsAdminAcls', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(6, 'actionsAdminAcls::apply', 'actionsAdminAcls', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(7, 'actionsAdminAcls::save_and_new', 'actionsAdminAcls', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(8, 'actionsAdminAcls::remove', 'actionsAdminAcls', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(9, 'actionsAdminAcls::acl_table', 'actionsAdminAcls', 'acl_table', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(10, 'actionsAdminAcls::acl_list', 'actionsAdminAcls', 'acl_list', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(11, 'actionsAdminAcls::get_actions', 'actionsAdminAcls', 'get_actions', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(12, 'actionsAjaxAdminAcls::action_before', 'actionsAjaxAdminAcls', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(13, 'actionsAjaxAdminAcls::change', 'actionsAjaxAdminAcls', 'change', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(14, 'actionsAdminAdmin::index', 'actionsAdminAdmin', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(15, 'actionsAjaxAdminCategories::index', 'actionsAjaxAdminCategorie', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(16, 'actionsAjaxAdminCategories::status_change', 'actionsAjaxAdminCategorie', 'status_change', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(17, 'actionsAjaxAdminCategories::image_uploader', 'actionsAjaxAdminCategorie', 'image_uploader', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(18, 'actionsAjaxAdminCategories::slug_generator', 'actionsAjaxAdminCategorie', 'slug_generator', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(19, 'actionsAjaxAdminCategories::add_pic', 'actionsAjaxAdminCategorie', 'add_pic', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(20, 'actionsAdminCategories::action_before', 'actionsAdminCategories', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(21, 'actionsAdminCategories::index', 'actionsAdminCategories', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(22, 'actionsAdminCategories::create', 'actionsAdminCategories', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(23, 'actionsAdminCategories::edit', 'actionsAdminCategories', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(24, 'actionsAdminCategories::save_this', 'actionsAdminCategories', 'save_this', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(25, 'actionsAdminCategories::save', 'actionsAdminCategories', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(26, 'actionsAdminCategories::apply', 'actionsAdminCategories', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(27, 'actionsAdminCategories::save_and_new', 'actionsAdminCategories', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(28, 'actionsAdminCategories::root_add', 'actionsAdminCategories', 'root_add', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(29, 'actionsAdminCategories::node_add', 'actionsAdminCategories', 'node_add', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(30, 'actionsAdminCategories::node_del', 'actionsAdminCategories', 'node_del', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(31, 'actionsAdminCategories::node_move_up', 'actionsAdminCategories', 'node_move_up', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(32, 'actionsAdminCategories::node_move_down', 'actionsAdminCategories', 'node_move_down', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(33, 'actionsAdminCategories::node_move_lft', 'actionsAdminCategories', 'node_move_lft', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(34, 'actionsAdminCategories::node_move_rgt', 'actionsAdminCategories', 'node_move_rgt', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(35, 'actionsAjaxAdminCoder::index', 'actionsAjaxAdminCoder', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(36, 'actionsAjaxAdminCoder::table_select', 'actionsAjaxAdminCoder', 'table_select', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(37, 'actionsAjaxAdminCoder::anonymous function', 'actionsAjaxAdminCoder', 'anonymous function', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(38, 'actionsAjaxAdminCoder::generate_code', 'actionsAjaxAdminCoder', 'generate_code', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(39, 'actionsAjaxAdminCoder::generate_files', 'actionsAjaxAdminCoder', 'generate_files', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(40, 'actionsAjaxAdminCoder::generate_component', 'actionsAjaxAdminCoder', 'generate_component', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(41, 'actionsAdminCoder::action_before', 'actionsAdminCoder', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(42, 'actionsAdminCoder::index', 'actionsAdminCoder', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(43, 'actionsAdminCoder::faker', 'actionsAdminCoder', 'faker', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(44, 'actionsAdminCoder::componenter', 'actionsAdminCoder', 'componenter', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(45, 'actionsAjaxAdminContent::index', 'actionsAjaxAdminContent', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(46, 'actionsAjaxAdminContent::image_uploader', 'actionsAjaxAdminContent', 'image_uploader', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(47, 'actionsAjaxAdminContent::add_pic', 'actionsAjaxAdminContent', 'add_pic', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(48, 'actionsAjaxAdminContent::slug_generator', 'actionsAjaxAdminContent', 'slug_generator', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(49, 'actionsAdminContent::action_before', 'actionsAdminContent', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(50, 'actionsAdminContent::index', 'actionsAdminContent', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(51, 'actionsAdminContent::create', 'actionsAdminContent', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(52, 'actionsAdminContent::edit', 'actionsAdminContent', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(53, 'actionsAdminContent::save_this', 'actionsAdminContent', 'save_this', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(54, 'actionsAdminContent::save', 'actionsAdminContent', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(55, 'actionsAdminContent::apply', 'actionsAdminContent', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(56, 'actionsAdminContent::save_and_new', 'actionsAdminContent', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(57, 'actionsAdminContent::remove', 'actionsAdminContent', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(58, 'actionsAdminContent::copy', 'actionsAdminContent', 'copy', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(59, 'actionsContent::action_before', 'actionsContent', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(60, 'actionsContent::index', 'actionsContent', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(61, 'actionsContent::category', 'actionsContent', 'category', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(62, 'actionsContent::category_collections', 'actionsContent', 'category_collections', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(63, 'actionsContent::category_about', 'actionsContent', 'category_about', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(64, 'actionsContent::_category', 'actionsContent', '_category', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(65, 'actionsContent::view', 'actionsContent', 'view', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(66, 'actionsExample::index', 'actionsExample', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(67, 'actionsExample::joosBenchmark_example', 'actionsExample', 'joosBenchmark_example', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(68, 'actionsExample::joosModulesException_example', 'actionsExample', 'joosModulesException_exam', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(69, 'actionsMainpage::index', 'actionsMainpage', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(70, 'actionsAdminMetainfo::action_before', 'actionsAdminMetainfo', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(71, 'actionsAdminMetainfo::index', 'actionsAdminMetainfo', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(72, 'actionsAdminMetainfo::save', 'actionsAdminMetainfo', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(73, 'actionsAdminMetainfo::apply', 'actionsAdminMetainfo', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(74, 'actionsAdminMetainfo::cancel', 'actionsAdminMetainfo', 'cancel', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(75, 'actionsAjaxAdminModules::status_change', 'actionsAjaxAdminModules', 'status_change', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(76, 'actionsAjaxAdminModules::reorder', 'actionsAjaxAdminModules', 'reorder', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(77, 'actionsAjaxAdminModules::index', 'actionsAjaxAdminModules', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(78, 'actionsAjaxAdminModules::get_positions', 'actionsAjaxAdminModules', 'get_positions', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(79, 'actionsAjaxAdminModules::save_position', 'actionsAjaxAdminModules', 'save_position', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(80, 'actionsAdminModules::action_before', 'actionsAdminModules', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(81, 'actionsAdminModules::index', 'actionsAdminModules', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(82, 'actionsAdminModules::create', 'actionsAdminModules', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(83, 'actionsAdminModules::edit', 'actionsAdminModules', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(84, 'actionsAdminModules::save', 'actionsAdminModules', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(85, 'actionsAdminModules::apply', 'actionsAdminModules', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(86, 'actionsAdminModules::save_and_new', 'actionsAdminModules', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(87, 'actionsAdminModules::remove', 'actionsAdminModules', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(88, 'actionsAjaxAdminPages::status_change', 'actionsAjaxAdminPages', 'status_change', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(89, 'actionsAjaxAdminPages::slug_generator', 'actionsAjaxAdminPages', 'slug_generator', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(90, 'actionsAdminPages::action_before', 'actionsAdminPages', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(91, 'actionsAdminPages::index', 'actionsAdminPages', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(92, 'actionsAdminPages::create', 'actionsAdminPages', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(93, 'actionsAdminPages::edit', 'actionsAdminPages', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(94, 'actionsAdminPages::save_this', 'actionsAdminPages', 'save_this', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(95, 'actionsAdminPages::save', 'actionsAdminPages', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(96, 'actionsAdminPages::apply', 'actionsAdminPages', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(97, 'actionsAdminPages::save_and_new', 'actionsAdminPages', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(98, 'actionsAdminPages::remove', 'actionsAdminPages', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(99, 'actionsPages::index', 'actionsPages', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(100, 'actionsPages::view', 'actionsPages', 'view', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(101, 'actionsAdminQuickicons::index', 'actionsAdminQuickicons', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(102, 'actionsAdminQuickicons::create', 'actionsAdminQuickicons', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(103, 'actionsAdminQuickicons::edit', 'actionsAdminQuickicons', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(104, 'actionsAdminQuickicons::save', 'actionsAdminQuickicons', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(105, 'actionsAdminQuickicons::save_and_new', 'actionsAdminQuickicons', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(106, 'actionsAdminQuickicons::remove', 'actionsAdminQuickicons', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(107, 'actionsAdminSitemap::index', 'actionsAdminSitemap', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(108, 'actionsAdminSitemap::generate_xml', 'actionsAdminSitemap', 'generate_xml', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(109, 'actionsSitemap::action_before', 'actionsSitemap', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(110, 'actionsSitemap::index', 'actionsSitemap', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(111, 'actionsAdminTest::index', 'actionsAdminTest', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(112, 'actionsAdminTest::test_function', 'actionsAdminTest', 'test_function', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(113, 'actionsAdminTest::get_menu', 'actionsAdminTest', 'get_menu', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(114, 'actionsTest::index', 'actionsTest', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(115, 'actionsTest::upload', 'actionsTest', 'upload', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(116, 'actionsTest::db', 'actionsTest', 'db', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(117, 'actionsAjaxTest::upload', 'actionsAjaxTest', 'upload', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(118, 'actionsAjaxAdminUsers::status_change', 'actionsAjaxAdminUsers', 'status_change', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(119, 'actionsAdminUsers::index', 'actionsAdminUsers', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(120, 'actionsAdminUsers::create', 'actionsAdminUsers', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(121, 'actionsAdminUsers::edit', 'actionsAdminUsers', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(122, 'actionsAdminUsers::save', 'actionsAdminUsers', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(123, 'actionsAdminUsers::apply', 'actionsAdminUsers', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(124, 'actionsAdminUsers::save_and_new', 'actionsAdminUsers', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(125, 'actionsAdminUsers::remove', 'actionsAdminUsers', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(126, 'actionsCurrent::index', 'actionsCurrent', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(127, 'actionsCurrent::create', 'actionsCurrent', 'create', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(128, 'actionsCurrent::edit', 'actionsCurrent', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(129, 'actionsCurrent::save', 'actionsCurrent', 'save', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(130, 'actionsCurrent::apply', 'actionsCurrent', 'apply', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(131, 'actionsCurrent::save_and_new', 'actionsCurrent', 'save_and_new', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(132, 'actionsCurrent::remove', 'actionsCurrent', 'remove', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(133, 'viewsCurrent::get_hrefs', 'viewsCurrent', 'get_hrefs', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(134, 'viewsCurrent::index', 'viewsCurrent', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(135, 'viewsCurrent::edit', 'viewsCurrent', 'edit', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(136, 'actionsAjaxUsers::login', 'actionsAjaxUsers', 'login', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(137, 'actionsAjaxUsers::logout', 'actionsAjaxUsers', 'logout', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(138, 'actionsAjaxUsers::register', 'actionsAjaxUsers', 'register', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(139, 'actionsAjaxUsers::reload_login_area', 'actionsAjaxUsers', 'reload_login_area', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(140, 'actionsAjaxUsers::reload_menu', 'actionsAjaxUsers', 'reload_menu', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(141, 'actionsUsers::action_before', 'actionsUsers', 'action_before', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(142, 'actionsUsers::index', 'actionsUsers', 'index', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(143, 'actionsUsers::view', 'actionsUsers', 'view', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(144, 'actionsUsers::edituser', 'actionsUsers', 'edituser', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(145, 'actionsUsers::login', 'actionsUsers', 'login', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(146, 'actionsUsers::logout', 'actionsUsers', 'logout', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(147, 'actionsUsers::register', 'actionsUsers', 'register', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(148, 'actionsUsers::check', 'actionsUsers', 'check', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(149, 'actionsUsers::save_register', 'actionsUsers', 'save_register', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(150, 'actionsUsers::lostpassword', 'actionsUsers', 'lostpassword', '2011-12-12 14:57:30', '0000-00-00 00:00:00'),
(151, 'actionsUsers::send_new_pass', 'actionsUsers', 'send_new_pass', '2011-12-12 14:57:30', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_acl_users_groups`
--

CREATE TABLE IF NOT EXISTS `jos_acl_users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` tinyint(5) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `jos_acl_users_groups`
--

INSERT INTO `jos_acl_users_groups` (`id`, `user_id`, `group_id`, `created_at`, `modified_at`) VALUES
(4, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_hits`
--

INSERT INTO `jos_hits` (`id`, `obj_id`, `obj_option`, `obj_task`, `hit`) VALUES
(1, 0, 'pages', 'view', 2),
(2, 1, 'pages', 'view', 119);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_pages`
--

INSERT INTO `jos_pages` (`id`, `title`, `slug`, `text`, `meta_keywords`, `meta_description`, `created_at`, `state`) VALUES
(1, 'Главная страничка с текстом, тадааа!', 'glavnaya-stranichka-s-tekstom-tadaaa', '<div>Energistically evisculate end-to-end supply chains after web-enabled services. Objectively mesh open-source infrastructures without B2C opportunities. Efficiently envisioneer goal-oriented technologies before premium experiences. Appropriately restore strategic internal or "organic" sources through turnkey information. Conveniently utilize business human capital whereas functional potentialities.</div><div><br></div><div>Objectively engineer unique metrics rather than client-centered experiences. Dramatically productivate prospective channels with front-end initiatives. Monotonectally cultivate accurate materials through enterprise-wide supply chains. Professionally grow unique outsourcing with pandemic deliverables. Rapidiously extend go forward models without extensible materials.</div><div><br></div><div>Progressively deploy e-business alignments through stand-alone partnerships. Proactively redefine resource maximizing partnerships with quality methods of empowerment. Assertively fashion installed base quality vectors after wireless channels. Assertively recaptiualize one-to-one bandwidth rather than resource-leveling e-commerce. Compellingly leverage existing exceptional functionalities through just in time resources.</div><div><br></div><div>Interactively target stand-alone core competencies and standards compliant experiences. Enthusiastically whiteboard reliable process improvements after 2.0 scenarios. Progressively engage 2.0 interfaces after sticky core competencies. Credibly provide access to web-enabled manufactured products and professional services. Competently communicate team driven internal or "organic" sources and user friendly internal or "organic" sources.</div><div><br></div><div>Collaboratively brand cutting-edge manufactured products whereas alternative alignments. Credibly formulate top-line schemas whereas cross-unit process improvements. Dynamically deploy synergistic web-readiness without 24/365 infomediaries. Competently matrix multifunctional results before granular applications. Phosfluorescently fabricate prospective metrics via interoperable content.</div><div><br></div><div>Efficiently strategize customer directed web services rather than frictionless scenarios. Rapidiously initiate covalent best practices whereas excellent resources. Credibly formulate client-centered action items after compelling e-markets. Globally syndicate scalable resources rather than adaptive outsourcing. Holisticly syndicate B2C methods of empowerment after efficient solutions.</div><div><br></div><div>Professionally re-engineer multimedia based expertise and 2.0 customer service. Appropriately engage leading-edge "outside the box" thinking without cutting-edge intellectual capital. Credibly recaptiualize synergistic catalysts for change for seamless resources. Monotonectally incentivize exceptional customer service and enterprise-wide best practices. Objectively network fully tested portals through client-focused potentialities.</div><div><br></div><div>Enthusiastically integrate distributed human capital whereas leading-edge catalysts for change. Credibly integrate robust models through principle-centered materials. Progressively evisculate one-to-one growth strategies through proactive paradigms. Progressively scale world-class e-services via standardized infomediaries. Professionally evisculate cross-platform total linkage without performance based potentialities.</div>  ', '', '', '2012-01-03 22:58:52', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` (`id`, `user_name`, `user_name_canonikal`, `real_name`, `email`, `openid`, `password`, `state`, `group_id`, `group_name`, `register_date`, `lastvisit_date`, `activation`, `bad_auth_count`) VALUES
(1, 'admin', 'atm1n', 'Кооолька!', 'admin@joostina.ru', '', '1e72fb95f1710557274dcfd23a4582fd:DAj5Qf4Y1ktEenhE', 1, 8, 'SuperAdministrator', '2010-11-28 15:29:47', '2012-02-13 14:13:03', '', 0);

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
(6, '', NULL, '', '', '0000-00-00', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

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
(8, 7, 'SuperAdministrator', 'Супер Администраторы'),
(9, 4, 'piders', 'Пидры');

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
('67f3625255cd0e9c55539504f79cb670', 'admin', NULL, '1329135616', 0, 1, 'SuperAdministrator', 8, 1);

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

