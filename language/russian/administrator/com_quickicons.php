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

DEFINE('_QUICK_BUTTONS','Кнопки быстрого доступа');
DEFINE('_DISPLAY_METHOD','Отображение');
DEFINE('_DISPLAY_ONLY_TEXT','Только текст');
DEFINE('_DISPLAY_ONLY_ICON','Только значок');
DEFINE('_DISPLAY_TEXT_AND_ICON','Значок и текст');
DEFINE('_PRESS_TO_EDIT_ELEMENT','Нажмите для редактирования элемента');
DEFINE('_EDIT_BUTTON','Редактирование кнопки');
DEFINE('_BUTTON_TEXT','Текст кнопки');
DEFINE('_BUTTON_TITLE','Подсказка');
DEFINE('_BUTTON_TITLE_TIP','<strong>Опционально</strong><br />Здесь вы можете определить текст для всплывающей подсказки.<br />Это свойство очень важно заполнить если вы выбрали отображение только картинки!');
DEFINE('_BUTTON_LINK_TIP','Ссылка для вызова сайта или компонента.<br />Для компонентов внутри системы ссылка должна быть подобной: <br />index2.php?option=com_joomlastats&task=stats  [ joomlastats - компонент, &task=stats вызов определённой функции компонента ].<br />Внешние ссылки должны быть <strong>абсолютными ссылками</strong> (например: http://www....)!');
DEFINE('_BUTTON_LINK_IN_NEW_WINDOW_TIP','Ссылка будет открыта в новом окне');
DEFINE('_BUTTON_ORDER','Расположить после');
DEFINE('_BUTTONS_TAB_DISPLAY','Отображение');
DEFINE('_DISPLAY_BUTTON','Отображать');
DEFINE('_PRESS_TO_CHOOSE_ICON','Нажмите для выбора картинки (откроется в новом окне)');
DEFINE('_CHOOSE_ICON','Выбрать картинку');
DEFINE('_CHOOSE_ICON_TIP','Пожалуйста, выберите картинку для этой кнопки. Если хотите загрузить собственную картинку для кнопки, то она должна быть загружена в ../administrator/images - ../images ../images/icons');
DEFINE('_PLEASE_ENTER_NUTTON_LINK','Требуется картинка');
DEFINE('_PLEASE_ENTER_BUTTON_TEXT','Пожалуйста, заполните поле Текст');
DEFINE('_BUTTON_ERROR_PUBLISHING','Ошибка публикации');
DEFINE('_BUTTON_ERROR_UNPUBLISHING','Ошибка скрытия');
DEFINE('_BUTTONS_DELETED','Кнопки успешно удалены');
DEFINE('_CHANGE_QUICK_BUTTONS','Изменить кнопки быстрого доступа');
DEFINE('_QI_REFERENCE_NOT_SELECTED','Ссылка не выбрана');