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

/*
 * Класс формирования представлений
 */

class quickiconsHTML {

	/**
	 * Список объектов
	 * @param JDBmodel $obj - основной объект отображения
	 * @param array $obj_list - список объектов вывода
	 * @param mosPageNav $pagenav - объект постраничной навигации
	 */
	public static function index($obj, $obj_list, $pagenav) {
		// массив названий элементов для отображения в таблице списка
		$fields_list = array('id', 'title', 'category_id', 'state');
		// передаём информацию о объекте и настройки полей в формирование представления
		JoiAdmin::listing($obj, $obj_list, $pagenav, $fields_list);
	}

	/**
	 * Редактирование-создание объекта
	 * @param JDBmodel $articles_obj - объект  редактирования с данными, либо пустой - при создании
	 * @param stdClass $articles_data - свойства объекта
	 */
	public static function edit($articles_obj, $articles_data) {
		// передаём данные в формирование представления
		JoiAdmin::edit($articles_obj, $articles_data);
	}

	public static function module( $gid=8 ){

	 require joosCore::path('quickicons', 'admin_class');

		?><div class="cpicons"><?php
	$query = 'SELECT* FROM #__quickicons WHERE state = 1 AND gid <= ' . $gid . ' ORDER BY ordering';
	$buttons = database::getInstance()->setQuery($query)->loadObjectList();
	foreach ($buttons as $button) {
		self::quickiButton($button);
	}
?>
    <div style="display: block; clear: both; text-align:left; padding-top:10px;">
<?php if (Jacl::isAllowed('quickicons', 'edit')) { ?>
	        <a href="index2.php?option=quickicons">
	            <img border="0" src="<?php echo JPATH_SITE . '/' . JADMIN_BASE . '/templates/' . JTEMPLATE; ?>/images/ico/shortcut.png" alt="<?php echo _CHANGE_QUICK_BUTTONS ?>" /><?php echo _CHANGE_QUICK_BUTTONS ?>
        </a>
<?php } ?>
    </div>
</div><?php
	}


	private static function quickiButton($button) {
		$alt = $button->alt_text ? $button->alt_text : $button->title;
		$icon_web_root =  JPATH_SITE .  Quickicons::get_ico_pach();
?><span><a href="<?php echo $button->href; ?>" title="<?php echo $alt; ?>"><?php
		$icon = '<img src="' . $icon_web_root . $button->icon . '" alt="' . $alt . '" border="0" />';
		echo $icon . $button->title; // значок и текст
?></a></span><?php
	}

}
