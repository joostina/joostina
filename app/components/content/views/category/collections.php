<?php
/**
 *
 * */
defined( '_JOOS_CORE' ) or die();
//_xdump($category_children);

?>

<?php if ( $category->level == 1 ): //Коллекции ?>
<h1><?php echo $category->name ?></h1>
<div class="b-clear"><?php echo $category_details->desc_short ?></div>

<?php foreach ( $category_children as $child ): ?>
	<?php $details = isset( $children_details[$child->id] ) ? $children_details[$child->id] : null;
		$image     = JPATH_SITE_IMAGES . '/' . $details->image . '/thumb.jpg'; ?>
	<div class="b-relative b-50 b-left" style="padding:30px 0 0 0">
		<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $child->slug ) )?>">
			<img width="274" height="383" alt="" src="<?php echo $image ?>">
		</a>

		<div class="<?php echo strtolower( $child->name ) ?>_block">
			<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $child->slug ) )?>">
				<span><?php echo $child->name ?></span>
			</a>
		</div>
		<p style="padding:8px 10px 0 0"><?php echo  $details->desc_short ?></p>
	</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php if ( $category->level == 2 ): //Женские коллекции / Мужские коллекции ?>
<h1><?php echo $category->name ?></h1>
<div class="text_block_bg">
	<?php foreach ( $category_children as $child ): ?>
	<?php $details = isset( $children_details[$child->id] ) ? $children_details[$child->id] : null;
	$image         = JPATH_SITE_IMAGES . '/' . $details->image . '/thumb.jpg'; ?>
	<div class="text_block">
		<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $child->slug ) )?>">
			<img width="180" height="325" alt="" src="<?php echo $image ?>">
		</a>

		<div class="<?php echo strtolower( str_replace( '!' , '' , $child->name ) ) ?>_block">
			<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $child->slug ) )?>">
				<span><?php echo $child->name ?></span>
			</a>
		</div>
		<p><?php echo  $details->desc_short ?></p>
	</div>
	<?php endforeach;?>
</div>
<?php endif; ?>

<?php if ( $category->level == 3 ): //Марка ?>
<h1><?php echo $category->name ?></h1>
<div class="text_block_bg">
	<?php foreach ( $category_children as $child ): ?>
	<?php $details = isset( $children_details[$child->id] ) ? $children_details[$child->id] : null;

	//Выбираем картинки
	$image = $details->image ? JPATH_SITE_IMAGES . '/' . $details->image . '/thumb.jpg' : '';
	/*$attachments = json_decode($details->attachments, true);
if($attachments && isset($attachments['images'])){

	$images = $attachments['images'];
	shuffle($images);
	$first = array_shift($images);

	$image = JPATH_SITE_IMAGES.'/' . $first . '/thumb.jpg';
}*/

	/*if($attachments && isset($attachments['items_images'])){

	$images = $attachments['items_images'];
	shuffle($images);
	$first = array_shift($images);

	$image = JPATH_SITE_IMAGES.'/' . $first . '/thumb.jpg';
}*/

	?>
	<div class="text_block_01">
		<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $child->slug ) )?>">
			<img width="168" alt="" src="<?php echo $image ?>">
		</a>

		<div class="block_reg">
			<a href="<?php echo joosRoute::href( 'category_view' , array ( 'slug' => $child->slug ) )?>"><?php echo $child->name ?></a>
		</div>
	</div>
	<?php endforeach;?>
</div>
<?php echo $pager->output ?>
<?php endif; ?>


<?php if ( $category->level == 4 ): //Тпр-р-рууу. Конечная ?>
<h1><?php echo $category->name ?></h1>
<div class="b-clear"><?php echo $category_details->desc_short ?></div>
<div class="text_block_bg">
	<?php if ( $items ): //Записи ?>
	<?php foreach ( $items as $item ):
		$item->image_path = $item->image;
		?>
		<div class="text_block_01">
			<a href="<?php echo joosRoute::href( 'content_view' , array ( 'slug' => $item->slug ) )?>">
				<?php echo modelContent::get_image( $item ) ?>
			</a>

			<div class="block_name">
				<a href="<?php echo joosRoute::href( 'content_view' , array ( 'slug' => $item->slug ) )?>"><?php echo $item->title?></a>
				<br><?php echo isset( $ef_data[$item->id] ) ? $ef_data[$item->id] : '' ?>
			</div>
		</div>
		<?php endforeach; ?>
	<?php endif;?>
</div>

<?php endif; 