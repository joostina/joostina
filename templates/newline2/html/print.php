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

$mainframe->addCSS(JPATH_SITE.'/templates/css/print.css');
$mainframe->addJS(JPATH_SITE.'/includes/js/print/print.js');

$pg_link = str_replace(array('&pop=1','&page=0'),'',$_SERVER['REQUEST_URI']);
$pg_link = str_replace('index2.php','index.php',$pg_link);

?>
<div class="logo"><?php echo $mosConfig_sitename; ?></div>
<div id="main"><?php echo $_MOS_OPTION['buffer'];?> </div>
<div id="ju_foo">
	<?php echo _PRINT_PAGE_LINK; ?> :
	<br />
	<i><?php echo sefRelToAbs($pg_link); ?></i>
	<br />
	<br />
	&copy;<?php echo $mosConfig_sitename; ?>,&nbsp;'<?php echo date('Y'); ?>
</div>