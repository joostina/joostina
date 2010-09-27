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

require_once  joosCore::path('pages', 'class');

mosMainFrame::addLib('html');
mosMainFrame::addLib('text');
?>
<div class="page page_tags">
    <h5><a href="<?php echo sefRelToAbs('index.php?option=com_tags&task=cloud', true) ?>">Тэги</a></h5>
    <br/>
    <h1 style="clear: both;"><?php echo $tag; ?></h1>

<?php foreach ($tags_results as $row) : ?>
<?php
	$row->href = sefRelToAbs('index.php?option=pages&task=view&id=' . sprintf('%s:%s', $row->id, $row->title));
?>
	<div class="news_item_wrap">
		<div class="news_item">
			<h2><?php echo html::anchor($row->href, $row->title); ?></h2>
			<p><?php echo Text::word_limiter($row->text, 50) ?></p>
		</div>
	</div>
<?php endforeach; ?>

	<?php echo $pager->output; ?>
	<div class="pagination_wrap">
		<div class="paginator" id="paginator"></div>
	</div>

</div>