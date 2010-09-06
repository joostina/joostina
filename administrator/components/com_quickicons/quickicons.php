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

global $my;

$database = database::getInstance();

function quickiButton($row,$newWindow) {
	$title = $row->title ? $row->title : $row->text;
	?><span><a href="<?php echo $row->target; ?>" title="<?php echo $title; ?>"<?php echo $newWindow; ?>><?php
			$icon = '<img src="'.JPATH_SITE.$row->icon.'" alt="'.$title.'" border="0" />';
			if($row->display == 1) {
				?><p><?php echo $row->text; ?></p><?php
			} elseif($row->display == 2) {
				echo $icon; // только значок
			} else {
				echo $icon.$row->text; // значок и текст
			} ?>
    </a></span><?php
}
?>
<div class="cpicons"><?php
	$query = 'SELECT* FROM #__quickicons WHERE published = 1 AND gid <= '.$my->gid.' ORDER BY ordering';
	$rows = $database->setQuery($query)->loadObjectList();
	foreach($rows as $row) {
		$newWindow = $row->new_window ? ' target="_blank"':'';
		quickiButton($row,$newWindow);
	}
	unset($query,$rows);
	?>
    <div style="display: block; clear: both; text-align:left; padding-top:10px;">
		<?php if( Jacl::isAllowed('quickicons', 'edit') ) { ?>
        <a href="index2.php?option=com_quickicons">
            <img border="0" src="<?php echo JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE;?>/images/ico/shortcut.png" alt="<?php echo _CHANGE_QUICK_BUTTONS ?>" /><?php echo _CHANGE_QUICK_BUTTONS?>
        </a>
			<?php } ?>
    </div>
</div>