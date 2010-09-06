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

class HTML_QuickIcons {

	public static function show(&$rows,$option,$search,&$pageNav) {

		$mainframe = mosMainFrame::getInstance();
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';

		?>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th class="quickicons"><?php echo _QUICK_BUTTONS?></th>
			<td><?php echo _SEARCH?>:</td>
			<td align="right"><input type="text" name="search" value="<?php echo $search; ?>" class="inputbox" onChange="document.adminForm.submit();" /></td>
		</tr>
	</table>
	<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll();" /></th>
			<th width="5%" class="jtd_nowrap"><?php echo _ICON?></th>
			<th width="53%" class="title"><?php echo _CAPTION?></th>
			<th width="10%" class="jtd_nowrap"><?php echo _DISPLAY_METHOD?></th>
			<th width="7%" class="jtd_nowrap"><?php echo _ACCESS?></th>
			<th width="7%" class="jtd_nowrap"><?php echo _PUBLISHED?></th>
			<th width="7%" colspan="2" class="jtd_nowrap"><?php echo _ORDERING?></th>
			<th width="2%"><?php echo _SORT_ORDER ?></th>
			<th width="1%">
				<a href="javascript:saveorder(<?php echo count($rows) - 1; ?>)" title="<?php echo _SAVE_ORDER?>"><img src="<?php echo $cur_file_icons_path;?>/saveorder.png" border="0" width="16" height="16" alt="<?php echo _SAVE_ORDER?>" /></a>
			</th>
		</tr>
				<?php
				$k = 0;
				$n = count($rows);
				for($i = 0; $i < $n; $i++) {
					$row	= $rows[$i];
					$editLink = 'index2.php?option=com_quickicons&amp;task=edit&amp;id='.$row->id;
					$link	= 'index2.php?option=com_quickicons&amp;task=';

					$img	= $row->published ? 'tick.png':'publish_x.png';

					$checked =mosHTML::idBox($i,$row->id);

					// check display
					$display = '';
					switch($row->display) {
						case '1':
							$display = _DISPLAY_ONLY_TEXT;
							break;

						case '2':
							$display = _DISPLAY_ONLY_ICON;
							break;

						default:
							$display = _DISPLAY_TEXT_AND_ICON;
							break;
					}
					?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $row->id; ?></td>
			<td><?php echo $checked; ?></td>
			<td align="center"><img src="<?php echo JPATH_SITE.$row->icon ;?>" alt="" border="0" /></td>
			<td align="left">
				<a href="<?php echo $editLink; ?>" title="<?php echo _PRESS_TO_EDIT_ELEMENT?>"><?php echo $row->text; ?></a><br />
							<?php
							if($row->target == 'index2.php?option=' || !$row->target) {
								?><span style="color:red; font-weight:bold;"><?php echo _QI_REFERENCE_NOT_SELECTED ?></span><?php
							} else {
								echo htmlentities($row->target);
							}
							?>
			</td>
			<td align="center"><?php echo $display; ?></td>
			<td align="left"><?php echo $row->groupname; ?></td>
			<td align="center" onclick="ch_publ(<?php echo $row->id; ?>,'com_quickicons');" class="td-state">
				<img class="img-mini-state" src="<?php echo $cur_file_icons_path;?>/<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="<?php echo _PUBLISHING?>" />
			</td>
			<td align="center">
							<?php if($i!=0) {?>
				<a href="<?php echo $link.'orderUp&amp;id='.$row->id; ?>" title="<?php echo _NAV_ORDER_UP?>"><img src="<?php echo $cur_file_icons_path;?>/uparrow.png" border="0" alt="<?php echo _NAV_ORDER_UP; ?>" /></a>
								<?php };?>
			</td>
			<td align="center">
							<?php if($i!=(count($rows)-1)) {?>
				<a href="<?php echo $link.'orderDown&amp;id='.$row->id; ?>" title="<?php echo _NAV_ORDER_DOWN?>"><img src="<?php echo $cur_file_icons_path;?>/downarrow.png" border="0" alt="<?php echo _NAV_ORDER_DOWN; ?>" /></a>
								<?php };?>
			</td>
			<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td>
		</tr>
					<?php
					$k = 1 - $k;
				} ?>
	</table>
			<?php echo $pageNav->getListFooter(); ?>

	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0" />
</form>
		<?php
	}

	public static function edit(&$row,$lists,$option) {
		mosMakeHtmlSafe($row,ENT_QUOTES);

		$header = $row->id ? $row->text._EDIT_BUTTON : _CREATION;

		?>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th><?php echo $header ?></th>
		</tr>
	</table>
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminform">
		<tr>
			<td align="right"><?php echo _BUTTON_TEXT?>:</td>
			<td align="left">
				<input class="inputbox" type="text" name="text" size="75" maxlength="100" value="<?php echo $row->text; ?>" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo _BUTTON_TITLE?>:</td>
			<td align="left">
				<input class="inputbox" type="text" name="title" size="75" maxlength="100" value="<?php echo $row->title; ?>" />
			</td>
		</tr>
		<tr>
			<td align="right" width="120"><?php echo _WEBLINK?>:</td>
			<td align="left">
				<input class="inputbox" type="text" name="target" id="target" size="75" maxlength="255" value="<?php echo ($row->target ? $row->target:'index2.php?option='); ?>" />
				<button onclick="addTarget(); return false;">&larr;</button>&nbsp;
						<?php echo $lists['targets']; ?>
			</td>
		</tr>
		<tr>
			<td align="right">
				<label for="new_window"><?php echo _BUTTON_LINK_IN_NEW_WINDOW?>:</label>
			</td>
			<td align="left">
				<input type="hidden" name="new_window" value="0" />
				<input type="checkbox" name="new_window" value="1" id="new_window"<?php echo $row->new_window ? ' checked="checked"':''; ?> />
						<?php echo mosToolTip(_BUTTON_LINK_IN_NEW_WINDOW_TIP);?>
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo _BUTTON_ORDER?>:</td>
			<td align="left"><?php echo $lists['ordering']; ?></td>
		</tr>
		<tr>
			<td align="right" valign="top"><?php echo _ACCESS?>:</td>
			<td align="left"><?php echo $lists['gid']; ?></td>
		</tr>
		<tr>
			<td align="right" width="130"><?php echo _PUBLISHED?>:</td>
			<td align="left">
				<input type="radio" id="published1" name="published" value="1"<?php echo $row->published ? ' checked="checked"':''; ?> /><label for="published1"><?php echo _YES?></label>
				<input type="radio" id="published2" name="published" value="0"<?php echo $row->published ? '':' checked="checked"'; ?> /><label for="published2"><?php echo _NO?></label>
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo _DISPLAY_BUTTON?>:</td>
			<td align="left"><?php echo $lists['display']; ?></td>
		</tr>
		<tr>
			<td align="right"><?php echo _ICON?>:</td>
			<td align="left"><input class="inputbox" type="text" name="icon" id="icon_file" size="100" maxlength="100" value="<?php echo JPATH_SITE.$row->icon; ?>" onblur="changeIcon(this.value)" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="padding-top:10px">
				<img id="iconImg" src="<?php echo JPATH_SITE.( empty($row->icon) ? 'blank.png' : $row->icon ); ?>" alt="" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><div id="quickicons_selector"><?php echo chooseIcon() ?></div></td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>
		<?php
	}

	public static function chooseIcon( array $imgs) {
		$_n = count($imgs);
		for($i = 0; $i < $_n; $i++) {
			$image = str_replace(JPATH_BASE,JPATH_SITE,$imgs[$i]);
			$image = str_replace('\\','/',$image);
			?><img src="<?php echo $image; ?>" onclick="$('#icon_file').val($(this).attr('src'));" alt="<?php echo $image; ?>" title="<?php echo $image; ?>" border="0" /><?php
		}
	}
}