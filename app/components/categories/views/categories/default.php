<?php
/**
 * Компонент управления контентом
 * View::дерево категорий
 *
 * @version    1.0
 * @package    ComponentsAdmin
 * @subpackage Categories
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    see license.txt
 *
 * */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();
$ico = JPATH_SITE . '/app/components/categories/media/ico/';

?>
<?php if ( !$rootExists ): ?>
<form action="index2.php" method="post">
	<input type="hidden" name="option" value="categories"/>
	<input type="hidden" name="task" value="root_add"/>
	Создать корень<br/>
	<input type="text" size="20" name="name"/>
	<input type="submit" value="Создать"/>
</form>
<?php return;
endif; ?>

<form id="adminForm" method="post" action="index2.php">
	<table class="adminlist">
		<tr>
			<th><?php echo __( 'Название' ) ?></th>
			<th class="b-10">&nbsp;</th>
			<th class="b-10"><?php echo __( 'Уровень вверх' ) ?></th>
			<th class="b-10"><?php echo __( 'Уровень вниз' ) ?></th>
			<th class="b-10"><?php echo __( 'Порядок выше' ) ?></th>
			<th class="b-10"><?php echo __( 'Порядок ниже' ) ?></th>
			<th style="width:40px;">ID</th>
			<th style="width:50px; text-align:center">Состояние</th>
		</tr>
		<?php
		$treeCount = count( $tree );
		for ( $i = 0; $i < $treeCount; $i++ ):
			?>
			<tr>
				<td>
					<?php echo $tree[$i]['level'] == 1 ? '&nbsp;-' : '  ' . str_repeat( '&nbsp;-' , $tree[$i]['level'] ); ?>
					<a href="index2.php?option=categories&amp;task=edit&amp;id=<?php echo $tree[$i]['id'] ?><?php echo $cats->get_link_suff() ?>">
						<?php echo $tree[$i]['name'] ?>
					</a>
				</td>

				<td>
					<a href="index2.php?option=categories&amp;task=node_del&amp;id=<?php echo $tree[$i]['id'] ?><?php echo $cats->get_link_suff() ?>">
						<img src="<?php echo joosHtml::ico( 'trashcan_empty' ); ?>" alt="Удалить"
						     title="Удалить узел"/></a>

					<?php if ( $tree[$i]['childs'] > 0 ): ?>
					<a href="index2.php?option=categories&amp;task=node_del&amp;id=<?php echo $tree[$i]['id'] ?>&amp;del_childs=yes<?php echo $cats->get_link_suff() ?>">
						<img src="<?php echo joosHtml::ico( 'trashcan_full' ) ?>" alt="Удалить вместе с потомками"
						     title="Удалить узел вместе с потомками"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] > 1 ): ?>
					<a href="index2.php?option=categories&amp;task=node_move_up&amp;id=<?php echo $tree[$i]['id'] ?><?php echo $cats->get_link_suff() ?>">
						<img src="<?php echo joosHtml::ico( 'stock_up' ) ?>" alt="Узел на уровень ВВЕРХ"
						     title="Узел на уровень ВВЕРХ"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] != 1 && $tree[$i]['upper'] > 0 ): ?>
					<a href="index2.php?option=categories&amp;task=node_move_down&amp;id=<?php echo $tree[$i]['id'] ?><?php echo $cats->get_link_suff() ?>">
						<img src="<?php echo joosHtml::ico( 'stock_down' ) ?>" alt="Узел на уровень ВНИЗ"
						     title="Узел на уровень ВНИЗ"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] != 0 && $tree[$i]['upper'] > 0 && $i > 0 ): ?>
					<a href="index2.php?option=categories&amp;task=node_move_lft&amp;id=<?php echo $tree[$i]['id'] ?><?php echo $cats->get_link_suff() ?>">
						<img src="<?php echo joosHtml::ico( 'stock_left' ) ?>" alt="Порядок вверх"
						     title="Порядок вверх"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] != 0 && $tree[$i]['lower'] > 0 ): ?>
					<a href="index2.php?option=categories&amp;task=node_move_rgt&amp;id=<?php echo $tree[$i]['id'] ?><?php echo $cats->get_link_suff() ?>">
						<img src="<?php echo joosHtml::ico( 'stock_right' )  ?>" alt="Порядок вниз"
						     title="Порядок вниз"/></a>
					<?php endif; ?>
				</td>
				<td><?php echo $tree[$i]['id'] ?></td>

				<td class="td-state-joiadmin">
					<?php
					if ( $tree[$i]['state'] == 1 ) {
						echo $state_img = '<img alt="Опубликовано" obj_key="state" obj_id="' . $tree[$i]['id'] . '" id="img-pub-' . $tree[$i]['id'] . '" src="../media/images/admin/publish_g.png" class="img-mini-state">';
					} else {
						echo $state_img = '<img alt="Скрыто" obj_key="state" obj_id="' . $tree[$i]['id'] . '" id="img-pub-' . $tree[$i]['id'] . '" src="../media/images/admin/publish_x.png" class="img-mini-state">';
					}

					?>

				</td>
			</tr>
			<?php endfor ?>
	</table>

	<?php
	echo forms::hidden( 'option' , 'categories' );
	echo forms::hidden( 'model' , 'modelCategories' );
	echo forms::hidden( 'task' , '' );
	echo forms::hidden( 'group' , $cats->group );
	echo forms::hidden( 'obj_name' , 'categories' );
	echo forms::hidden( joosCSRF::get_code() , 1 );
	?>
</form>