<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// Установка флага родительского файла
define('_VALID_MOS',1);
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR );
// корень файлов
define('JPATH_BASE', dirname(dirname(__FILE__)) );
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(__FILE__) );

require_once (JPATH_BASE.DS.'configuration.php');

// live_site
define('JPATH_SITE', $mosConfig_live_site );

// для совместимости
$mosConfig_absolute_path = JPATH_BASE;
// ядро
require_once (JPATH_BASE .DS. 'includes'.DS.'joostina.php');
// подключаем расширенные административные функции
require_once (JPATH_BASE_ADMIN.DS.'includes'.DS.'admin.php');

$acl = gacl::getInstance( true );

// must start the session before we create the mainframe object
session_name(md5($mosConfig_live_site));
session_start();
// заголовки
header('Content-type: text/html; charset=UTF-8');

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);
$mainframe->set('lang', $mosConfig_lang);
include_once($mainframe->getLangFile());

$act		= strtolower(mosGetParam($_REQUEST,'act',''));
$section	= mosGetParam($_REQUEST,'section','');
$no_html	= intval(mosGetParam($_REQUEST,'no_html',''));
$id			= intval(mosGetParam($_REQUEST,'id',0));
$mosmsg		= strval(strip_tags(mosGetParam($_REQUEST,'mosmsg','')));
$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));

// admin session handling
$my = $mainframe->initSessionAdmin($option,$task);

// start the html output
if($no_html) {
	if($path = $mainframe->getPath('admin')) {
		//Подключаем язык компонента
		if($mainframe->getLangFile($option)) {
			include($mainframe->getLangFile($option));
		}
		require $path;
	}
	exit;
}

initGzip();
?>
<?php echo "<?xml version=\"1.0\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $mosConfig_sitename; ?> - Joostina</title>
		<link rel="stylesheet" href="templates/<?php echo JTEMPLATE; ?>/css/template_css.css" type="text/css" />
		<link rel="stylesheet" href="templates/<?php echo JTEMPLATE; ?>/css/theme.css" type="text/css" />
		<script language="JavaScript" src="../includes/js/JSCookMenu.js" type="text/javascript"></script>
		<script language="JavaScript" src="includes/js/ThemeOffice/theme.js" type="text/javascript"></script>
		<script language="JavaScript" src="../includes/js/joomla.javascript.js" type="text/javascript"></script>
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
		<?php
		$mainframe->set('loadEditor',true);
		include_once (JPATH_BASE . '/includes/editor.php');
		initEditor();
		?>
	</head>
	<body>
		<?php
		if($mosmsg) {
			if(!get_magic_quotes_gpc()) {
				$mosmsg = addslashes($mosmsg);
			}
			echo "\n<script language=\"javascript\" type=\"text/javascript\">alert('$mosmsg');</script>";
		}

// Show list of items to edit or delete or create new
		if($path = $mainframe->getPath('admin')) {
			require $path;
		} else { ?>
		<img src="<?php echo JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE;?>/images/ico/error.png" border="0" alt="Joostina!" />
		<br />
			<?php } ?>
	</body>
</html>
<?php
doGzip();