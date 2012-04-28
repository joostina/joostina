<?php defined('_JOOS_CORE') or exit();

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
class joosHTML {

    // Enable or disable automatic setting of target="_blank"
    public static $windowed_urls = FALSE;

    /**
     * Массив хранения подключенных расширений Jquery
     *
     * @var array
     */
    private static $jqueryplugins;

    /**
     * Подключение JS файла в тело страницы
     *
     * @param string $file путь до js файла
     *
     * @return string код включение js файла
     */
    public static function js_file($file) {
        $file = ( ( strpos($file, '://') === false ) ) ? JPATH_SITE . $file : $file;
        return '<script type="text/javascript" src="' . $file . '"></script>';
    }

    /**
     * Вывод JS кодя в тело страницы
     *
     * @param string $code текст js кода
     *
     * @return string
     */
    public static function js_code($code) {
        return '<script type="text/javascript" charset="utf-8">;' . $code . ';</script>';
    }

    public static function load_jquery($ret = false) {
        joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.js', array('first' => true));
    }

    public static function load_jquery_ui($ret = false) {
        joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.ui/jquery-ui.js');
    }

    public static function load_jquery_ui_css($ret = false, $theme = 'ui-lightness') {
        if (!defined('_JQUERY_UICSS_LOADED')) {
            define('_JQUERY_UICSS_LOADED', 1);
            if ($ret) {
                echo joosHtml::css_file(JPATH_SITE . '/media/js/jquery.ui/themes/' . $theme . '/jquery-ui.css');
            } else {
                joosDocument::instance()->add_css(JPATH_SITE . '/media/js/jquery.ui/themes/' . $theme . '/jquery-ui.css');
            }
        }
    }

    public static function load_jquery_plugins($name, $ret = false, $css = false) {
        // формируем константу-флаг для исключения повтороной загрузки

        if (!isset(self::$jqueryplugins[$name])) {
            // отмечаем плагин в массиве уже подключенных
            self::$jqueryplugins[$name] = true;
            if ($ret) {
                echo joosHtml::js_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '.js');
                echo ( $css ) ? joosHtml::css_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '/' . $name . '.css') : '';
            } else {
                joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '.js');
                $css ? joosDocument::instance()->add_css(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '/' . $name . '.css') : null;
            }
        }
    }

    /**
     * Подключение CSS файла в тело страницы
     *
     * @param string $file  путь до js файла
     * @param string $media парматр media для css файла
     *
     * @return string код включение js файла
     */
    public static function css_file($file, $media = 'all') {
        $file = ( ( strpos($file, '://') === false ) ) ? JPATH_SITE . $file : $file;
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
     * @param string $name название файла значка
     * @param string $size размер значка
     * @return string
     */
    public static function ico($name, $size = '16x16') {
        return sprintf('%s/media/images/icons/%s/candy/%s.png', JPATH_SITE, $size, $name);
    }

    public static function anchor($uri, $title = NULL, $attributes = NULL, $escape_title = true) {

        return // Parsed URL
            '<a href="' . joosFilter::specialurlencode($uri, FALSE) . '"' // Attributes empty? Use an empty string
            . ( is_array($attributes) ? joosHtml::attributes($attributes) : '' ) . '>' // Title empty? Use the parsed URL
            . ( $escape_title ? joosFilter::htmlspecialchars(( ( $title === NULL ) ? $uri : $title), FALSE) : ( ( $title === NULL ) ? $uri : $title ) ) . '</a>';
    }

    public static function attributes($attrs) {
        if (empty($attrs)) {
            return '';
        }

        if (is_string($attrs)) {
            return ' ' . $attrs;
        }

        $compiled = '';
        foreach ($attrs as $key => $val) {
            $compiled .= ' ' . $key . '="' . joosFilter::htmlspecialchars($val) . '"';
        }

        return $compiled;
    }

    public static function make_option($value, $text = '', $value_name = 'value', $text_name = 'text') {

        $obj = new stdClass;
        $obj->$value_name = $value;
        $obj->$text_name = trim($text) ? $text : $value;

        return $obj;
    }

    public static function select_list(array $arr, $tag_name, $tag_attribs, $key, $text, $selected = null, $first_el_key = '*000', $first_el_text = '*000') {

        is_array($arr) ? reset($arr) : null;

        $html = "<select name=\"$tag_name\" $tag_attribs>";

        if ($first_el_key != '*000' && $first_el_text != '*000') {
            $html .= "\n\t<option value=\"$first_el_key\">$first_el_text</option>";
        }

        $count = count($arr);
        for ($i = 0, $n = $count; $i < $n; $i++) {
            $k = $arr[$i]->$key;
            $t = $arr[$i]->$text;
            $id = ( isset($arr[$i]->id) ? $arr[$i]->id : null );

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
                $extra .= ( $k == $selected ? " selected=\"selected\"" : '' );
            }
            $html .= "\n\t<option value=\"" . $k . "\"$extra>" . $t . "</option>";
        }
        $html .= "\n</select>\n";

        return $html;
    }

    public static function select_day($tag_name, $tag_attribs, $selected) {

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

    public static function select_month($tag_name, $tag_attribs, $selected, $type = 0) {

        // месяца для выбора
        $arr_1 = array(
            joosHtml::make_option('01', _JAN),
            joosHtml::make_option('02', _FEB),
            joosHtml::make_option('03', _MAR),
            joosHtml::make_option('04', _APR),
            joosHtml::make_option('05', _MAY),
            joosHtml::make_option('06', _JUN),
            joosHtml::make_option('07', _JUL),
            joosHtml::make_option('08', _AUG),
            joosHtml::make_option('09', _SEP),
            joosHtml::make_option('10', _OCT),
            joosHtml::make_option('11', _NOV),
            joosHtml::make_option('12', _DEC)
        );

        // месяца с правильным склонением
        $arr_2 = array(
            joosHtml::make_option('01', _JAN_2),
            joosHtml::make_option('02', _FEB_2),
            joosHtml::make_option('03', _MAR_2),
            joosHtml::make_option('04', _APR_2),
            joosHtml::make_option('05', _MAY_2),
            joosHtml::make_option('06', _JUN_2),
            joosHtml::make_option('07', _JUL_2),
            joosHtml::make_option('08', _AUG_2),
            joosHtml::make_option('09', _SEP_2),
            joosHtml::make_option('10', _OCT_2),
            joosHtml::make_option('11', _NOV_2),
            joosHtml::make_option('12', _DEC_2)
        );

        $arr = $type ? $arr_2 : $arr_1;
        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function select_year($tag_name, $tag_attribs, $selected, $min = 1900, $max = null) {

        $max = ( $max == null ) ? date('Y', time()) : $max;

        $arr = array();
        for ($i = $min; $i <= $max; $i++) {
            $arr[] = joosHtml::make_option($i, $i);
        }
        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function gender_select_list($tag_name, $tag_attribs, $selected) {

        $arr = array(
            joosHtml::make_option('no_gender', _GENDER_NONE),
            joosHtml::make_option('male', _MALE),
            joosHtml::make_option('female', _FEMALE)
        );
        return joosHtml::select_list($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function id_box($rowNum, $recId, $checkedOut = false, $name = 'cid') {
        return $checkedOut ? '' : '<input class="js-select" type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId . '"  />';
    }

    public static function dropdown( $data , $options = NULL , $selected = NULL , $extra = '' ) {

        if ( !is_array( $data ) ) {
            $data = array ( 'name' => $data );
        } else {
            if ( isset( $data['options'] ) ) {
                // Use data options
                $options = $data['options'];
            }

            if ( isset( $data['selected'] ) ) {
                // Use data selected
                $selected = $data['selected'];
            }
        }

        if ( is_array( $selected ) ) {
            // Multi-select box
            $data['multiple'] = 'multiple';
        } else {
            // Single selection (but converted to an array)
            $selected = array ( $selected );
        }

        $input = '<select' . joosHTML::attributes( $data , 'select' ) . ' ' . $extra . '>' . "\n";
        foreach ( (array) $options as $key => $val ) {
            // Key should always be a string
            $key = (string) $key;

            if ( is_array( $val ) ) {
                $input .= '<optgroup label="' . $key . '">' . "\n";
                foreach ( $val as $inner_key => $inner_val ) {
                    // Inner key should always be a string
                    $inner_key = (string) $inner_key;

                    $sel       = in_array( $inner_key , $selected ) ? ' selected="selected"' : '';
                    $input .= '<option value="' . $inner_key . '"' . $sel . '>' . $inner_val . '</option>' . "\n";
                }
                $input .= '</optgroup>' . "\n";
            } else {
                $sel = in_array( $key , $selected ) ? ' selected="selected"' : '';
                $input .= '<option value="' . $key . '"' . $sel . '>' . $val . '</option>' . "\n";
            }
        }
        $input .= '</select>';

        return $input;
    }

    public static function textarea( $data , $value = '' , $extra = '' , $double_encode = TRUE ) {

        if ( !is_array( $data ) ) {
            $data = array ( 'name' => $data );
        }

        // Use the value from $data if possible, or use $value
        $value = isset( $data['value'] ) ? $data['value'] : $value;

        // Value is not part of the attributes
        unset( $data['value'] );

        return '<textarea' . joosHtml::attributes( $data , 'textarea' ) . ' ' . $extra . '>' . joosFilter::htmlspecialchars( $value , $double_encode ) . '</textarea>';
    }

    public static function input( $data , $value = '' , $extra = '' ) {

        if ( !is_array( $data ) ) {
            $data = array ( 'name' => $data );
        }

        // Type and value are required attributes
        $data += array ( 'type'  => 'text' ,
            'value' => $value );

        return '<input' . joosHtml::attributes( $data ) . ' ' . $extra . ' />';
    }

    public static function label( $data = '' , $text = NULL , $extra = '' ) {

        if ( !is_array( $data ) ) {
            if ( is_string( $data ) ) {
                // Specify the input this label is for
                $data = array ( 'for' => $data );
            } else {
                // No input specified
                $data = array ();
            }
        }

        return '<label class="control-label"' . joosHtml::attributes( $data ) . ' ' . $extra . '>' . $text . '</label>';
    }

    public static function hidden( $data , $value = '' ) {

        if ( !is_array( $data ) ) {
            $data = array ( $data => $value );
        }

        $input = '';
        foreach ( $data as $name => $value ) {
            $attr = array ( 'type'  => 'hidden' ,
                'name'  => $name ,
                'value' => $value );

            $input .= joosHtml::input( $attr ) . "\n";
        }

        return $input;
    }

    public static function checkbox( $data , $value = '' , $checked = FALSE , $extra = '' ) {

        if ( !is_array( $data ) ) {
            $data = array ( 'name' => $data );
        }

        $data['type'] = 'checkbox';

        if ( $checked == TRUE OR ( isset( $data['checked'] ) AND $data['checked'] == TRUE ) ) {
            $data['checked'] = 'checked';
        } else {
            unset( $data['checked'] );
        }

        return joosHtml::input( $data , $value , $extra );
    }

}

// TODO убрать это стаьё
class htmlTabs {

    private $useCookies = 0;
    private static $loaded = false;

    public function htmlTabs($useCookies = false, $xhtml = 0) {

        /* запрет повторного включения css и js файлов в документ */
        if (self::$loaded == false) {
            self::$loaded = true;

            $js_file = JPATH_SITE . '/media/js/tabs.js';
            $css_file = JPATH_SITE . '/media/js/tabs/tabpane.css';

            if ($xhtml) {
                joosDocument::instance()->add_js_file($js_file)->add_css($css_file);
            } else {
                echo joosHtml::css_file($css_file) . "\n\t";
                echo joosHtml::js_file($js_file) . "\n\t";
            }
            $this->useCookies = $useCookies;
        }
    }

    public function startPane($id) {
        echo '<div class="tab-page" id="' . $id . '">';
        echo '<script type="text/javascript">var tabPane1 = new WebFXTabPane( document.getElementById( "' . $id . '" ), ' . $this->useCookies . ' )</script>';
    }

    public function endPane() {
        echo '</div>';
    }

    public function startTab($tabText, $paneid) {
        echo '<div class="tab-page" id="' . $paneid . '">';
        echo '<h2 class="tab">' . $tabText . '</h2>';
        echo '<script type="text/javascript">tabPane1.addTabPage( document.getElementById( "' . $paneid . '" ) );</script>';
    }

    public function endTab() {
        echo '</div>';
    }

}
