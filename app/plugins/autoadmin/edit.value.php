<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для прямого вывода значения элемента
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
class pluginAutoadminEditValue implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {
		$element   = array ();

		$element[] = $params['label_begin'];
		$element[] = joosHtml::label( array ( 'for' => $key ) , $element_param['name'] );
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] =  '<p>'.$value.'</p>';
		$element[] = $params['el_end'];

		return implode( "\n" , $element );
	}

}