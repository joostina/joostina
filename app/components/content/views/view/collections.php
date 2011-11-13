<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die(); ?>

<?php

//_xdump($dop_photos);
?>


<h3><?php echo $category->name ?></h3>
<h1><?php echo $item->title?></h1>

<div class="model_block">
	<?php $item->image_path = $item->image;?>
	<!--<a id="model_color" rel="colors_img" class="__lightbox" href="<?php echo JPATH_SITE_IMAGES . '/' . $item->image . '/big.jpg' ?>">
				</a>-->
	<?php echo modelContent::get_image( $item , 'medium' ) ?>

	<?php if ( $ef ): ?>
	<?php foreach ( $ef['rules'] as $f_id => $f ): ?>
		<?php echo ( isset( $ef['values'][$f_id] ) && $ef['values'][$f_id] ) ? '<p>' . $f['name'] . ': ' . $ef['values'][$f_id] . '</p>' : '' ?>
		<?php endforeach; ?>
	<?php endif;?>
	<br>

	<p><?php echo $item->fulltext?></p>

	<div class="cl"></div>
</div>
<div class="big_block">

	<?php if ( $other_items ): ?>
	<?php foreach ( $other_items as $_item ): ?>
		<?php   $_item->image_path = $_item->image;
		$active                    = $_item->id == $item->id ? 'class="active"' : '';
		?>
		<div class="min_block">
			<?php echo modelContent::get_image( $_item , 'thumb' , array ( 'width' => 102 ) ) ?>
			<div class="min_bg">

				<a href="<?php echo joosRoute::href( 'content_view' , array ( 'slug' => $_item->slug ) )?>" <?php echo $active?>>
					<span><?php echo $_item->title?></span>
				</a>
			</div>
		</div>
		<?php endforeach; ?>
	<?php endif;?>

	<div class="cl"></div>
</div>
<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $category->slug ) )?>">Посмотреть всю
	коллекцию</a>
