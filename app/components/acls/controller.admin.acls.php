<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Acl - Компонент для управления правами
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Acls
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminAcls {

	/**
	 * Название обрабатываемой модели
	 *
	 * @var joosModel модель
	 */
	public static $model = 'modelAdminAclGroups';
	public static $submenu = array(
		'groups' => array(
			'name' => 'Группы',
			'href' => 'index2.php?option=acls&menu=groups',
			'model' => 'modelAdminAclGroups',
			'fields' => array('title'),
			'active' => false
		),
		'acls' => array(
			'name' => 'Права',
			'href' => 'index2.php?option=acls&menu=acls',
			'model' => 'modelAdminAclList',
			'fields' => array('title'),
			'active' => false
		),
		'acl_table' => array(
			'name' => 'Рапределение прав',
			'href' => 'index2.php?option=acls&menu=acl_table&task=acl_table',
			'model' => false,
			'active' => false
		),
	);
	private static $fields_list;

	public static function action_before() {

		joosLoader::model('acls');
		joosLoader::admin_model('acls');

		$menu = joosRequest::request('menu', false);

		if ($menu && isset(self::$submenu[$menu])) {

			$active = self::$submenu[$menu];

			self::$submenu[$menu]['active'] = true;
			self::$model = $active['model'];

			joosAutoadmin::$submenu = $menu;
		}

		self::$fields_list = isset(self::$submenu[$menu]['fields']) ? self::$submenu[$menu]['fields'] : array('title');

		joosAutoadmin::$model = self::$model;


		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/app/components/acls/media/js/acls.js');
	}

	/**
	 * Список объектов
	 */
	public static function index($option) {
		$obj = new self::$model;
		$obj_count = joosAutoadmin::get_count($obj);

		$pagenav = joosAutoadmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);
		$obj_list = joosAutoadmin::get_list($obj, $param);

		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoadmin::listing($obj, $obj_list, $pagenav, self::$fields_list);
	}

	/**
	 * Редактирование
	 */
	public static function create($option) {
		self::edit($option, 0);
	}

	/**
	 * Редактирование объекта
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit($option, $id) {
		$obj_data = new self::$model;
		$id > 0 ? $obj_data->load($id) : null;

		joosAutoadmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $id, $page, $task, $redirect = 0) {

		joosCSRF::check_code();

		$obj_data = new self::$model;
		$result = $obj_data->save($_POST);

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, null, null, null, 1);
	}

	public static function save_and_new($option) {
		return self::save($option, null, null, null, 2);
	}

	/**
	 * Удаление одного или группы объектов
	 */
	public static function remove($option) {
		joosCSRF::check_code();

		// идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, 'id') ? joosRoute::redirect('index2.php?option=' . $option, 'Удалено успешно!') : joosRoute::redirect('index2.php?option=' . $option, 'Ошибка удаления');
	}

	public static function acl_table() {

		$group_obj = new modelAclGroups;
		$groups = $group_obj->find_all();

		$acl_list_obj = new modelAclList;
		$acls = $acl_list_obj->find_all();

		$acl_list = array();
		foreach ($acls as $acl) {
			$acl_list[$acl->acl_group][sprintf('%s::%s', $acl->acl_group, $acl->acl_name)] = $acl;
		}

		$acl_groups = array_keys($acl_list);

		sort($acl_groups);
		sort($acls);

		$sql = 'SELECT ag.id AS group_id, al.id AS list_id FROM  #__acl_access AS aa INNER JOIN #__acl_groups AS ag ON ( ag.id=aa.group_id ) INNER JOIN #__acl_list AS al ON ( al.id=aa.task_id )';
		$acl_rules_array = joosDatabase::instance()->set_query($sql)->load_assoc_list();

		$acl_rules = array();
		foreach ($acl_rules_array as $value) {
			$acl_rules[$value['group_id']][$value['list_id']] = true;
		}

		return array(
			'groups' => $groups,
			'acl_groups' => $acl_groups,
			'acl_list' => $acl_list,
			'acls' => $acls,
			'acl_rules' => $acl_rules
		);
	}

	public static function acl_list() {
		$sql = 'SELECT ag.name, al.acl_name FROM  #__acl_access AS aa INNER JOIN #__acl_groups AS ag ON ( ag.id=aa.group_id ) INNER JOIN #__acl_list AS al ON ( al.id=aa.task_id ) WHERE ag.id IN (1,3)';
		$r = joosDatabase::instance()->set_query($sql)->load_assoc_list();

		$d = array();
		foreach ($r as $value) {
			$d[$value['name']][$value['acl_name']] = true;
		}

//		$v  = $d['admins'] + $d['moders'];

		_xdump($d);
	}

	public static function get_actions() {

		$location = JPATH_BASE . '/app/components/';

		$Directory = new RecursiveDirectoryIterator($location);
		$Iterator = new RecursiveIteratorIterator($Directory);
		$Regex = new RegexIterator($Iterator, '/^.+controller.+/i', RecursiveRegexIterator::GET_MATCH);

		joosLoader::lib('Reflect', 'Reflect');

		$options = array(
			'properties' => array(
				'class' => array(
					'methods'
				),
			)
		);
		$reflect = new PHP_Reflect($options);

		$classes = array();
		foreach ($Regex as $path) {
			$source = $path[0];
			$reflect->scan($source);
			$cl = $reflect->getClasses();
			foreach ($cl['\\'] as $k => $cc) {
				foreach ($cc['methods'] as $km => $m) {
					$classes[$k . $km] = array(
						'title' => sprintf('%s::%s', $k, $km),
						'acl_group' => $k,
						'acl_name' => $km,
						'created_at' => JCURRENT_SERVER_TIME
					);
				}
			}
		}

		//_xdump($classes);

		$acls_list = new modelAclList;
		$acls_list->insert_array($classes);

		echo sprintf('Вставлено %s правил', count($classes));
	}

}

