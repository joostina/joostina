<?php
/**
 * Блоги
 * Шаблон вывода модуля
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<div class="m-blogs">
    <h3 class="m-blogs_title">Новое в <a class="m-blogs_title_link" href="<?php echo joosRoute::href('blog') ?>" title="Перейти в блог">блоге</a></h3>

    <ul class="listreset m-blogs_list">
		<?php foreach ($items as $item): ?>
			<?php $view_href = joosRoute::href('blog_view', array('id' => $item->id, 'cat_slug' => $item->cat_slug)); ?>
			<?php $user_href = joosRoute::href('user_view', array('id' => (int) $item->userid, 'username' => $item->username)); ?>
			<li class="m-blogs_list_item">
				<h4 class="m-blogs_item_title"><a class="g-black_link"
												  href="<?php echo $view_href ?>"><?php echo $item->title ?></a></h4>
				<span class="date"><?php echo $item->created_at ?></span>
				<a class="el-username" href="<?php echo $user_href ?>"><?php echo $item->username ?></a>
			</li>
		<?php endforeach; ?>

    </ul>
</div>
