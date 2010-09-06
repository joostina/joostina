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

/**
 * Системный счетчик
 */
class Jhit {
    /**
     * Добавление / увеличение счетчика хитов
     * @param string $option название компонента
     * @param integer $id - идентификатор объекта
     * @param string $task - подзадача
     * @return boolean результат увеличения счетчика
     */
    public static function add($option, $id, $task='') {
        $sql = sprintf("INSERT INTO `#__hits` (`id`, `obj_id`, `obj_option`, `obj_task`, `hit`) VALUES (NULL, %s, '%s', '%s', 1)
									ON DUPLICATE KEY UPDATE hit=hit+1;",
                        (int) $id, $option, $task);
        return database::getInstance()->setQuery($sql)->query();
    }

}