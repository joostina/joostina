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

class noneEditor {
    
    public static function init() {
        return <<< EOD
<script type="text/javascript">
	function insertAtCursor(myField, myValue) {
		if (document.selection) {
			// IE
			myField.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
		} else if (myField.selectionStart || myField.selectionStart == '0') {
			// MOZILLA/NETSCAPE
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			myField.value = myField.value.substring(0, startPos)+ myValue+ myField.value.substring(endPos, myField.value.length);
		} else {
			myField.value += myValue;
		}
	}
</script>
EOD;
    }
    /**
     * Не визуальный редактор - отображение редактора
     * @param string - Название области редактора
     * @param string - Поле содержимого
     * @param string - Название поля формы
     * @param string - Ширина области редактора
     * @param string - Высота области редактора
     * @param int - Число столбцов области редактора
     * @param int - Число строк области редактора
     */
    public static function getEditorArea( $name,$content,$hiddenField,$width,$height,$col,$row ) {

        return <<< EOD
<textarea name="$hiddenField" id="$hiddenField" cols="$col" rows="$row" style="width:$width;height:$height;">$content</textarea>
<br />
EOD;
    }

    /**
     * Не визуальный редактор - копирование содержимого редактора в поле формы
     * @param string - Название области редактора
     * @param string - Название поля формы
     */
    public static function getContents($name,$hiddenField) {

    }
}
