<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsAjaxAdminAcls extends joosController {

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