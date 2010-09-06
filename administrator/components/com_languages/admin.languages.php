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

Jacl::isDeny('languages') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require_once ($mainframe->getPath('admin_html'));
// XML library
require_once (JPATH_BASE.'/includes/domit/xml_domit_lite_include.php');

$cid = mosGetParam($_REQUEST,'cid',array(0));
if(!is_array($cid)) {
	$cid = array(0);
} else {
	foreach($cid as $key => $value) {
		$key = preg_replace('#\W#','',$value);
	}
}

switch($task) {
	case 'new':
		mosRedirect('index2.php?option=com_installer&element=language');
		break;

	case 'edit_source':
		editLanguageSource($cid[0],$option);
		break;

	case 'save_source':
		saveLanguageSource($option);
		break;

	case 'remove':
		removeLanguage($cid[0],$option);
		break;

	case 'publish':
		publishLanguage($cid[0],$option);
		break;

	case 'cancel':
		mosRedirect("index2.php?option=$option");
		break;

	default:
		viewLanguages($option);
		break;
}

/**
 * Compiles a list of installed languages
 */
function viewLanguages($option) {
	global $languages;
	global $mainframe;
	global $mosConfig_lang,$mosConfig_list_limit;

	$limit = $mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mosConfig_list_limit);
	$limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart",'limitstart',0);

	// get current languages
	$cur_language = $mosConfig_lang;

	$rows = array();
	// Read the template dir to find templates
	$languageBaseDir = mosPathName(mosPathName(JPATH_BASE)."language");

	$rowid = 0;

	$xmlFilesInDir = mosReadDirectory($languageBaseDir,'.xml$');

	$dirName = $languageBaseDir;
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
		if($root->getAttribute("type") != "language") {
			continue;
		}

		$row = new StdClass();
		$row->id = $rowid;
		$row->language = substr($xmlfile,0,-4);
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

		// if current than set published
		if($cur_language == $row->language) {
			$row->published = 1;
		} else {
			$row->published = 0;
		}

		$row->checked_out = 0;
		$row->mosname = strtolower(str_replace(" ","_",$row->name));
		$rows[] = $row;
		$rowid++;
	}

	require_once (JPATH_BASE.'/'.JADMIN_BASE.'/includes/pageNavigation.php');
	$pageNav = new mosPageNav(count($rows),$limitstart,$limit);

	$rows = array_slice($rows,$pageNav->limitstart,$pageNav->limit);

	HTML_languages::showLanguages($cur_language,$rows,$pageNav,$option);
}

/**
 * Publish, or make current, the selected language
 */
function publishLanguage($p_lname,$option) {
	global $mosConfig_lang;
	josSpoofCheck();
	$config = '';

	$fp = fopen('../configuration.php','r');
	while(!feof($fp)) {
		$buffer = fgets($fp,4096);
		if(strstr($buffer,"\$mosConfig_lang")) {
			$config .= "\$mosConfig_lang = \"$p_lname\";\n";
		} else {
			$config .= $buffer;
		}
	}
	fclose($fp);

	if($fp = fopen('../configuration.php','w')) {
		fputs($fp,$config,strlen($config));
		fclose($fp);
		mosRedirect('index2.php?option=com_languages',_LANGUAGE_SAVED." $p_lname");
	} else {
		mosRedirect('index2.php?option=com_languages','Ошибка!');
	}

}

/**
 * Remove the selected language
 */
function removeLanguage($cid,$option,$client = 'admin') {
	global $mosConfig_lang;
	josSpoofCheck();
	$client_id = $client == 'admin'?1:0;

	$cur_language = $mosConfig_lang;

	if($cur_language == $cid) {
		mosErrorAlert(_YOU_CANNOT_DELETE_LANG_FILE);
	}

	mosRedirect( 'index2.php?option=com_installer&element=language&client='. $client .'&task=remove&cid[]='. $cid . '&' . josSpoofValue() . '=1' );

}

function editLanguageSource($p_lname,$option) {
	$file = stripslashes("../language/$p_lname/system.php");

	if($fp = fopen($file,"r")) {
		$content = fread($fp,filesize($file));
		$content = htmlspecialchars($content);

		HTML_languages::editLanguageSource($p_lname,$content,$option);
	} else {
		mosRedirect("index2.php?option=$option&mosmsg="._UNSUCCES_OPERATION_CANNOT_OPEN." $file");
	}
}

function saveLanguageSource($option) {
	josSpoofCheck();
	$language = mosGetParam($_POST,'language','');
	$filecontent = mosGetParam($_POST,'filecontent','',_MOS_ALLOWHTML);

	if(!$language) {
		mosRedirect("index2.php?option=$option&mosmsg="._UNSUCCESS_OPERATION_NO_LANGUAGE);
	}
	if(!$filecontent) {
		mosRedirect("index2.php?option=$option&mosmsg="._UNSUCCESS_OPERATION_NO_TEMPLATE);
	}

	$file = "../language/$language/system.php";
	$enable_write = mosGetParam($_POST,'enable_write',0);
	$oldperms = fileperms($file);
	if($enable_write) @chmod($file,$oldperms | 0222);

	clearstatcache();
	if(is_writable($file) == false) {
		mosRedirect("index2.php?option=$option&mosmsg="._UNSUCCES_OPERATION_CANNOT_OPEN);
	}

	if($fp = fopen($file,"w")) {
		fputs($fp,stripslashes($filecontent));
		fclose($fp);
		if($enable_write) {
			@chmod($file,$oldperms);
		} else {
			if(mosGetParam($_POST,'disable_write',0)) @chmod($file,$oldperms & 0777555);
		} // if
		mosRedirect("index2.php?option=$option");
	} else {
		if($enable_write) @chmod($file,$oldperms);
		mosRedirect("index2.php?option=$option&mosmsg="._UNSUCCES_OPERATION_CANNOT_OPEN);
	}
}