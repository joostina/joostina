<?php

/**
 * Installer - компонент-установщик расширений
 * AJAX контроллер
 *
 * @version    1.0
 * @package    ComponentsAdmin
 * @subpackage Installer
 * @author     JoostinaTeam
 * @copyright  (C) 2008-2010 Joostina Team
 * @license    see license.txt
 *
 * */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

class actionsAjaxAdminInstaller {

	/**
	 * Загрузка архива расширения
	 *
	 * @static
	 *
	 */
	public static function upload() {

		joosLoader::lib( 'valumsfileuploader' , 'files' );

		$result = array ( 'success' => true );

		$file   = ValumsfileUploader::upload_temp();

		if ( $file['basename'] ) {
			$zip = $file['basename'];

			$ret     = joosArhive::extract( $zip, $file['dir'] );
			if ( $ret == 0 ) {
				$result['message'] = __("Ошибка распаковки архива");
				$result['success'] = false;
			}

			joosFile::delete($zip);

			foreach ( glob( $file['dir'] . DS . "*.params.php" ) as $filename ) {

				require_once( $filename );

				if ( isset( $extension_install ) ) {
					$installer = new Installer( $extension_install , $file['dir'] );
					$result    = $installer->run();
				} else {
					$result['message'] = __('Нет установочной информации');
					$result['success'] = false;
				}
			}
		} else {
			$result['message'] = __('Не удалось загрузить архив');
			$result['success'] = false;
		}


		echo json_encode( $result );
	}

}