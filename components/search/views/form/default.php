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

<div class="search_form"> 
   
    <form action="/index.php" method="get" name="searchForm" id="searchForm">
        <div class="search_input_wrap">
            <input type="text" class="inputbox search_input" size="30" maxlength="30" name="q" value="<?php echo stripslashes($q); ?>" />
        </div>
        <button class="search_button" type="submit">&rarr;</button>
        <input type="hidden" name="option" value="search" />
        <input type="hidden" name=":antisuf" value="true" />
    </form>

</div>
