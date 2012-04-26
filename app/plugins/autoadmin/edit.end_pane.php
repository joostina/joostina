<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для закрытия области табов
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
class pluginAutoadminEditEndPane implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {
		$element   = array ();

		$element[] = $tabs->endPane();
		$element[] = $params['tab_wrap_end'];

		return implode( "\n" , $element );
	}

}