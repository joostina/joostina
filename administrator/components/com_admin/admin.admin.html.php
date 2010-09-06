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

class HTML_admin_misc {

	public static function controlPanel() {
		global $mainframe;
		$path = JPATH_BASE_ADMIN.'/templates/'.JTEMPLATE.'/html/cpanel.php';
		if(file_exists($path)) {
			require $path;
		} else {
			echo '<br />';
			mosLoadAdminModules('cpanel',1);
		}
	}

	public static function get_php_setting($val,$colour = 0,$yn = 1) {
		$r = (ini_get($val) == '1'?1:0);

		if($colour) {
			if($yn) {
				$r = $r?'<span style="color: green;">ON</span>':'<span style="color: red;">OFF</span>';
			} else {
				$r = $r?'<span style="color: red;">ON</span>':'<span style="color: green;">OFF</span>';
			}

			return $r;
		} else {
			return $r?'ON':'OFF';
		}
	}

	public static function get_server_software() {
		if(isset($_SERVER['SERVER_SOFTWARE'])) {
			return $_SERVER['SERVER_SOFTWARE'];
		} else
		if(($sf = phpversion() <= '4.2.1'?getenv('SERVER_SOFTWARE'):$_SERVER['SERVER_SOFTWARE'])) {
			return $sf;
		} else {
			return 'n/a';
		}
	}

	public static function system_info($version) {
		global $mosConfig_cachepath;

		$mainframe = mosMainFrame::getInstance();
		$database = $mainframe->getDBO();

		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';

		$width = 400;

		mosMainFrame::addClass('mosTabs');
		$tabs = new mosTabs(0);
		?>
<table class="adminheading">
	<tr>
		<th class="info"><?php echo _INFO?></th>
	</tr>
</table>
		<?php
		$tabs->startPane("sysinfo");
		$tabs->startTab(_ABOUT_SYSTEM,"system-page");
		?>
<table class="adminform">
	<tr>
		<td colspan="2"><h2><?php echo coreVersion::$CMS.' '.coreVersion::$CMS_ver.'.'.coreVersion::$RELDATE.' '.coreVersion::$RELTIME?></h2><br /><?php echo coreVersion::$SUPPORT; ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php josSecurityCheck();?></td>
	</tr>
	<tr>
		<td valign="top" width="250"><strong><?php echo _SYSTEM_OS?>:</strong></td>
		<td><?php echo php_uname(); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo _DB_VERSION?>:</strong></td>
		<td><?php echo $database->getUtils()->getVersion(); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo _PHP_VERSION?>:</strong></td>
		<td><?php echo phpversion(); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo _APACHE_VERSION?>:</strong></td>
		<td><?php echo HTML_admin_misc::get_server_software(); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo _PHP_APACHE_INTERFACE?>:</strong></td>
		<td><?php echo php_sapi_name(); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo _BROWSER?>:</strong></td>
		<td><?php echo phpversion() <= '4.2.1'?getenv('HTTP_USER_AGENT'):$_SERVER['HTTP_USER_AGENT']; ?></td>
	</tr>
	<tr>
		<td colspan="2" style="height: 10px;">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">
			<strong><?php echo _PHP_SETTINGS?>:</strong>
		</td>
		<td>
			<table cellspacing="1" cellpadding="1" border="0">
				<tr>
					<td><?php echo _REGISTER_GLOBALS?>:</td>
					<td style="font-weight: bold;"><?php echo HTML_admin_misc::get_php_setting('register_globals',1,0); ?></td>
					<td>
								<?php $img = ((ini_get('register_globals'))?'publish_x.png':'tick.png'); ?>
						<img src="<?php echo $cur_file_icons_path;?>/<?php echo $img; ?>" />
					</td>
				</tr>
				<tr>
					<td><?php echo _MAGIC_QUOTES?>:</td>
					<td style="font-weight: bold;">
								<?php echo HTML_admin_misc::get_php_setting('magic_quotes_gpc',1,1); ?>
					</td>
					<td>
								<?php $img = (!(ini_get('magic_quotes_gpc'))?'publish_x.png':'tick.png'); ?>
						<img src="<?php echo $cur_file_icons_path;?>/<?php echo $img; ?>" />
					</td>
				</tr>
				<tr>
					<td><?php echo _SAFE_MODE?>:</td>
					<td style="font-weight: bold;">
								<?php echo HTML_admin_misc::get_php_setting('safe_mode',1,0); ?>
					</td>
					<td>
								<?php $img = ((ini_get('safe_mode'))?'publish_x.png':'tick.png'); ?>
						<img src="<?php echo $cur_file_icons_path;?>/<?php echo $img; ?>" />
					</td>
				</tr>
				<tr>
					<td><?php echo _FILE_UPLOAD?>:</td>
					<td style="font-weight: bold;">
								<?php echo HTML_admin_misc::get_php_setting('file_uploads',1,1); ?>
					</td>
					<td>
								<?php $img = ((!ini_get('file_uploads'))?'publish_x.png':'tick.png'); ?>
						<img src="<?php echo $cur_file_icons_path;?>/<?php echo $img; ?>" />
					</td>
				</tr>
				<tr>
					<td><?php echo _SESSION_HANDLING?>:</td>
					<td style="font-weight: bold;">
								<?php echo HTML_admin_misc::get_php_setting('session.auto_start',1,0); ?>
					</td>
					<td>
								<?php $img = ((ini_get('session.auto_start'))?'publish_x.png':'tick.png'); ?>
						<img src="<?php echo $cur_file_icons_path;?>/<?php echo $img; ?>" />
					</td>
				</tr>
				<tr>
					<td><?php echo _SESS_SAVE_PATH?>:</td>
					<td style="font-weight: bold;" colspan="2">
								<?php echo (($sp = ini_get('session.save_path'))?$sp:'none'); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo _PHP_TAGS?>:</td>
					<td style="font-weight: bold;">
								<?php echo HTML_admin_misc::get_php_setting('short_open_tag'); ?>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td><?php echo _BUFFERING?>:</td>
					<td style="font-weight: bold;">
								<?php echo HTML_admin_misc::get_php_setting('output_buffering'); ?>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td><?php echo _OPEN_BASEDIR?>:</td>
					<td style="font-weight: bold;" colspan="2">
								<?php echo (($ob = ini_get('open_basedir'))?$ob:'none'); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo _ERROR_REPORTING?>:</td>
					<td style="font-weight: bold;" colspan="2">
								<?php echo HTML_admin_misc::get_php_setting('display_errors'); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo _XML_SUPPORT?>:</td>
					<td style="font-weight: bold;" colspan="2">
								<?php echo extension_loaded('xml')?'Yes':'No'; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo _ZLIB_SUPPORT?>:</td>
					<td style="font-weight: bold;" colspan="2">
								<?php echo extension_loaded('zlib')?'Yes':'No'; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo _DISABLED_FUNCTIONS?>:</td>
					<td style="font-weight: bold;" colspan="2">
								<?php echo (($df = ini_get('disable_functions'))?$df:'none'); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height: 10px;">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top"><strong><?php echo _CONFIGURATION_FILE?>:</strong></td>
		<td>
					<?php
					$cf = file(JPATH_BASE.'/configuration.php');
					foreach($cf as $k => $v) {
						if(preg_match('/mosConfig_host/i',$v)) {
							$cf[$k] = '$mosConfig_host = \'xxxxxx\'';
						} elseif(preg_match('/mosConfig_user/i',$v)) {
							$cf[$k] = '$mosConfig_user = \'xxxxxx\'';
						} elseif(preg_match('/mosConfig_password/i',$v)) {
							$cf[$k] = '$mosConfig_password = \'xxxxxx\'';
						} elseif(preg_match('/mosConfig_db /i',$v)) {
							$cf[$k] = '$mosConfig_db = \'xxxxxx\'';
						} elseif(preg_match('/mosConfig_smtppass /i',$v)) {
							$cf[$k] = '$mosConfig_smtppass = \'xxxxxx\'';
						}
					}
					foreach($cf as $k => $v) {
						$k = htmlspecialchars($k);
						$v = htmlspecialchars($v);
						$cf[$k] = $v;
					}
					echo implode("<br />",$cf);
					?>
		</td>
	</tr>
</table>
		<?php
		$tabs->endTab();
		$tabs->startTab("PHP Info","php-page");
		?>
<table class="adminform">
	<tr>
		<td>
					<?php
					ob_start();
					phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
					$phpinfo = ob_get_contents();
					ob_end_clean();
					preg_match_all('#<body[^>]*>(.*)</body>#siU',$phpinfo,$output);
					$output = preg_replace('#<table#','<table class="adminlist" align="center"',$output[1][0]);
					$output = preg_replace('#(\w),(\w)#','\1, \2',$output);
					$output = preg_replace('#border="0" cellpadding="3" width="600"#','border="0" cellspacing="1" cellpadding="4" width="95%"',$output);
					$output = preg_replace('#<hr />#','',$output);
					echo $output;
					?>
		</td>
	</tr>
</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(_ACCESS_RIGHTS,'perms');
		?>
<table class="adminform">
	<tr>
		<td>
			<strong><?php echo _DIRS_WITH_RIGHTS?>:</strong><br />
					<?php
					$sp = ini_get('session.save_path');

					mosHTML::writableCell(JADMIN_BASE.'/backups');
					mosHTML::writableCell(JADMIN_BASE.'/components');
					mosHTML::writableCell(JADMIN_BASE.'/modules');
					mosHTML::writableCell(JADMIN_BASE.'/templates');
					mosHTML::writableCell('components');
					mosHTML::writableCell('images');
					mosHTML::writableCell('language');
					mosHTML::writableCell('plugins');
					mosHTML::writableCell('media');
					mosHTML::writableCell('modules');
					mosHTML::writableCell('templates');
					mosHTML::writableCell($mosConfig_cachepath,0,'<strong>'._CACHE_DIR.'</strong> ');
					mosHTML::writableCell($sp,0,'<strong>'._SESSION_DIRECTORY.'</strong> ');
					?>
		</td>
	</tr>
</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(_DATABASE,'db');
		?>
<table class="adminform">
	<tr>
		<th><?php echo _TABLE_NAME?>:</th>
		<th><?php echo _DB_CHARSET?>:</th>
		<th><?php echo _DB_NUM_RECORDS?>:</th>
		<th><?php echo _DB_SIZE?>:</th>
	</tr>
			<?php
			$db_info = HTML_admin_misc::db_info();
			$k = 0;
			foreach($db_info as $table) {
				if($table->Collation != 'utf8_general_ci') $table->Collation ='<font color="red"><b>'.$table->Collation.'</b></font>';
				echo '<tr class="row'.$k.'"><td><b>'.$table->Name.'</b></td><td>'.$table->Collation.'</td><td>'.$table->Rows.'</td><td>'.$table->Data_length.'</td></tr>';
				$k = 1 - $k;
			}
			?>

</table>
		<?php
		$tabs->endTab();
		$tabs->endPane();
		?>
		<?php
	}
	// получение информации о базе данных
	public static function db_info() {
		$sql = 'SHOW TABLE STATUS FROM '.Jconfig::getInstance()->config_db;
		return database::getInstance()->setQuery($sql)->loadObjectList();
	}

	public static function ListComponents() {
		global $database;

		$query = "SELECT params FROM #__modules WHERE module = 'mod_components'";
		$database->setQuery($query);
		$row = $database->loadResult();
		$params = new mosParameters($row);

		mosLoadAdminModule('components',$params);
	}

	public static function preview($tp = 0) {
		$tp = intval($tp);
		?>
<style type="text/css">
	.previewFrame {
		border: none;
		width: 95%;
		height: 600px;
		padding: 0px 5px 0px 10px;
	}
</style>
<table class="adminform">
	<tr>
		<th width="50%" class="title"><?php echo _PREVIEW_SITE?></th>
		<th width="50%" style="text-align:right">
			<a href="<?php echo JPATH_SITE.'/index.php?tp='.$tp; ?>" target="_blank"><?php echo _IN_NEW_WINDOW?></a>
		</th>
	</tr>
	<tr>
		<td width="100%" valign="top" colspan="2">
			<iframe name="previewFrame" src="<?php echo JPATH_SITE.'/index.php?tp='.$tp; ?>" class="previewFrame" ></iframe>
		</td>
	</tr>
</table>
		<?php
	}

	public static function changelog() {
		?><pre><?php readfile(JPATH_BASE.'/changeslog'); ?></pre><?php
	}
}

function getHelpTOC($helpsearch) {
	$helpurl = strval(mosGetParam($GLOBALS,'mosConfig_helpurl',''));
	$files = mosReadDirectory(JPATH_BASE.'/help/','\.xml$|\.html$');

	require_once (JPATH_BASE.'/includes/domit/xml_domit_lite_include.php');

	$toc = array();
	foreach($files as $file) {
		$buffer = file_get_contents(JPATH_BASE.'/help/'.$file);
		if(preg_match('#<title>(.*?)</title>#',$buffer,$m)) {
			$title = trim($m[1]);
			if($title) {
				if($helpurl) {
					// strip the extension
					$file = preg_replace('#\.xml$|\.html$#','',$file);
				}
				if($helpsearch) {
					if(strpos(strip_tags($buffer),$helpsearch) !== false) {
						$toc[$file] = $title;
					}
				} else {
					$toc[$file] = $title;
				}
			}
		}
	}
	asort($toc);
	return $toc;
}