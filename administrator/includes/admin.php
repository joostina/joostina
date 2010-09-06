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

// получаем название шаблона для панели управления
define('JTEMPLATE', 'joostfree' );

mosMainFrame::addClass('mosAdminMenus');
mosMainFrame::addClass('mosHTML');

/* класс дополнительного оформления */
mosMainFrame::addClass('mosCommonHTML');

/* класс парсинга параметров и работы с XML */
mosMainFrame::addClass('parameters');

/* файл данных версии */
require_once (JPATH_BASE . '/includes/version.php');


/**
 * @param string THe template position
 */
function mosCountAdminModules($position = 'left') {
	$database = database::getInstance();

	$query = "SELECT COUNT( m.id )"
			."\n FROM #__modules AS m"
			."\n WHERE m.published = 1"
			."\n AND m.position = ".$database->Quote($position)
			."\n AND m.client_id = 1";
	return $database->setQuery($query)->loadResult();
}
/**
 * Loads admin modules via module position
 * @param string The position
 * @param int 0 = no style, 1 = tabbed
 */
function mosLoadAdminModules($position = 'left',$style = 0) {
	global $my;

	static $all_modules;
	if(!isset($all_modules)) {
		$database = database::getInstance();

		$query = "SELECT id, title, module, position, content, showtitle, params FROM #__modules AS m WHERE m.published = 1 AND m.client_id = 1 ORDER BY m.ordering";
		$_all_modules = $database->setQuery($query)->loadObjectList();

		$all_modules = array();
		foreach($_all_modules as $__all_modules) {
			$all_modules[$__all_modules->position][]=$__all_modules;
		}
		unset($_all_modules,$__all_modules);
	}

	$modules = isset($all_modules[$position]) ? $all_modules[$position] : array();

	switch($style) {
		case 1:
		// Tabs
			mosMainFrame::addClass('mosTabs');
			$tabs = new mosTabs(1,1);
			$tabs->startPane('modules-'.$position);
			foreach($modules as $module) {
				$params = new mosParameters($module->params);

				// special handling for components module
				if($module->module != 'mod_components' || ($module->module == 'mod_components')) {
					$tabs->startTab($module->title,'module'.$module->id);
					if($module->module == '') {
						mosLoadCustomModule($module,$params);
					} else {
						mosLoadAdminModule(substr($module->module,4),$params);
					}
					$tabs->endTab();
				}
			}
			$tabs->endPane();
			break;

		case 2:
		// Div'd
			foreach($modules as $module) {
				$params = new mosParameters($module->params);
				echo '<div>';
				if($module->module == '') {
					mosLoadCustomModule($module,$params);
				} else {
					mosLoadAdminModule(substr($module->module,4),$params);
				}
				echo '</div>';
			}
			break;

		case 0:
		default:
			foreach($modules as $module) {
				$params = new mosParameters($module->params);
				if($module->module == '') {
					mosLoadCustomModule($module,$params);
				} else {
					mosLoadAdminModule(substr($module->module,4),$params);
				}
			}
			break;
	}
}
/**
 * Loads an admin module
 */
function mosLoadAdminModule($name,$params = null) {
	global $task,$my,$option;

	$mainframe = mosMainFrame::getInstance(true);
	$database = $mainframe->getDBO();

	// legacy support for $act
	$act = mosGetParam($_REQUEST,'act','');

	$name = str_replace('/','',$name);
	$name = str_replace('\\','',$name);
	$path = JPATH_BASE_ADMIN."/modules/mod_$name/mod_$name.php";
	if(file_exists($path)) {
		if($mainframe->getLangFile('mod_'.$name)) {
			include($mainframe->getLangFile('mod_'.$name));
		}
		require $path;
	}
}

function mosLoadCustomModule(&$module,&$params) {
	global $mosConfig_cachepath;

	$rssurl = $params->get('rssurl','');
	$rssitems = $params->get('rssitems','');
	$rssdesc = $params->get('rssdesc','');
	$moduleclass_sfx = $params->get('moduleclass_sfx','');
	$rsscache = $params->get('rsscache',3600);
	$cachePath = $mosConfig_cachepath.'/';

	echo '<table cellpadding="0" cellspacing="0" class="moduletable'.$moduleclass_sfx.'">';

	if($module->content) {
		echo '<tr><td>'.$module->content.'</td></tr>';
	}

	// feed output
	if($rssurl) {
		if(!is_writable($cachePath)) {
			echo '<tr><td>'._CACHE_DIR_IS_NOT_WRITEABLE.'</td></tr>';
		} else {
			$LitePath = JPATH_BASE.'/includes/Cache/Lite.php';
			require_once (JPATH_BASE.'/includes/domit/xml_domit_rss_lite.php');
			$rssDoc = new xml_domit_rss_document_lite();
			$rssDoc->setRSSTimeout(5);
			$rssDoc->useHTTPClient(true);
			$rssDoc->useCacheLite(true,$LitePath,$cachePath,$rsscache);
			$success = $rssDoc->loadRSS($rssurl);

			if($success) {
				$totalChannels = $rssDoc->getChannelCount();

				for($i = 0; $i < $totalChannels; $i++) {
					$currChannel = &$rssDoc->getChannel($i);

					$feed_title = $currChannel->getTitle();
					$feed_title = mosCommonHTML::newsfeedEncoding($rssDoc,$feed_title);

					echo '<tr>';
					echo '<td><strong><a href="'.$currChannel->getLink().'" target="_child">';
					echo $feed_title.'</a></strong></td>';
					echo '</tr>';

					if($rssdesc) {
						$feed_descrip = $currChannel->getDescription();
						$feed_descrip = mosCommonHTML::newsfeedEncoding($rssDoc,$feed_descrip);

						echo '<tr>';
						echo '<td>'.$feed_descrip.'</td>';
						echo '</tr>';
					}

					$actualItems = $currChannel->getItemCount();
					$setItems = $rssitems;

					if($setItems > $actualItems) {
						$totalItems = $actualItems;
					} else {
						$totalItems = $setItems;
					}

					for($j = 0; $j < $totalItems; $j++) {
						$currItem = &$currChannel->getItem($j);

						$item_title = $currItem->getTitle();
						$item_title = mosCommonHTML::newsfeedEncoding($rssDoc,$item_title);

						$text = $currItem->getDescription();
						$text = mosCommonHTML::newsfeedEncoding($rssDoc,$text);

						echo '<tr>';
						echo '<td><strong><a href="'.$currItem->getLink().'" target="_child">';
						echo $item_title.'</a></strong> - '.$text.'</td>';
						echo '</tr>';
					}
				}
			}
		}
	}
	echo '</table>';
}

function mosShowSource($filename,$withLineNums = false) {
	ini_set('highlight.html','000000');
	ini_set('highlight.default','#800000');
	ini_set('highlight.keyword','#0000ff');
	ini_set('highlight.string','#ff00ff');
	ini_set('highlight.comment','#008000');

	if(!($source = @highlight_file($filename,true))) {
		return 'Операция невозможна';
	}
	$source = explode("<br />",$source);

	$ln = 1;

	$txt = '';
	foreach($source as $line) {
		$txt .= "<code>";
		if($withLineNums) {
			$txt .= "<font color=\"#aaaaaa\">";
			$txt .= str_replace(' ','&nbsp;',sprintf("%4d:",$ln));
			$txt .= "</font>";
		}
		$txt .= "$line<br /><code>";
		$ln++;
	}
	return $txt;
}
// проверка на доступность смены прав
function mosIsChmodable($file) {
	$perms = fileperms($file);
	if($perms !== false) {
		if(@chmod($file,$perms ^ 0001)) {
			@chmod($file,$perms);
			return true;
		} // if
	}
	return false;
} // mosIsChmodable

/**
 * @param string An existing base path
 * @param string A path to create from the base path
 * @param int Directory permissions
 * @return boolean True if successful
 */
function mosMakePath($base,$path = '',$mode = null) {
	global $mosConfig_dirperms;

	// convert windows paths
	$path = str_replace('\\','/',$path);
	$path = str_replace('//','/',$path);
	// ensure a clean join with a single slash
	$path = ltrim( $path, '/' );
	$base = rtrim( $base, '/' ).'/';

	// check if dir exists
	if(file_exists($base.$path)) return true;

	// set mode
	$origmask = null;
	if(isset($mode)) {
		$origmask = @umask(0);
	} else {
		if($mosConfig_dirperms == '') {
			// rely on umask
			$mode = 0777;
		} else {
			$origmask = @umask(0);
			$mode = octdec($mosConfig_dirperms);
		} // if
	} // if

	$parts = explode('/',$path);
	$n = count($parts);
	$ret = true;
	if($n < 1) {
		if(substr($base,-1,1) == '/') {
			$base = substr($base,0,-1);
		}
		$ret = @mkdir($base,$mode);
	} else {
		$path = $base;
		for($i = 0; $i < $n; $i++) {
			// don't add if part is empty
			if ($parts[$i]) {
				$path .= $parts[$i] . '/';
			}
			if(!file_exists($path)) {
				if(!@mkdir(substr($path,0,-1),$mode)) {
					$ret = false;
					break;
				}
			}
		}
	}
	if(isset($origmask)) {
		@umask($origmask);
	}

	return $ret;
}

function mosMainBody_Admin() {
	echo $GLOBALS['_MOS_OPTION']['buffer'];
}

// boston, кэширование меню администратора
function js_menu_cache($data,$groupname,$state = 0) {
	global $mosConfig_secret,$mosConfig_cachepath,$mosConfig_adm_menu_cache;
	if(!is_writeable($mosConfig_cachepath) && $mosConfig_adm_menu_cache) {
		echo '<script>alert(\''._CACHE_DIR_IS_NOT_WRITEABLE.'\');</script>';
		return false;
	}
	$menuname = md5($groupname.$mosConfig_secret);
	$file = $mosConfig_cachepath.'/adm_menu_'.$menuname.'.js';
	if(!file_exists($file)) { // файла нету
		if($state == 1) return false; // файла у нас не было и получен сигнал 0 - продолжаем вызывающую функцию, а отсюда выходим
		touch($file);
		$handle = fopen($file,'w');
		fwrite($handle,$data);
		fclose($handle);
		return true; // файла не было - но был создан заново
	} else {
		return true; // файл уже был, просто завершаем функцию
	}
}
/*
* Добавлено в версии 1.0.11
*/
function josSecurityCheck($width = '95%') {
	global $mosConfig_cachepath,$mosConfig_caching;
	$wrongSettingsTexts = array();
	// проверка на запись  в каталог кэша
	if(!is_writeable($mosConfig_cachepath) && $mosConfig_caching) $wrongSettingsTexts[] = _CACHE_DIR_IS_NOT_WRITEABLE2;
	// проверка magic_quotes_gpc
	if(ini_get('magic_quotes_gpc') != '1') $wrongSettingsTexts[] = _PHP_MAGIC_QUOTES_ON_OFF;
	// проверка регистрации глобальных переменных
	if(ini_get('register_globals') == '1')$wrongSettingsTexts[] = _PHP_REGISTER_GLOBALS_ON_OFF;

	if(count($wrongSettingsTexts)) {
		?>
<div style="width: <?php echo $width; ?>;" class="jwarning">
	<h3 style="color:#484848"><?php echo _PHP_SETTINGS_WARNING?>:</h3>
	<ul style="margin: 0px; padding: 0px; padding-left: 15px; list-style: none;" >
				<?php
				foreach($wrongSettingsTexts as $txt) {
					?>
		<li style="font-size: 12px; color: red;"><b><?php echo $txt;?></b></li>
					<?php
				}
				?>
	</ul>
</div>
		<?php
	}
}

//boston, удаление кэша меню панели управления
function js_menu_cache_clear($echo = true) {
	global $my,$mosConfig_secret,$mosConfig_adm_menu_cache;

	if(!$mosConfig_adm_menu_cache) return;

	$groupname = str_replace(' ','_',$my->groupname);
	$menuname = md5($groupname.$mosConfig_secret);
	$file = JPATH_BASE.'/cache/adm_menu_'.$menuname.'.js';
	if(file_exists($file)) {
		if(unlink($file))
			echo $echo ? joost_info(_MENU_CACHE_CLEANED):null;
		else
			echo $echo ? joost_info(_CLEANING_ADMIN_MENU_CACHE):null;
	} else {
		echo $echo ? joost_info(_NO_MENU_ADMIN_CACHE):null;
	}
}

/* вывод информационного поля*/
function joost_info($msg) {
	return '<div class="message">'.$msg.'</div>';
}

function ajax_acl_error(){
	echo json_encode( array('error'=>'acl') );
}

function mosWarning($warning, $title = _MOS_WARNING) {
    $mouseover = 'return overlib(\'' . $warning . '\', CAPTION, \'' . $title . '\', BELOW, RIGHT);';
    $tip = '<a href="javascript: void(0)" onmouseover="' . $mouseover . '" onmouseout="return nd();">';
    $tip .= '<img src="' . JPATH_SITE . '/media/images/warning.png" border="0" alt="' . _WARNING . '"/></a>';
    return $tip;
}

function mosToolTip($tooltip, $title = '', $n='', $image = 'tooltip.png', $text ='', $href = '#') {

    if (!$text) {
        $image = JPATH_SITE . '/media/images/' . $image;
        $text = '<img src="' . $image . '" border="0" alt="tooltip"/>';
    }

    $tip = '<a href="' . $href . '" title="' . $tooltip . '" >' . $text . '</a>';

    return $tip;
}