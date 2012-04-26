<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Для вывода элемента смены статуса объекта
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
class pluginAutoadminTableStatusChange implements joosAutoadminPluginsTable{

    public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

        $element_param = array_merge_recursive($element_param, array(
                'html_table_element_param' =>
                array(
                    'statuses' =>
                    array(
                        0 => __('Активно'),
                        1 => __('Не активно')
                    ),
                    'images' => array(
                        0 => 'remove',
                        1 => 'ok'
                    )
                ),
            )
        );

        $style = isset($element_param['html_table_element_param']['images'][$value]) ? $element_param['html_table_element_param']['images'][$value] : 'error.png';
        $text = isset($element_param['html_table_element_param']['statuses'][$value]) ? $element_param['html_table_element_param']['statuses'][$value] : 'ERROR';

        return '
		    <button class="btn btn-mini js-tooltip js-set_state" title="' . $text . '" data-id="' . $values->id . '" data-state="'.$values->state.'">
		        <i class="icon-'.$style.'"></i>
		    </button>';

    }

}