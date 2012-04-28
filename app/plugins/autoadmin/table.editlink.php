<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();

/**
 * Для вывода значения объекта как ссылки на его редактирование
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
class pluginAutoadminTableEditlink implements joosAutoadminPluginsTable{
    
    public static function render( joosModel $obj , array $element_param , $key , $value , stdClass $values , $option ) {
		return '<a href="index2.php?option=' . $option . ( joosAutoadmin::get_active_model_name() ? '&menu=' . joosAutoadmin::get_active_menu_name() : '' ) . '&task=edit&' . $obj->get_key_field() . '=' . $values->{$obj->get_key_field()} . '">' . $value . '</a>';
	}

}