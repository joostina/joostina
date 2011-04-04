<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

Jacl::isDeny('linkeditor') ? joosRoute::redirect('index2.php?', _NOT_AUTH) : null;

require_once ($mainframe->getPath('admin_html'));

$cid = joosRequest::array_param('cid');
$id = intval(mosGetParam($_REQUEST,'id',0));

switch($task) {
	case 'edit':
		editLink($id);
		break;

	case 'new':
		editLink();
		break;

	case 'cancel':
		joosRoute::redirect("index2.php?option=com_linkeditor");
		break;

	case 'savelink':
		js_menu_cache_clear();
		saveLink($cid);
		break;

	default:
	case 'all':
		viewLinks();
		break;

	case 'saveorder':
		js_menu_cache_clear();
		saveOrder($cid);
		break;

	case 'remove':
		js_menu_cache_clear();
		deleteLink($cid);
		break;
}

function deleteLink(&$cid) {
	$database = database::instance();

	if(count($cid)) {
		$cids = implode(',',$cid);
		$query = "DELETE FROM #__components WHERE id IN ( $cids )";
		$database->set_query($query);
		if(!$database->query()) {
			echo "<script> alert('".$database->get_error_msg()."'); window.history.go(-1); </script>\n";
		}
	}

	joosRoute::redirect('index2.php?option=com_linkeditor&amp;task=all',_MENU_ITEM_DELETED);

}
function saveOrder(&$cid) {
	$database = database::instance();

	$total = count($cid);
	$order = mosGetParam($_POST,'order',array(0));
	$row = new mosComponent($database);
	$conditions = array();

	// update ordering values
	for($i = 0; $i < $total; $i++) {
		$row->load($cid[$i]);
		if($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if(!$row->store()) {
				echo "<script> alert('".$database->get_error_msg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "parent='$row->parent' AND iscore >= 0";
			$found = false;
			foreach($conditions as $cond)
				if($cond[1] == $condition) {
					$found = true;
					break;
				} // if
			if(!$found) $conditions[] = array($row->id,$condition);
		} // if
	} // for

	// execute updateOrder for each group
	foreach($conditions as $cond) {
		$row->load($cond[0]);
		$row->update_order($cond[1]);
	} // foreach

	$msg = _NEW_ORDER_SAVED;
	joosRoute::redirect('index2.php?option=com_linkeditor',$msg);
} // saveOrder

function editLink($id = 0) {

	// подключение класса компонентов
	joosLoader::core_class('component');

	$row = new mosComponent;
	$row->load($id);

	$pathA = JPATH_BASE.'/administrator/images/menu';
	$pathL = JPATH_SITE.'/administrator/images/menu';
	$images = array();
	$folders = array();
	$folders[] = html::make_option('/');

	$images['/'][] = html::make_option('spacer.png',' --- ');
	ReadImages($pathA,'/',$folders,$images);

	$lists['image'] = GetImages($images,$pathL,$row);

	$options = array();
	$options[] = html::make_option('0',_FIRST_LEVEL);
	$lists['parent'] = categoryParentList($row->id,"",$options);

	HTML_linkeditor::edit($row,$lists);
}

function GetImages(&$images,$pathL,$row) {
	if(!isset($images['/'])) {
		$images['/'][] = html::make_option('');
	}
	$javascript = "onchange=\" $('#view_imagefiles').attr('src','$pathL/'+$(this).val() )\"";
	$admin_menu_img = str_replace('administrator/images/menu/', '', $row->admin_menu_img);
	return html::selectList($images['/'],'admin_menu_img','class="inputbox" size="10" style="width:95%"'.$javascript,'value','text',$admin_menu_img);
}

function ReadImages($imagePath,$folderPath,&$folders,&$images) {
	$imgFiles = mosReadDirectory($imagePath);
	foreach($imgFiles as $file) {
		$ff = $folderPath.$file;
		$i_f = $imagePath.'/'.$file;
		if( preg_match("/^bmp|gif|jpg|png/",$file) && is_file($i_f)) {
			$imageFile = substr($ff,1);
			$images[$folderPath][] = html::make_option($imageFile,$file);
		}
	}
}

function categoryParentList($id,$action,$options = array()) {
	$database = database::instance();

	$list = categoryArray();

	$cat = new mosComponent($database);
	$cat->load($id);

	$this_treename = '';
	foreach($list as $item) {
		if($this_treename) {
			if($item->id != $cat->id && strpos($item->treename,$this_treename) === false) {
				$options[] = html::make_option($item->id,$item->treename);
			}
		} else {
			if($item->id != $cat->id) {
				$options[] = html::make_option($item->id,$item->treename);
			} else {
				$this_treename = $item->treename.'/';
			}
		}
	}

	$parent = html::selectList($options,'parent','class="inputbox" size="1" style="width:80%"','value','text',$cat->parent);
	return $parent;
}

function categoryArray() {
	$database = database::instance();

	// get a list of the menu items
	$query = "SELECT* FROM #__components ORDER BY ordering";

	$database->set_query($query);
	$items = $database->load_object_list();
	// establish the hierarchy of the menu
	$children = array();
	// first pass - collect children
	foreach($items as $v) {
		$pt = $v->parent;
		$list = @$children[$pt]?$children[$pt]:array();
		array_push($list,$v);
		$children[$pt] = $list;
	}
	// second pass - get an indent list of the items
	$array = mosTreeRecurse(0,'',array(),$children);

	return $array;
}


function saveLink() {
	$database = database::instance();

	$image = mosGetParam($_POST,'admin_menu_img');

	$admin_menu_img = "administrator/images/menu/".$image;
	$_POST['admin_menu_img'] = $admin_menu_img;
	$_POST['option'] = $_POST['cur_option'];

	joosLoader::core_class('component');
	$row = new mosComponent($database);

	if(!$row->bind($_POST)) {
		echo "<script> alert('".$row->get_error()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if(!$row->store()) {
		echo "<script> alert('".$row->get_error()."'); window.history.go(-1); </script>\n";
		exit();
	}
	joosRoute::redirect("index2.php?option=com_linkeditor",'Изменения сохранены');
}

function viewLinks() {
	global $mainframe,$mosConfig_list_limit,$option,$section,$menutype;

	$database = database::instance();

	$limit = intval(joosSession::get_user_state_from_request("viewlistlimit",'limit',$mosConfig_list_limit));
	$limitstart = intval(joosSession::get_user_state_from_request("view{$section}limitstart",'limitstart',0));
	$levellimit = intval(joosSession::get_user_state_from_request("view{$option}limit$menutype",'levellimit',10));
	$database->set_query("SELECT* FROM #__components ORDER by ordering, name");
	$rows = $database->load_object_list();
	if($database->get_error_num()) {
		echo $database->stderr();
		return false;
	}
	// establish the hierarchy of the categories
	$children = array();
	// first pass - collect children
	foreach($rows as $v) {
		$pt = $v->parent;
		$list = @$children[$pt]?$children[$pt]:array();
		array_push($list,$v);
		$children[$pt] = $list;
	}
	// second pass - get an indent list of the items
	$list = mosTreeRecurse(0,'',array(),$children,max(0,$levellimit - 1));

	$total = count($list);

	require_once (JPATH_BASE.DS.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new joosPagenator($total,$limitstart,$limit);

	// slice out elements based on limits
	$list = array_slice($list,$pageNav->limitstart,$pageNav->limit);
	HTML_linkeditor::viewall($list,$pageNav);

}