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

/**
 * @package Joostina
 * @subpackage Menus
 */
class HTML_menusections {

	public static function showMenusections($rows,$pageNav,$search,$levellist,$menutype,$option) {
		global $my;

		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';

		?>
<form action="index2.php" method="post" name="adminForm">
    <table class="adminheading">
        <tr>
            <th class="menus"><?php echo _MENU_MANAGER?> <small>[ <?php echo $menutype; ?> ]</small></th>
            <td class="jtd_nowrap"><?php echo _MAXIMUM_LEVELS?></td>
            <td><?php echo $levellist; ?></td>
            <td><?php echo _FILTER?>:</td>
            <td>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="inputbox" onChange="document.adminForm.submit();" />
            </td>
        </tr>
				<?php if($menutype == 'mainmenu') { ?>
        <tr>
            <td align="right" class="jtd_nowrap" style="color: red; font-weight: normal;" colspan="5">
							<?php echo _MAINMENU_DEL; ?>
                <br />
                <span style="color: black;"><?php echo _MAINMENU_HOME; ?></span>
            </td>
        </tr>
					<?php } ?>
    </table>
    <table class="adminlist">
        <tr>
            <th width="20">#</th>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
            </th>
            <th class="title" width="40%"><?php echo _MENUITEM?></th>
            <th width="5%"><?php echo _PUBLISHED?></th>
            <th colspan="2" width="5%"><?php echo _ORDERING?></th>
            <th width="2%"><?php echo _COM_MENUS_ORDER_DROPDOWN?></th>
            <th width="1%">
                <a href="javascript: saveorder( <?php echo count($rows) - 1; ?> )"><img src="<?php echo $cur_file_icons_path;?>/saveorder.png" border="0" width="16" height="16" alt="<?php echo _SAVE_ORDER?>" /></a>
            </th>
            <th width="10%"><?php echo _ACCESS?></th>
            <th>Itemid</th>
            <th width="35%" align="left"><?php echo _TYPE?></th>
            <th>CID</th>
        </tr>
				<?php
				$k = 0;
				$i = 0;
				$n = count($rows);
				foreach($rows as $row) {
					mosMakeHtmlSafe($row,ENT_QUOTES,'treename');
					$access		= mosCommonHTML::AccessProcessing($row,$i,1);
					$checked	= mosCommonHTML::CheckedOutProcessing($row,$i);
					$title = $row->published ?  _PUBLISHED : _UNPUBLISHED;
					$img = $row->published ? 'publish_g.png' : 'publish_x.png';
					$img = $cur_file_icons_path.'/'.$img;
					?>
        <tr class="<?php echo "row$k"; ?>" id="tr-el-<?php echo $row->id;?>">
            <td><?php echo $i + 1 + $pageNav->limitstart; ?></td>
            <td><?php echo $checked; ?></td>
            <td class="jtd_nowrap" align="left">
							<?php
							if($row->checked_out && ($row->checked_out != $my->id)) {
								echo $row->treename;
							} else {
								$link = 'index2.php?option=com_menus&menutype='.$row->menutype.'&task=edit&id='.$row->id.'&hidemainmenu=1';
								?>
                <a href="<?php echo $link; ?>"><?php echo $row->treename; ?></a>
								<?php
							}
							?>
            </td>
            <td align="center" class="td-state">
                <img class="img-mini-state" src="<?php echo $img;?>" obj_id="<?php echo $row->id;?>" obj_task="publish" alt="<?php echo $title?>" title="<?php echo $title?>" />
            </td>
            <td><?php echo $pageNav->orderUpIcon($i); ?></td>
            <td><?php echo $pageNav->orderDownIcon($i,$n); ?></td>
            <td align="center" colspan="2">
                <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
            </td>
            <td align="center" id="acc-id-<?php echo $row->id;?>"><?php echo $access; ?></td>
            <td align="center"><?php echo $row->id; ?></td>
            <td align="left">
                <span class="editlinktip">
								<?php echo mosToolTip($row->descrip,'',280,'tooltip.png',$row->type,$row->edit); ?>
                </span>
            </td>
            <td align="center"><?php echo $row->componentid; ?></td>
        </tr>
					<?php
					$k = 1 - $k;
					$i++;
				}
				?>
    </table>
			<?php echo $pageNav->getListFooter(); ?>
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}


	/**
	 * Отображение списка типов пунктов меню для создания
	 */
	public static function addMenuItem(&$cid,$menutype,$option,$types_component,$types_link,$types_other) {

		
		?>
<style type="text/css">
    fieldset {
        border: 1px solid #777;
    }
    legend {
        font-weight: bold;
    }
</style>
<form action="index2.php" method="post" name="adminForm">
    <table class="adminheading">
        <tr>
            <th width="100px" class="menus"><?php echo _NEW_MENU_ITEM ?></th>
            <td class="jtd_nowrap" style="color: red;">
						<?php echo _NOTE_MENU_ITEMS1?>
            </td>
        </tr>
    </table>
    <table class="adminform">
        <tr>
            <td width="50%" valign="top">
                <fieldset>
                    <legend><?php echo _MENU_ITEMS_OTHER?></legend>
                    <table class="adminform">
								<?php
								$k = 0;
								$count = count($types_other);
								for($i = 0; $i < $count; $i++) {
									$row = &$types_other[$i];

									$link = 'index2.php?option=com_menus&menutype='.$menutype.'&task=edit&type='.$row->type.'&hidemainmenu=1';
									HTML_menusections::htmlOptions($row,$link,$k,$i);

									$k = 1 - $k;
								}
								?>
                    </table>
                </fieldset>
            </td>
            <td width="50%" valign="top">
                <fieldset>
                    <legend><?php echo _COMPONENTS?></legend>
                    <table class="adminform">
								<?php
								$k = 0;
								$count = count($types_component);
								for($i = 0; $i < $count; $i++) {
									$row = &$types_component[$i];

									$link = 'index2.php?option=com_menus&menutype='.$menutype.'&task=edit&type='.$row->type.'&hidemainmenu=1';
									HTML_menusections::htmlOptions($row,$link,$k,$i);

									$k = 1 - $k;
								}
								?>
                    </table>
                </fieldset>
                <fieldset>
                    <legend><?php echo _LINKS?></legend>
                    <table class="adminform">
								<?php
								$k = 0;
								$count = count($types_link);
								for($i = 0; $i < $count; $i++) {
									$row = &$types_link[$i];

									$link = 'index2.php?option=com_menus&menutype='.$menutype.'&task=edit&type='.$row->type.'&hidemainmenu=1';
									HTML_menusections::htmlOptions($row,$link,$k,$i);

									$k = 1 - $k;
								}
								?>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
    <input type="hidden" name="task" value="edit" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	public static function htmlOptions($row,$link,$k,$i) {
		?>
<tr class="<?php echo "row$k"; ?>">
    <td width="20">
    </td>
    <td style="height: 30px;">
        <span class="editlinktip" style="cursor: pointer;">
					<?php
					echo mosToolTip($row->descrip,$row->name,250,'',$row->name,$link,1);
					?>
        </span>
    </td>
    <td width="20">
        <input type="radio" id="cb<?php echo $i; ?>" name="type" value="<?php echo $row->type; ?>" onClick="isChecked(this.checked);" />
    </td>
    <td width="20">
    </td>
</tr>
		<?php
	}

	/**
	 * Form to select Menu to move menu item(s) to
	 */
	function moveMenu($option,$cid,$MenuList,$items,$menutype) {
		?>
<form action="index2.php" method="post" name="adminForm">
    <br />
    <table class="adminheading">
        <tr>
            <th><?php echo _MOVE_MENU_ITEMS?></th>
        </tr>
    </table>
    <br />
    <table class="adminform">
        <tr>
            <td width="3%"></td>
            <td align="left" valign="top" width="30%">
                <strong><?php echo _MOVE_MENU_ITEMS?>:</strong>
                <br />
						<?php echo $MenuList ?>
                <br /><br />
            </td>
            <td align="left" valign="top">
                <strong><?php echo _MENU_ITEMS_TO_MOVE?>:</strong>
                <br />
                <ol>
							<?php foreach($items as $item) { ?>
                    <li><?php echo $item->name; ?></li>
								<?php } ?>
                </ol>
            </td>
        </tr>
    </table>
    <br /><br />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="boxchecked" value="1" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
			<?php
			foreach($cid as $id) {
				echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
			}
			?>
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}


	/**
	 * Form to select Menu to copy menu item(s) to
	 */
	function copyMenu($option,$cid,$MenuList,$items,$menutype) {
		?>
<form action="index2.php" method="post" name="adminForm">
    <br />
    <table class="adminheading">
        <tr>
            <th><?php echo _COPY_MENU_ITEMS?></th>
        </tr>
    </table>
    <br />
    <table class="adminform">
        <tr>
            <td width="3%"></td>
            <td align="left" valign="top" width="30%">
                <strong><?php echo _COPY_MENU_ITEMS_TO?>:</strong>
                <br />
						<?php echo $MenuList ?>
                <br /><br />
            </td>
            <td align="left" valign="top">
                <strong><?php echo _MENU_ITEMS_TO_COPY?>:</strong>
                <br />
                <ol>
							<?php foreach($items as $item) { ?>
                    <li><?php echo $item->name; ?></li>
								<?php } ?>
                </ol>
            </td>
        </tr>
    </table>
    <br /><br />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
			<?php
			foreach($cid as $id) {
				echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
			}
			?>
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}
}