<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<div class="mod_menu">
    <ul class="mainmenu">
        <li><a href="<?php echo sefRelToAbs('index.php?option=com_users', true) ?>">Пользователи</a></li>
        <li><a href="<?php echo sefRelToAbs('index.php?option=com_pages&task=view&id=1') ?>">О проекте</a></li>
    </ul>
</div>