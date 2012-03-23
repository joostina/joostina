<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент для управления правами
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
class actionsAjaxAdminAcls extends joosAdminControllerAjax {

	public static function action_before() {
		joosLoader::model('acls');
		joosLoader::admin_model('acls');
	}

	/**
	 * Смена статуса доступа группы к действию
	 *
	 * @return array
	 */
	public static function change() {

		$group_id = joosRequest::int('group_id');
		$task_id = joosRequest::int('task_id');

		$access = new modelAclAccess;

		$access->group_id = $group_id;
		$access->task_id = $task_id;

		$access->find();
		if ($access->id) {
			$access->delete($access->id);
		} else {
			$access->store();
		}


		return array(
			'success' => true,
			'body' => 555
		);
	}

}