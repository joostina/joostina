<?php defined('_JOOS_CORE') or exit;

/**
 * Библиотека генерации HTML кода
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Html
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosHTML
{
    /**
     * Подключение JS файла в тело страницы
     *
     * @param string $file путь до js файла
     *
     * @return string код включение js файла
     */
    public static function js_file($file)
    {
        $file = ((strpos($file, '://') === false)) ? JPATH_SITE . $file : $file;

        return '<script type="text/javascript" src="' . $file . '"></script>';
    }

    /**
     * Вывод JS кодя в тело страницы
     *
     * @param string $code текст js кода
     *
     * @return string
     */
    public static function js_code($code)
    {
        return '<script type="text/javascript" charset="utf-8">;' . $code . ';</script>';
    }

    /**
     * Подключение CSS файла в тело страницы
     *
     * @param string $file  путь до js файла
     * @param string $media парматр media для css файла
     *
     * @return string код включение js файла
     */
    public static function css_file($file, $media = 'all')
    {
        $file = ((strpos($file, '://') === false)) ? JPATH_SITE . $file : $file;

        return '<link rel="stylesheet" type="text/css" media="' . $media . '" href="' . $file . '" />';
    }

    /**
     * Получение пути до требуемого значка
     * В системе используются значки 2х размеров - 16x16 и 32x32
     * Функция по умолчанию выводит путь до значка 16x16
     *
     * @tutorial joosHtml::ico('filenew') => /media/images/icons/16x16/candy/filenew.png
     * @tutorial joosHtml::ico('filenew', '32x32') => /media/images/icons/32x32/candy/filenew.png
     *
     * @param  string $name название файла значка
     * @param  string $size размер значка
     * @return string
     */
    public static function ico($name, $size = '16x16')
    {
        return sprintf('%s/media/images/icons/%s/candy/%s.png', JPATH_SITE, $size, $name);
    }

    /**
     * Вывод ссылки
     *
     * @param $uri адрес
     * @param  null       $title        название и title ссылки
     * @param  array|null $attributes   дополнительные атрибуты ссылки
     * @param  bool       $escape_title экранирование html сущностей названия ссылки
     * @return string
     */
    public static function anchor($uri, $title = NULL, $attributes = NULL, $escape_title = true)
    {
        return '<a href="' . joosFilter::specialurlencode($uri, FALSE) . '"' . (is_array($attributes) ? joosHtml::attributes($attributes) : '') . '>' . ($escape_title ? joosFilter::htmlspecialchars((($title === NULL) ? $uri : $title), FALSE) : (($title === NULL) ? $uri : $title)) . '</a>';
    }

    /**
     * Создание одного элемента для выпадающего списка select_list
     *
     * @param $value значение элемента
     * @param  string   $text       название
     * @param  string   $value_name название элемента значения
     * @param  string   $text_name  значение элемента значения
     * @return stdClass
     */
    public static function make_option($value, $text = '', $value_name = 'value', $text_name = 'text')
    {
        $obj = new stdClass;
        $obj->$value_name = $value;
        $obj->$text_name = trim($text) ? $text : $value;

        return $obj;
    }

    public static function select_list(array $arr, $tag_name, $tag_attribs, $key, $text, $selected = null, $first_el_key = '*000', $first_el_text = '*000')
    {
        is_array($arr) ? reset($arr) : null;

        $html = "<select name=\"$tag_name\" $tag_attribs>";

        if ($first_el_key != '*000' && $first_el_text != '*000') {
            $html .= "\n\t<option value=\"$first_el_key\">$first_el_text</option>";
        }

        $count = count($arr);
        for ($i = 0, $n = $count; $i < $n; $i++) {
            $k = $arr[$i]->$key;
            $t = $arr[$i]->$text;
            $id = (isset($arr[$i]->id) ? $arr[$i]->id : null);

            $extra = '';
            $extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
            if (is_array($selected)) {
                foreach ($selected as $obj) {
                    $k2 = $obj->$key;
                    if ($k == $k2) {
                        $extra .= " selected=\"selected\"";
                        break;
                    }
                }
            } else {
                $extra .= ($k == $selected ? " selected=\"selected\"" : '');
            }
            $html .= "\n\t<option value=\"" . $k . "\"$extra>" . $t . "</option>";
        }
        $html .= "\n</select>\n";

        return $html;
    }

    public static function select_day($tag_name, $tag_attribs, $selected)
    {
        $arr = array();

        for ($i = 1; $i <= 31; $i++) {
            $pref = '';
            if ($i <= 9) {
                $pref = '0';
            }
            $arr[] = joosHtml::make_option($pref . $i, $pref . $i);
        }

        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function select_month($tag_name, $tag_attribs, $selected, $type = 0)
    {
        // месяца для выбора
        $arr_1 = array(
            joosHtml::make_option('01', 'Январь'),
            joosHtml::make_option('02', 'Февраль'),
            joosHtml::make_option('03', 'Март'),
            joosHtml::make_option('04', 'Апрель'),
            joosHtml::make_option('5', 'Май'),
            joosHtml::make_option('06', 'Июнь'),
            joosHtml::make_option('07', 'Июль'),
            joosHtml::make_option('08', 'Август'),
            joosHtml::make_option('09', 'Сентябрь'),
            joosHtml::make_option('10', 'Октябрь'),
            joosHtml::make_option('11', 'Ноябрь'),
            joosHtml::make_option('12', 'Декабрь')
        );

        // месяца с правильным склонением
        $arr_2 = array(
            joosHtml::make_option('01', 'Января'),
            joosHtml::make_option('02', 'Февраля'),
            joosHtml::make_option('03', 'Марта'),
            joosHtml::make_option('04', 'Апреля'),
            joosHtml::make_option('05', 'Мая'),
            joosHtml::make_option('06', 'Июня'),
            joosHtml::make_option('07', 'Июля'),
            joosHtml::make_option('08', 'Августа'),
            joosHtml::make_option('09', 'Сентября'),
            joosHtml::make_option('10', 'Октября'),
            joosHtml::make_option('11', 'Ноября'),
            joosHtml::make_option('12', 'Декабря')
        );

        $arr = $type ? $arr_2 : $arr_1;

        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function select_year($tag_name, $tag_attribs, $selected, $min = 1900, $max = null)
    {
        $max = ($max == null) ? date('Y', time()) : $max;

        $arr = array();
        for ($i = $min; $i <= $max; $i++) {
            $arr[] = joosHtml::make_option($i, $i);
        }

        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function gender_select_list($tag_name, $tag_attribs, $selected)
    {
        $arr = array(joosHtml::make_option('no_gender', 'Не указано'), joosHtml::make_option('male', 'М'), joosHtml::make_option('female', 'Ж'));

        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function id_box($rowNum, $recId, $checkedOut = false, $name = 'cid')
    {
        return $checkedOut ? '' : '<input class="js-select" type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId . '"  />';
    }

    public static function dropdown($data, $options = NULL, $selected = NULL, $extra = '')
    {
        if (!is_array($data)) {
            $data = array('name' => $data);
        } else {
            if (isset($data['options'])) {
                // Use data options
                $options = $data['options'];
            }

            if (isset($data['selected'])) {
                // Use data selected
                $selected = $data['selected'];
            }
        }

        if (is_array($selected)) {
            // Multi-select box
            $data['multiple'] = 'multiple';
        } else {
            // Single selection (but converted to an array)
            $selected = array($selected);
        }

        $input = '<select' . joosHTML::attributes($data, 'select') . ' ' . $extra . '>' . "\n";

        foreach ((array) $options as $key => $val) {
            // Key should always be a string
            $key = (string) $key;

            if (is_array($val)) {
                $input .= '<optgroup label="' . $key . '">' . "\n";
                foreach ($val as $inner_key => $inner_val) {
                    // Inner key should always be a string
                    $inner_key = (string) $inner_key;

                    $sel = in_array($inner_key, $selected) ? ' selected="selected"' : '';
                    $input .= '<option value="' . $inner_key . '"' . $sel . '>' . $inner_val . '</option>' . "\n";
                }
                $input .= '</optgroup>' . "\n";
            } else {
                $sel = in_array($key, $selected) ? ' selected="selected"' : '';
                $input .= '<option value="' . $key . '"' . $sel . '>' . $val . '</option>' . "\n";
            }
        }
        $input .= '</select>';

        return $input;
    }

    public static function textarea($data, $value = '', $extra = '', $double_encode = TRUE)
    {
        if (!is_array($data)) {
            $data = array('name' => $data);
        }

        $value = isset($data['value']) ? $data['value'] : $value;

        unset($data['value']);

        return '<textarea' . joosHtml::attributes($data, 'textarea') . ' ' . $extra . '>' . joosFilter::htmlspecialchars($value, $double_encode) . '</textarea>';
    }

    public static function input($data, $value = '', $extra = '')
    {
        if (!is_array($data)) {
            $data = array('name' => $data);
        }

        $data += array('type' => 'text', 'value' => $value);

        return '<input' . joosHtml::attributes($data) . ' ' . $extra . ' />';
    }

    public static function label($data = '', $text = NULL, $extra = '')
    {
        if (!is_array($data)) {
            if (is_string($data)) {
                $data = array('for' => $data);
            } else {
                $data = array();
            }
        }

        return '<label class="control-label"' . joosHtml::attributes($data) . ' ' . $extra . '>' . $text . '</label>';
    }

    public static function hidden($data, $value = '')
    {
        if (!is_array($data)) {
            $data = array($data => $value);
        }

        $input = '';
        foreach ($data as $name => $value) {
            $attr = array('type' => 'hidden', 'name' => $name, 'value' => $value);

            $input .= joosHtml::input($attr) . "\n";
        }

        return $input;
    }

    public static function checkbox($data, $value = '', $checked = FALSE, $extra = '')
    {
        if (!is_array($data)) {
            $data = array('name' => $data);
        }

        $data['type'] = 'checkbox';

        if ($checked == TRUE OR (isset($data['checked']) AND $data['checked'] == TRUE)) {
            $data['checked'] = 'checked';
        } else {
            unset($data['checked']);
        }

        return joosHtml::input($data, $value, $extra);
    }

    /**
     * Вывод расширенных элементов html тега
     *
     * @param  string|array $attrs строка или массив параметров
     * @return string
     */
    private static function attributes($attrs)
    {
        if (empty($attrs)) {
            return '';
        }

        if (is_string($attrs)) {
            return ' ' . $attrs;
        }

        $compiled = '';
        foreach ($attrs as $key => $val) {

            // @todo тут что-то не правильно!!!
            if (is_array($val)) {
                continue;
            }

            $compiled .= ' ' . $key . '="' . joosFilter::htmlspecialchars($val) . '"';
        }

        return $compiled;
    }

    /**
     * Очистка HTML кода от мусорных символов
     *
     * @static
     * @param  string $content строка с html для очистки
     * @return string
     */
    public static function prepare_for_ajax_output($content)
    {
        $content = str_replace(array("\n", "\t", "\r"), ' ', $content);
        $content = preg_replace("/\s\s+/iu", ' ', $content);
        $content = preg_replace("/>\s+</iu", '><', $content);

        return $content;
    }

}
