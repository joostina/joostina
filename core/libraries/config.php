<?php defined('_JOOS_CORE') or exit();

/**
 * Библиотека управления параметрами
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Config
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * @todo       рассмотреть возможность использования SPL ArrayObject
 *
 * */
class joosConfig {

	private static $data = array();

	public static function init() {
		if (empty(self::$data)) {
			$conf = require_once JPATH_APP_CONFIG . DS . 'site.php';
			self::$data = $conf;
		}
	}

	public static function get_all() {
		return self::$data;
	}

	public static function get($name, $default = null) {
        $name_array = explode(':', $name);
        $count = count($name_array);

        if($count == 1){
            return isset(self::$data[$name]) ? self::$data[$name] : $default;
        }

        //@todo собрать в алгоритм
        else{

            switch($count){
                case 2:
                default:
                    return
                        isset(self::$data[$name_array[0]][$name_array[1]])
                            ? self::$data[$name_array[0]][$name_array[1]]
                            : $default;
                break;

                case 3:
                    return
                        isset(self::$data[$name_array[0][$name_array[1][$name_array[2]]]])
                            ? self::$data[$name_array[0][$name_array[1][$name_array[2]]]]
                            : $default;
                break;

                case 4:
                    return
                        isset(self::$data[$name_array[0][$name_array[1][$name_array[2][$name_array[3]]]]])
                            ? self::$data[$name_array[0][$name_array[1][$name_array[2][$name_array[3]]]]]
                            : $default;
                break;
            }

        }

	}

    /**
     *@deprecated
     */
	public static function get2($type, $name, $default = null) {
        return self::get($type.':'.$name, $default);
	}

	public static function set($name, $value) {
		self::$data[$name] = $value;
	}

}
