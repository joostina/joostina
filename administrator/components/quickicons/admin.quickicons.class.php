<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class Quickicons
 * @package	Quickicons
 * @subpackage	Joostina CMS
 * @created	2010-09-26 02:05:12
 */
class Quickicons extends JDBmodel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(64)
	 */
	public $title;
	/**
	 * @var varchar(255)
	 */
	public $alt_text;
	/**
	 * @var varchar(255)
	 */
	public $href;
	/**
	 * @var varchar(100)
	 */
	public $icon;
	/**
	 * @var int(10) unsigned
	 */
	public $ordering;
	/**
	 * @var tinyint(1) unsigned
	 */
	public $state;
	/**
	 * @var int(3)
	 */
	public $gid;

	// каталог размещения значков для кнопок быстрого доступа
	private static $ico_dir = "/media/images/icons/32x32/faenza";

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->JDBmodel('#__quickicons', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'title' => array(
				'name' => 'Текст ссылки',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'alt_text' => array(
				'name' => 'Текст подсказки',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'href' => array(
				'name' => 'Ссылка',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'href_selector_helper' => array(
				'name' => 'Помошник',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Quickicons::get_href_helper'
				),
			),			
			'ordering' => array(
				'name' => 'Порядок',
				'editable' => true,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'state' => array(
				'name' => 'Состояние',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Опубликовано',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'statuses' => array(
						0 => 'Скрыто',
						1 => 'Опубликовано'
					),
					'images' => array(
						0 => 'publish_x.png',
						1 => 'publish_g.png',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
				)
			),
			'gid' => array(
				'name' => 'Показывать для групп не ниже',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'Quickicons::get_usergroup'
				),
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'Quickicons::get_usergroup'
				),
			),
			'icon' => array(
				'name' => 'Красивый значек',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Quickicons::get_icon_list'
				),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Ссылки быстрого доступа',
			'header_new' => 'Создание ссылки',
			'header_edit' => 'Редактирование ссылки'
		);
	}

	public static function get_ico_pach() {
		return self::$ico_dir . '/';
	}

	public static function get_usergroup($gid = false) {
		$games = new Usergroup;
		$groups = $games->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title'));
		return $gid ? $groups[$gid] : $groups;
	}

	public static function get_href_helper(self $cur_obj) {
		mosMainFrame::addLib('form');
		mosMainFrame::addLib('files');

		$files = new File(0755);
		$controller_list = $files->getList(JPATH_BASE .DS.JADMIN_BASE.'/components', 'folder');

		$controllers = array();
		foreach( $controller_list as $controller ){
			$controllers[$controller['name']] = $controller['name'];
		}

		return form::dropdown('quickicons_href_helper',$controllers,null);
	}

	public static function get_icon_list(self $cur_obj) {
		mosMainFrame::addLib('html');
		mosMainFrame::addLib('files');

		$files = new File(0755);
		$icons = $files->getList(JPATH_BASE . self::get_ico_pach(), 'file');

		$icon_web_root = JPATH_SITE . self::get_ico_pach();

		$images = array();
		$images[] = '<table><tr>';
		$images[] = '<td align="center" width="150">';
		$images[] = HTML::image(array('src' => $icon_web_root . $cur_obj->icon));
		$images[] = '<br /> Текущий значек ';
		$images[] = '</td>';
		$images[] = '<td align="center" width="150">';
		$images[] = HTML::image(array('src' => $icon_web_root . $cur_obj->icon, 'id' => 'new_quickicons_icon'));
		$images[] = '<br /> Новый значек ';
		$images[] = '</td>';
		$images[] = '</tr>';
		$images[] = '</table>';

		$image_icons = array();
		foreach ($icons as $icon) {
			if ($icon['extension'] == 'png') {
				$class = $icon['name'] == $cur_obj->icon ? 'quickicons_icons_active' : false;
				$image_icons[] = HTML::image(array('src' => $icon_web_root . $icon['name'], 'class' => $class, 'alt' => $icon['name']));
			}
		}

		$images[] = '<div id="quickicons_icons">' . implode("\n", $image_icons) . '</div>';
		$images[] = sprintf('<input type="hidden" name="icon" id="quickicons_icon_value" value="%s"  />', $cur_obj->icon);

		return implode("\n", $images);
	}

}
