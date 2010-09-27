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

class jdebug {

    private static $_instance;
    /* стек сообщений лога*/
    private $_log = array();
    /* буфер сообщений лога*/
    private $text = null;
    /* счетчики */
    private $_inc = array();

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new jdebug();
        }
        return self::$_instance;
    }

    private function __clone() {

    }

    public function add($text, $top = 0) {
        $top ? array_unshift($this->_log, $text) : $this->_log[] = $text;
    }

    public function inc($key) {
        if (!isset($this->_inc[$key])) {
            $this->_inc[$key] = 0;
        }
        $this->_inc[$key]++;
    }

    public function get() {
        echo '<span style="display:none"><![CDATA[<noindex>]]></span><pre>';

		$this->text = '';

        /* счетчики */
        $this->text .= '<ul class="debug_log">';
        foreach ($this->_inc as $key => $value) {
            $this->text .= '<li>Counter: <b>' . htmlentities($key) . '</b>: ' . $value . '</small>';
        }
        $this->text .= '</ul>';
		// выведем лог в более приятном отображении
		array_multisort($this->_log);

        /* лог */
        $this->text .= '<ul class="debug_log">';
        foreach ($this->_log as $key => $value) {
            $this->text .= '<li><small>LOG:</small> ' . $value . '</li>';
        }
        $this->text .= '</ul>';

        $this->text .= $this->db_debug();

        /* подключенные файлы */
        $files = get_included_files();
        $f = array();
        $f[] = '<div onclick="$(\'#_sql_debug_file\').toggle();" style="cursor: pointer;border-bottom:1px solid #CCCCCC;border-top:1px solid #CCCCCC;">' . _INCLUDED_FILES . ': ' . count($files) . '</div>';
        $f[] = '<div id="_sql_debug_file" style="display:none">';
        foreach ($files as $key => $value) {
            $f[] = '<small>' . $key . ':</small> ' . $value . '<br />';
        }
        $f[] = '</div>';

        $this->text .= implode('', $f);
        unset($f);
        echo '<div id="jdebug">' . $this->text . '</div>';
        echo '</pre><span style="display:none"><![CDATA[</noindex>]]></span>';
    }

    public function db_debug() {
        $profs = database::getInstance()->setQuery('show profiles;')->loadObjectList();

        $r = array();
        $r[] = '<div onclick="$(\'#_sql_debug_log\').toggle();" style="cursor: pointer;border-bottom:1px solid #CCCCCC;border-top:1px solid #CCCCCC;">SQL: ' . count($profs) . '</div>';
        $r[] = '<table id="_sql_debug_log" style="display:none"><tr><th colspan="3"></th></tr>';
        if (isset($profs[0])) {
            foreach ($profs as $prof) {
                $r[] = '<tr valign="top"><td>#' . $prof->Query_ID . ' </td><td> ' . $prof->Duration . ' </td><td> ' . $prof->Query . ' </td></tr>';
            }
        }
        $r[] = '</table>';
        return implode('', $r);
    }
}

/* упрощенная процедура добавления сообщения в лог */
function jd_log($text) {
    jdebug::getInstance()->add($text);
}

/* упрощенная процедура добавления сообщения в начало лога */
function jd_log_top($text) {
    jdebug::getInstance()->add($text, 1);
}

/* счетчики вызывов */
function jd_inc($name = 'counter') {
    jdebug::getInstance()->inc($name);
}

function jd_get() {
    echo jdebug::getInstance()->get();
}
