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

	}

    public static function check_access_for_user_id($full_operations_name,$user_id) {

        static $allowed_rules;
        
        if( $allowed_rules===null ){
            $sql = sprintf("SELECT DISTINCT CONCAT_WS('::',al.acl_group, al.acl_name) AS rule_name, 1 AS value FROM  #__acl_access AS aa INNER JOIN #__acl_groups AS ag ON ( ag.id=aa.group_id ) INNER JOIN #__acl_list AS al ON ( al.id=aa.task_id ) WHERE ag.id IN (  SELECT group_id FROM #__acl_users_groups WHERE user_id = %s )",  $user_id );
            $allowed_rules = joosDatabase::instance()->set_query($sql)->load_row_array('rule_name','value');
        }
            
        return isset( $allowed_rules[$full_operations_name] );
    }


}

/**
 * Class AclGroups
 * @package Models
 * @subpackage Acls
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
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
}

/**
 * Class AclList
 * @package Models
 * @subpackage Acls
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
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
}

/**
 * Class AclUsersGroups
 * @package Models
 * @subpackage Acls
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
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
}

/**
 * Class AclAccess
 * @package Models
 * @subpackage Acls
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-12-07 14:23:55
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class modelAclAccess extends joosModel {
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
	function __construct(){
		parent::__construct( '#__acl_access', 'id' );
	}

	public function check() {
		$this->filter();
		return true;
	}
}
