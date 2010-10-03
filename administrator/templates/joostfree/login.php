<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

$config = Jconfig::getInstance();

Jdocument::getInstance()
		->addCSS(JPATH_SITE . '/' . JADMIN_BASE . '/templates/joostfree/css/admin_login.css')
		->addJS(JPATH_SITE . '/media/js/jquery.js', array('first' => true)) // jquery всегда первое!
		->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.corner.js')
		->addJS(JPATH_SITE . '/' . JADMIN_BASE . '/media/js/administrator.login.js');

// подключение языкого файла tmpl_joostfree.php
if ($mainframe->getLangFile('tmpl_joostfree')) {
	include_once($mainframe->getLangFile('tmpl_joostfree'));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $config->config_sitename; ?> - <?php echo _JOOSTINA_CONTROL_PANEL ?></title>
		<meta http-equiv="Content-Type" content="text/html;  charset=UTF-8" />
		<link rel="shortcut icon" href="<?php echo JPATH_SITE; ?>/media/favicon.ico" />
		<?php echo Jdocument::stylesheet(); ?>
	</head>
	<body>
		<div id="joo">
			<img src="templates/joostfree/images/logo_130.png" alt="Joostina!" />
		</div>
		<?php
		include_once (JPATH_BASE_ADMIN . DS . 'modules' . DS . 'mosmsg' . DS . 'mod_mosmsg.php');
		?>
			<div id="ctr1" align="center">
			<div class="login">
				<div class="login-form">
					<form action="index.php" method="post" name="loginForm" id="loginForm">
						<div class="form-block">
							<?php echo _USERNAME ?>
							<input name="usrname" id="usrname" type="text" class="inputbox" size="15" />
							<?php echo _PASSWORD ?>
							<input name="pass" type="password" class="inputbox" size="15" />
							<div align="center">
								<input type="submit" name="submit" class="button" value="Войти" />
								<br />
								<input type="button" name="submit" onClick="document.location.href='<?php echo JPATH_SITE; ?>'" class="button" value="Перейти на сайт" />
							</div>
						</div>
					</form>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<div id="break"></div>
		<div align="center">
			<noscript><div class="message"><?php echo _PLEASE_ENABLE_JAVASCRIPT ?></div></noscript>
		</div>
		<div id="footer" align="center">
			<div align="center"><?php echo coreVersion::get('URL'); ?></div>
		</div>
		<?php echo Jdocument::javascript(); ?>
	</body>
</html>