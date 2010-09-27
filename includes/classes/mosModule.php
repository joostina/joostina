<?php


class mosModule extends JDBmodel {

	private static $_instance;
	public $id;
	public $title;
	public $showtitle;
	public $content;
	public $ordering;
	public $position;
	public $checked_out;
	public $checked_out_time;
	public $published;
	public $module;
	public $numnews;
	public $access;
	public $params;
	public $iscore;
	public $client_id;
	public $template;
	public $helper;
	public $cache_time;
	private $_all_modules = null;
	private $_view = null;
	private $_mainframe = null;

	public function __construct() {
		jd_log('А вот и модули...');
		$this->JDBmodel('#__modules', 'id');
	}

	public static function getInstance() {

		JDEBUG ? jd_inc('mosModule') : null;

		if (self::$_instance === null) {
			$modules = new mosModule();
			$modules->initModules();
			self::$_instance = $modules;
		}

		return self::$_instance;
	}

	public function check() {
		if (trim($this->title) == '') {
			$this->_error = _PLEASE_ENTER_MODULE_NAME;
			return false;
		}

		return true;
	}

	public static function convert_to_object($module) {

		$module_obj = new mosModule();
		$rows = get_object_vars($module_obj);
		foreach ($rows as $key => $value) {
			if (isset($module->$key)) {
				$module_obj->$key = $module->$key;
			}
		}
		unset($module_obj->_mainframe, $module_obj->_db);

		return $module_obj;
	}

	function set_template($params) {

		if ($params->get('template', '') == '') {
			return false;
		}

		$default_template = 'modules' . DS . $this->module . DS . 'view' . DS . 'default.php';

		if ($params->get('template_dir', 0) == 0) {
			$template_dir = 'modules' . DS . $this->module . DS . 'view';
		} else {
			$template_dir = 'templates' . DS . JTEMPLATE . DS . 'html' . DS . 'modules' . DS . $this->module;
		}

		if ($params->get('template')) {
			$file = JPATH_BASE . DS . $template_dir . DS . $params->get('template');
			if (is_file($file)) {
				$this->template = $file;
				return true;
			} elseif (is_file(JPATH_BASE . DS . $default_template)) {
				$this->template = JPATH_BASE . DS . $default_template;
				return true;
			}
		}

		return false;
	}

	function set_template_custom($template) {

		$template_file = JPATH_BASE . DS . 'templates' . DS . JTEMPLATE . DS . 'html' . DS . 'user_modules' . DS . $template;

		if (is_file($template_file)) {
			$this->template = $template_file;
			return true;
		}
		return false;
	}

	function get_helper($mainframe) {

		$file = JPATH_BASE . DS . 'modules' . DS . $this->module . DS . 'helper.php';

		if (is_file($file)) {
			require_once($file);
			$helper_class = $this->module . '_Helper';
			$this->helper = new $helper_class($mainframe);
			return true;
		}
		return false;
	}

	function load_module($name = '', $title = '') {
		$where = " m.module = '" . $name . "'";
		if (!$name || $title) {
			$where = " m.title = '" . $title . "'";
		}

		$query = 'SELECT * FROM #__modules AS m WHERE ' . $where . ' AND published=1';
		$this->_view->_mainframe->getDBO()->setQuery($query)->loadObject($this);
	}

	public function initModules() {
		global $my, $Itemid;
		$this->_all_modules = self::_initModules($Itemid, $my->gid);
		require (JPATH_BASE . '/includes/frontend.php');
		$this->_view = new modules_html($this->_mainframe);
	}

	public static function _initModules($Itemid, $my_gid) {
		$mainframe = mosMainFrame::getInstance();

		$all_modules = array();

		$Itemid = intval($Itemid);
		$check_Itemid = ($Itemid) ? "OR mm.menuid = " . $Itemid : '';

		$query = "SELECT id, title, module, position,showtitle,params,access,cache_time FROM #__modules AS m"
				. "\n INNER JOIN #__modules_menu AS mm ON mm.moduleid = m.id"
				. "\n WHERE m.published = 1"
				. "\n AND m.client_id != 1 AND ( mm.menuid = 0 $check_Itemid )"
				. "\n ORDER BY ordering";

		$modules = $mainframe->getDBO()->setQuery($query)->loadObjectList();

		foreach ($modules as $module) {
			if ($module->access == 3) {
				$my_gid == 0 ? $all_modules[$module->position][] = $module : null;
			} else {
				$all_modules[$module->position][] = $module;
			}
		}


		return $all_modules;
	}

	function mosCountModules($position = 'left') {
		if (intval(mosGetParam($_GET, 'tp', 0))) {
			return 1;
		}
		$allModules = $this->_all_modules;
		return (isset($allModules[$position])) ? count($allModules[$position]) : 0;
	}

	function mosLoadModules($position = 'left') {
		global $my, $Itemid;

		$tp = intval(mosGetParam($_GET, 'tp', 0));

		if ($tp && !$this->_view->_mainframe->config->config_disable_tpreview) {
			echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">' . $position . '</div>';
			return;
		}

		$config_caching = $this->_view->_mainframe->config->config_caching;
		$allModules = $this->_all_modules;

		$modules = (isset($allModules[$position])) ? $modules = $allModules[$position] : array();

		foreach ($modules as $module) {
			if ((int) $module->cache_time > 0 && $config_caching == 1) {
				// кешируем модуль
				$cache = mosCache::getCache($module->module . '_' . $module->id, 'function', null, $module->cache_time, $this->_view);
				$cache->call('module', $module, $params, $Itemid, $my->gid);
			} else {
				// не кешируем модуль
				$this->_view->module($module, $params, $Itemid);
			}
		}
		return;
	}

	function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
		global $my, $Itemid;

		$config = $this->_view->_mainframe->get('config');

		$tp = intval(mosGetParam($_GET, 'tp', 0));

		if ($tp && !$config->config_disable_tpreview) {
			echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">' . $name . '</div>';
			return;
		}

		$this->load_module($name, $title);

		if (!$this->id) {
			echo JDEBUG ? '<!-- mosLoadModule::' . $name . ' - не найден -->' : '';
			return;
		}

		if ((int) $this->cache_time > 0 && $config_caching == 1) {
			// кешируем модуль
			$cache = mosCache::getCache($this->module . '_' . $this->id, 'function', null, $this->cache_time, $this->_view);
			$cache->call('module', $this, $params, $Itemid, $my->gid);
		} else {
			// не кешируем модуль
			$this->_view->module($this, $params, $Itemid);
		}

		return;
	}

}