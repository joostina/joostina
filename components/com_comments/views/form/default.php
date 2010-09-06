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

mosMainFrame::addLib('acl');

if( Jacl::isAllowed('comments', 'add') ) { ?>
<div class="block_wrap">
    <div class="comment_form">
        <span> Ваш комментарий </span>
        <form action="" id="comments_addform">
            <textarea id="comment_input" name="comment_text"></textarea>
            <button class="comment_button" type="button">добавить комментарий</button>
            <input type="hidden" name="option" value="com_comments"/>
            <input type="hidden" name="task" value="add_comment"/>
        </form>
    </div>
</div> 
	<?php } else { ?>
	оставлять комментарии могут только зарегистрированные пользователи
		<?php
}