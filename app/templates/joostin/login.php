<?php
/**
 * @JoostFREE
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosDocument::instance()
		->add_css(JPATH_SITE . '/app/templates/joostin/media/css/admin_login.css')
		->add_js_file(JPATH_SITE . '/media/js/jquery.js', array('first' => true)) // jquery всегда первое!
		->add_js_file(JPATH_SITE . '/media/js/administrator.login.js');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo __('Панель управления Joostina CMS') ?></title>
		<meta http-equiv="Content-Type" content="text/html;  charset=UTF-8"/>
		<link rel="shortcut icon" href="<?php echo JPATH_SITE; ?>/media/favicon.ico"/>
		<?php echo joosDocument::stylesheet(); ?>
	</head>
	<body>
		<?php joosModuleAdmin::load_by_name('flash_message') ?>
		<div class="login">
			<form action="<?php echo JPATH_SITE_ADMIN ?>/index.php" method="post" name="loginForm" id="loginForm">
				<div class="form-block">

					<div class="f">
						<label>Логин</label>
						<span class="i-wrap">
							<input name="user_name" id="user_name" type="text" class="inputbox" size="39"/>
						</span>
					</div>

					<div class="f">
						<label><?php echo __('Пароль') ?></label>
						<span class="i-wrap">
							<input name="password" type="password" class="inputbox" size="22"/>
							<a href="#">Забыли пароль?</a>
						</span>
					</div>

					<div class="f">
						<input type="submit" name="submit" class="button" value="Войти"/>
						<a style="float:  right; margin: 8px 38px 0 0;" href="<?php echo JPATH_SITE; ?>"><?php echo __('Перейти на сайт') ?></a>
					</div>

				</div>
				<input type="hidden" value="1" name="<?php echo joosCSRF::get_code('admin_login') ?>"/>
			</form>
		</div>

		<div align="center">
			<noscript>
				<div class="message"><?php echo __('Включите JavaScript') ?></div>
			</noscript>
		</div>

		<div id="footer"><?php echo joosVersion::get('URL'); ?></div>
		<?php echo joosDocument::javascript(); ?>
	</body>
</html>