<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();


/**
 * Упрощённая заглушка для проверки прав
 */
class helperAcl {

	public static function is_allowed($full_operations_name) {
        
        $user_id = joosCore::user()->id;
        return self::check_access_for_user_id($full_operations_name,$user_id);
	}

	public static function is_deny($full_operations_name) {
        return !self::is_allowed($full_operations_name);
}

    public static function check_access_for_user_id($full_operations_name,$user_id) {

        static $allowed_rules;
        
        if( $allowed_rules===null ){
            $sql = sprintf("SELECT DISTINCT CONCAT_WS('::',al.acl_group, al.acl_name) AS rule_name, 1 AS value FROM  #__users_acl_rules_groups AS aa INNER JOIN #__users_acl_groups AS ag ON ( ag.id=aa.group_id ) INNER JOIN #__users_acl_rules AS al ON ( al.id=aa.task_id ) WHERE ag.id IN (  SELECT group_id FROM #__users_acl_groups_users WHERE user_id = %s )",  $user_id );
            $allowed_rules = joosDatabase::instance()->set_query($sql)->load_row_array('rule_name','value');
        }
            
        return isset( $allowed_rules[$full_operations_name] );
    }


}

/**
 * Class AclGroups
 * 
 * @package    Components\Acls
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-05 12:38:49
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelUsersAclGroups extends joosModel {

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
		parent::__construct('#__users_acl_groups', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}
}

/**
 * Class AclList
 * 
 * @package    Components\Acls
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-05 12:38:49
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelUsersAclRules extends joosModel {

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
		parent::__construct('#__users_acl_rules', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}
}

/**
 * Class AclUsersGroups
 * 
 * @package    Components\Acls
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-05 12:38:49
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelUsersAclGroupsUsers extends joosModel {

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

	/*
	 * Constructor
	 */

	function __construct() {
		parent::__construct('#__users_acl_groups_users', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}
}

/**
 * Class AclAccess
 * 
 * @package    Components\Acls
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-07 14:23:55
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelUsersAclRolesGroups extends joosModel {
	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $id;
	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $group_id;
	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $task_id;

	/*
	 * Constructor
	 */
	function __construct(){
		parent::__construct( '#__users_acl_rules_groups', 'id' );
	}

	public function check() {
		$this->filter();
		return true;
	}
}
