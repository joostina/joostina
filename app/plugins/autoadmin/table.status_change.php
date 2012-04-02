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
class autoadminTableStatusChange extends joosAutoadminPlugins{

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		$element_param = array_merge_recursive($element_param, array(
			'html_table_element_param' =>
			array('statuses' =>
				array(
					0 => __('Скрыто'),
					1 => __('Опубликовано')
				),
				'images' => array(
					0 => 'publish_x.png',
					1 => 'publish_g.png'
				)
			)
				)
		);

		$images = isset($element_param['html_table_element_param']['images'][$value]) ? $element_param['html_table_element_param']['images'][$value] : 'error.png';
		$text = isset($element_param['html_table_element_param']['statuses'][$value]) ? $element_param['html_table_element_param']['statuses'][$value] : 'ERROR';

		return '<img class="img-mini-state" src="' . joosConfig::get('admin_icons_path') . $images . '" id="img-pub-' . $values->id . '" data-obj-id="' . $values->id . '" data-obj-key="' . $key . '" alt="' . $text . '" />';
	}

}