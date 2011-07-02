<?php
/**
 * ByUser - сообщения из блогов по текущему пользователю
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

if (!$items) {
	return;
}

$user = $params['user'];
?>
<h3 class="g-blocktitle_grey"><a class="g-blocktitle_link el-arrow_big"
                                 href="<?php echo joosRoute::href('blog_user', array('username' => $user->username)) ?>">Блог <?php echo $user->username ?>
		<small>(<?php echo count($items) ?>)</small>
	</a></h3>
<ul class="news-list listreset">
	<?php foreach ($items as $item): ?>
		<?php $view_href = joosRoute::href('blog_view', array('id' => $item->id, 'cat_slug' => $item->cat_slug)); ?>
	    <li class="news-list_item">
	        <div class="news-list_item_date"><?php echo joosDateTime::format($item->created_at, '<b>%d.%m</b><small>%Y</small>') ?></div>
	        <a class="news-list_item_link" href="<?php echo $view_href ?>"><?php echo $item->title ?></a>
	    </li>
	<?php endforeach; ?>
</ul>