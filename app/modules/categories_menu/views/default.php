<?php
/**

 *
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

$current_category = $object_data['category']->id;

array_shift( $items );

?>
<ul>
	<?php foreach ( $items as $id => $item ) : ?>
	<?php
	$href            = joosRoute::href( 'category_view' , array ( 'slug' => $item['slug'] ) );
	$active          = ( $item['id'] == $current_category ) ? true : false;
	$active_but_link = $active && joosController::$task != 'category' ? true : false;
	?>
	<li class="<?php echo $active ? ' active ' : ''; ?>parent-<?php echo $item['parent_id'] ?> level-<?php echo $item['level'] ?>">
		<?php if ( $active ): ?>
		<?php if ( $active_but_link ): ?>
			<span><a href="<?php echo $href ?>"><?php echo $item['name'] ?></a></span>
			<?php else: ?>
			<span><?php echo $item['name'] ?></span>
			<?php endif; ?>
		<?php else: ?>
		<a href="<?php echo $href ?>"><?php echo $item['name'] ?></a>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>
