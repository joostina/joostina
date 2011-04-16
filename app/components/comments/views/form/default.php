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
<a href="#add_comment" id="comment_back" class="add_comment">Написать комментарий</a>
<div id="first_comment_wrap">
    <div class="comment_form" id="comment_form">
        <form action="" id="comments_addform">
            <textarea id="comment_input" name="comment_text" cols="10" rows="10" style="width: 98%"></textarea>
            <br/>
            <button class="comment_button" type="submit">добавить комментарий</button>
            <input type="hidden" name="parent_id" id="parent_id" value="0"/>
        </form>
    </div>
</div>
