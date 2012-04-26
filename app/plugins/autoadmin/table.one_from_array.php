<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для вывода одного активного значения из массива значений полученных из сторонней функции
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
class pluginAutoadminTableOneFromArray implements joosAutoadminPluginsTable{

	private static $element_datas = array ();

	public static function render( joosModel $obj , array $element_param , $key , $value , stdClass $values , $option ) {
		$datas_for_select = array ();

		// избавления из от множества запросов
		if ( !isset( self::$element_datas[$key] ) ) {
			// сохраняем полученные значения в статичном мессиве
			self::$element_datas[$key] = ( isset( $element_param['html_table_element_param']['call_from'] ) && is_callable( $element_param['html_table_element_param']['call_from'] ) ) ? call_user_func( $element_param['html_table_element_param']['call_from'] ) : $datas_for_select;
		}

		$datas_for_select = self::$element_datas[$key];

		$datas_for_select = isset( $element_param['html_table_element_param']['options'] ) ? $element_param['html_table_element_param']['options'] : $datas_for_select;
		return isset( $datas_for_select[$value] ) ? $datas_for_select[$value] : $value;
	}

}