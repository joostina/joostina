<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class AclGroups
 * @package Joostina.Components
 * @subpackage AclGroups
 * @author JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-05 12:38:49
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelAclGroups extends joosModel {

	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $id;

	/**
	 * @field varchar(20)
	 * @type string
	 */
	public $title;

	/**
	 * @field varchar(250)
	 * @type string
	 */
	public $name;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $created_at;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $modified_at;

	/*
	 * Constructor
	 */

	function __construct() {
		parent::__construct('#__acl_groups', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function before_insert() {
		return true;
	}

	public function after_insert() {
		return true;
	}

	public function before_update() {
		return true;
	}

	public function after_update() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

}

/**
 * Class AclList
 * @package Joostina.Components
 * @subpackage AclList
 * @author JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-05 12:38:49
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelAclList extends joosModel {

	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $id;

	/**
	 * @field varchar(200)
	 * @type string
	 */
	public $title;

	/**
	 * @field varchar(15)
	 * @type string
	 */
	public $acl_group;

	/**
	 * @field varchar(15)
	 * @type string
	 */
	public $acl_name;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $created_at;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $modified_at;

	/*
	 * Constructor
	 */

	function __construct() {
		parent::__construct('#__acl_list', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function before_insert() {
		return true;
	}

	public function after_insert() {
		return true;
	}

	public function before_update() {
		return true;
	}

	public function after_update() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

}

/**
 * Class AclUsersGroups
 * @package Joostina.Components
 * @subpackage AclUsersGroups
 * @author JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-05 12:38:49
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelAclUsersGroups extends joosModel {

	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $id;

	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $user_id;

	/**
	 * @field tinyint(5) unsigned
	 * @type int
	 */
	public $group_id;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $created_at;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $modified_at;

	/*
	 * Constructor
	 */

	function __construct() {
		parent::__construct('#__acl_users_groups', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function before_insert() {
		return true;
	}

	public function after_insert() {
		return true;
	}

	public function before_update() {
		return true;
	}

	public function after_update() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

}

/**
 * Упрощённая заглушка для проверки прав
 */
class Acl {

	public static function check_access($full_operations_name) {
		//if(Yii::app()->user->checkAccess('deletePost'))
	}

	public static function is_allow($full_operations_name) {

	}

	public static function is_deny($full_operations_name) {

	}

}