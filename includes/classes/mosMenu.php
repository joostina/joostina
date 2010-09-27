<?php


class mosMenu extends JDBmodel {

	/**
	 * Инстанция хранения всех пунктов меню
	 */
	private static $_all_menus_instance;
	public $id;
	public $menutype;
	public $name;
	public $link_title;
	public $link;
	public $type;
	public $published;
	public $componentid;
	public $parent;
	public $sublevel;
	public $ordering;
	public $checked_out;
	public $checked_out_time;
	public $pollid;
	public $browserNav;
	public $access;
	public $utaccess;
	public $params;
	private $_menu = array();

	/**
	 * @param database A database connector object
	 */
	function mosMenu() {
		$this->JDBmodel('#__menu', 'id');
	}

	// получение инстанции меню
	public static function get_all($menutype = false) {

		if (self::$_all_menus_instance === NULL) {
			$database = database::getInstance();
			// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
			$sql = 'SELECT id,menutype,name,link_title,link,type,parent,params,access,browserNav FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
			$menus = $database->setQuery($sql)->loadObjectList();

			$all_menus = array();
			foreach ($menus as $menu) {
				$all_menus[$menu->menutype][$menu->id] = $menu;
			}
			self::$_all_menus_instance = $all_menus;
		}

		return $menutype ? self::$_all_menus_instance[$menutype] : self::$_all_menus_instance;
	}

	/**
	 *
	 * @return array
	 */
	function all_menu() {
		// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
		$sql = 'SELECT* FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
		$menus = $this->_db->setQuery($sql)->loadObjectList();

		$m = array();
		foreach ($menus as $menu) {
			$m[$menu->menutype][$menu->id] = $menu;
		}

		return $m;
	}

	/**
	 *
	 * @return boolean
	 */
	function check() {
		$this->filter(array('link'));
		return true;
	}

	function getMenu($id = null, $type = '', $link = '') {

		$where = '';
		$and = array();
		if ($id || $type || $link) {
			$where .= ' WHERE ';
		}
		if ($id) {
			$and[] = ' menu.id = ' . $id;
		}
		if ($type) {
			$and[] = " menu.type = '" . $type . "'";
		}
		if ($link) {
			$and[] = "menu.link LIKE '%$link'";
		}
		$and = implode(' AND ', $and);

		$query = 'SELECT menu.* FROM #__menu AS menu ' . $where . $and;
		$r = null;
		$this->_db->setQuery($query)->loadObject($r);
		return $r;
	}

	// возвращает всё содержимое всех меню
	function get_menu() {
		return $this->_menu;
	}

	public static function get_menu_links() {
		$_all = self::get_all();

		$return = array();
		foreach ($_all as $menus) {
			foreach ($menus as $menu) {
				// TODO тут еще можно будет сделать красивые sef-ссылки на пункты меню
				//$return[$menu->link]=array('id'=>$menu->id,'name'=>$menu->name);
				$return[$menu->link] = array('id' => $menu->id, 'type' => $menu->type);
			}
		}
		unset($menu, $menuss);
		return $return;
	}

}
