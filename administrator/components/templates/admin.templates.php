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

Jacl::isDeny('templates') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

global $mosConfig_one_template;

require_once ($mainframe->getPath('admin_html'));
require_once (JPATH_BASE_ADMIN.'/components/com_templates/admin.templates.class.php');
// XML library
require_once (JPATH_BASE.'/includes/domit/xml_domit_lite_include.php');

$client = strval(mosGetParam($_REQUEST,'client',''));
$cid = mosGetParam($_REQUEST,'cid',array(0));

if($mosConfig_one_template != '...') {
	echo joost_info(_INOGLOBAL_CONFIG_ONE_TEMPLATE_USING.' <b>'.$mosConfig_one_template.'</b>');
}

if(!is_array($cid)) {
	$cid = array(0);
}
if(get_magic_quotes_gpc()) {
	$cid[0] = stripslashes($cid[0]);
}

switch($task) {
	case 'new':
		mosRedirect('index2.php?option=com_installer&element=template&client='.$client);
		break;

	case 'edit_source':
		editTemplateSource($cid[0],$option,$client);
		break;

	case 'save_source':
	case 'apply':
		saveTemplateSource($option,$client,$task);
		break;

	case 'edit_css':
		editTemplateCSS($cid[0],$option,$client);
		break;

	case 'save_css':
		saveTemplateCSS($option,$client);
		break;

	case 'remove':
		removeTemplate($cid[0],$option,$client);
		break;

	case 'publish':
		defaultTemplate($cid[0],$option,$client);
		break;

	case 'default':
		defaultTemplate($cid[0],$option,$client);
		break;

	case 'assign':
		assignTemplate($cid[0],$option,$client);
		break;

	case 'save_assign':
		saveTemplateAssign($option,$client);
		break;

	case 'cancel':
		mosRedirect('index2.php?option='.$option.'&client='.$client);
		break;

	case 'positions':
		editPositions($option);
		break;

	case 'save_positions':
		savePositions($option);
		break;

	default:
		viewTemplates($option,$client);
		break;
}


/**
 * Compiles a list of installed, version 4.5+ templates
 *
 * Based on xml files found.  If no xml file found the template
 * is ignored
 */
function viewTemplates($option,$client) {
	$mainframe = mosMainFrame::getInstance();
	$database = $mainframe->getDBO();

	$limit = $mainframe->getUserStateFromRequest('viewlistlimit','limit',$mainframe->config->config_list_limit);
	$limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart",'limitstart',0);

	if($client == 'admin') {
		$templateBaseDir = mosPathName(JPATH_BASE_ADMIN.'/templates');
	} else {
		$templateBaseDir = mosPathName(JPATH_BASE.'/templates');
	}

	$rows = array();
	// Read the template dir to find templates
	$templateDirs = mosReadDirectory($templateBaseDir);

	$id = intval($client == 'admin');

	if($client == 'admin') {
		$query = "SELECT template"
				."\n FROM #__templates_menu"
				."\n WHERE client_id = 1"
				."\n AND menuid = 0";
		$database->setQuery($query);
	} else {
		$query = "SELECT template"
				."\n FROM #__templates_menu"
				."\n WHERE client_id = 0"
				."\n AND menuid = 0";
		$database->setQuery($query);
	}
	$cur_template = $database->loadResult();

	$rowid = 0;
	// Check that the directory contains an xml file
	foreach($templateDirs as $templateDir) {
		$dirName = mosPathName($templateBaseDir.$templateDir);
		$xmlFilesInDir = mosReadDirectory($dirName,'.xml$');

		foreach($xmlFilesInDir as $xmlfile) {
			// Read the file to see if it's a valid template XML file
			$xmlDoc = new DOMIT_Lite_Document();
			$xmlDoc->resolveErrors(true);
			if(!$xmlDoc->loadXML($dirName.$xmlfile,false,true)) {
				continue;
			}

			$root = &$xmlDoc->documentElement;

			if($root->getTagName() != 'mosinstall') {
				continue;
			}
			if($root->getAttribute('type') != 'template') {
				continue;
			}

			$row = new StdClass();
			$row->id = $rowid;
			$row->directory = $templateDir;
			$element = &$root->getElementsByPath('name',1);
			$row->name = $element->getText();

			$element = &$root->getElementsByPath('creationDate',1);
			$row->creationdate = $element?$element->getText():'Unknown';

			$element = &$root->getElementsByPath('author',1);
			$row->author = $element?$element->getText():'Unknown';

			$element = &$root->getElementsByPath('copyright',1);
			$row->copyright = $element?$element->getText():'';

			$element = &$root->getElementsByPath('authorEmail',1);
			$row->authorEmail = $element?$element->getText():'';

			$element = &$root->getElementsByPath('authorUrl',1);
			$row->authorUrl = $element?$element->getText():'';

			$element = &$root->getElementsByPath('version',1);
			$row->version = $element?$element->getText():'';

			// Get info from db
			if($cur_template == $templateDir) {
				$row->published = 1;
			} else {
				$row->published = 0;
			}

			$row->checked_out = 0;
			$row->mosname = strtolower(str_replace(' ','_',$row->name));

			// check if template is assigned
			$query = "SELECT COUNT(*) FROM #__templates_menu WHERE client_id = 0 AND template = ".$database->Quote($row->directory)."\n AND menuid != 0";
			$database->setQuery($query);
			$row->assigned = $database->loadResult()?1:0;

			$rows[] = $row;
			$rowid++;

			unset($xmlDoc);
		}
	}

	require_once (JPATH_BASE.'/'.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav(count($rows),$limitstart,$limit);

	$rows = array_slice($rows,$pageNav->limitstart,$pageNav->limit);

	HTML_templates::showTemplates($rows,$pageNav,$option,$client);
}


/**
 * Publish, or make current, the selected template
 */
function defaultTemplate($p_tname,$option,$client) {
	global $database;
	josSpoofCheck();
	if($client == 'admin') {
		$query = "DELETE FROM #__templates_menu WHERE client_id = 1 AND menuid = 0";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO #__templates_menu SET client_id = 1, template = ".$database->Quote($p_tname).", menuid = 0";
		$database->setQuery($query);
		$database->query();
	} else {
		$query = "DELETE FROM #__templates_menu WHERE client_id = 0 AND menuid = 0";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO #__templates_menu SET client_id = 0, template = ".$database->Quote($p_tname).", menuid = 0";
		$database->setQuery($query);
		$database->query();

		$_SESSION['cur_template'] = $p_tname;
	}

	mosRedirect('index2.php?option='.$option.'&client='.$client);
}

/**
 * Remove the selected template
 */
function removeTemplate($cid,$option,$client) {
	global $database;
	josSpoofCheck();
	$client_id = $client == 'admin'?1:0;

	$query = "SELECT template FROM #__templates_menu WHERE client_id = ".(int)$client_id."\n AND menuid = 0";
	$database->setQuery($query);
	$cur_template = $database->loadResult();

	if($cur_template == $cid) {
		mosErrorAlert(_CANNOT_DELETE_THIS_TEMPLATE_WHEN_USING);
	}

	// Un-assign
	$query = "DELETE FROM #__templates_menu WHERE template = ".$database->Quote($cid)."\n AND client_id = ".(int)$client_id."\n AND menuid != 0";
	$database->setQuery($query);
	$database->query();

	mosRedirect( 'index2.php?option=com_installer&element=template&client='. $client .'&task=remove&cid[]='. $cid . '&' . josSpoofValue() . '=1');
}

function editTemplateSource($p_tname,$option,$client) {

	if($client == 'admin') {
		$file = JPATH_BASE_ADMIN.'/templates/'.$p_tname.'/index.php';
	} else {
		$file = JPATH_BASE.'/templates/'.$p_tname.'/index.php';
	}

	if($fp = fopen($file,'r')) {
		$content = fread($fp,filesize($file));
		$content = htmlspecialchars($content);
		HTML_templates::editTemplateSource($p_tname,$content,$option,$client);
	} else {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCES_OPERATION_CANNOT_OPEN.' '.$file);
	}
}


function saveTemplateSource($option,$client,$task) {
	josSpoofCheck();
	$template = strval(mosGetParam($_POST,'template',''));
	$filecontent = mosGetParam($_POST,'filecontent','',_MOS_ALLOWHTML);

	if(!$template) {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCESS_OPERATION_NO_TEMPLATE);
	}
	if(!$filecontent) {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCESS_OPERATION_EMPTY_FILE);
	}

	if($client == 'admin') {
		$file = JPATH_BASE_ADMIN.'/templates/'.$template.	'/index.php';
	} else {
		$file = JPATH_BASE.'/templates/'.$template.'/index.php';
	}

	$enable_write = mosGetParam($_POST,'enable_write',0);
	$oldperms = fileperms($file);

	if($enable_write) @chmod($file,$oldperms | 0222);

	clearstatcache();
	if(is_writable($file) == false) {
		mosRedirect('index2.php?option='.$option,_UNSUCCES_OPERAION.' '.$file.' - '._UNWRITEABLE);
	}
	if($fp = fopen($file,'w')) {
		fputs($fp,stripslashes($filecontent),strlen($filecontent));
		fclose($fp);
		if($enable_write) {
			@chmod($file,$oldperms);
		} else {
			if(mosGetParam($_POST,'disable_write',0)) @chmod($file,$oldperms & 0777555);
		} // if
		mosRedirect('index2.php?option='.$option.'&client='.$client);
	} else {
		if($enable_write) @chmod($file,$oldperms);
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCES_OPERAION.' '._CANNOT_OPEN_FILE_DOR_WRITE);
	}

}

function editTemplateCSS($p_tname,$option,$client) {
	josSpoofCheck();
	if($client == 'admin') {
		$file = JPATH_BASE_ADMIN.'/templates/'.$p_tname.'/css/template_css.css';
	} else {
		$file = JPATH_BASE.'/templates/'.$p_tname.'/css/template_css.css';
	}

	if($fp = fopen($file,'r')) {
		$content = fread($fp,filesize($file));
		$content = htmlspecialchars($content);
		HTML_templates::editCSSSource($p_tname,$content,$option,$client);
	} else {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCES_OPERATION_CANNOT_OPEN.' '.$file);
	}
}


function saveTemplateCSS($option,$client) {
	$template = strval(mosGetParam($_POST,'template',''));
	$filecontent = mosGetParam($_POST,'filecontent','',_MOS_ALLOWHTML);

	if(!$template) {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCESS_OPERATION_NO_TEMPLATE);
	}

	if(!$filecontent) {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCESS_OPERATION_EMPTY_FILE);
	}

	if($client == 'admin') {
		$file = JPATH_BASE_ADMIN.'/templates/'.$template.'/css/template_css.css';
	} else {
		$file = JPATH_BASE.'/templates/'.$template.'/css/template_css.css';
	}

	$enable_write = mosGetParam($_POST,'enable_write',0);
	$oldperms = fileperms($file);

	if($enable_write) {
		@chmod($file,$oldperms | 0222);
	}

	clearstatcache();
	if(is_writable($file) == false) {
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCES_OPERAION.' '._CANNOT_OPEN_FILE_DOR_WRITE);
	}

	if($fp = fopen($file,'w')) {
		fputs($fp,stripslashes($filecontent));
		fclose($fp);
		if($enable_write) {
			@chmod($file,$oldperms);
		} else {
			if(mosGetParam($_POST,'disable_write',0)) @chmod($file,$oldperms & 0777555);
		} // if
		mosRedirect('index2.php?option='.$option.'&client='.$client);
	} else {
		if($enable_write) @chmod($file,$oldperms);
		mosRedirect('index2.php?option='.$option.'&client='.$client,_UNSUCCES_OPERAION.' '._CANNOT_OPEN_FILE_DOR_WRITE);
	}

}


function assignTemplate($p_tname,$option,$client) {
	global $database;
	josSpoofCheck();
	// get selected pages for $menulist
	if($p_tname) {
		$query = "SELECT menuid AS value FROM #__templates_menu WHERE client_id = 0 AND template = ".$database->Quote($p_tname);
		$database->setQuery($query);
		$lookup = $database->loadObjectList();
	}

	// build the html select list
	$menulist = mosAdminMenus::MenuLinks($lookup,0,1);

	HTML_templates::assignTemplate($p_tname,$menulist,$option,$client);
}


function saveTemplateAssign($option,$client) {
	global $database;
	josSpoofCheck();
	$menus = josGetArrayInts('selections');

	$template = stripslashes(strval(mosGetParam($_POST,'template','')));

	$query = "DELETE FROM #__templates_menu WHERE client_id = 0 AND template = ".$database->Quote($template)."\n AND menuid != 0";
	$database->setQuery($query);
	$database->query();

	if(!in_array('',$menus)) {
		foreach($menus as $menuid) {
			$menuid = (int)$menuid;

			// If 'None' is not in array
			if($menuid != -999) {
				// check if there is already a template assigned to this menu item
				$query = "DELETE FROM #__templates_menu WHERE client_id = 0 AND menuid = ".(int)
						$menuid;
				$database->setQuery($query);
				$database->query();

				$query = "INSERT INTO #__templates_menu SET client_id = 0, template = ".$database->Quote($template).", menuid = ".(int)$menuid;
				$database->setQuery($query);
				$database->query();
			}
		}
	}

	mosRedirect('index2.php?option='.$option.'&client='.$client);
}


/**
 */
function editPositions($option) {
	global $database;

	$query = "SELECT* FROM #__template_positions";
	$database->setQuery($query);
	$positions = $database->loadObjectList();

	HTML_templates::editPositions($positions,$option);
}

/**
 */
function savePositions($option) {
	global $database;
	josSpoofCheck();
	$positions = mosGetParam($_POST,'position',array());
	$descriptions = mosGetParam($_POST,'description',array());

	$query = "DELETE FROM #__template_positions";
	$database->setQuery($query);
	$database->query();

	foreach($positions as $id => $position) {
		$position = trim($position);
		if(get_magic_quotes_gpc()) {
			$position = stripslashes($position);
		}
		$description = stripslashes(strval(mosGetParam($descriptions,$id,'')));
		if($position != '') {
			$query = "INSERT INTO #__template_positions VALUES ( ".(int)$id.", ".$database->Quote($position).", ".$database->Quote($description)." )";
			$database->setQuery($query);
			$database->query();
		}
	}
	mosRedirect('index2.php?option='.$option.'&task=positions',_POSITIONS_SAVED);
}