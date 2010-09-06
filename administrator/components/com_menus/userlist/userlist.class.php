<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
* @package Joostina
* @subpackage Menus
*/
class userlist_menu {

	/**
	* @param database A database connector object
	* @param integer The unique id of the category to edit (0 if new)
	*/
	function edit(&$uid,$menutype,$option,$menu) {
		global $database,$my,$mainframe;

		if( !$uid) {
			$menu->type = 'userlist';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent = intval(mosGetParam($_POST,'parent',0));
			$menu->published = 1;
		}

		// build html select list for target window
		$lists['target'] = mosAdminMenus::Target($menu);

		// build the html select list for ordering
		$lists['ordering'] = mosAdminMenus::Ordering($menu,$uid);
		// build the html select list for the group access
		$lists['access'] = mosAdminMenus::Access($menu);
		// build the html select list for paraent item
		$lists['parent'] = mosAdminMenus::Parent($menu);
		// build published button option
		$lists['published'] = mosAdminMenus::Published($menu);
		// build the url link output
		$lists['link'] = mosAdminMenus::Link($menu,$uid);

		// get params definitions
		$params = new mosParameters($menu->params,$mainframe->getPath('menu_xml',$menu->type),'menu');

		userlist_menu_html::edit($menu,$lists,$params,$option);
	}
	
	function saveMenu($option,$task) {
		$database = database::getInstance();

		$params = mosGetParam($_POST,'params','');
		$params['group'] = $_POST['gid'];
		if(is_array($params)) {
			$txt = array();
			foreach($params as $k => $v) {
				$txt[] = "$k=$v";
			}
			$_POST['params'] = mosParameters::textareaHandling($txt);
		}

		$row = new mosMenu($database);

		if(!$row->bind($_POST)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if(!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if(!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$row->updateOrder("menutype = ".$database->Quote($row->menutype)." AND parent = ".(int)$row->parent);

		$msg = _MENU_ITEM_SAVED;
		switch($task) {
			case 'apply':
				mosRedirect('index2.php?option='.$option.'&menutype='.$row->menutype.'&task=edit&id='.$row->id,$msg);
				break;

			case 'save':
			default:
				mosRedirect('index2.php?option='.$option.'&menutype='.$row->menutype,$msg);
				break;

			case 'save_and_new':
			default:
				mosRedirect('index2.php?option='.$option.'&task=new&menutype='.$row->menutype.'&'.josSpoofValue().'=1');
				break;
			}
	}
}
?>
