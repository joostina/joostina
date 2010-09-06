<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * My Check in
 */
class HTML_checkin {

	function showlist($option,&$itemlist,$itemcnt) {
		?>
<table class="adminheading">
	<tr>
		<th class="checkin"><?php echo _BLOCKED_OBJECTS?></th>
	</tr>
</table>
<table class="adminlist">
	<tr>
		<th class="title"><?php echo _OBJECT?></th>
		<th class="title"><?php echo _CAPTION?></th>
		<th><?php echo _WHO_BLOCK?></th>
		<th><?php echo _BLOCK_TIME?></th>
		<th><?php echo _ACTION?></th>
	</tr>
			<?php
			$k = 0;
			for($i = 0; $i < $itemcnt; $i++) {
				echo "<tr class=\"row$k\"><td align=\"center\">\n";
				echo $itemlist[$i]["component"];
				echo "</td>\n<td>\n";
				echo $itemlist[$i]["title"];
				echo "</td>\n<td>\n";
				echo $itemlist[$i]["name"];
				echo "</td>\n<td>\n";
				echo $itemlist[$i]["cotime"];
				echo "</td>\n<td>\n";
				echo "<a href=\"".JPATH_SITE."/".JADMIN_BASE."/index2.php?option=$option&task=checkin&component="
						.$itemlist[$i]["component"]."&pkey="
						.$itemlist[$i]["PKEY"]."&checkid="
						.$itemlist[$i]["id"]."&editor="
						.$itemlist[$i]["editor"]."\">"._CHECKIN_OJECT."</a>\n";
				echo "</td></tr>";
				$k = 1 - $k;
			}
			?>
</table>
		<?php
	}
}