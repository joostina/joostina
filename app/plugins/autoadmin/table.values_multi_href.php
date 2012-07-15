<?php

// запрет прямого доступа
defined('_JOOS_CORE') or exit();

/**
 * Для вывода строки значений из нескольких элементов объекта в виде ссылки на редактирование этого объекта
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
class pluginAutoadminTableValuesMultiHref implements joosAutoadminPluginsTable
{
    public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option)
    {
        if (!isset($element_param['html_table_element_param']['format'])) {
            throw new joosException('Для поля не указана строка форматирования вывода format');
        }

        $format = $element_param['html_table_element_param']['format'];

        $href_title = strtr($format, (array) $values);

        return '<a href="index2.php?option=' . $option . ( joosAutoadmin::get_active_model_name() ? '&model=' . joosAutoadmin::get_active_model_name() : '' ) . '&task=edit&' . $obj->get_key_field() . '=' . $values->{$obj->get_key_field()} . '">' . $href_title . '</a>';
    }

}
