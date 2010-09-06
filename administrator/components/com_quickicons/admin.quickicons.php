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

Jacl::isDeny('quickicons') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require_once ($mainframe->getPath('class'));
require_once ($mainframe->getPath('admin_html'));

global $task;
$id		= mosGetParam($_REQUEST,'id',null);
$cid	= josGetArrayInts('cid');

if(!is_array($cid)) {
	$cid = array(0);
}

// при обращении к настройкам быстрых значков доступа почистим их кэш
mosCache::cleanCache('quick_icons');

switch($task) {
	case 'new':
		editIcon(null,$option);
		break;

	case 'edit':
		editIcon($id,$option);
		break;

	case 'editA':
		editIcon($cid[0],$option);
		break;


	case 'delete':
		deleteIcon($cid,$option);
		break;

	case 'save':
		saveIcon(1,$option);
		break;

	case 'apply':
		saveIcon(0,$option);
		break;

	case 'publish':
		changeIcon($cid,1,$option);
		break;

	case 'unpublish':
		changeIcon($cid,0,$option);
		break;

	case 'orderUp':
		orderIcon($id,-1,$option);
		break;

	case 'orderDown':
		orderIcon($id,1,$option);
		break;

	case 'chooseIcon':
		chooseIcon($option);
		break;

	case 'saveorder':
		saveOrder($cid,$option);
		break;

	default:
		show($option);
		break;
}

// show the Items
function show($option) {
	$mainframe = mosMainFrame::getInstance();
	$database = $mainframe->getDBO();

	$limit		= intval($mainframe->getUserStateFromRequest('viewlistlimit','limit', $mainframe->getCfg('list_limit') ));
	$limitstart	= intval($mainframe->getUserStateFromRequest("view{$option}limitstart",'limitstart',0));
	$search		= $mainframe->getUserStateFromRequest("search{$option}",'search','');
	$search		= $database->getEscaped(Jstring::trim(Jstring::strtolower($search)));

	$where = array();

	if($search) {
		$where[] = 'LOWER( a.text ) LIKE \'%$search%\' OR LOWER( a.target ) LIKE \'%$search%\' OR LOWER( a.cm_path ) LIKE \'%$search%\'';
	}

	$query = 'SELECT COUNT(*) FROM #__quickicons AS a'.(count($where)?' WHERE '.implode(' AND ',$where):'');
	$total = $database->setQuery($query)->loadResult();

	require_once (JPATH_BASE.'/'.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total,$limitstart,$limit);

	$query = 'SELECT a.*, g.name AS groupname FROM #__quickicons AS a LEFT JOIN #__core_acl_aro_groups AS g ON g.group_id = a.gid'.(count($where)?' WHERE '.implode(' AND ',$where):'').' ORDER BY ordering';
	$rows = $database->setQuery($query,$pageNav->limitstart,$pageNav->limit)->loadObjectList();

	HTML_QuickIcons::show($rows,$option,$search,$pageNav);
}

function editIcon($id,$option) {
	global $my;

	$database = database::getInstance();

	$row = new CustomQuickIcons();
	$row->load($id);
	$row->published = 1;

	$query = 'SELECT ordering AS value, text AS text FROM #__quickicons ORDER BY ordering';
	$lists['ordering'] = mosAdminMenus::SpecificOrdering($row,$id,$query,1);

	$query = 'SELECT CONCAT_WS( \' \', link, admin_menu_link ) AS value, name AS text, id, parent FROM #__components WHERE link != \'\' OR admin_menu_link != \'\' ORDER BY id, parent';
	$lists['components'] = mosAdminMenus::SpecificOrdering($row,$id = true,$query,1); // id special handling

	$query = 'SELECT admin_menu_link AS value, CONCAT_WS( \' :: \', name, `option` ) AS text FROM #__components WHERE admin_menu_link != \'\' AND (parent = 0 OR parent = 1) ORDER BY name';
	$targets = $database->setQuery($query)->loadObjectList();
	$lists['targets'] = mosHTML::selectList($targets,'tar_gets','id="tar_gets" class="inputbox" size="1"','value','text',null);

	$query = 'SELECT name AS value, CONCAT_WS( \' :: \', `option`, name ) AS text FROM #__components WHERE parent = \'0\' AND `option` != \'\' ORDER BY name';
	$ccheck = $database->setQuery($query)->loadObjectList();
	$lists['components_check'] = mosHTML::selectList($ccheck,'ccheck','id="ccheck" class="inputbox" size="1"','value','text',null);

	$lists['gid'] = '<input class="inputbox" type="hidden" name="gid" value="'.$my->gid.'" /><strong>Тратата</strong>';


	$display[] = mosHTML::makeOption('',_DISPLAY_TEXT_AND_ICON);
	$display[] = mosHTML::makeOption('1',_DISPLAY_ONLY_TEXT);
	$display[] = mosHTML::makeOption('2',_DISPLAY_ONLY_ICON);

	$lists['display'] = mosHTML::selectList($display,'display','class="inputbox" size="1"','value','text',$row->display);

	HTML_QuickIcons::edit($row,$lists,$option);
}

function saveIcon($redirect,$option) {

	$row = new CustomQuickIcons();
	if(!$row->bind($_POST)) {
		echo "<script> alert('1 ".html_entity_decode($row->getError())."'); window.history.go(-1); </script>\n";
		exit();
	}

	$row->icon = str_replace(JPATH_SITE,'',$row->icon);

	if(!$row->check()) {
		echo "<script> alert('".html_entity_decode($row->getError())."'); window.history.go(-1); </script>\n";
		exit();
	}

	if($row->target == 'index2.php?option=' || !$row->target) {
		$row->published = 0;
	}

	if(!$row->store()) {
		echo "<script> alert('3 ".html_entity_decode($row->getError())."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->updateOrder();

	$redirect ? mosRedirect('index2.php?option='.$option) : mosRedirect('index2.php?option='.$option.'&amp;task=edit&amp;id='.$row->id);
}

function orderIcon($id,$inc,$option) {
	$database = database::getInstance();

	// Cleaning ordering
	$query = 'SELECT id, ordering FROM #__quickicons ORDER BY ordering';
	$rows = $database->setQuery($query)->loadObjectList();
	$i = 0;
	foreach($rows as $row) {
		$query = 'UPDATE #__quickicons SET ordering = '.$i.' WHERE id = '.$row->id;
		$database->setQuery($query)->query();
		$i++;
	}

	$query = 'SELECT ordering FROM #__quickicons WHERE id = '.$id;
	$database->setQuery($query)->loadObject($row);

	if($row) {
		$newOrder = $row->ordering + $inc;

		$query = 'SELECT id FROM #__quickicons WHERE ordering = '.$newOrder;
		$database->setQuery($query)->loadObject($row2);

		if($row2) {
			$query = 'UPDATE #__quickicons SET ordering = '.$newOrder.' WHERE id = '.$id;

			if(!$database->setQuery($query)->query()) {
				echo "<script> alert('".$database->getErrorMsg().
						"'); window.history.go(-1); </script>\n";
				exit();
			}

			$query = 'UPDATE #__quickicons SET ordering = '.$row->ordering.' WHERE id = '.$row2->id;
			if(!$database->setQuery($query)->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		mosRedirect('index2.php?option='.$option);
	} else {
		mosRedirect('index2.php?option='.$option);
	}
}

function saveOrder($cid,$option) {
	$database = database::getInstance();

	$total = count($cid);
	$order = mosGetParam($_POST,'order',array(0));

	for($i = 0; $i < $total; $i++) {
		$query = 'UPDATE #__quickicons SET ordering = '.$order[$i].' WHERE id = '.$cid[$i];
		if(!$database->setQuery($query)->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$row = new CustomQuickicons($database);
		$row->load($cid[$i]);
		$row->updateOrder();
	}

	$msg = _NEW_ORDER_SAVED;
	mosRedirect('index2.php?option='.$option,$msg);
}

function deleteIcon($cid,$option) {
	$icons = new CustomQuickIcons();
	$icons->delete_array( $cid, 'id') ?  mosRedirect( 'index2.php?option='.$option, _BUTTONS_DELETED) : mosRedirect( 'index2.php?option='.$option , _BUTTONS_DELETE_ERROR);
}

function chooseIcon() {

	$imgs = array();
	$folder[] = JPATH_BASE_ADMIN.'/images/quickicons/';

	foreach($folder as $fold) {
		if(is_dir($fold)) {
			$handle = opendir($fold);
			while($file = readdir($handle)) {
				if(strpos($file,'.jpg') || strpos($file,'.jpeg') || strpos($file,'.gif') || strpos($file,'.png')) {
					if(!in_array($fold.$file,$imgs)) {
						$imgs[] = $fold.$file;
					}
				}
			}
			closedir($handle);
		}
	}
	sort($imgs);
	HTML_QuickIcons::chooseIcon($imgs);
}