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

DEFINE('_MODULE_IS_EDITING_MY_ADMIN', 'Модуль в настоящее время редактируется другим администратором'); //The module is currently being edited by another administrator
DEFINE('_LINK_MUST_HAVE_NAME', 'Ссылка должна иметь имя');
DEFINE('_CHOOSE_COMPONENT_FOR_LINK', 'Вы должны выбрать компонент для создания ссылки на него');
DEFINE('_MENU_ITEM_COMPONENT_LINK', 'Пункт меню :: Ссылка - Объект компонента');
DEFINE('_LINK_TITLE', 'title ссылки');
DEFINE('_LINK_COMPONENT', 'Компонент для ссылки');
DEFINE('_LINK_TARGET', 'При нажатии открыть в');
DEFINE('_OBJECT_MUST_HAVE_NAME', 'Объект должен иметь имя');
DEFINE('_CHOOSE_COMPONENT', 'Выберите компонент');
DEFINE('_MENU_ITEM_COMPONENT', 'Пункт меню :: Компонент');
DEFINE('_MENU_PARAMS_AFTER_SAVE', 'Список параметров будет доступен только после сохранения пункта меню');
DEFINE('_MENU_ITEM_TABLE_CONTACT_CATEGORY', 'Пункт меню :: Таблица - Контакты категории');
DEFINE('_CATEGORY_TITLE_IF_FILED_IS_EMPTY', 'Если поле будет оставлено пустым, то автоматически будет использовано название категории');
DEFINE('_CHOOSE_CONTACT_FOR_LINK', 'Для создания ссылки необходимо выбрать контакт');
DEFINE('_MENU_ITEM_CONTACT_OBJECT', 'Пункт меню :: Ссылка - Объект контакта');
DEFINE('_MENU_ITEM_BLOG_CATEGORY_ARCHIVE', 'Пункт меню :: Блог - Содержимое категории в архиве');
DEFINE('_MENU_ITEM_BLOG_SECTION_ARCHIVE', 'Пункт меню :: Блог - Содержимое раздела в архиве');
DEFINE('_SECTION_TITLE_IF_FILED_IS_EMPTY', 'Если поле будет оставлено пустым, то автоматически будет использовано название раздела');
DEFINE('_MENU_ITEM_SAVED', 'Пункт меню сохранен');
DEFINE('_MENU_ITEM_BLOGCATEGORY', 'Пункт меню :: Блог - Содержимое категории');
DEFINE('_YOU_CAN_CHOOSE_SEVERAL_CATEGORIES', 'Вы можете выбрать несколько категорий');
DEFINE('_MENU_ITEM_BLOG_CONTENT_CATEGORY', 'Пункт меню :: Блог - Содержимое раздела');
DEFINE('_YOU_CAN_CHOOSE_SEVERAL_SECTIONS', 'Вы можете выбрать несколько разделов');
DEFINE('_MENU_ITEM_TABLE_CONTENT_CATEGORY', 'Пункт меню :: Таблица - Содержимое категории');
DEFINE('_CHANGE_CONTENT_ITEM', 'Изменить объект содержимого');
DEFINE('_CONTENT_ITEM_TO_LINK_TO', 'Выберите статью для связи');
DEFINE('_MENU_ITEM_CONTENT_ITEM', 'Пункт меню :: Ссылка - Объект содержимого');
DEFINE('_CONTENT_TO_LINK_TO', 'Содержимое для связи');
DEFINE('_MENU_ITEM_TABLE_CONTENT_SECTION', 'Пункт меню :: Таблица - содержимое раздела');
DEFINE('_CHOOSE_OBJECT_TO_LINK_TO', 'Вы должны выбрать объект для связи с ним');
DEFINE('_MENU_ITEM_STATIC_CONTENT', 'Пункт меню :: Ссылка - Статичное содержимое');
DEFINE('_MENU_ITEM_CATEGORY_NEWSFEEDS', 'Пункт меню :: Таблица - Ленты новостей из категории');
DEFINE('_CHOOSE_NEWSFEED_TO_LINK', 'Вы должны выбрать ленту новостей для связи с пунктом меню');
DEFINE('_MENU_ITEM_NEWSFEED', 'Пункт меню :: Ссылка - Лента новостей');
DEFINE('_LINKED_TO_NEWSFEED', 'Связано с лентой');
DEFINE('_MENU_ITEM_SEPARATOR', 'Пункт меню :: Разделитель / Заполнитель');
DEFINE('_ENTER_URL_PLEASE', 'Вы должны ввести url.');
DEFINE('_MENU_ITEM_URL', 'Пункт меню :: Ссылка - URL');
DEFINE('_MENU_ITEM_LINKS_CATEGORY', 'Пункт меню :: Таблица - Web-ссылки категории');
DEFINE('_MENU_ITEM_WRAPPER', 'Пункт меню :: Wrapper');
DEFINE('_WRAPPER_LINK', 'Ссылка Wrapper\'a');
DEFINE('_MAXIMUM_LEVELS', 'Максимально уровней');
DEFINE('_NOTE_MENU_ITEMS1', '* Обратите внимание, что некоторые пункты меню входят в несколько групп, но они относятся к одному типу меню.');
DEFINE('_MENU_ITEMS_OTHER', 'Разное');
DEFINE('_MENU_ITEMS_SEND', 'Отправка');
DEFINE('_MOVE_MENU_ITEMS', 'Перемещение пунктов меню');
DEFINE('_MENU_ITEMS_TO_MOVE', 'Перемещаемые пункты меню');
DEFINE('_COPY_MENU_ITEMS', 'Копирование пунктов меню');
DEFINE('_COPY_MENU_ITEMS_TO', 'Копировать в меню');
DEFINE('_CHANGE_THIS_NEWSFEED', 'Изменить эту ленту новостей');
DEFINE('_CHANGE_THIS_CONTACT', 'Изменить этот контакт');
DEFINE('_CHANGE_THIS_CONTENT', 'Изменить это содержимое');
DEFINE('_CHANGE_THIS_STATIC_CONTENT', 'Изменить это статичное содержимое');
DEFINE('_MAINMENU_HOME', '* Первый по списку опубликованный пункт этого меню [mainmenu] автоматически становится `Главной` страницей сайта*');
DEFINE('_MAINMENU_DEL', '* Вы не можете `удалить` это меню, поскольку оно необходимо для нормального функционирования сайта*');
DEFINE('_MENU_GROUP', '* Некоторые `Типы меню` появляются более чем в одной группе*');
DEFINE('_NEW_MENU_ITEM', 'Новый пункт меню');
DEFINE('_ALL_CATEGORIES', '- все категории -');
DEFINE('_MENU_MANAGER', 'Управление меню');
DEFINE('_COM_MENUS_ORDER_DROPDOWN', 'Порядок');
DEFINE('_COM_MENUS_SUBMIT_CONTENT', 'Пункт меню :: Добавить содержимое');
DEFINE('_MOVE_MENUS', 'перемещено');
DEFINE('_MOVE_MENUS_TO', 'перемещено в');
DEFINE('_EDIT_CONTENT_TYPED', 'Изменить статичное содержимое');