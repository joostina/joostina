<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

class elrteEditor {

	public static function init() {

		mosCommonHTML::loadJquery();
		mosCommonHTML::loadJqueryUI(true);

		Jdocument::getInstance()
				->addCSS(JPATH_SITE . '/plugins/editors/elrte/js/ui-themes/base/ui.all.css')
				->addCSS(JPATH_SITE . '/plugins/editors/elrte/css/elrte.full.css')
				->addJS(JPATH_SITE . '/plugins/editors/elrte/js/elrte.min.js')
				->addJS(JPATH_SITE . '/plugins/editors/elrte/js/i18n/elrte.ru.js');
	}

	public static function getEditorArea($name, $content, $hiddenField, $width, $height, $col, $row, $params) {

		/**
		 *  tiny: только кнопки изменения стиля текста (жирный, наклонный, подчеркнутый, перечеркнутый, subscript, superscript)
		 * compact: тоже, что и tiny + сохранить, отмена/повтор, выравнивание, списки, ссылки, полноэкранный режим
		 * normal: compact + копировать/вставить, цвета, отступы, элементы, изображения
		 * complete: normal + форматирование, размер и стиль шрифта
		 * maxi: complete + таблицы
		 */
		$toolbar = isset($params['toolbar']) ? $params['toolbar'] : 'complete';

		return <<< EOD
	<script type="text/javascript" charset="utf-8">
		$().ready(function() {
			var opts = {
				cssClass : 'el-rte',
				lang     : 'ru',
				height   : $height,
				width: $width,
				toolbar  : '$toolbar',
				//cssfiles : ['css/elrte-inner.css']
			}
			$('#$name').elrte(opts);
		})
	</script>
        <textarea name="$hiddenField" id="$hiddenField" cols="$col" rows="$row" style="width:$width;height:$height;">$content</textarea>
EOD;
	}

	public static function getContents($name, $params = array()) {
		return isset($params['js_wrap']) ? JHTML::js_code('$(\'#' . $name . '\').elrte("updateSource");') : '$(\'#' . $name . '\').elrte("updateSource");';
	}

}
