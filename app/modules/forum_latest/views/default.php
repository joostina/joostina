<?php
/**
 * Последние сообщения с форума 
 * Шаблон вывода модуля
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

if (!$items) {
	return;
}

joosLoader::lib('text');
?>
<div class="m-forum_latest">
	<h5 class="g-blocktitle_footer"><a class="g-blocktitle_footer_link" target="_blank" href="http://forum.joostina.ru">Новое на форуме Joostina CMS</a></h5>
	<ul class="listreset m-forum_latest_list">
		<?php foreach ($items as $item): ?>
			<li class="m-forum_latest_list_item">
				<a class="m-forum_latest_post" target="_blank" href="<?php echo str_replace('%3D', '=', $item->get_permalink()); ?>">
					<span class="m-forum_latest_post_title"><?php echo $item->get_title() ?></span>
					<span class="m-forum_latest_post_text"><?php echo Text::word_limiter(Text::simple_clean($item->get_description()), 10) ?></span>
				</a>
			</li>	
		<?php endforeach; ?>
	</ul>
</div>