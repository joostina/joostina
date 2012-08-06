<?php defined('_JOOS_CORE') or exit;

/**
 * Библиотека фильтрации данных
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Filter
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFilter
{
    /**
     * Преобразует символы в соответствующие HTML сущности
     *
     * @param string $value      строка для
     * @param string $quoteStyle одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
     *
     * @return string преобразованная строка
     */
    public static function htmlentities($value, $quoteStyle = ENT_QUOTES)
    {
        return htmlentities($value, $quoteStyle, 'UTF-8');
    }

    /**
     * Преобразует специальные символы в HTML сущности
     *
     * @param string $value      строка для
     * @param string $quoteStyle - одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
     *
     * @return string преобразованная строка
     */
    public static function htmlspecialchars($value, $quoteStyle = ENT_QUOTES)
    {
        return htmlspecialchars($value, $quoteStyle, 'UTF-8');
    }

    /**
     * "Обезопасивание" сущностей. Защищает данные от вывода их в прямом HTML формате.
     * Варианты использования - в формах или небезопасных выводах
     *
     * @param mixed $mixed        строка массив или объект для обезопасивания
     * @param const $quote_style  тип зашиты - ENT_COMPAT, ENT_QUOTES или ENT_NOQUOTES
     * @param mixed $exclude_keys массив или название ключа массива или поля объекта которые обезопасивать не стоит
     *
     * @return mixed обезопасенная сущность
     */
    public static function make_safe(&$mixed, $quote_style = ENT_QUOTES, $exclude_keys = '')
    {
        if (is_object($mixed)) {
            foreach (get_object_vars($mixed) as $k => $v) {
                if (is_array($v) || is_object($v) || $v == null || substr($k, 1, 1) == '_') {
                    continue;
                }
                if (is_string($exclude_keys) && $k == $exclude_keys) {
                    continue;
                } elseif (is_array($exclude_keys) && in_array($k, $exclude_keys)) {
                    continue;
                }
                $mixed->$k = htmlspecialchars($v, $quote_style, 'UTF-8');
            }
        } elseif (is_string($mixed)) {
            return htmlspecialchars($mixed, $quote_style, 'UTF-8');
        }
    }

    /**
     * Perform a joosHtml::specialchars() with additional URL specific encoding.
     *
     * @param   string   string to convert
     * @param   boolean  encode existing entities
     *
     * @return string
     */
    public static function specialurlencode($str, $double_encode = TRUE)
    {
        return str_replace(' ', '%20', joosFilter::htmlspecialchars($str, $double_encode));
    }

}
