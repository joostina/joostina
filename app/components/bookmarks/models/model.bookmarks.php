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

/**
 * Модель Bookmarks - пользовательские закладки
 */
class Bookmarks extends joosDBModel
{

    public $id;
    public $user_id;
    public $obj_id;
    public $obj_option;
    public $obj_task;
    public $created_at;

    function __construct()
    {
        $this->joosDBModel('#__bookmarks', 'id');
    }

    public function check()
    {
        $this->filter();
    }

    public static function get_label($type = 'label', $action = 'add', $option)
    {

        $labels = array(
            'add' => array(
                'label' => array(
                    'News' => 'Добавить новость в закладки',
                    'Blogs' => 'Добавить блогозапись в закладки',
                    'default' => 'Добавить  в закладки'
                ),
                'message' => array(
                    'News' => 'Новость добавлена в закладки',
                    'Blogs' => 'Блогозапись добавлена в закладки',
                    'default' => 'Закладка добавлена',
                )
            ),
            'del' => array(
                'label' => array(
                    'News' => 'Удалить новость из закладок',
                    'Blogs' => 'Удалить блогозапись из закладок',
                    'default' => 'Удалить  из закладок'
                ),
                'message' => array(
                    'News' => 'Новость убрана из закладок',
                    'Blogs' => 'Блогозапись убрана из закладок',
                    'default' => 'Закладка удалена'
                )
            )
        );

        return isset($labels[$action][$type][$option]) ? $labels[$action][$type][$option] : $labels[$action][$type]['default'];
    }

    /**
     * Добавление элемента в закладки
     * @param string $option - название компонента
     * @param integer $id - идентификатор элемента компонента
     * @param string $task - задача компонента
     * @return boolean or error obj - результат выполнения вставки или объект с данными о ошибке
     */
    public static function add($option, $id, $task = '')
    {
        $bookmarks = new self;
        $bookmarks->user_id = Users::current()->id;
        $bookmarks->obj_id = $id;
        $bookmarks->obj_option = $option;
        $bookmarks->obj_task = $task;

        if ($bookmarks->find()) {
            // удаляем существующую закладку
            $bookmarks->delete();
            // счетчик уменьшаем
            $bookmarks->update_counters(false);
            // обновляем кеш закладок текущего пользователя
            $bookmarks->update_user_cache();

            // считаем сколкьо раз это содержимое внесено в закладки
            $bookmarks_counter = BookmarksCounter::get_count($id, $option, $task);

            // информируем
            return json_encode(array('message' => self::get_label('message', 'del', $option), 'task' => 'unactive', 'count' => $bookmarks_counter->counter, 'label' => self::get_label('label', 'add', $option)));
        }

        $bookmarks->created_at = _CURRENT_SERVER_TIME;


        $result = $bookmarks->store() ? array('message' => self::get_label('message', 'add', $option), 'option' => $option, 'label' => self::get_label('label', 'del', $option)) : array('error' => 'Упс, закладка уже есть, или у нас проблемы...');

        $bookmarks_counter = BookmarksCounter::get_count($id, $option, $task);

        // общее число закладок на этот элемент в базе
        $result['count'] = $bookmarks_counter->counter;

        $where = array();
        $where[] = "user_id = " . Users::current()->id;
        $where[] = "obj_option = '" . $option . "'";
        $where[] = "obj_task = '" . $task . "'";

        // число закладок текущего типа конкретного пользователя
        $result['current_count'] = $bookmarks->count(' WHERE ' . implode(' AND ', $where));


        // сделать значек избранного активным
        $result['task'] = 'active';
        return json_encode($result);
    }

    // после каждого добавления в закладки
    public function after_store()
    {
        // после записи закладки обновим счетчики
        $this->update_counters();
        $this->update_user_cache();
    }

    // обновление кеша закладок текущего пользователя
    private function update_user_cache()
    {
        joosMainframe::instance()->getPath('class', 'com_users');
        UsersExtra::update_cache_bookmarks(Users::current());
    }

    public static function addlink($obj = false, array $obj_array = array())
    {
        $obj_option = $obj ? get_class($obj) : $obj_array['class'];
        $obj_id = $obj ? $obj->id : $obj_array['id'];

        if (isset(Users::current()->extra(Users::current()->id)->cache_bookmarks[$obj_option]['all'][$obj_id])) {
            $title = self::get_label('label', 'del', $obj_option);
            return sprintf('<span title="%s" class="g-bookmark g-bookmark_simple active" obj_option="%s" obj_id="%s">-</span>', $title, $obj_option, $obj_id);
        }
        else {
            $title = self::get_label('label', 'add', $obj_option);
            return sprintf('<span title="%s" class="g-bookmark g-bookmark_simple" obj_option="%s" obj_id="%s">+</span>', $title, $obj_option, $obj_id);
        }

    }

    public static function by_user(User $user)
    {
        return joosDatabase::instance()->set_query('SELECT count(id) FROM #__bookmarks WHERE user_id = ' . $user->id)->load_result();
    }

    // TODO а это зачем?
    public static function by_user_list(User $user)
    {
        return joosDatabase::instance()->set_query('SELECT * FROM #__bookmarks WHERE user_id = ' . $user->id . ' AND obj_option!=\'games_trace\'')->load_object_list();
    }

    public static function get_count(array $params = array())
    {

        $u = isset($params['user_id']) ? 'AND user_id=' . $params['user_id'] : '';
        $o_id = isset($params['obj_id']) ? 'AND obj_id=' . $params['obj_id'] : '';
        $o_option = isset($params['obj_option']) ? "AND obj_option='" . $params['obj_option'] . "'" : '';
        $o_task = isset($params['obj_task']) ? "AND obj_task='" . $params['obj_task'] . "'" : '';

        $sql = sprintf('SELECT count(id) FROM #__bookmarks WHERE true %s %s %s %s', $u, $o_id, $o_option, $o_task);

        return joosDatabase::instance()->set_query($sql)->load_result();
    }

    private function update_counters($more = true)
    {
        $sql = sprintf("INSERT INTO `#__bookmarks_counter` (`obj_id`, `obj_option`,`obj_task`,`counter`)
            VALUES (%s, '%s','%s',1)
            ON DUPLICATE KEY UPDATE counter=counter" . ($more ? '+1' : '-1'), $this->obj_id, $this->obj_option, $this->obj_task);
        return $this->_db->set_query($sql)->query();
    }

}

class BookmarksCounter extends joosDBModel
{

    public $id;
    public $obj_id;
    public $obj_option;
    public $obj_task;
    public $counter;

    function __construct()
    {
        $this->joosDBModel('#__bookmarks_counter', 'id');
    }

    public static function get_count($id, $option, $task)
    {
        $bookmarks_counter = new self;
        $bookmarks_counter->obj_id = $id;
        $bookmarks_counter->obj_option = $option;
        $bookmarks_counter->obj_task = $task;
        $bookmarks_counter->find();
        return $bookmarks_counter;
    }

}