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

/*
 * Общесистемная "Корзина" для хранения удалённых объектов
 */
class Jtrash extends mosDBTable {

    public $id;
    public $obj_id;
    public $obj_table;
    public $title;
    public $data;
    public $user_id;
    public $deleted_at;

    public function __construct() {
        $this->mosDBTable('#__trash', 'id');
    }

    /**
     * Добавление копии удалённого объекта в корзину
     * @global mosUser $my - объект текущего пользователя
     * @param stdClass $obj - удаляемый объект
     * @return boolean результат сохранения копии удаляемого объекта в корзину
     */
    public static function add($obj_original) {
        global $my;

        $obj = clone $obj_original;

        // ключевое индексное поле объекта
        $_tbl_key = $obj->_tbl_key;

        // если у удаляемого объекта отсутствует ключ - то объет не определён
        if (!$obj_original->$_tbl_key)
            return false;

        // удаляем объект базы данных
        unset($obj->_db, $obj->_error);

        // собираем данные для сохранения резервной копии
        $trash = new self;
        $trash->obj_id = $obj->$_tbl_key;
        $trash->obj_table = $obj->_tbl;
        $trash->title = isset($obj->title) ? $obj->title : $obj->$_tbl_key;
        $trash->data = json_encode($obj);
        $trash->user_id = $my->id;
        $trash->deleted_at = _CURRENT_SERVER_TIME;

        return (bool) $trash->store();
    }
}