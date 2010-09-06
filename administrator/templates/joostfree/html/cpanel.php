<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

?>
<table width="100%">
	<tr valign="top">
		<td width="65%">
			<?php mosLoadAdminModules('icon',0); ?>
			<?php mosLoadAdminModules('advert1',0); ?>
		</td>
		<td width="35%">
			<?php mosLoadAdminModules('advert2',0); ?>
		</td>
	</tr>
</table>