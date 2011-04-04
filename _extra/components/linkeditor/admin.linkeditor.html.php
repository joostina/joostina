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

class HTML_linkeditor {

	public static function viewall(&$rows,$pageNav) {
		$cur_file_icons_path = joosConfig::get('admin_icons_path');
		
		?>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th class="linkeditor"><?php echo _COMPONENTS_MENU_EDITOR?></th>
		</tr>
	</table>
	<table class="adminlist">
		<tr>
			<th width="1%" align="left">#</th>
			<th class="title" width="1%">
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th width="30"><?php echo _ICON?></th>
			<th width="20%"><?php echo _CAPTION?></th>
			<th width="60%"><?php echo _DESCRIPTION?></th>
			<th width="30"><?php echo _KERNEL?></th>
			<th width="30" class="jtd_nowrap"><?php echo _ORDER_DROPDOWN?></th>
			<th width="1%"><a href="javascript: saveorder( <?php echo count($rows) - 1; ?> )"><img src="<?php echo $cur_file_icons_path;?>/saveorder.png" border="0" width="16" height="16" alt="Save Order" /></a></th>
		</tr>
				<?php
				$k = 0;
				$i = 0;
				foreach($rows as $row) {
					$checked = html::idBox($i,$row->id,null);
					$link = 'index2.php?option=com_linkeditor&amp;task=edit&amp;hidemainmenu=1&amp;id='.$row->id;
					$img = $row->admin_menu_img ? $row->admin_menu_img:'js/ThemeOffice/spacer.png';
					?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $pageNav->rowNumber($i); ?></td>
			<td><?php echo $checked; ?></td>
			<td align="center">
				<img src="<?php echo JPATH_SITE; ?>/<?php echo $img; ?>" />
			</td>
			<td align="left"><a href="<?php echo $link; ?>"><?php echo stripslashes($row->treename); ?></a></td>
			<td align="left"><?php echo $row->admin_menu_alt; ?></td>
			<td align="center">
				<img src="<?php echo $cur_file_icons_path;?>/<?php echo ($row->iscore)?'tick.png':'publish_x.png'; ?>" border="0" alt="<?php echo ($row->iscore) ? 'Да':'Нет'; ?>" />
			</td>
			<td align="center" colspan="2"><input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" /></td>
		</tr>
					<?php
					$k = 1 - $k;
					$i++;
				}
				?>
	</table>
			<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="task" value="all" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_linkeditor" />
</form>
		<?php
	}

	public static function edit($row,$lists) {
		
		?>
<table class="adminheading">
	<tr>
		<th class="edit"><?php echo $row->id ? _COMPONENTS_MENU_EDIT : _COMPONENTS_MENU_NEW; ?></th>
	</tr>
</table>
		<?php if($row->iscore == 1) echo '<div style="background-color: red;color:white">'._COMPONENT_IS_A_PART_OF_CMS.'</div>'; ?>
<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table class="adminform">
		<tr>
			<td width="20%" align="right" >
						<?php echo _CAPTION?><font color="red">*</font>:
			</td>
			<td width="25%">
				<input class="inputbox" type="text" name="name" size="45" value="<?php echo $row->name; ?>" />
						<?php
						$tip = _MENU_NAME_REQUIRED;
						echo mosToolTip($tip);
						?>
			</td>
			<td colspan="1" rowspan="4">
				<img name="view_imagefiles" id="view_imagefiles" src="<?php echo JPATH_SITE; ?>/includes/<?php echo ($row->admin_menu_img !='js/ThemeOffice/')?$row->admin_menu_img:'js/ThemeOffice/spacer.png'; ?>" width="16" />
						<?php echo _MENU_ITEM_ICON?>
						<?php echo $lists['image']; ?>
			</td>
		</tr>
		<tr>
			<td align="right">
						<?php echo _DESCRIPTION?>:
			</td>
			<td>
				<input class="inputbox" type="text" name="admin_menu_alt" size="45" value="<?php echo $row->admin_menu_alt; ?>" />
						<?php
						$tip = _MENU_ITEM_DESCRIPTION;
						echo mosToolTip($tip);
						?>
			</td>
		</tr>
		<tr>
			<td align="right">
						<?php echo _C_LINKEDITOR_LINK?>:<font color="red">*</font>:
			</td>
			<td>
				<input class="inputbox" type="text" name="admin_menu_link" size="45" value="<?php echo $row->admin_menu_link; ?>" />
						<?php
						$tip = _MENU_ITEM_LINK;
						echo mosToolTip($tip);
						?>
			</td>
		</tr>
		<tr>
			<td align="right">
						<?php echo _PARENT_MENU_ITEM?>:
			</td>
			<td>
						<?php
						echo $lists['parent'];
						$tip = _PARENT_MENU_ITEM2;
						echo mosToolTip($tip);
						?>
			</td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="task" value="savelink" />
	<input type="hidden" name="hidemainmenu" value="1" />
	<input type="hidden" name="option" value="com_linkeditor" />
	<input type="hidden" name="cur_option" value="<?php echo $row->option; ?>" />
</form>
		<?php

	}
}