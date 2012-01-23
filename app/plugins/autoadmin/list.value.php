<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * autoadminListValue - расширение joosAutoadmin для прямого вывода значения элемента
 * Базовый плагин
 *
 * @version    1.0
 * @package    Joostina.Plugins
 * @subpackage Plugins
 * @category   joosAutoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminListValue {

	public static function render( joosModel $obj , array $element_param , $key , $value , stdClass $values , $option ) {
		return $value;
	}

}