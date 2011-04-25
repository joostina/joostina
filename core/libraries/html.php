<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosHTML - Библиотека генерации HTML кода
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosHTML {

	/**
	 * Массив хранения подключенных расширений Jquery
	 * @var array
	 */
	private static $jqueryplugins;

	/**
	 * Подключение JS файла в тело страницы
	 * @param string $file путь до js файла
	 * @return string код включение js файла
	 */
	public static function js_file($file) {
		$file = ((strpos($file, '://') === false)) ? JPATH_SITE . $file : $file;
		return '<script type="text/javascript" src="' . $file . '"></script>';
	}

	/**
	 * Вывод JS кодя в тело страницы
	 * @param string $code текст js кода
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
				echo joosHTML::css_file(JPATH_SITE . '/media/js/jquery.ui/themes/' . $theme . '/jquery-ui.css');
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
				echo joosHTML::js_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '.js');
				echo ($css) ? joosHTML::css_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '/' . $name . '.css') : '';
			} else {
				joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '.js');
				$css ? joosDocument::instance()->add_css(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '/' . $name . '.css') : null;
			}
		}
	}

	/**
	 * Подключение CSS файла в тело страницы
	 * @param string $file путь до js файла
	 * @param string $media парматр media для css файла
	 * @return string код включение js файла
	 */
	public static function css_file($file, $media = 'all') {
		$file = ((strpos($file, '://') === false)) ? JPATH_SITE . $file : $file;
		return '<link rel="stylesheet" type="text/css" media="' . $media . '" href="' . $file . '" />';
	}

	/**
	 * Получение пути до требуемого значка
	 * @param string $name название файла значка
	 * @param string $size размер значка
	 */
	public static function ico($name, $size = '16x16') {
		return sprintf('%s/media/images/icons/%s/candy/%s.png', JPATH_SITE, $size, $name);
	}

	/**
	 * "Обезопасивание" сущностей. Защищает данные от вывода их в прямом HTML формате.
	 * Варианты использования - в формах или небезопасных выводах
	 * @param mixed $mixed строка массив или объект для обезопасивания
	 * @param const $quote_style тип зашиты - ENT_COMPAT, ENT_QUOTES или ENT_NOQUOTES
	 * @param mixed $exclude_keys массив или название ключа массива или поля объекта которые обезопасивать не стоит
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
				} else
				if (is_array($exclude_keys) && in_array($k, $exclude_keys)) {
					continue;
				}
				$mixed->$k = htmlspecialchars($v, $quote_style, 'UTF-8');
			}
		} elseif (is_string($mixed)) {
			return htmlspecialchars($mixed, $quote_style, 'UTF-8');
		}
	}

}