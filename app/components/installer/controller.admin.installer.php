<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Installer - Компонент установки и обновления расширений
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Installer  
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminInstaller {

	public static function action_before() {
		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/media/js/valumsfileuploader/fileuploader.js')
				->add_js_file(JPATH_SITE . '/administrator/components/installer/media/js/installer.js');
	}

	public static function index($option) {
		echo joosAutoadmin::header('Установка расширений');
		?>
		<div id="installer_wrapper">
			<div id="installer_result"></div>
			<div id="file-uploader"></div>
		</div>
		<?php
	}

}