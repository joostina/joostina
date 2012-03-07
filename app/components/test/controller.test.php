<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Tags
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsTest {

	/**
	 * Метод контроллера, запускаемый по умолчанию
	 *
	 * @static
	 * @return array
	 */
	public static function index() {

		$user = joosCore::user();
		_xdump($user->user_name);

		$user_id = 1;

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

		joosDebug::dump($acl_rules,$acl_list);

		return array('asd' => crc32('Alanis Morissette - Crazy'));
	}

	/**
	 * Тестирование загрузчика
	 */
	public static function upload() {
		return array();
	}

	/**
	 * Примеры работы с базой данных
	 */
	public static function db() {

		$user_1 = joosDatabase::instance()->set_query('select * from #__users where id=1')->load_result();

		_xdump($user_1);
	}

}
