<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

?>
<div class="login">
	<form action="<?php echo sefRelToAbs('index.php?option=com_users&task=logout', true) ?>" method="post" name="logout">
		<input type="submit" name="Submit" id="logout_button" class="button" value="Выйти <?php echo $my->username; ?>" />
	или  <a href="<?php echo sefRelToAbs('index.php?option=com_users&task=edit',true) ?>">Править</a>
		<input type="hidden" name="<?php echo josSpoofValue(1); ?>" value="1" />
	</form>
</div>