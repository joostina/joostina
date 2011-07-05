<?php

/**
 * Installer - компонент-установщик расширений
 * AJAX контроллер
 *
 * @version 1.0
 * @package ComponentsAdmin
 * @subpackage Installer
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosAutoadmin::dispatch();

class actionsInstaller {

	public static function index() {
		
	}

	public static function upload() {

		joosLoader::lib('valumsfileuploader', 'files');

		$result = array('success' => true);

		$file = ValumsfileUploader::upload_temp();

		if ($file['basename']) {
			$zip = $file['basename'];

			require_once (JPATH_BASE . '/includes/libraries/joostina/archive/pclzip.lib.php');
			require_once (JPATH_BASE . '/includes/libraries/joostina/archive/pclerror.lib.php');

			$zipfile = new PclZip($zip);

			$ret = $zipfile->extract(PCLZIP_OPT_PATH, $file['dir']);
			if ($ret == 0) {
				$result['message'] = "Ошибка распаковки архива: " . $zipfile->errorName(true);
				$result['success'] = false;
			}

			unlink($zip);

			foreach (glob($file['dir'] . DS . "*.params.php") as $filename) {

				require_once($filename);

				if (isset($extension_install)) {
					$installer = new Installer($extension_install, $file['dir']);
					$result = $installer->run();
				} else {
					$result['message'] = 'Нет установочной информации';
					$result['success'] = false;
				}
			}
		} else {
			$result['message'] = 'Не удалось загрузить архив';
			$result['success'] = false;
		}


		echo json_encode($result);
	}

}