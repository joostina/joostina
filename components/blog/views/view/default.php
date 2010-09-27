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
?>
<div class="post">

	<div class="post-title"><h1><?php echo $blog->title ?></h1></div>

	<div class="post-date"><?php echo $blog->created_at ?>. Тэги: <?php echo $tags->show_tags($blog) ?></div>

	<div class="post-body">
		<?php echo $blog->fulltext ?>
	</div>

</div>
<?php echo $comments->load_comments_tree($blog) ?>