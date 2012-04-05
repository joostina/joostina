<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
  * Библиотека генерации HTML кода
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
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
	 */
	public static function ico($name, $size = '16x16') {
		return sprintf('%s/media/images/icons/%s/candy/%s.png', JPATH_SITE, $size, $name);
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
	public static function make_safe(&$mixed, $quote_style = ENT_QUOTES, $exclude_keys = '') {
		if (is_object($mixed)) {
			foreach (get_object_vars($mixed) as $k => $v) {
				if (is_array($v) || is_object($v) || $v == null || substr($k, 1, 1) == '_') {
					continue;
				}
				if (is_string($exclude_keys) && $k == $exclude_keys) {
					continue;
				} else if (is_array($exclude_keys) && in_array($k, $exclude_keys)) {
					continue;
				}
				$mixed->$k = htmlspecialchars($v, $quote_style, 'UTF-8');
			}
		} elseif (is_string($mixed)) {
			return htmlspecialchars($mixed, $quote_style, 'UTF-8');
		}
	}

	/**
	 * Convert special characters to HTML entities
	 *
	 * @param   string   string to convert
	 * @param   boolean  encode existing entities
	 *
	 * @return  string
	 */
	public static function specialchars($str, $double_encode = TRUE) {
		// Force the string to be a string
		$str = (string) $str;

		// Do encode existing HTML entities (default)
		if ($double_encode === TRUE) {
			$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
		} else {
			$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8', FALSE);
		}

		return $str;
	}

	/**
	 * Perform a joosHtml::specialchars() with additional URL specific encoding.
	 *
	 * @param   string   string to convert
	 * @param   boolean  encode existing entities
	 *
	 * @return  string
	 */
	public static function specialurlencode($str, $double_encode = TRUE) {
		return str_replace(' ', '%20', joosHtml::specialchars($str, $double_encode));
	}

	/**
	 * Create HTML link anchors.
	 *
	 * @param   string  URL or URI string
	 * @param   string  link text
	 * @param   array   HTML anchor attributes
	 * @param   string  non-default protocol, eg: https
	 * @param   boolean option to escape the title that is output
	 *
	 * @return  string
	 */
	public static function anchor($uri, $title = NULL, $attributes = NULL, $protocol = NULL, $escape_title = FALSE) {
		if ($uri === '') {
			$site_url = JPATH_SITE;
		} elseif (strpos($uri, '#') === 0) {
			// This is an id target link, not a URL
			$site_url = $uri;
		} else {
			if (joosHtml::$windowed_urls === TRUE AND empty($attributes['target'])) {
				$attributes['target'] = '_blank';
			}

			$site_url = $uri;
		}

		return // Parsed URL
				'<a href="' . joosHtml::specialurlencode($site_url, FALSE) . '"' // Attributes empty? Use an empty string
				. ( is_array($attributes) ? joosHtml::attributes($attributes) : '' ) . '>' // Title empty? Use the parsed URL
				. ( $escape_title ? joosHtml::specialchars(( ( $title === NULL ) ? $site_url : $title), FALSE) : ( ( $title === NULL ) ? $site_url : $title ) ) . '</a>';
	}

	/**
	 * Creates an HTML anchor to a file.
	 *
	 * @param   string  name of file to link to
	 * @param   string  link text
	 * @param   array   HTML anchor attributes
	 * @param   string  non-default protocol, eg: ftp
	 *
	 * @return  string
	 */
	public static function file_anchor($file, $title = NULL, $attributes = NULL, $protocol = NULL) {
		return // Base URL + URI = full URL
				'<a href="' . joosHtml::specialurlencode(url::base(FALSE, $protocol) . $file, FALSE) . '"' // Attributes empty? Use an empty string
				. ( is_array($attributes) ? joosHtml::attributes($attributes) : '' ) . '>' // Title empty? Use the filename part of the URI
				. ( ( $title === NULL ) ? end(explode('/', $file)) : $title ) . '</a>';
	}

	/**
	 * Similar to anchor, but with the protocol parameter first.
	 *
	 * @param   string  link protocol
	 * @param   string  URI or URL to link to
	 * @param   string  link text
	 * @param   array   HTML anchor attributes
	 *
	 * @return  string
	 */
	public static function panchor($protocol, $uri, $title = NULL, $attributes = FALSE) {
		return joosHtml::anchor($uri, $title, $attributes, $protocol);
	}

	/**
	 * Create an array of anchors from an array of link/title pairs.
	 *
	 * @param   array  link/title pairs
	 *
	 * @return  array
	 */
	public static function anchor_array(array $array) {
		$anchors = array();
		foreach ($array as $link => $title) {
			// Create list of anchors
			$anchors[] = joosHtml::anchor($link, $title);
		}
		return $anchors;
	}

	/**
	 * Generates an obfuscated version of an email address.
	 *
	 * @param   string  email address
	 *
	 * @return  string
	 */
	public static function email($email) {
		$safe = '';
		foreach (str_split($email) as $letter) {
			switch (( $letter === '@' ) ? rand(1, 2) : rand(1, 3)) {
				// HTML entity code
				case 1:
					$safe .= '&#' . ord($letter) . ';';
					break;
				// Hex character code
				case 2:
					$safe .= '&#x' . dechex(ord($letter)) . ';';
					break;
				// Raw (no) encoding
				case 3:
					$safe .= $letter;
			}
		}

		return $safe;
	}

	/**
	 * Creates an email anchor.
	 *
	 * @param   string  email address to send to
	 * @param   string  link text
	 * @param   array   HTML anchor attributes
	 *
	 * @return  string
	 */
	public static function mailto($email, $title = NULL, $attributes = NULL) {
		if (empty($email)) {
			return $title;
		}

		// Remove the subject or other parameters that do not need to be encoded
		if (strpos($email, '?') !== FALSE) {
			// Extract the parameters from the email address
			list ( $email, $params ) = explode('?', $email, 2);

			// Make the params into a query string, replacing spaces
			$params = '?' . str_replace(' ', '%20', $params);
		} else {
			// No parameters
			$params = '';
		}

		// Obfuscate email address
		$safe = joosHtml::email($email);

		// Title defaults to the encoded email address
		empty($title) and $title = $safe;

		// Parse attributes
		empty($attributes) or $attributes = joosHtml::attributes($attributes);

		// Encoded start of the href="" is a static encoded version of 'mailto:'
		return '<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;' . $safe . $params . '"' . $attributes . '>' . $title . '</a>';
	}

	/**
	 * Creates a stylesheet link.
	 *
	 * @param   string|array  filename      , or array of filenames to match to array of medias
	 * @param   string|array  media         type of stylesheet, or array to match filenames
	 * @param                 boolean       include the index_page in the link
	 *
	 * @return  string
	 */
	public static function stylesheet($style, $media = FALSE, $index = FALSE) {
		return joosHtml::link($style, 'stylesheet', 'text/css', '.css', $media, $index);
	}

	/**
	 * Creates a link tag.
	 *
	 * @param   string|array  filename
	 * @param   string|array  relationship
	 * @param   string|array  mimetype
	 * @param                 string        specifies suffix of the file
	 * @param   string|array  specifies     on what device the document will be displayed
	 * @param                 boolean       include the index_page in the link
	 *
	 * @return  string
	 */
	public static function link($href, $rel, $type, $suffix = FALSE, $media = FALSE, $index = FALSE) {
		$compiled = '';

		if (is_array($href)) {
			foreach ($href as $_href) {
				$_rel = is_array($rel) ? array_shift($rel) : $rel;
				$_type = is_array($type) ? array_shift($type) : $type;
				$_media = is_array($media) ? array_shift($media) : $media;

				$compiled .= joosHtml::link($_href, $_rel, $_type, $suffix, $_media, $index);
			}
		} else {
			if (strpos($href, '://') === FALSE) {
				// Make the URL absolute
				$href = url::base($index) . $href;
			}

			$length = strlen($suffix);

			if ($length > 0 AND substr_compare($href, $suffix, -$length, $length, FALSE) !== 0) {
				// Add the defined suffix
				$href .= $suffix;
			}

			$attr = array('rel' => $rel,
				'type' => $type,
				'href' => $href,);

			if (!empty($media)) {
				// Add the media type to the attributes
				$attr['media'] = $media;
			}

			$compiled = '<link' . joosHtml::attributes($attr) . ' />';
		}

		return $compiled . "\n";
	}

	/**
	 * Creates a script link.
	 *
	 * @param   string|array  filename
	 * @param                 boolean       include the index_page in the link
	 *
	 * @return  string
	 */
	public static function script($script, $index = FALSE) {
		$compiled = '';

		if (is_array($script)) {
			foreach ($script as $name) {
				$compiled .= joosHtml::script($name, $index);
			}
		} else {
			if (strpos($script, '://') === FALSE) {
				// Add the suffix only when it's not already present
				$script = url::base((bool) $index) . $script;
			}

			if (substr_compare($script, '.js', -3, 3, FALSE) !== 0) {
				// Add the javascript suffix
				$script .= '.js';
			}

			$compiled = '<script type="text/javascript" src="' . $script . '"></script>';
		}

		return $compiled . "\n";
	}

	/**
	 * Creates a image link.
	 *
	 * @param                 string        image source, or an array of attributes
	 * @param   string|array  image         alt attribute, or an array of attributes
	 * @param                 boolean       include the index_page in the link
	 *
	 * @return  string
	 */
	public static function image($src = NULL, $alt = NULL, $index = FALSE) {
		// Create attribute list
		$attributes = is_array($src) ? $src : array('src' => $src);

		if (is_array($alt)) {
			$attributes += $alt;
		} elseif (!empty($alt)) {
			// Add alt to attributes
			$attributes['alt'] = $alt;
		}

		if (strpos($attributes['src'], '://') === FALSE) {
			// Make the src attribute into an absolute URL
			$attributes['src'] = url::base($index) . $attributes['src'];
		}

		return '<img' . joosHtml::attributes($attributes) . ' />';
	}

	/**
	 * Compiles an array of HTML attributes into an attribute string.
	 *
	 * @param   string|array  array of attributes
	 *
	 * @return  string
	 */
	public static function attributes($attrs) {
		if (empty($attrs)) {
			return '';
		}

		if (is_string($attrs)) {
			return ' ' . $attrs;
		}

		$compiled = '';
		foreach ($attrs as $key => $val) {
			$compiled .= ' ' . $key . '="' . joosHtml::specialchars($val) . '"';
		}

		return $compiled;
	}

	public static function make_option($value, $text = '', $value_name = 'value', $text_name = 'text') {
		$obj = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name = trim($text) ? $text : $value;
		return $obj;
	}

	public static function selectList(array $arr, $tag_name, $tag_attribs, $key, $text, $selected = null, $first_el_key = '*000', $first_el_text = '*000') {

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

	public static function select_month($tag_name, $tag_attribs, $selected, $type = 0) {
		// месяца для выбора
		$arr_1 = array(joosHtml::make_option('01', _JAN), joosHtml::make_option('02', _FEB), joosHtml::make_option('03', _MAR), joosHtml::make_option('04', _APR), joosHtml::make_option('05', _MAY), joosHtml::make_option('06', _JUN), joosHtml::make_option('07', _JUL), joosHtml::make_option('08', _AUG), joosHtml::make_option('09', _SEP), joosHtml::make_option('10', _OCT), joosHtml::make_option('11', _NOV), joosHtml::make_option('12', _DEC));
		// месяца с правильным склонением
		$arr_2 = array(joosHtml::make_option('01', _JAN_2), joosHtml::make_option('02', _FEB_2), joosHtml::make_option('03', _MAR_2), joosHtml::make_option('04', _APR_2), joosHtml::make_option('05', _MAY_2), joosHtml::make_option('06', _JUN_2), joosHtml::make_option('07', _JUL_2), joosHtml::make_option('08', _AUG_2), joosHtml::make_option('09', _SEP_2), joosHtml::make_option('10', _OCT_2), joosHtml::make_option('11', _NOV_2), joosHtml::make_option('12', _DEC_2));
		$arr = $type ? $arr_2 : $arr_1;
		return joosHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
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

		return joosHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}

	public static function select_year($tag_name, $tag_attribs, $selected, $min = 1900, $max = null) {

		$max = ( $max == null ) ? date('Y', time()) : $max;

		$arr = array();
		for ($i = $min; $i <= $max; $i++) {
			$arr[] = joosHtml::make_option($i, $i);
		}
		return joosHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}

	public static function genderSelectList($tag_name, $tag_attribs, $selected) {
		$arr = array(joosHtml::make_option('no_gender', _GENDER_NONE), joosHtml::make_option('male', _MALE), joosHtml::make_option('female', _FEMALE));
		return joosHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}

	public static function yesnoSelectList($tag_name, $tag_attribs, $selected, $yes = _YES, $no = _NO) {
		$arr = array(joosHtml::make_option('0', $no), joosHtml::make_option('1', $yes));

		return joosHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}

	public static function radioList(&$arr, $tag_name, $tag_attribs, $selected = null, $key = 'value', $text = 'text') {
		reset($arr);

		$html = '';
		for ($i = 0, $n = count($arr); $i < $n; $i++) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = isset($arr[$i]->id) ? $arr[$i]->id : null;

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
				$extra .= ( $k == $selected ? " checked=\"checked\"" : '' );
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"" . $k . "\"$extra $tag_attribs />";
			$html .= "\n\t<label for=\"$tag_name$k\">$t</label>";
		}
		$html .= "\n";

		return $html;
	}

	public static function yesnoRadioList($tag_name, $tag_attribs, $selected, $yes = _YES, $no = _NO) {
		$arr = array(joosHtml::make_option('0', $no), joosHtml::make_option('1', $yes));

		return joosHtml::radioList($arr, $tag_name, $tag_attribs, $selected);
	}

	public static function idBox($rowNum, $recId, $checkedOut = false, $name = 'cid') {
		return $checkedOut ? '' : '<input class="js-select" type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId . '"  />';
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
