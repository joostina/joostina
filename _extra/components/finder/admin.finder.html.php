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

class elFinder {

	/**
	 * Вывод файлового менеджера elFinder
	 * @param string $elfinder_config - конфигурация клиента elFinder
	 */
	public static function index($elfinder_config) {
		echo adminHTML::controller_header(_COM_FILES, 'config');

		$code = <<<EOD
	$().ready(function() {
		        var finder_options = $elfinder_config;
		        $('#finder').elfinder( finder_options );
		    });
EOD;

		joosDocument::$data['footer'][] = '<script language="JavaScript" type="text/javascript">' . $code . '</script>';
?><div id="finder"></div><?php
	}

}
