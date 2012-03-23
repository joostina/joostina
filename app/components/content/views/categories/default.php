<?php
/**
 * Компонент управления контентом
 * View::дерево категорий
 *
 * @version    1.0
 * @package    ComponentsAdmin
 * @subpackage Content
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    see license.txt
 *
 * */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

$ico = JPATH_SITE_ADMIN . '/components/content/media/ico/' ?>


<?php if ( !$rootExists ): ?>
<form action="index2.php" method="post">
	<input type="hidden" name="option" value="content"/>
	<input type="hidden" name="task" value="root_add"/>
	Создать корень<br/>
	<input type="text" size="20" name="name"/>
	<input type="submit" value="Создать"/>
</form>
<?php return; endif; ?>

<form id="adminForm" method="post" action="index2.php">
	<table class="adminlist">
		<tr>

			<th>Название</th>
			<th class="b-10">&nbsp;</th>
			<th class="b-10">Уровень ВВЕРХ</th>
			<th class="b-10">Уровень ВНИЗ</th>
			<th class="b-10">Порядок выше</th>
			<th class="b-10">Порядок ниже</th>
			<th style="width:40px;">ID</th>
			<!--
<th style="width:40px;">Lower</th>
<th style="width:40px;">Upper</th>
<th style="width:40px;">Childs</th>
<th style="width:40px;">lft</th>
<th style="width:40px;">rgt</th>
-->
		</tr>

		<?php
		$treeCount = count( $tree ) + 1;
		for ( $i = 2; $i < $treeCount; $i++ ) {
			?>
			<tr>
				<td>
					<?php echo $tree[$i]['level'] == 1 ? '' : str_repeat( '|&ndash;&nbsp;' , $tree[$i]['level'] - 1 ); ?>
					<a href="index2.php?option=content&amp;task=edit_category&amp;id=<?php echo $tree[$i]['id'] ?>"><?php echo $tree[$i]['name'] ?></a>
				</td>


				<td>
					<a href="index2.php?option=content&amp;task=node_del&amp;id=<?php echo $tree[$i]['id'] ?>">
						<img src="<?php echo $ico ?>/del.gif" alt="Удалить" title="Удалить узел"/></a>

					<?php if ( $tree[$i]['childs'] > 0 ): ?>
					<a href="index2.php?option=content&amp;task=node_del&amp;id=<?php echo $tree[$i]['id'] ?>&amp;del_childs=yes">
						<img src="<?php echo $ico ?>/delch.gif" alt="Удалить вместе с потомками"
						     title="Удалить узел вместе с потомками"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] > 2 ): ?>
					<a href="index2.php?option=content&amp;task=node_move_up&amp;id=<?php echo $tree[$i]['id'] ?>">
						<img src="<?php echo $ico ?>/up.gif" alt="Узел на уровень ВВЕРХ" title="Узел на уровень ВВЕРХ"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] != 1 && $tree[$i]['upper'] > 0 ): ?>
					<a href="index2.php?option=content&amp;task=node_move_down&amp;id=<?php echo $tree[$i]['id'] ?>">
						<img src="<?php echo $ico ?>/down.gif" alt="Узел на уровень ВНИЗ" title="Узел на уровень ВНИЗ"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] != 1 && $tree[$i]['upper'] > 0 ): ?>
					<a href="index2.php?option=content&amp;task=node_move_lft&amp;id=<?php echo $tree[$i]['id'] ?>">
						<img src="<?php echo $ico ?>/lft.gif" alt="Порядок вверх" title="Порядок вверх"/></a>
					<?php endif; ?>
				</td>

				<td class="b-center">
					<?php if ( $tree[$i]['level'] != 1 && $tree[$i]['lower'] > 0 ): ?>
					<a href="index2.php?option=content&amp;task=node_move_rgt&amp;id=<?php echo $tree[$i]['id'] ?>">
						<img src="<?php echo $ico ?>/rgt.gif" alt="Порядок вниз" title="Порядок вниз"/></a>
					<?php endif; ?>
				</td>

				<td><?php echo $tree[$i]['id'] ?></td>

				<!--
		        <td class="td_right"><?= $tree[$i]['level'] ?></td>
		        <td class="td_right"><?= $tree[$i]['lower'] ?></td>
		        <td class="td_right"><?= $tree[$i]['upper'] ?></td>
		        <td class="td_right"><?= $tree[$i]['childs'] ?></td>
		        <td class="td_right"><?= $tree[$i]['lft'] ?></td>
		        <td class="td_right"><?= $tree[$i]['rgt'] ?></td>
				-->
			</tr>
			<?php } ?>
	</table>

	<?php
	echo forms::hidden( 'option' , 'content' );
	echo forms::hidden( 'model' , 'ContentCategories' );
	echo forms::hidden( 'task' , '' );
	echo forms::hidden( joosCSRF::get_code() , 1 );
	?>
</form>