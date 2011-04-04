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

/*
 * Класс формирования представлений
 */
class thisHTML {

    /**
     * Список объектов
     * @param joosDBModel $obj - основной объект отображения
     * @param array $obj_list - список объектов вывода
     * @param mosPageNav $pagenav - объект постраничной навигации
     */
    public static function index( $obj, $obj_list, $pagenav) {
        // массив названий элементов для отображения в таблице списка
        $fields_list = array( 'id', 'title', 'type_id','category_id', 'state');
        // передаём информацию о объекте и настройки полей в формирование представления
        JoiAdmin::listing( $obj, $obj_list, $pagenav, $fields_list );
    }

    /**
     * Редактирование-создание объекта
     * @param joosDBModel $articles_obj - объект  редактирования с данными, либо пустой - при создании
     * @param stdClass $articles_data - свойства объекта
     */
    public static function edit( $articles_obj, $articles_data ) {
        // передаём данные в формирование представления
        JoiAdmin::edit($articles_obj, $articles_data);
    }
}
