<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Tags
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
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

		return array('asd' => crc32('Alanis Morissette - Crazy'));
	}

	/**
	 * Тестирование загрузчика
	 */
	public static function upload() {
		return array();
	}

	/**
	 * Примеры работы с базой данных
	 */
	public static function db() {

		$user_1 = joosDatabase::instance()->set_query('select * from #__users where id=1')->load_result();

		_xdump($user_1);
	}

}
