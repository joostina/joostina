<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
  * Библиотека генерации случайных данных
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @category   modelModules
 * @category   joosModule
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosRandomizer {

	/**
	 * Генерация уникального хеша определённой длины
     * 
	 * @param int $length длина символов хеша
	 * @param string $symbols список символов, разрешённых в хеше
     * 
	 * @return string
	 */
	public static function hash($length = 6, $symbols = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") {

		mt_srand(10000000 * (double) microtime());

		$symbols_length = strlen($symbols) - 1;

		$hash = array();

		for ($i = 0; $i < $length; $i++) {
			$hash[] = $symbols{mt_rand(0, $symbols_length)};
		}

		return implode('', $hash);
	}

}
