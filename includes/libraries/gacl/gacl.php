<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 * phpGACL - Generic Access Control List
 * Copyright (C) 2002,2003 Mike Benoit
 **/

defined('_VALID_MOS') or die();

class gacl {

	private static $_instance;

	private $_debug = false;
	public $db = null;
	public $_db_table_prefix = '#__core_acl_';
	var $_caching = false;
	var $_force_cache_expire = true;

	//var $acl = null;
	var $acl_count = 50;
	
	// TODO - так-то
	var $acl = array (
			0 =>
			array (
							0 => 'administration',
							1 => 'login',
							2 => 'users',
							3 => 'administrator',
							4 => NULL,
							5 => NULL,
			),
			1 =>
			array (
							0 => 'administration',
							1 => 'login',
							2 => 'users',
							3 => 'super administrator',
							4 => NULL,
							5 => NULL,
			),
			2 =>
			array (
							0 => 'administration',
							1 => 'login',
							2 => 'users',
							3 => 'manager',
							4 => NULL,
							5 => NULL,
			),
			3 =>
			array (
							0 => 'administration',
							1 => 'config',
							2 => 'users',
							3 => 'super administrator',
							4 => NULL,
							5 => NULL,
			),
			4 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_cache',
			),
			5 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_templates',
			),
			6 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'super administrator',
							4 => 'installers',
							5 => 'all',
			),
			7 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'super administrator',
							4 => 'templates',
							5 => 'all',
			),
			8 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'administrator',
							4 => 'components',
							5 => 'com_trash',
			),
			9 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_trash',
			),
			10 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'administrator',
							4 => 'components',
							5 => 'com_menumanager',
			),
			11 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_menumanager',
			),
			12 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_languages',
			),
			13 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'super administrator',
							4 => 'languages',
							5 => 'all',
			),
			14 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'administrator',
							4 => 'modules',
							5 => 'all',
			),
			15 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'super administrator',
							4 => 'modules',
							5 => 'all',
			),
			16 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'super administrator',
							4 => 'modules',
							5 => 'all',
			),
			17 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'administrator',
							4 => 'modules',
							5 => 'all',
			),
			18 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'administrator',
							4 => 'plugins',
							5 => 'all',
			),
			19 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'super administrator',
							4 => 'plugins',
							5 => 'all',
			),
			20 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'super administrator',
							4 => 'plugins',
							5 => 'all',
			),
			21 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'administrator',
							4 => 'plugins',
							5 => 'all',
			),
			22 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'administrator',
							4 => 'components',
							5 => 'all',
			),
			23 =>
			array (
							0 => 'administration',
							1 => 'install',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'all',
			),
			24 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'all',
			),
			25 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'administrator',
							4 => 'components',
							5 => 'all',
			),
			26 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'manager',
							4 => 'components',
							5 => 'com_frontpage',
			),
			27 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_massmail',
			),
			28 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'administrator',
							4 => 'components',
							5 => 'com_users',
			),
			29 =>
			array (
							0 => 'administration',
							1 => 'manage',
							2 => 'users',
							3 => 'super administrator',
							4 => 'components',
							5 => 'com_users',
			),
			30 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'administrator',
							4 => 'user properties',
							5 => 'block_user',
			),
			31 =>
			array (
							0 => 'administration',
							1 => 'edit',
							2 => 'users',
							3 => 'super administrator',
							4 => 'user properties',
							5 => 'block_user',
			),
			32 =>
			array (
							0 => 'workflow',
							1 => 'email_events',
							2 => 'users',
							3 => 'administrator',
							4 => NULL,
							5 => NULL,
			),
			33 =>
			array (
							0 => 'workflow',
							1 => 'email_events',
							2 => 'users',
							3 => 'super administrator',
							4 => NULL,
							5 => NULL,
			),
			34 =>
			array (
							0 => 'action',
							1 => 'add',
							2 => 'users',
							3 => 'author',
							4 => 'content',
							5 => 'all',
			),
			35 =>
			array (
							0 => 'action',
							1 => 'add',
							2 => 'users',
							3 => 'editor',
							4 => 'content',
							5 => 'all',
			),
			36 =>
			array (
							0 => 'action',
							1 => 'add',
							2 => 'users',
							3 => 'publisher',
							4 => 'content',
							5 => 'all',
			),
			37 =>
			array (
							0 => 'action',
							1 => 'edit',
							2 => 'users',
							3 => 'author',
							4 => 'content',
							5 => 'own',
			),
			38 =>
			array (
							0 => 'action',
							1 => 'edit',
							2 => 'users',
							3 => 'editor',
							4 => 'content',
							5 => 'all',
			),
			39 =>
			array (
							0 => 'action',
							1 => 'edit',
							2 => 'users',
							3 => 'publisher',
							4 => 'content',
							5 => 'all',
			),
			40 =>
			array (
							0 => 'action',
							1 => 'publish',
							2 => 'users',
							3 => 'publisher',
							4 => 'content',
							5 => 'all',
			),
			41 =>
			array (
							0 => 'action',
							1 => 'add',
							2 => 'users',
							3 => 'manager',
							4 => 'content',
							5 => 'all',
			),
			42 =>
			array (
							0 => 'action',
							1 => 'edit',
							2 => 'users',
							3 => 'manager',
							4 => 'content',
							5 => 'all',
			),
			43 =>
			array (
							0 => 'action',
							1 => 'publish',
							2 => 'users',
							3 => 'manager',
							4 => 'content',
							5 => 'all',
			),
			44 =>
			array (
							0 => 'action',
							1 => 'add',
							2 => 'users',
							3 => 'administrator',
							4 => 'content',
							5 => 'all',
			),
			45 =>
			array (
							0 => 'action',
							1 => 'edit',
							2 => 'users',
							3 => 'administrator',
							4 => 'content',
							5 => 'all',
			),
			46 =>
			array (
							0 => 'action',
							1 => 'publish',
							2 => 'users',
							3 => 'administrator',
							4 => 'content',
							5 => 'all',
			),
			47 =>
			array (
							0 => 'action',
							1 => 'add',
							2 => 'users',
							3 => 'super administrator',
							4 => 'content',
							5 => 'all',
			),
			48 =>
			array (
							0 => 'action',
							1 => 'edit',
							2 => 'users',
							3 => 'super administrator',
							4 => 'content',
							5 => 'all',
			),
			49 =>
			array (
							0 => 'action',
							1 => 'publish',
							2 => 'users',
							3 => 'super administrator',
							4 => 'content',
							5 => 'all',
			),
	);

	function gacl(  ) {
		return;
/*
		$this->acl = array();
		$this->_mos_add_acl('administration', 'login', 'users', 'administrator', null, null);
		$this->_mos_add_acl('administration', 'login', 'users', 'super administrator', null, null);
		$this->_mos_add_acl('administration', 'login', 'users', 'manager', null, null);
		$this->_mos_add_acl('administration', 'config', 'users', 'super administrator', null, null);
		$this->_mos_add_acl('administration', 'edit', 'users', 'super administrator','components', 'com_cache');
		$this->_mos_add_acl('administration', 'manage', 'users', 'super administrator','components', 'com_templates');
		$this->_mos_add_acl('administration', 'install', 'users', 'super administrator','installers', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'super administrator','templates', 'all');
		$this->_mos_add_acl('administration', 'manage', 'users', 'administrator','components', 'com_trash');
		$this->_mos_add_acl('administration', 'manage', 'users', 'super administrator','components', 'com_trash');
		$this->_mos_add_acl('administration', 'manage', 'users', 'administrator','components', 'com_menumanager');
		$this->_mos_add_acl('administration', 'manage', 'users', 'super administrator','components', 'com_menumanager');
		$this->_mos_add_acl('administration', 'manage', 'users', 'super administrator','components', 'com_languages');
		$this->_mos_add_acl('administration', 'install', 'users', 'super administrator','languages', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'administrator','modules', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'super administrator','modules', 'all');
		$this->_mos_add_acl('administration', 'edit', 'users', 'super administrator','modules', 'all');
		$this->_mos_add_acl('administration', 'edit', 'users', 'administrator','modules', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'administrator','mambots', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'super administrator','mambots', 'all');
		$this->_mos_add_acl('administration', 'edit', 'users', 'super administrator','mambots', 'all');
		$this->_mos_add_acl('administration', 'edit', 'users', 'administrator','mambots', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'administrator','components', 'all');
		$this->_mos_add_acl('administration', 'install', 'users', 'super administrator','components', 'all');
		$this->_mos_add_acl('administration', 'edit', 'users', 'super administrator','components', 'all');
		$this->_mos_add_acl('administration', 'edit', 'users', 'administrator','components', 'all');
		//$this->_mos_add_acl('administration', 'edit', 'users', 'manager', 'components','com_newsflash');
		$this->_mos_add_acl('administration', 'edit', 'users', 'manager', 'components','com_frontpage');
		$this->_mos_add_acl('administration', 'manage', 'users', 'super administrator','components', 'com_massmail');
		$this->_mos_add_acl('administration', 'manage', 'users', 'administrator','components', 'com_users');
		$this->_mos_add_acl('administration', 'manage', 'users', 'super administrator','components', 'com_users');
		$this->_mos_add_acl('administration', 'edit', 'users', 'administrator','user properties', 'block_user');
		$this->_mos_add_acl('administration', 'edit', 'users', 'super administrator','user properties', 'block_user');
		$this->_mos_add_acl('workflow', 'email_events', 'users', 'administrator', null, null);
		$this->_mos_add_acl('workflow', 'email_events', 'users', 'super administrator', null, null);
		$this->_mos_add_acl('action', 'add', 'users', 'author', 'content', 'all');
		$this->_mos_add_acl('action', 'add', 'users', 'editor', 'content', 'all');
		$this->_mos_add_acl('action', 'add', 'users', 'publisher', 'content', 'all');
		$this->_mos_add_acl('action', 'edit', 'users', 'author', 'content', 'own');
		$this->_mos_add_acl('action', 'edit', 'users', 'editor', 'content', 'all');
		$this->_mos_add_acl('action', 'edit', 'users', 'publisher', 'content', 'all');
		$this->_mos_add_acl('action', 'publish', 'users', 'publisher', 'content', 'all');
		$this->_mos_add_acl('action', 'add', 'users', 'manager', 'content', 'all');
		$this->_mos_add_acl('action', 'edit', 'users', 'manager', 'content', 'all');
		$this->_mos_add_acl('action', 'publish', 'users', 'manager', 'content', 'all');
		$this->_mos_add_acl('action', 'add', 'users', 'administrator', 'content', 'all');
		$this->_mos_add_acl('action', 'edit', 'users', 'administrator', 'content', 'all');
		$this->_mos_add_acl('action', 'publish', 'users', 'administrator', 'content','all');
		$this->_mos_add_acl('action', 'add', 'users', 'super administrator', 'content','all');
		$this->_mos_add_acl('action', 'edit', 'users', 'super administrator', 'content','all');
		$this->_mos_add_acl('action', 'publish', 'users', 'super administrator','content', 'all');
		$this->acl_count = count($this->acl);
*/
	}

	public static function getInstance( $use_db = true ) {
		if ( self::$_instance===null ) {
			self::$_instance = new gacl_api();
			self::$_instance->db = $use_db ? database::getInstance() : null;
		}
		return self::$_instance;
	}

	private function __clone() {}

	function _mos_add_acl($aco_section_value, $aco_value, $aro_section_value, $aro_value, $axo_section_value = null, $axo_value = null) {
		$this->acl[] = array($aco_section_value, $aco_value, $aro_section_value, $aro_value, $axo_section_value, $axo_value);
		$this->acl_count = count($this->acl);
	}
	function debug_text($text) {
		if($this->_debug) {
			echo htmlspecialchars($text)."<br>\n";
		}
		return true;
	}
	function debug_db($function_name = '') {
		if($function_name != '') {
			$function_name .= ' (): ';
		}
		return $this->debug_text($function_name.'database error: '.$this->db->getErrorMsg().' ('.$this->db->getErrorNum().')');
	}
	function acl_check($aco_section_value, $aco_value, $aro_section_value, $aro_value,$axo_section_value = null, $axo_value = null) {
		$acl_result = 0;

		for($i = 0; $i < $this->acl_count; $i++) {
			if(strcasecmp($aco_section_value, $this->acl[$i][0]) == 0) {
				if(strcasecmp($aco_value, $this->acl[$i][1]) == 0) {
					if(strcasecmp($aro_section_value, $this->acl[$i][2]) == 0) {
						if(strcasecmp($aro_value, $this->acl[$i][3]) == 0) {
							if(strcasecmp($axo_section_value, $this->acl[$i][4]) == 0) {
								if(strcasecmp($axo_value, $this->acl[$i][5]) == 0) {
									$acl_result = 1;
									break;
								}
							}
						}
					}
				}
			}
		}
		return $acl_result;
	}
}
class gacl_api extends gacl {
	var $_items_per_page = 100;
	var $_max_select_box_items = 100;
	var $_max_search_return_items = 100;
	function showarray($array) {
		_xdump($array);
	}
	function return_page($url = "") {
		global $_SERVER, $debug;
		if(empty($url) and !empty($_SERVER[HTTP_REFERER])) {
			$this->debug_text("return_page(): URL not set, using referer!");
			$url = $_SERVER[HTTP_REFERER];
		}
		if(!$debug or $debug == 0) {
			header("Location: $url\n\n");
		} else {
			$this->debug_text("return_page(): URL: $url -- Referer: $_SERVER[HTTP_REFERRER]");
		}
	}
	function get_paging_data($rs) {
		return array('prevpage' => $rs->absolutepage() - 1, 'currentpage' => $rs->absolutepage(), 'nextpage' => $rs->absolutepage() + 1, 'atfirstpage' => $rs->atfirstpage(),'atlastpage' => $rs->atlastpage(), 'lastpageno' => $rs->lastpageno());
	}
	function count_all($arg = null) {
		switch(true) {
			case is_scalar($arg):
			case is_object($arg):
				return 1;
			case is_array($arg):
				$count = 0;
				foreach($arg as $val) {
					$count += $this->count_all($val);
				}
				return $count;
		}
		return false;
	}
	function get_group_id($name = null, $group_type = 'ARO') {
		$this->debug_text("get_group_id(): Name: $name");
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$table = $this->_db_table_prefix.'aro_groups';
				break;
		}
		$name = trim($name);
		if(empty($name)) {
			$this->debug_text("get_group_id(): name ($name) is empty, this is required");
			return false;
		}
		$this->db->setQuery("SELECT group_id FROM $table WHERE name=".$this->db->Quote($name));
		$rows = $this->db->loadRowList();
		if($this->db->getErrorNum()) {
			$this->debug_db('get_group_id');
			return false;
		}
		$row_count = count($rows);
		if($row_count > 1) {
			$this->debug_text("get_group_id(): Returned $row_count rows, can only return one. Please make your names unique.");
			return false;
		}
		if($row_count == 0) {
			$this->debug_text("get_group_id(): Returned $row_count rows");
			return false;
		}
		$row = $rows[0];
		return $row[0];
	}
	function get_group_name($group_id = null, $group_type = 'ARO') {
		$this->debug_text("get_group_name(): ID: $group_id");
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$table = $this->_db_table_prefix.'aro_groups';
				break;
		}
		$group_id = intval($group_id);
		if(!$group_id) {
			$this->debug_text("get_group_name(): group_id ($group_id) is empty, this is required");
			return false;
		}
		$this->db->setQuery("SELECT name FROM $table WHERE group_id=".(int)$group_id);
		$rows = $this->db->loadRowList();
		if($this->db->getErrorNum()) {
			$this->debug_db('get_group_name');
			return false;
		}
		$row_count = count($rows);
		if($row_count > 1) {
			$this->debug_text("get_group_name(): Returned $row_count rows, can only return one. Please make your names unique.");
			return false;
		}
		if($row_count == 0) {
			$this->debug_text("get_group_name(): Returned $row_count rows");
			return false;
		}
		$row = $rows[0];
		return $row[0];
	}
	function get_group_children($group_id, $group_type = 'ARO', $recurse ='NO_RECURSE') {
		$this->debug_text("get_group_children(): Group_ID: $group_id Group Type: $group_type Recurse: $recurse");
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'aro_groups';
		}
		if(empty($group_id)) {
			$this->debug_text("get_group_children(): ID ($group_id) is empty, this is required");
			return false;
		}
		$query = '
				SELECT		g1.group_id
				FROM		'.$table.' g1';
		switch(strtoupper($recurse)) {
			case 'RECURSE':
				$query .= '
				LEFT JOIN	'.$table.' g2 ON g2.lft<g1.lft AND g2.rgt>g1.rgt
				WHERE		g2.group_id='.(int)$group_id;
				break;
			default:
				$query .= '
				WHERE		g1.parent_id='.(int)$group_id;
		}
		$query .= '
				ORDER BY	g1.name';
		$this->db->setQuery($query);
		return $this->db->loadResultArray();
	}
	function get_group_parents($group_id, $group_type = 'ARO', $recurse ='NO_RECURSE') {
		$this->debug_text("get_group_parents(): Group_ID: $group_id Group Type: $group_type Recurse: $recurse");
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'aro_groups';
		}
		if(empty($group_id)) {
			$this->debug_text("get_group_parents(): ID ($group_id) is empty, this is required");
			return false;
		}
		$query = '
				SELECT		g2.group_id
				FROM		'.$table.' g1';
		switch(strtoupper($recurse)) {
			case 'RECURSE':
				$query .= '
				LEFT JOIN	'.$table.' g2 ON g1.lft > g2.lft AND g1.lft < g2.rgt
				WHERE		g1.group_id='.(int)$group_id;
				break;
			case 'RECURSE_INCL':
				$query .= '
				LEFT JOIN	'.$table.' g2 ON g1.lft >= g2.lft AND g1.lft <= g2.rgt
				WHERE		g1.group_id='.(int)$group_id;
				break;
			default:
				$query .= '
				LEFT JOIN '.$table.' g2 ON g1.parent_id = g2.group_id
				WHERE		g1.group_id='.(int)$group_id;
		}
		$query .= '
				ORDER BY	g2.lft';
		$this->db->setQuery($query);
		return $this->db->loadResultArray();
	}
	function add_group($name, $parent_id = 0, $group_type = 'ARO') {
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'aro_groups';
				break;
		}
		$this->debug_text("add_group(): Name: $name Parent ID: $parent_id Group Type: $group_type");
		$name = trim($name);
		if(empty($name)) {
			$this->debug_text("add_group(): name ($name) OR parent id ($parent_id) is empty, this is required");
			return false;
		}
		$this->db->setQuery("SELECT MAX(group_id)+1 FROM $table");
		$insert_id = intval($this->db->loadResult());
		if($parent_id == 0) {
			$$this->db->setQuery('SELECT group_id FROM '.$table.' WHERE parent_id=0');
			$rs = $this->db->loadResultArray();
			if(!is_array($rs)) {
				$this->debug_db('add_group');
				$this->db->RollBackTrans();
				return false;
			}
			if(count($rs) > 0) {
				$this->debug_text('add_group (): A root group already exists.');
				return false;
			}
//			$parent_lft = 0;
			$parent_rgt = 1;
		} else {
			if(empty($parent_id)) {
				$this->debug_text("add_group (): parent id ($parent_id) is empty, this is required");
				return false;
			}
			$this->db->setQuery('SELECT group_id, lft, rgt FROM '.$table.' WHERE group_id='.(int)$parent_id);
			$rows = $this->db->loadRowList();
			if(!is_array($rows) or $this->db->getErrorNum() > 0) {
				$this->debug_db('add_group');
				return false;
			}
			if(empty($rows)) {
				$this->debug_text('add_group (): Parent ID: '.$parent_id.' not found.');
				return false;
			}
			$row = $rows[0];
//			$parent_lft = &$row[1];
			$parent_rgt = &$row[2];
			$this->db->setQuery('UPDATE '.$table.' SET rgt=rgt+2 WHERE rgt>='.(int)$parent_rgt);
			$rs = $this->db->query();
			if(!$rs) {
				$this->debug_db('add_group: make room for the new group - right');
				return false;
			}
			$this->db->setQuery('UPDATE '.$table.' SET lft=lft+2 WHERE lft>'.(int)$parent_rgt);
			$rs = $this->db->query();
			if(!$rs) {
				$this->debug_db('add_group: make room for the new group - left');
				return false;
			}
		}
		$this->db->setQuery('INSERT INTO '.$table.
				' (group_id,parent_id,name,lft,rgt) VALUES ('.(int)$insert_id.','.(int)$parent_id.
				',\''.$this->db->getEscaped($name).'\','.(int)$parent_rgt.','.(int)($parent_rgt +
						1).')');
		$rs = $this->db->query();
		if(!$rs) {
			$this->debug_db('add_group: insert record');
			return false;
		}
		$this->debug_text('add_group (): Added group as ID: '.$insert_id);
		return $insert_id;
	}
	function get_group_objects($group_id, $group_type = 'ARO', $option = 'NO_RECURSE') {
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$object_table = $this->_db_table_prefix.'axo';
				$group_table = $this->_db_table_prefix.'axo_groups';
				$map_table = $this->_db_table_prefix.'groups_axo_map';
				break;
			default:
				$group_type = 'aro';
				$object_table = $this->_db_table_prefix.'aro';
				$group_table = $this->_db_table_prefix.'aro_groups';
				$map_table = $this->_db_table_prefix.'groups_aro_map';
				break;
		}
		$this->debug_text("get_group_objects(): Group ID: $group_id");
		if(empty($group_id)) {
			$this->debug_text("get_group_objects(): Group ID:  ($group_id) is empty, this is required");
			return false;
		}
		$query = '
				SELECT		o.section_value,o.value
				FROM		'.$object_table.' o
				LEFT JOIN	'.$map_table.' gm ON o.'.$group_type.'_id=gm.'.$group_type.'_id';
		if($option == 'RECURSE') {
			$query .= '
				LEFT JOIN	'.$group_table.' g1 ON g1.group_id=gm.group_id
				LEFT JOIN	'.$group_table.' g2 ON g2.lft<=g1.lft AND g2.rgt>=g1.rgt
				WHERE		g2.group_id='.(int)$group_id;
		} else {
			$query .= '
				WHERE		gm.group_id='.(int)$group_id;
		}
		$this->db->setQuery($query);
		$rs = $this->db->loadRowList();
		if(!is_array($rs)) {
			$this->debug_db('get_group_objects');
			return false;
		}
		$this->debug_text("get_group_objects(): Got group objects, formatting array.");
		$retarr = array();
		foreach($rs as $row) {
			$section = &$row[0];
			$value = &$row[1];
			$retarr[$section][] = $value;
		}
		return $retarr;
	}
	function add_group_object($group_id, $object_section_value, $object_value, $group_type = 'ARO') {
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'groups_axo_map';
				$object_table = $this->_db_table_prefix.'axo';
				$group_table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'groups_aro_map';
				$object_table = $this->_db_table_prefix.'aro';
				$group_table = $this->_db_table_prefix.'aro_groups';
				break;
		}
		$this->debug_text("add_group_object(): Group ID: $group_id, Section Value: $object_section_value, Value: $object_value, Group Type: $group_type");
		$object_section_value = trim($object_section_value);
		$object_value = trim($object_value);
		if(empty($group_id) or empty($object_value) or empty($object_section_value)) {
			$this->debug_text("add_group_object(): Group ID:  ($group_id) OR Value ($object_value) OR Section value ($object_section_value) is empty, this is required");
			return false;
		}
		$this->db->setQuery('
			SELECT		g.group_id,o.'.$group_type.'_id,gm.group_id AS member
			FROM		'.$object_table.' o
			LEFT JOIN	'.$group_table.' g ON g.group_id='.(int)$group_id.'
			LEFT JOIN	'.$table.' gm ON (gm.group_id=g.group_id AND gm.'.$group_type.
				'_id=o.'.$group_type.'_id)
			WHERE		(o.section_value=\''.$this->db->getEscaped($object_section_value).'\' AND o.value=\''.
				$this->db->getEscaped($object_value).'\')');
		$rows = $this->db->loadRowList();
		if($this->db->getErrorNum()) {
			$this->debug_db('add_group_object');
			return false;
		}
		if(count($rows) != 1) {
			$this->debug_text("add_group_object (): Group ID ($group_id) OR Value ($object_value) OR Section value ($object_section_value) is invalid. Does this object exist?");
			return false;
		}
		$row = $rows[0];
		if($row[2] == 1) {
			$this->debug_text("add_group_object (): Object: $object_value is already a member of Group ID: $group_id");
			return true;
		}
		$object_id = $row[1];
		$this->db->setQuery('INSERT INTO '.$table.' (group_id,'.$group_type.'_id) VALUES ('.(int)$group_id.','.(int)$object_id.')');
		if(!$this->db->query()) {
			$this->debug_db('add_group_object');
			return false;
		}
		$this->debug_text('add_group_object(): Added Object: '.$object_id.' to Group ID: '.$group_id);
		if($this->_caching == true and $this->_force_cache_expire == true) {
			$this->Cache_Lite->clean('default');
		}
		return true;
	}
	function del_group_object($group_id, $object_section_value, $object_value, $group_type = 'ARO') {
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'groups_axo_map';
				break;
			default:
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'groups_aro_map';
				break;
		}
		$this->debug_text("del_group_object(): Group ID: $group_id Section value: $object_section_value Value: $object_value");
		$object_section_value = trim($object_section_value);
		$object_value = trim($object_value);
		if(empty($group_id) or empty($object_value) or empty($object_section_value)) {
			$this->debug_text("del_group_object(): Group ID:  ($group_id) OR Section value: $object_section_value OR Value ($object_value) is empty, this is required");
			return false;
		}
		if(!$object_id = $this->get_object_id($object_section_value, $object_value, $group_type)) {
			$this->debug_text("del_group_object (): Group ID ($group_id) OR Value ($object_value) OR Section value ($object_section_value) is invalid. Does this object exist?");
			return false;
		}
		$this->db->setQuery('DELETE FROM '.$table.' WHERE group_id='.(int)$group_id.
				' AND '.$group_type.'_id='.(int)$object_id);
		$this->db->query();
		if($this->db->getErrorNum()) {
			$this->debug_db('del_group_object');
			return false;
		}
		$this->debug_text("del_group_object(): Deleted Value: $object_value to Group ID: $group_id assignment");
		if($this->_caching == true and $this->_force_cache_expire == true) {
			$this->Cache_Lite->clean('default');
		}
		return true;
	}
	function del_group($group_id, $reparent_children = true, $group_type = 'ARO') {
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'axo_groups';
//				$groups_map_table = $this->_db_table_prefix.'axo_groups_map';
				$groups_object_map_table = $this->_db_table_prefix.'groups_axo_map';
				break;
			default:
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'aro_groups';
//				$groups_map_table = $this->_db_table_prefix.'aro_groups_map';
				$groups_object_map_table = $this->_db_table_prefix.'groups_aro_map';
				break;
		}
		$this->debug_text("del_group(): ID: $group_id Reparent Children: $reparent_children Group Type: $group_type");
		if(empty($group_id)) {
			$this->debug_text("del_group(): Group ID ($group_id) is empty, this is required");
			return false;
		}
		$this->db->setQuery('SELECT group_id, parent_id, name, lft, rgt FROM '.$table.
				' WHERE group_id='.(int)$group_id);
		$group_details = $this->db->loadRow();
		if(!is_array($group_details)) {
			$this->debug_db('del_group: get group details');
			return false;
		}
		$parent_id = $group_details[1];
		$left = $group_details[3];
		$right = $group_details[4];
		$children_ids = $this->get_group_children($group_id, $group_type, 'RECURSE');
		if($parent_id == 0) {
			$this->db->setQuery('SELECT count(*) FROM '.$table.' WHERE parent_id='.(int)$group_id);
			$child_count = $this->db->loadResult();
			if($child_count > 1 && $reparent_children) {
				$this->debug_text('del_group (): You cannot delete the root group and reparent children, this would create multiple root groups.');
				return false;
			}
		}
		$success = false;
		switch(true) {
			case !is_array($children_ids):
			case count($children_ids) == 0:
				$this->db->setQuery('DELETE FROM '.$groups_object_map_table.' WHERE group_id='.
						(int)$group_id);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('DELETE FROM '.$table.' WHERE group_id='.(int)$group_id);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET lft=lft-'.(int)($right - $left + 1).
						' WHERE lft>'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET rgt=rgt-'.(int)($right - $left + 1).
						' WHERE rgt>'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$success = true;
				break;
			case $reparent_children == true:
				$this->db->setQuery('DELETE FROM '.$groups_object_map_table.' WHERE group_id='.
						(int)$group_id);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('DELETE FROM '.$table.' WHERE group_id='.(int)$group_id);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET parent_id='.(int)$parent_id.
						' WHERE parent_id='.(int)$group_id);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET lft=lft-1, rgt=rgt-1 WHERE lft>'.(int)
						$left.' AND rgt<'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET lft=lft-2 WHERE lft>'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET rgt=rgt-2 WHERE rgt>'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$success = true;
				break;
			default:
				$group_ids = $children_ids;
				$group_ids[] = $group_id;
				mosArrayToInts($group_ids);
				$this->db->setQuery('DELETE FROM '.$groups_object_map_table.
						' WHERE group_id IN ('.implode(',', $group_ids).')');
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('DELETE FROM '.$table.' WHERE group_id IN ('.implode(',', $group_ids).
						')');
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET lft=lft-'.(int)($right - $left + 1).
						' WHERE lft>'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$this->db->setQuery('UPDATE '.$table.' SET rgt=rgt-'.(int)($right - $left + 1).
						' WHERE rgt>'.(int)$right);
				$rs = $this->db->query();
				if(!$rs) {
					break;
				}
				$success = true;
		}
		if(!$success) {
			$this->debug_db('del_group');
			$this->db->RollBackTrans();
			return false;
		}
		$this->debug_text("del_group(): deleted group ID: $group_id");
		if($this->_caching == true and $this->_force_cache_expire == true) {
			$this->Cache_Lite->clean('default');
		}
		return true;
	}
	function get_object($section_value = null, $return_hidden = 1, $object_type = null) {
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$table = $this->_db_table_prefix.'aco';
				break;
			case 'aro':
				$object_type = 'aro';
				$table = $this->_db_table_prefix.'aro';
				break;
			case 'axo':
				$object_type = 'axo';
				$table = $this->_db_table_prefix.'axo';
				break;
			default:
				$this->debug_text('get_object(): Invalid Object Type: '.$object_type);
				return false;
		}
		$this->debug_text("get_object(): Section Value: $section_value Object Type: $object_type");
		$$this->db->setQuery('SELECT '.$object_type.'_id FROM '.$table);
		$where = array();
		if(!empty($section_value)) {
			$where[] = 'section_value='.$this->db->getEscaped($section_value);
		}
		if($return_hidden == 0) {
			$where[] = 'hidden=0';
		}
		/*
		if(!empty($where)) {
			$query .= ' WHERE '.implode(' AND ', $where);
		}
		*/
		$rs = $this->db->loadResultArray();
		if(!is_array($rs)) {
			$this->debug_db('get_object');
			return false;
		}
		return $rs;
	}
	function get_object_groups($object_section_value, $object_value, $object_type = null) {
		switch(strtolower(trim($object_type))) {
			case 'aro':
				$group_type = 'aro';
				$table = $this->_db_table_prefix.'groups_aro_map';
				$object_table = $this->_db_table_prefix.'aro';
				$group_table = $this->_db_table_prefix.'aro_groups';
				break;
			case 'axo':
				$group_type = 'axo';
				$table = $this->_db_table_prefix.'groups_axo_map';
				$object_table = $this->_db_table_prefix.'axo';
				$group_table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$this->debug_text('get_object_groups(): Invalid Object Type: '.$object_type);
				return false;
		}
		$this->debug_text("get_object_groups(): Section Value: $object_section_value Value: $object_value Object Type: $object_type");
		$object_section_value = trim($object_section_value);
		$object_value = trim($object_value);
		if(empty($object_section_value) and empty($object_value)) {
			$this->debug_text("get_object_groups(): Section Value ($object_section_value) AND value ($object_value) is empty, this is required");
			return false;
		}
		if(empty($object_type)) {
			$this->debug_text("get_object_groups(): Object Type ($object_type) is empty, this is required");
			return false;
		}
		$this->db->setQuery('
			SELECT		g.group_id,o.'.$group_type.'_id,(gm.group_id IS NOT NULL) AS member
			FROM		'.$group_table.' g
			LEFT JOIN	'.$table.' gm ON gm.group_id=g.group_id
			LEFT JOIN	'.$object_table.' o ON o.'.$group_type.'_id = gm.'.$group_type.
				'_id
			WHERE		(o.section_value=\''.$this->db->getEscaped($object_section_value).'\' AND o.value=\''.
				$this->db->getEscaped($object_value).'\')');
		$rs = $this->db->loadResultArray();
		if($this->db->getErrorNum()) {
			$this->debug_db('get_object_id');
			return false;
		}
		return $rs;
	}
	function get_object_id($section_value, $value, $object_type = null) {
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$table = $this->_db_table_prefix.'aco';
				break;
			case 'aro':
				$object_type = 'aro';
				$table = $this->_db_table_prefix.'aro';
				break;
			case 'axo':
				$object_type = 'axo';
				$table = $this->_db_table_prefix.'axo';
				break;
			default:
				$this->debug_text('get_object_id(): Invalid Object Type: '.$object_type);
				return false;
		}
		$this->debug_text("get_object_id(): Section Value: $section_value Value: $value Object Type: $object_type");
		$section_value = trim($section_value);
		$value = trim($value);
		if(empty($section_value) and empty($value)) {
			$this->debug_text("get_object_id(): Section Value ($value) AND value ($value) is empty, this is required");
			return false;
		}
		if(empty($object_type)) {
			$this->debug_text("get_object_id(): Object Type ($object_type) is empty, this is required");
			return false;
		}
		$this->db->setQuery('SELECT '.$object_type.'_id FROM '.$table.
				' WHERE section_value=\''.$this->db->getEscaped($section_value).'\' AND value=\''.
				$this->db->getEscaped($value).'\'');
		$rs = $this->db->loadRowList();
		if($this->db->getErrorNum()) {
			$this->debug_db('get_object_id');
			return false;
		}
		$row_count = count($rs);
		if($row_count > 1) {
			$this->debug_text("get_object_id(): Returned $row_count rows, can only return one. This should never happen, the database may be missing a unique key.");
			return false;
		}
		if($row_count == 0) {
			$this->debug_text("get_object_id(): Returned $row_count rows");
			return false;
		}
		$row = $rs[0];
		return $row[0];
	}
	function add_object($section_value, $name, $value = 0, $order = 0, $hidden = 0,
			$object_type = null) {
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$table = $this->_db_table_prefix.'aco';
				$object_sections_table = $this->_db_table_prefix.'aco_sections';
				break;
			case 'aro':
				$object_type = 'aro';
				$table = $this->_db_table_prefix.'aro';
				$object_sections_table = $this->_db_table_prefix.'aro_sections';
				break;
			case 'axo':
				$object_type = 'axo';
				$table = $this->_db_table_prefix.'axo';
				$object_sections_table = $this->_db_table_prefix.'axo_sections';
				break;
			default:
				$this->debug_text('add_object(): Invalid Object Type: '.$object_type);
				return false;
		}
		$this->debug_text("add_object(): Section Value: $section_value Value: $value Order: $order Name: $name Object Type: $object_type");
		$section_value = trim($section_value);
		$name = trim($name);
		$value = trim($value);
		$order = trim($order);
		$hidden = (int)$hidden;
		if($order == null or $order == '') {
			$order = 0;
		}
		if(empty($name) or empty($section_value)) {
			$this->debug_text("add_object(): name ($name) OR section value ($section_value) is empty, this is required");
			return false;
		}
		if(strlen($name) >= 255 or strlen($value) >= 230) {
			$this->debug_text("add_object(): name ($name) OR value ($value) is too long.");
			return false;
		}
		if(empty($object_type)) {
			$this->debug_text("add_object(): Object Type ($object_type) is empty, this is required");
			return false;
		}
		$this->db->setQuery('
			SELECT		(o.'.$object_type.'_id IS NOT NULL) AS object_exists
			FROM		'.$object_sections_table.' s
			LEFT JOIN	'.$table.' o ON (s.value=o.section_value AND o.value=\''.$this->db->getEscaped
				($value).'\')
			WHERE		s.value=\''.$this->db->getEscaped($section_value).'\'');
		$rows = $this->db->loadRowList();
		if($this->db->getErrorNum()) {
			$this->debug_db('add_object');
			return false;
		}
		if(count($rows) != 1) {
			$this->debug_text("add_object(): Section Value: $section_value Object Type ($object_type) does not exist, this is required");
			return false;
		}
		$row = $rows[0];
		if($row[0] == 1) {
			return true;
		}
		$insert_id = $this->db->GenID($this->_db_table_prefix.$object_type.'_seq', 10);
		$this->db->setQuery("INSERT INTO $table ({$object_type}_id,section_value,value,order_value,name,hidden) VALUES(".
				(int)$insert_id.",".$this->db->Quote($section_value).",".$this->db->Quote($value).
				",".$this->db->Quote($order).",".$this->db->Quote($name).",".(int)$hidden.")");
		if(!$this->db->query()) {
			$this->debug_db('add_object');
			return false;
		}
		$insert_id = $this->db->insertid();
		$this->debug_text("add_object(): Added object as ID: $insert_id");
		return $insert_id;
	}
	function edit_object($object_id, $section_value, $name, $value = 0, $order = 0,	$hidden = 0, $object_type = null) {
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$table = $this->_db_table_prefix.'aco';
				$object_map_table = 'aco_map';
				break;
			case 'aro':
				$object_type = 'aro';
				$table = $this->_db_table_prefix.'aro';
				$object_map_table = 'aro_map';
				break;
			case 'axo':
				$object_type = 'axo';
				$table = $this->_db_table_prefix.'axo';
				$object_map_table = 'axo_map';
				break;
		}
		$this->debug_text("edit_object(): ID: $object_id, Section Value: $section_value, Value: $value, Order: $order, Name: $name, Object Type: $object_type");
		$section_value = trim($section_value);
		$name = trim($name);
		$value = trim($value);
		$order = trim($order);
		if(empty($object_id) or empty($section_value)) {
			$this->debug_text("edit_object(): Object ID ($object_id) OR Section Value ($section_value) is empty, this is required");
			return false;
		}
		if(empty($name)) {
			$this->debug_text("edit_object(): name ($name) is empty, this is required");
			return false;
		}
		if(empty($object_type)) {
			$this->debug_text("edit_object(): Object Type ($object_type) is empty, this is required");
			return false;
		}
		$this->db->setQuery('SELECT value, section_value FROM '.$table.' WHERE '.$object_type.
				'_id='.(int)$object_id);
		$old = $this->db->loadRow();
		$this->db->setQuery('
			UPDATE	'.$table.'
			SET		section_value=\''.$this->db->getEscaped($section_value).'\',
					value=\''.$this->db->getEscaped($value).'\',
					order_value=\''.$this->db->getEscaped($order).'\',
					name=\''.$this->db->getEscaped($name).'\',
					hidden='.(int)$hidden.'
			WHERE	'.$object_type.'_id='.(int)$object_id);
		$this->db->query();
		if(!$this->db->getErrorNum()) {
			$this->debug_db('edit_object');
			return false;
		}
		$this->debug_text('edit_object(): Modified '.strtoupper($object_type).' ID: '.$object_id);
		if($old[0] != $value or $old[1] != $section_value) {
			$this->debug_text("edit_object(): Value OR Section Value Changed, update other tables.");
			$this->db->setQuery('
				UPDATE	'.$object_map_table.'
				SET		value=\''.$this->db->getEscaped($value).'\',
						section_value=\''.$this->db->getEscaped($section_value).'\'
				WHERE	section_value=\''.$this->db->getEscaped($old[1]).'\'
					AND	value=\''.$this->db->getEscaped($old[0]).'\'');
			$this->db->query();
			if(!$this->db->getErrorNum()) {
				$this->debug_db('edit_object');
				return false;
			}
			$this->debug_text('edit_object(): Modified Map Value: '.$value.
					' Section Value: '.$section_value);
		}
		return true;
	}
	function del_object($object_id, $object_type = null, $erase = false) {
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$table = $this->_db_table_prefix.'aco';
				$object_map_table = $this->_db_table_prefix.'aco_map';
				break;
			case 'aro':
				$object_type = 'aro';
				$table = $this->_db_table_prefix.'aro';
				$object_map_table = $this->_db_table_prefix.'aro_map';
//				$groups_map_table = $this->_db_table_prefix.'aro_groups_map';
				$object_group_table = $this->_db_table_prefix.'groups_aro_map';
				break;
			case 'axo':
				$object_type = 'axo';
				$table = $this->_db_table_prefix.'axo';
				$object_map_table = $this->_db_table_prefix.'axo_map';
//				$groups_map_table = $this->_db_table_prefix.'axo_groups_map';
				$object_group_table = $this->_db_table_prefix.'groups_axo_map';
				break;
			default:
				$this->debug_text('del_object(): Invalid Object Type: '.$object_type);
				return false;
		}
		$this->debug_text("del_object(): ID: $object_id Object Type: $object_type, Erase all referencing objects: $erase");
		if(empty($object_id)) {
			$this->debug_text("del_object(): Object ID ($object_id) is empty, this is required");
			return false;
		}
		if(empty($object_type)) {
			$this->debug_text("del_object(): Object Type ($object_type) is empty, this is required");
			return false;
		}
		$this->db->setQuery('SELECT section_value,value FROM '.$table.' WHERE '.$object_type.'_id='.(int)$object_id);
		$object = $this->db->loadRow();
		if(empty($object)) {
			$this->debug_text('del_object(): The specified object ('.strtoupper($object_type).' ID: '.$object_id.') could not be found.<br />SQL = '.$this->db->stderr());
			return false;
		}
		$section_value = $object[0];
		$value = $object[1];
		//$this->db->setQuery("SELECT acl_id FROM $object_map_table WHERE value=".$this->db->Quote($value)." AND section_value=".$this->db->Quote($section_value));
		//$acl_ids = $this->db->loadResultArray();
		$acl_ids = array();
		if($erase) {
			$this->debug_text("del_object(): Erase was set to TRUE, delete all referencing objects");
			if($object_type == "aro" or $object_type == "axo") {
				$this->db->setQuery('DELETE FROM '.$object_group_table.' WHERE '.$object_type.
						'_id='.(int)$object_id);
				$rs = $this->db->query();
				if(!$rs) {
					$this->debug_db('edit_object');
					return false;
				}
			}
			if($acl_ids) {
				if($object_type == 'aco') {
					$orphan_acl_ids = $acl_ids;
				} else {
					$this->db->setQuery("DELETE FROM $object_map_table WHERE section_value=".$this->db->Quote
							($section_value)." AND value=".$this->db->Quote($value));
					$rs = $this->db->query();
					if(!$rs) {
						$this->debug_db('edit_object');
						$this->db->RollBackTrans();
						return false;
					}
					mosArrayToInts($acl_ids);
					$sql_acl_ids = implode(",", $acl_ids);
					$this->db->setQuery('
						SELECT		a.id
						FROM		'.$this->_db_table_prefix.'acl a
						LEFT JOIN	'.$object_map_table.' b ON a.id=b.acl_id
						'.'
						WHERE		value IS NULL
							AND		section_value IS NULL
							AND		group_id IS NULL
							AND		a.id in ('.$sql_acl_ids.')');
					$orphan_acl_ids = $this->db->loadResultArray();
				}
				if($orphan_acl_ids) {
					foreach($orphan_acl_ids as $acl) {
						$this->del_acl($acl);
					}
				}
			}
			$this->db->setQuery("DELETE FROM $table WHERE {$object_type}_id=".(int)$object_id);
			$rs = $this->db->query();
			if(!$rs) {
				$this->debug_db('edit_object');
				return false;
			}
			return true;
		}
		$groups_ids = false;
		if($object_type == 'axo' or $object_type == 'aro') {
			$this->db->setQuery('SELECT group_id FROM '.$object_group_table.' WHERE '.$object_type.'_id='.(int)$object_id);
			$groups_ids = $this->db->loadResultArray();
		}
		if((isset($acl_ids) and $acl_ids !== false) or (isset($groups_ids) and $groups_ids
						!== false)) {
			$this->debug_text("del_object(): Can't delete the object as it is being referenced by GROUPs (".@implode($groups_ids).") or ACLs (".@implode($acl_ids, ",").")");
			return false;
		} else {
			$this->db->setQuery("DELETE FROM $table WHERE {$object_type}_id=".(int)$object_id);
			$this->db->query();
			if($this->db->getErrorNum()) {
				$this->debug_db('edit_object');
				return false;
			}
			return true;
		}
	}
	function is_group_child_of($grp_src, $grp_tgt, $group_type = 'ARO') {
		$this->debug_text("has_group_parent(): Source=$grp_src, Target=$grp_tgt, Type=$group_type");
		switch(strtolower(trim($group_type))) {
			case 'axo':
				$table = $this->_db_table_prefix.'axo_groups';
				break;
			default:
				$table = $this->_db_table_prefix.'aro_groups';
				break;
		}
		if(is_int($grp_src) && is_int($grp_tgt)) {
			$this->db->setQuery("SELECT COUNT(*)"."\nFROM $table AS g1"."\nLEFT JOIN $table AS g2 ON g1.lft > g2.lft AND g1.lft < g2.rgt"."\nWHERE g1.group_id=".(int)$grp_src." AND g2.group_id=".(int)$grp_tgt);
		} else
		if(is_string($grp_src) && is_string($grp_tgt)) {
			$this->db->setQuery("SELECT COUNT(*)"."\nFROM $table AS g1"."\nLEFT JOIN $table AS g2 ON g1.lft > g2.lft AND g1.lft < g2.rgt"."\nWHERE g1.name=".$this->db->Quote($grp_src)." AND g2.name=".$this->db->Quote($grp_tgt));
		} else
		if(is_int($grp_src) && is_string($grp_tgt)) {
			$this->db->setQuery("SELECT COUNT(*)"."\nFROM $table AS g1"."\nLEFT JOIN $table AS g2 ON g1.lft > g2.lft AND g1.lft < g2.rgt"."\nWHERE g1.group_id=".(int)$grp_src." AND g2.name=".$this->db->Quote($grp_tgt));
		} else {
			$this->db->setQuery("SELECT COUNT(*)"."\nFROM $table AS g1"."\nLEFT JOIN $table AS g2 ON g1.lft > g2.lft AND g1.lft < g2.rgt"."\nWHERE g1.name=".$this->db->Quote($grp_src)." AND g2.group_id=".(int)$grp_tgt);
		}
		return $this->db->loadResult();
	}
	function getAroGroup($value) {
		return $this->_getGroup('aro', $value);
	}
	function _getGroup($type, $value) {
		$database = &$this->db;

		$database->setQuery("SELECT g.* FROM #__core_acl_{$type}_groups AS g INNER JOIN #__core_acl_groups_{$type}_map AS gm ON gm.group_id = g.group_id INNER JOIN #__core_acl_{$type} AS ao ON ao.{$type}_id = gm.{$type}_id"."\nWHERE ao.value=".$database->Quote($value));
		$obj = null;
		$database->loadObject($obj);
		return $obj;
	}
	function _getAbove() {
	}
	function _getBelow($table, $fields, $groupby = null, $root_id = null, $root_name = null,$inclusive = true) {
		$database = &$this->db;

		$root = new stdClass();
		$root->lft = 0;
		$root->rgt = 0;
		if($root_id) {
		} else
		if($root_name) {
			$database->setQuery("SELECT lft, rgt FROM $table WHERE name=".$database->Quote($root_name));
			$database->loadObject($root);
		}
		$where = '';
		if($root->lft + $root->rgt != 0) {
			if($inclusive) {
				$where = "WHERE g1.lft BETWEEN ".(int)$root->lft." AND ".(int)$root->rgt;
			} else {
				$where = "WHERE g1.lft BETWEEN ".(int)($root->lft + 1)." AND ".(int)($root->rgt -1);
			}
		}
		$database->setQuery("SELECT $fields FROM $table AS g1 INNER JOIN $table AS g2 ON g1.lft BETWEEN g2.lft AND g2.rgt"."\n$where".($groupby?"\nGROUP BY $groupby":"")."\nORDER BY g1.lft");
		return $database->loadObjectList();
	}
	function get_group_children_tree($root_id = null, $root_name = null, $inclusive = true) {
		$tree = gacl_api::_getBelow('#__core_acl_aro_groups','g1.group_id, g1.name, COUNT(g2.name) AS level', 'g1.name', $root_id, $root_name,$inclusive);
		$n = count($tree);
		$min = $tree[0]->level;
		$max = $tree[0]->level;
		for($i = 0; $i < $n; $i++) {
			$min = min($min, $tree[$i]->level);
			$max = max($max, $tree[$i]->level);
		}
		$indents = array();
		foreach(range($min, $max) as $i) {
			$indents[$i] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		$indents[$min] = '';
		$list = array();
		for($i = $n - 1; $i >= 0; $i--) {
			$shim = '';
			foreach(range($min, $tree[$i]->level) as $j) {
				$shim .= $indents[$j];
			}
			if(@$indents[$tree[$i]->level + 1] == '.&nbsp;') {
				$twist = '&nbsp;';
			} else {
				$twist = "-&nbsp;";
			}
			$list[$i] = mosHTML::makeOption($tree[$i]->group_id, $shim.$twist.$tree[$i]->name);
			if($tree[$i]->level < @$tree[$i - 1]->level) {
				$indents[$tree[$i]->level + 1] = '.&nbsp;';
			}
		}
		ksort($list);
		return $list;
	}
}
class mosARO extends mosDBTable {
	var $aro_id = null;
	var $section_value = null;
	var $value = null;
	var $order_value = null;
	var $name = null;
	var $hidden = null;
	function mosARO(&$db) {
		$this->mosDBTable('#__core_acl_aro', 'aro_id', $db);
	}
}
class mosAroGroup extends mosDBTable {
	var $group_id = null;
	var $parent_id = null;
	var $name = null;
	var $lft = null;
	var $rgt = null;
	function mosAroGroup(&$db) {
		$this->mosDBTable('#__core_acl_aro_groups', 'group_id', $db);
	}
}