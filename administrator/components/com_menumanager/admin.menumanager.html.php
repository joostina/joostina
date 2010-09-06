<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

class HTML_menumanager {

	public static function show($option,$menus,$pageNav) {
		?>
<script language="javascript" type="text/javascript">
	function menu_listItemTask( id, task, option ) {
		var f = document.adminForm;
		cb = eval( 'f.' + id );
		if (cb) {
			cb.checked = true;
			submitbutton(task);
		}
		return false;
	}
</script>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th class="menus"><?php echo _MENU_MANAGER?></th>
		</tr>
	</table>
	<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="2%">&nbsp;</th>
			<th class="title"><?php echo _MENU_NAME?></th>
			<th width="5%" class="jtd_nowrap"><?php echo _MENU_ITEMS?></th>
			<th width="10%"><?php echo _PUBLISHED?></th>
			<th width="15%"><?php echo _MENU_ITEMS_UNPUBLISHED?></th>
			<th width="15%"><?php echo _IN_TRASH?></th>
			<th width="15%"><?php echo _MENU_MUDULES?></th>
		</tr>
				<?php
				$k = 0;
				$i = 0;
				$start = 0;
				if($pageNav->limitstart) $start = $pageNav->limitstart;
				$count = count($menus) - $start;
				if($pageNav->limit)
					if($count > $pageNav->limit) $count = $pageNav->limit;
				for($m = $start; $m < $start + $count; $m++) {
					$menu	= $menus[$m];
					$menu->type = htmlspecialchars($menu->type);
					$link	= 'index2.php?option=com_menumanager&task=edit&hidemainmenu=1&menu='.$menu->type;
					$linkA	= 'index2.php?option=com_menus&menutype='.$menu->type;
					?>
		<tr class="<?php echo "row".$k; ?>">
			<td align="center" width="30px"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
			<td width="30px" align="center">
				<input type="radio" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $menu->type; ?>" onclick="isChecked(this.checked);" />
			</td>
			<td align="left">
				<a href="<?php echo $link; ?>" title="<?php echo _CHANGE_MENU_NAME?>"><?php echo $menu->type; ?></a>
			</td>
			<td align="center">
				<a href="<?php echo $linkA; ?>" title="<?php echo _CHANGE_MENU_ITEMS?>">
					<img src="<?php echo JPATH_SITE.'/'.JADMIN_BASE; ?>/images/menu/icon-16-menu.png" border="0"/>
				</a>
			</td>
			<td align="center"><?php echo $menu->published; ?></td>
			<td align="center"><?php echo $menu->unpublished; ?></td>
			<td align="center"><?php echo $menu->trash;?></td>
			<td align="center"><?php echo $menu->modules;?></td>
		</tr>
					<?php
					$k = 1 - $k;
					$i++;
				}
				?>
	</table>
			<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	public static function edit(&$row,$option) {
		$new = $row->menutype ? 0:1;

		$row->menutype = htmlspecialchars($row->menutype);
		?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'savemenu') {
			if ( form.menutype.value == '' ) {
				alert( '<?php echo _PLEASE_ENTER_MENU_NAME?>' );
				form.menutype.focus();
				return;
			}
			var r = new RegExp("[\']", "i");
			if ( r.exec(form.menutype.value) ) {
				alert( '<?php echo _NO_QUOTES_IN_NAME?>' );
				form.menutype.focus();
				return;
			}
		<?php
		if($new) {
			?>
						if ( form.title.value == '' ) {
							alert( '<?php echo _PLEASE_ENTER_MENU_MODULE_NAME?>' );
							form.title.focus();
							return;
						}
			<?php
		}
		?>
					submitform( 'savemenu' );
				} else {
					submitform( pressbutton );
				}
			}
</script>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th class="menus"><?php echo _MENU_INFO?></th>
		</tr>
	</table>
	<table class="adminform">
		<tr height="45px;">
			<td width="100px" align="left">
				<strong><?php echo _MENU_NAME?>:</strong>
			</td>
			<td>
				<input class="inputbox" type="text" name="menutype" size="30" maxlength="25" value="<?php echo isset($row->menutype)?$row->menutype:''; ?>" />
						<?php echo mosToolTip(_MENU_NAME_TIP); ?>
			</td>
		</tr>
				<?php if($new) { ?>
		<tr>
			<td width="100px" align="left" valign="top">
				<strong><?php echo _MODULE_TITLE?>:</strong>
			</td>
			<td>
				<input class="inputbox" type="text" name="title" size="30" value="<?php echo $row->title?$row->title:''; ?>" />
							<?php echo mosToolTip(_MODULE_TITLE_TIP); ?>
				<br /><br /><br />
				<strong><?php echo _NEW_MENU_ITEM_TIP?></strong>
			</td>
		</tr>
					<?php } ?>
		<tr>
			<td colspan="2">
			</td>
		</tr>
	</table>
			<?php if($new) { ?>
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="iscore" value="<?php echo $row->iscore; ?>" />
	<input type="hidden" name="published" value="<?php echo $row->published; ?>" />
	<input type="hidden" name="position" value="<?php echo $row->position; ?>" />
	<input type="hidden" name="module" value="mod_menu" />
	<input type="hidden" name="params" value="<?php echo $row->params; ?>" />
				<?php } ?>
	<input type="hidden" name="new" value="<?php echo $new; ?>" />
	<input type="hidden" name="old_menutype" value="<?php echo $row->menutype; ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="savemenu" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	public static function showDelete($option,$type,$items,$modules) {
		$mainframe = mosMainFrame::getInstance();
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		?>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th><?php echo _REMOVE_MENU?>: <?php echo $type; ?></th>
		</tr>
	</table>

	<br />
	<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="20%">
						<?php if($modules) { ?>
				<strong><?php echo _MODULES_TO_REMOVE?>:</strong>
				<ol>
								<?php foreach($modules as $module) { ?>
					<li>
						<font color="#000066">
							<strong><?php echo $module->title; ?></strong>
						</font>
					</li>
					<input type="hidden" name="cid[]" value="<?php echo $module->id; ?>" />
									<?php } ?>
				</ol>
							<?php } ?>
			</td>
			<td align="left" valign="top" width="25%">
				<strong><?php echo _MENU_ITEMS_TO_REMOVE?>:</strong>
				<br />
				<ol>
							<?php foreach($items as $item) { ?>
					<li>
						<font color="#000066">
										<?php echo $item->name; ?>
						</font>
					</li>
					<input type="hidden" name="mids[]" value="<?php echo $item->id; ?>" />
								<?php } ?>
				</ol>
			</td>
			<td>
						<?php echo _THIS_OP_REMOVES_MENU?>
				<br /><br /><br />
				<div style="border: 1px dotted gray; width: 70px; padding: 10px; margin-left: 100px;">
					<a class="toolbar" href="javascript:if (confirm('<?php echo _REALLY_DELETE_MENU?>')){ submitbutton('deletemenu');}">
						<img name="remove" src="<?php echo $cur_file_icons_path;?>/delete.png" alt="<?php echo _DELETE?>" border="0" align="middle" />
						&nbsp;<?php echo _DELETE?>
					</a>
				</div>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>
	<br /><br />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="type" value="<?php echo $type; ?>" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	public static function showCopy($option,$type,$items) { ?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		if (pressbutton == 'copymenu') {
			if ( document.adminForm.menu_name.value == '' ) {
				alert( '<?php echo _PLEASE_ENTER_MENY_COPY_NAME?>' );
				return;
			} else if ( document.adminForm.module_name.value == '' ) {
				alert( '<?php echo _PLEASE_ENTER_MODULE_NAME?>' );
				return;
			} else {
				submitform( 'copymenu' );
			}
		} else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th><?php echo _MENU_COPYING?></th>
		</tr>
	</table>
	<br />
	<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
				<strong><?php echo _NEW_MENU_NAME?>:</strong>
				<br />
				<input class="inputbox" type="text" name="menu_name" size="30" value="" />
				<br /><br /><br />
				<strong><?php echo _NEW_MODULE_NAME?>:</strong>
				<br />
				<input class="inputbox" type="text" name="module_name" size="30" value="" />
				<br /><br />
			</td>
			<td align="left" valign="top" width="25%">
				<strong><?php echo _MENU_TO_COPY?>:</strong>
				<br />
				<font color="#000066">
					<strong><?php echo $type; ?></strong>
				</font>
				<br /><br />
				<strong><?php echo _MENU_ITEMS_TO_COPY?>:</strong>
				<br />
				<ol>
							<?php foreach($items as $item) { ?>
					<li>
						<font color="#000066"><?php echo $item->name; ?></font>
					</li>
					<input type="hidden" name="mids[]" value="<?php echo $item->id; ?>" />
								<?php } ?>
				</ol>
			</td>
			<td valign="top">
			</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="type" value="<?php echo $type; ?>" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}
}