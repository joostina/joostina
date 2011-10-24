<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Tags
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsTest {

	/**
	 * Метод контроллера, запускаемый по умолчанию
	 *
	 * @static
	 * @return array
	 */
	public static function index() {

		$file = JPATH_BASE.'/159.zip';

		joosFile::delete($file);

		return array ( 'asd' => crc32( 'Alanis Morissette - Crazy' ) );
	}

	/**
	 * Тестирование загрузчика
	 */
	public static function upload(){
		return array();
	}
	
}