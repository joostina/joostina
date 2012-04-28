<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();

/**
 * Для вывода заголовка в H3
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage Autoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class pluginAutoadminEditH3 implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params ) {
		$element   = array ();

		$element[] = $params['label_begin'];
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = '<h3>' . $element_param['name'] . '</h3>';
		$element[] = $params['el_end'];

		return implode( "\n" , $element );
	}

}