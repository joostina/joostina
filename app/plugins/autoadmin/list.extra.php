<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для вывода значения через вызов сторонней функции
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage joosAutoadmin
 * @category   joosAutoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminListExtra extends joosAutoadminPlugins{

	private static $datas_for_select = array ();

	public static function render( joosModel $obj , array $element_param , $key , $value , stdClass $values , $option ) {
		return ( isset( $element_param['html_table_element_param']['call_from'] ) && is_callable( $element_param['html_table_element_param']['call_from'] ) ) ? call_user_func( $element_param['html_table_element_param']['call_from'] , $values, $value ) : self::$datas_for_select;
	}

}