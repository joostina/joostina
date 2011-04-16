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

class actionsAdminInstaller {

	public static function on_start() {
		joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/valumsfileuploader/fileuploader.js');
		joosDocument::instance()->add_js_file(JPATH_SITE . '/administrator/components/installer/media/js/installer.js');
	}

	public static function index($option) {
		echo joosAutoAdmin::header('Установка расширений');
		?>
		<div id="installer_wrapper">
			<div id="installer_result"></div>
			<div id="file-uploader"></div>
		</div>
		<?php
	}

}