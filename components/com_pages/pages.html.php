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

class pagesHTML {

	public static function index( Pages $page ) {
		echo sprintf('<div class="page"><h1>%s</h1></div>', $page->title );
		echo sprintf('<div class="pc">%s</div>',$page->text);

		require_once mosMainFrame::getInstance()->getPath('class','com_tags');
		$tags = new Tags;
		echo $tags->show_tags($page);

		require_once mosMainFrame::getInstance()->getPath('class','com_comments');
		$comments = new Comments;
		echo '<div class="comments">'.$comments->load_comments_tree($page).'</div>';
	}
}