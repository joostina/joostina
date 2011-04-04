<?php
/**
 * News - компонент новостей
 * Представление (шаблон вывода): Список новостей
 *
 * */
defined('_JOOS_CORE') or die();

?>

	<?php foreach ($cats as $cat) : ?>
		<?php $href = joosRoute::href('category_view', array('id' => $cat->id, 'slug' => $cat->slug)); ?>
		<a class="title_item_link" href="<?php echo $href ?>"><?php echo $cat->name ?></a>
		<br/>
	<?php endforeach; ?>
