<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class jwysiwygEditor {

	public static function init() {

		joosHtml::load_jquery();

		joosDocument::instance()
				->add_css(JPATH_SITE . '/plugins/editors/jwysiwyg/media/css/jquery.wysiwyg.css')
				->add_js_file(JPATH_SITE . '/plugins/editors/jwysiwyg/media/js/jquery.wysiwyg.js');
	}

	public static function display($name, $content, $hiddenField, $width, $height, $col, $row, $params) {

		$toolbar = isset($params['toolbar']) ? $params['toolbar'] : 'complete';
		$config = is_file(__DIR__ . '/toolbars/' . $toolbar . '.php') ? require_once (__DIR__ . '/toolbars/' . $toolbar . '.php') : '';

		$code_on_ready = sprintf("$().ready(function() { $('#%s').wysiwyg(%s); })", $name, $config);
		joosDocument::$data['js_code'][] = $code_on_ready;
		return '<textarea name="' . $hiddenField . '" id="' . $hiddenField . '" cols="' . $col . '" rows="' . $row . '" style="width:' . $width . ';height:' . $height . ';">' . $content . '</textarea>';
	}

	public static function get_content($name, $params = array()) {
		// $('#wysiwyg').val();
		//return isset($params['js_wrap']) ? JjoosHtml::js_code('$(\'#' . $name . '\').elrte("updateSource");') : '$(\'#' . $name . '\').elrte("updateSource");';
	}

}