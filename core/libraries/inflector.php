<?php defined('_JOOS_CORE') or exit;

/**
 * Работа с формами слова
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Inflector
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosInflector
{
    /**
     * Переводит строку в CamelCase
     *
     * @tutorial    joosInflector::camelize('joostina php cms'); => JoostinaPhpCms
     * @tutorial    joosInflector::camelize('my cool class'); => MyCoolClass
     *
     * @param  string $string строка ввода
     * @return string
     */
    public static function camelize($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    /**
     * Переводит строку из CamelCase в under_score
     *
     * @tutorial    joosInflector::underscore('JoostinaPhpCms'); => joostina_php_cms
     * @tutorial    joosInflector::underscore('joosCoreAdmin'); => joos_core_admin
     *
     * @param  string $string строка в CamelCase
     * @return string строка в under_score
     */
    public static function underscore($string)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
    }

    /**
     * Переводит строку из under_score в человекочитаемые слова
     *
     * @tutorial    joosInflector::humanize('joos_Core_Admin'); => Joos Core Admin
     * @tutorial    joosInflector::humanize('function_delete'); => Function delete
     *
     * @param $string
     * @return string
     */
    public static function humanize($string)
    {
        return ucfirst(str_replace('_', ' ', $string));
    }

}
