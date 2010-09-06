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

Jacl::isDeny('menumanager') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require_once ($mainframe->getPath('admin_html'));

$menu = stripslashes(strval(mosGetParam($_GET,'menu','')));
$type = stripslashes(strval(mosGetParam($_POST,'type','')));

$cid = mosGetParam($_POST,'cid','');
if(isset($cid[0]) && get_magic_quotes_gpc()) {
	$cid[0] = stripslashes($cid[0]);
}

js_menu_cache_clear();

switch($task) {
	case 'new':
		editMenu($option,'');
		break;

	case 'edit':
		if(!$menu) {
			$menu = $cid[0];
		}
		editMenu($option,$menu);
		break;

	case 'savemenu':
		saveMenu();
		break;

	case 'deleteconfirm':
		deleteconfirm($option,$cid[0]);
		break;

	case 'deletemenu':
		deleteMenu($option,$cid,$type);
		break;

	case 'copyconfirm':
		copyConfirm($option,$cid[0]);
		break;

	case 'copymenu':
		copyMenu($option,$cid,$type);
		break;

	case 'cancel':
		cancelMenu($option);
		break;

	default:
		showMenu($option);
		break;
}

function showMenu($option) {

	$mainframe	= mosMainFrame::getInstance();
	$config		 = $mainframe->config;
	$database	= $mainframe->getDBO();

	$limit	   = intval($mainframe->getUserStateFromRequest("viewlistlimit",'limit',$config->config_list_limit));
	$limitstart	= intval($mainframe->getUserStateFromRequest("view{".$option."}limitstart",'limitstart',0));

	$menuTypes = mosAdminMenus::menutypes();

	$total = count($menuTypes);
	$i = 0;
	foreach($menuTypes as $a) {
		$menus[$i] = new stdClass();
		$menus[$i]->type = $a;

		$menus[$i]->modules = 0;

		// query to get number of modules for menutype
		$query = "SELECT id, params  FROM #__modules WHERE module = 'mod_menu' OR module LIKE '%menu%' AND params LIKE '%".$a."%'";
		$modules = $database->setQuery($query)->loadObjectList();

		foreach($modules as $mod) {
			if(stripos($mod->params, $a)!==false) {
				$menus[$i]->modules = $menus[$i]->modules + 1;
			}
		}

		if(!$modules) {
			$menus[$i]->modules = '-';
		}

		unset($modules);
		$i++;
	}

	$query = "SELECT a.menutype, count( a.menutype ) as num FROM #__menu AS a WHERE a.published = 1 GROUP BY a.menutype ORDER BY a.menutype";
	$published = $database->setQuery($query)->loadObjectList();

	$query = "SELECT a.menutype, count( a.menutype ) as num FROM #__menu AS a WHERE a.published = 0 GROUP BY a.menutype ORDER BY a.menutype";
	$unpublished = $database->setQuery($query)->loadObjectList();

	$query = "SELECT a.menutype, count( a.menutype ) as num FROM #__menu AS a WHERE a.published = -2 GROUP BY a.menutype ORDER BY a.menutype";
	$trash = $database->setQuery($query)->loadObjectList();

	for($i = 0; $i < $total; $i++) {
		foreach($published as $count) {
			if($menus[$i]->type == $count->menutype) {
				$menus[$i]->published = $count->num;
			}
		}

		if(@!$menus[$i]->published) {
			$menus[$i]->published = '-';
		}

		foreach($unpublished as $count) {
			if($menus[$i]->type == $count->menutype) {
				$menus[$i]->unpublished = $count->num;
			}
		}
		if(@!$menus[$i]->unpublished) {
			$menus[$i]->unpublished = '-';
		}

		foreach($trash as $count) {
			if($menus[$i]->type == $count->menutype) {
				$menus[$i]->trash = $count->num;
			}
		}
		if(@!$menus[$i]->trash) {
			$menus[$i]->trash = '-';
		}
	}

	require_once (JPATH_BASE_ADMIN.DS.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total,$limitstart,$limit);

	HTML_menumanager::show($option,$menus,$pageNav);
}

function editMenu($option,$menu) {

	$database = database::getInstance();
	$row = new mosModule($database);
	if($menu) {
		$row->menutype = $menu;
	} else {
		$row->menutype = '';
		$row->iscore = 0;
		$row->published = 0;
		$row->position = 'left';
		$row->module = 'mod_menu';
	}

	HTML_menumanager::edit($row,$option);
}

function saveMenu() {

	$menutype = stripslashes(strval(mosGetParam($_POST,'menutype','')));
	$old_menutype = stripslashes(strval(mosGetParam($_POST,'old_menutype','')));
	$new = intval(mosGetParam($_POST,'new',1));

	if($old_menutype == 'mainmenu') {
		if($menutype != 'mainmenu') {
			echo "<script> alert('"._CANNOT_RENAME_MAINMENU."'); window.history.go(-1); </script>\n";
			exit;
		}
	}

	if(strstr($menutype,'\'')) {
		echo "<script> alert('"._NO_QUOTES_IN_NAME."'); window.history.go(-1); </script>\n";
		exit;
	}

	$database = database::getInstance();

	$query = "SELECT params FROM #__modules WHERE module LIKE '%menu%' ";
	$menus = $database->setQuery($query)->loadResultArray();

	foreach($menus as $menu) {
		$params = mosParseParams($menu);
		if($params->menutype == $menutype) {
			echo "<script> alert('"._MENU_ALREADY_EXISTS."'); window.history.go(-1); </script>\n";
			exit;
		}
	}

	switch($new) {
		case 1:
		// create a new module for the new menu
			$row = new mosModule($database);
			$row->bind($_POST);

			$row->params = '{"menutype":"'.$menutype.'"}';

			// check then store data in db
			if(!$row->check()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			if(!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}

			$row->updateOrder("position=".$database->Quote($row->position));

			// module assigned to show on All pages by default
			// ToDO: Changed to become a Joomla! db-object
			$query = "INSERT INTO #__modules_menu VALUES ( ".(int)$row->id.", 0 )";
			if(!$database->setQuery($query)->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}

			$msg = _NEW_MENU_CREATED.' [ '.$menutype.' ]';
			break;

		default:
			$query = "SELECT id FROM #__modules WHERE module = 'mod_menu' AND params LIKE '%".$database->getEscaped($old_menutype)."%'";
			$modules = $database->setQuery($query)->loadResultArray();

			foreach($modules as $module) {
				$row = new mosModule($database);
				$row->load($module);

				$save = 0;
				$params = mosParseParams($row->params);
				if($params->menutype == $old_menutype) {
					$params->menutype = $menutype;
					$save = 1;
				}

				// save changes to module 'menutype' param
				if($save) {
					$txt = array();
					foreach($params as $k => $v) {
						$txt[] = "$k=$v";
					}
					$row->params = implode("\n",$txt);

					// check then store data in db
					if(!$row->check()) {
						echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
					if(!$row->store()) {
						echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}
			}

			// change menutype of all menuitems using old menutype
			if($menutype != $old_menutype) {
				$query = "UPDATE #__menu SET menutype = ".$database->Quote($menutype)."\n WHERE menutype = ".$database->Quote($old_menutype);
				$database->setQuery($query)->query();
			}

			$msg = _MENU_ITEMS_AND_MODULES_UPDATED;
			break;
	}

	mosRedirect('index2.php?option=com_menumanager',$msg);
}

function deleteConfirm($option,$type) {

	if($type == 'mainmenu') {
		mosMainFrame::set_mosmsg( _CANNOT_RENAME_MAINMENU );
		echo "<script>window.history.go(-1); </script>\n";
		exit();
	}

	$database = database::getInstance();

	$query = "SELECT a.name, a.id FROM #__menu AS a WHERE a.menutype = ".$database->Quote($type)." ORDER BY a.name";
	$items = $database->setQuery($query)->loadObjectList();

	$query = "SELECT id FROM #__modules WHERE module LIKE '%menu%' AND params LIKE '%".$database->getEscaped($type)."%'";
	$mods = $database->setQuery($query)->loadResultArray();

	foreach($mods as $module) {
		$row = new mosModule($database);
		$row->load($module);

		$params = mosParseParams($row->params);
		if($params->menutype == $type) {
			$mid[] = $module;
		}
	}

	mosArrayToInts($mid);
	if(count($mid)) {
		$mids = 'id='.implode(' OR id=',$mid);
		$query = "SELECT id, title FROM #__modules WHERE ( $mids )";
		$modules = $database->setQuery($query)->loadObjectList();
	} else {
		$modules = null;
	}

	HTML_menumanager::showDelete($option,$type,$items,$modules);
}

function deleteMenu($option,$cid,$type) {

	if($type == 'mainmenu') {
		echo "<script> alert('"._CANNOT_RENAME_MAINMENU."'); window.history.go(-1); </script>\n";
		exit();
	}

	$database = database::getInstance();

	$mid = mosGetParam($_POST,'mids');
	mosArrayToInts($mid);
	if(count($mid)) {
		// delete menu items
		$mids = 'id='.implode(' OR id=',$mid);
		$query = "DELETE FROM #__menu WHERE ( $mids )";
		if(!$database->setQuery($query)->query()) {
			echo "<script> alert('".$database->getErrorMsg()."');</script>\n";
			exit;
		}
	}

	mosArrayToInts($cid);
	// checks whether any modules to delete
	if(count($cid)) {
		// delete modules
		$cids = 'id='.implode(' OR id=',$cid);
		$query = "DELETE FROM #__modules WHERE ( $cids )";
		if(!$database->setQuery($query)->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit;
		}
		// delete all module entires in jos_modules_menu
		$cids = 'moduleid='.implode(' OR moduleid=',$cid);
		$query = "DELETE FROM #__modules_menu"."\n WHERE ( $cids )";
		if(!$database->setQuery($query)->query()) {
			echo "<script> alert('".$database->getErrorMsg()."');</script>\n";
			exit;
		}

		// reorder modules after deletion
		$mod = new mosModule($database);
		$mod->ordering = 0;
		$mod->updateOrder("position='left'");
		$mod->updateOrder("position='right'");
	}

	// clean any existing cache files
	mosCache::cleanCache('com_content');

	$msg = _MENU_DELETED;
	mosRedirect('index2.php?option='.$option,$msg);
}

function copyConfirm($option,$type) {
	$database = database::getInstance();

	$query = 'SELECT a.name, a.id FROM #__menu AS a WHERE a.menutype ='. $database->Quote($type).' ORDER BY a.name';
	$items = $database->setQuery($query)->loadObjectList();

	HTML_menumanager::showCopy($option,$type,$items);
}

function copyMenu($option,$cid,$type) {

	$menu_name = stripslashes(strval(mosGetParam($_POST,'menu_name',_NEW_MENU)));
	$module_name = stripslashes(strval(mosGetParam($_POST,'module_name',_NEW_MENU_MODULE)));

	$database = database::getInstance();

	$query = "SELECT params FROM #__modules WHERE module LIKE '%menu%' ";
	$menus = $database->setQuery($query)->loadResultArray();
	foreach($menus as $menu) {
		$params = mosParseParams($menu);
		if($params->menutype == $menu_name) {
			echo "<script> alert('"._MENU_ALREADY_EXISTS."'); window.history.go(-1); </script>\n";
			exit;
		}
	}

	// copy the menu items
	$mids = josGetArrayInts('mids');
	$total = count($mids);
	$copy = new mosMenu($database);
	$original = new mosMenu($database);
	sort($mids);
	$a_ids = array();

	foreach($mids as $mid) {
		$original->load($mid);
		$copy = $original;
		$copy->id = null;
		$copy->parent = $a_ids[$original->parent];
		$copy->menutype = $menu_name;

		if(!$copy->check()) {
			echo "<script> alert('".$copy->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if(!$copy->store()) {
			echo "<script> alert('".$copy->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$a_ids[$original->id] = $copy->id;
	}

	// create the module copy
	$row = new mosModule($database);
	$row->load(0);
	$row->title = $module_name;
	$row->iscore = 0;
	$row->published = 1;
	$row->position = 'left';
	$row->module = 'mod_menu';
	$row->params = 'menutype='.$menu_name;

	if(!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if(!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$row->updateOrder('position='.$database->Quote($row->position));

	$query = "INSERT INTO #__modules_menu VALUES ( ".(int)$row->id.", 0 )";

	if(!$database->setQuery($query)->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// clean any existing cache files
	mosCache::cleanCache('com_content');

	$msg = _MENU_COPY_FINISHED.' `'.$type.'`'. _MENU_COPY_FINISHED_ITEMS . $total;
	mosRedirect('index2.php?option='.$option,$msg);
}

function cancelMenu($option) {
	mosRedirect('index2.php?option='.$option.'&task=view');
}