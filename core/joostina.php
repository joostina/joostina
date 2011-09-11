<?php

/**
 * Ядро
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: joostina.php 238 2011-03-13 13:24:57Z LeeHarvey $
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);
// корень файлов
define('JPATH_BASE', dirname(__DIR__));

// Обработчик ошибок
require JPATH_BASE . DS . 'core' . DS . 'exception.php';
// Автозагрузчик
require JPATH_BASE . DS . 'core' . DS . 'autoloader.php';
// предстартовые конфигурации
require JPATH_BASE . DS . 'app' . DS . 'bootstrap.php';

/**
 * Главное ядро Joostina CMS
 * @package Joostina
 * @subpackage Core
 */
class joosCore {

	/**
	 * Флаг работы ядра в режиме FALSE - сайт, TRUE - панель управления
	 * @var bool
	 */
	private static $is_admin = false;

	/**
	 * Получение инстанции текущего авторизованного пользователя
	 * Функция поддерживает работу и на фронте и в панели управления сайта
	 *
	 * @example joosCore::user() => Объект пользователя Users
	 *
	 * @return Users
	 */
	public static function user() {
		return self::$is_admin ? joosCoreAdmin::user() : Users::instance();
	}

	public static function admin() {
		self::$is_admin = TRUE;
	}

	public static function is_admin() {
		return (bool) self::$is_admin;
	}

	/**
	 * Вычисление пути расположений файлов
	 * @static
	 * @param string $name название объекта
	 * @param string $type тип объекта, компонент, модуль
	 * @param string $cat категория ( для библиотек )
	 * @return bool|string
	 */
	public static function path($name, $type, $cat = '') {

		(JDEBUG && $name != 'debug') ? joosDebug::inc(sprintf('joosCore::%s - <b>%s</b>', $type, $name)) : null;

		switch ($type) {
			case 'controller':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.php';
				break;

			case 'admin_controller':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.admin.' . $name . '.php';
				break;

			case 'ajax_controller':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.ajax.php';
				break;

			case 'model':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.' . $name . '.php';
				break;

			case 'admin_model':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.admin.' . $name . '.php';
				break;

			case 'view':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'views' . DS . $cat . DS . 'default.php';
				break;

			case 'admin_view':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'admin_views' . DS . $cat . DS . 'default.php';
				break;

			case 'admin_template_html':
				$file = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'html' . DS . $name . '.php';
				break;


			case 'module_helper':
				$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';
				break;

			case 'module_admin_helper':
				$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';
				break;

			case 'lib':
				$file = JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . $name . '.php';
				break;

			case 'lib-vendor':
				$file = JPATH_BASE . DS . 'app' . DS . 'vendors' . DS . $cat . DS . $name . DS . $name . '.php';
				break;

			default:
				break;
		}

		if (JDEBUG && !is_file($file)) {
			throw new joosCoreException('Не найден требуемый файл :file для типа :name',
					array(':file' => $file, ':name' => ($cat ? sprintf('%s ( %s )', $name, $type) : $name )));
		}

		return $file;
	}

}

/**
 * Класс работы со страницой выдаваемой в браузер
 * @package Joostina
 * @subpackage Document
 */
class joosDocument {

	private static $instance;
	private static $title_separator = ' - ';
	public static $page_body;
	public static $data = array(
		'title' => array(),
		'meta' => array(),
		'custom' => array(), //JS-файлы
		'js_files' => array(), //Исполняемый код, подключаемый ПОСЛЕ js-файлов
		'js_code' => array(),
		'js_onready' => array(),
		'css' => array(),
		'header' => array(),
		'pathway' => array(),
		'pagetitle' => false,
		'page_body' => false,
		'html_body' => false,
		'footer' => array(),
	);
	public static $config = array(
		'favicon' => true, 'seotag' => true,
	);
	public static $seotag = array(
		'distribution' => 'global',
		'rating' => 'General',
		'document-state' => 'Dynamic',
		'documentType' => 'WebDocument',
		'audience' => 'all',
		'revisit' => '5 days',
		'revisit-after' => '5 days',
		'allow-search' => 'yes',
		'language' => 'russian',
		'robots' => 'index, follow',
	);
	// время кэширования страницы браузером, в секундах
	public static $cache_header_time = false;

	private function __construct() {
		
	}

	/**
	 *
	 * @return joosDocument
	 */
	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new self;
			self::$data['title'] = array(joosConfig::get2('info', 'title'));
		}
		return self::$instance;
	}

	public static function get_data($name) {
		return isset(self::$data[$name]) ? self::$data[$name] : false;
	}

	public static function set_body($body) {
		self::$data['page_body'] = $body;
	}

	public static function get_body() {
		return self::$data['page_body'];
	}

	/**
	 * Полностью заменяет заголовок страницы на переданный
	 *
	 * @param string $title Заголовок страницы
	 * @param string $pagetitle Название страницы
	 * @return joosDocument
	 */
	public function set_page_title($title = '', $pagetitle = '') {

		// title страницы
		$title = $title ? $title : joosConfig::get2('info', 'title');
		self::$data['title'] = array($title);

		// название страницы, не title!
		self::$data['pagetitle'] = $pagetitle ? $pagetitle : $title;

		return $this;
	}

	/**
	 * Добавляет строку в массив с фрагментами заголовка
	 *
	 * @param string $title Фрагмент заголовка страницы
	 * @return joosDocument
	 */
	public function add_title($title = '') {
		self::$data['title'][] = $title;
	}

	/**
	 * Возвращает заголовок страницы, собранный из фрагментов, отсортированных в обратном порядке
	 * @return string Заголовок
	 */
	public static function get_title() {
		$title = array_reverse(self::$data['title']);
		return implode(' / ', $title);
	}

	public function add_meta_tag($name, $content) {
		$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		self::$data['meta'][] = array($name, $content);

		return $this;
	}

	public function append_meta_tag($name, $content) {
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
				if ($content != '' & self::$data['meta'][$i][1] == "") {
					self::$data['meta'][$i][1] .= ' ' . $content;
				}
				;
				return;
			}
		}

		$this->add_meta_tag($name, $content);
	}

	function prepend_meta_tag($name, $content) {
		$name = joosString::trim(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = joosString::trim(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
				self::$data['meta'][$i][1] = $content . self::$data['meta'][$i][1];
				return;
			}
		}
		self::instance()->add_meta_tag($name, $content);
	}

	function add_custom_head_tag($html) {
		self::$data['custom'][] = trim($html);

		return $this;
	}

	function add_custom_footer_tag($html) {
		self::$data['custom'][] = trim($html);

		return $this;
	}

	public function get_head() {

		$head = array();
		$head[] = isset(self::$data['title']) ? "\t" . '<title>' . self::get_title() . '</title>' : false;

		foreach (self::$data['meta'] as $meta) {
			$head[] = '<meta name="' . $meta[0] . '" content="' . $meta[1] . '" />';
		}

		foreach (self::$data['custom'] as $html) {
			$head[] = $html;
		}

		return implode("\n\t", $head) . "\n";
	}

	/**
	 * Подключение JS файла
	 * @param string $path полный путь до файла
	 * @param array $params массив дополнительных параметров подключения файла
	 * @return joosDocument
	 */
	public function add_js_file($path, $params = array('first' => false)) {

		if (isset($params['first']) && $params['first'] == true) {
			array_unshift(self::$data['js_files'], $path);
		} else {
			self::$data['js_files'][] = $path;
		}

		/**
		  @var $this self */
		return $this;
	}

	public function add_js_code($code) {
		self::$data['js_code'][] = $code;
		return $this;
	}

	public function add_js_vars($code) {
		self::$data['js_vars'][] = $code;
		return $this;
	}

	public function add_css($path, $params = array('media' => 'all')) {
		self::$data['css'][] = array($path, $params);

		return $this;
	}

	public function seo_tag($name, $value) {
		self::$seotag[$name] = $value;

		return $this;
	}

	public static function javascript() {

		$result = '';
		$result .= JSCSS_CACHE ? self::js_files_cache() : self::js_files();
		echo $result .= self::js_code();

		//return $result . "\n";
	}

	public static function js_files() {
		$result = array();

		foreach (self::$data['js_files'] as $js_file) {
			// если включена отладка - то будет добавлять антикеш к имени файла
			$result[] = joosHtml::js_file($js_file . (JDEBUG ? '?' . time() : false));
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function js_code() {

		$c = array();
		foreach (self::$data['js_code'] as $js_code) {
			//$result[] = JHtml::js_code($js_code);
			$c[] = $js_code . ";\n";
		}
		$result = joosHtml::js_code(implode("", $c));

		return $result;
	}

	public static function stylesheet() {
		$result = array();

		foreach (self::$data['css'] as $css_file) {
			// если включена отладка - то будет добавлять онтикеш к имени файла
			$result[] = joosHtml::css_file($css_file[0] . (JDEBUG ? '?' . time() : JFILE_ANTICACHE), $css_file[1]['media']);
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function head() {

		$jdocument = self::instance();

		$meta = joosDocument::get_data('meta');
		$n = count($meta);

		$description = $keywords = false;

		for ($i = 0; $i < $n; $i++) {
			if ($meta[$i][0] == 'keywords') {
				$_meta_keys_index = $i;
				$keywords = $meta[$i][1];
			} else {
				if ($meta[$i][0] == 'description') {
					$_meta_desc_index = $i;
					$description = $meta[$i][1];
				}
			}
		}

		$description ? null : $jdocument->append_meta_tag('description', joosConfig::get2('info', 'description'));
		$keywords ? null : $jdocument->append_meta_tag('keywords', joosConfig::get2('info', 'keywords'));

		if (joosDocument::$config['seotag'] == true) {
			foreach (self::$seotag as $key => $value) {
				$value != false ? $jdocument->add_meta_tag($key, $value) : null;
			}
		}

		echo $jdocument->get_head();


		// favourites icon
		if (self::$config['favicon'] == true) {
			$icon = JPATH_SITE . '/media/favicon.ico?v=2';
			echo "\t" . '<link rel="shortcut icon" href="' . $icon . '" />' . "\n\t";
		}
	}

	public static function body() {
		echo self::$data['page_body'];
	}

	public static function footer_data() {
		return implode("\n", self::$data['footer']);
	}

	public static function head_data() {
		return implode("\n", self::$data['header']);
	}

	public static function header() {
		if (!headers_sent()) {
			if (self::$cache_header_time) {
				header_remove('Pragma');
				header('Cache-Control: max-age=' . self::$cache_header_time);
				header('Expires: ' . gmdate('r', time() + self::$cache_header_time));
			} else {
				header('Pragma: no-cache');
				header('Cache-Control: no-cache, must-revalidate');
			}
			header('X-Powered-By: Joostina CMS');
			header('Content-type: text/html; charset=UTF-8');
		}
	}

}

/**
 * Класс подключения файлов
 * @package Joostina
 * @subpackage Loader
 */
class joosLoader {

	public static function model($name) {
		// TODO разрешить после полной настройки автозагрузчика
		require_once joosCore::path($name, 'model');
	}

	public static function admin_model($name) {
		// TODO разрешить после полной настройки автозагрузчика
		require_once joosCore::path($name, 'admin_model');
	}

	public static function view($name, $task) {
		require_once joosCore::path($name, 'view', $task);
	}

	public static function admin_view($name, $task) {
		require_once joosCore::path($name, 'admin_view', $task);
	}

	public static function admin_template_view($name) {
		require_once joosCore::path($name, 'admin_template_html');
	}

	public static function controller($name) {
		require_once joosCore::path($name, 'controller');
	}

	public static function admin_controller($name) {
		require_once joosCore::path($name, 'admin_controller');
	}

	/**
	 * Прямое подключение внешних библиотек
	 * @param string $name название библиотеки
	 * @param string $category  подкаталог расположения библиотеки
	 */
	public static function lib($name, $vendor = false) {
		require_once $vendor ? joosCore::path($name, 'lib-vendor', $vendor) : joosCore::path($name, 'lib');
	}

}

/**
 * Базовый контроллер Joostina CMS
 * @package Joostina
 * @subpackage Contlroller
 *
 * @todo разделить/расширить инициализации контроллера для front, front-ajax, admin, admin-ajax
 */
class joosController {

	public static $activroute;
	public static $controller;
	public static $task;
	public static $param;
	public static $error = false;
	private static $jsondata = array('extradata' => array());

	public static function init() {
		joosDocument::header();
		joosRoute::route();
	}

	/**
	 * Автоматическое определение и запуск метода действия
	 * @todo добавить сюда события events ДО, ПОСЛЕ и ВМЕСТО выполнения задчи контроллера
	 */
	public static function run() {

		$class = 'actions' . ucfirst(self::$controller);

		JDEBUG ? joosDebug::add($class . '::' . self::$task) : null;

		/**
		 * @todo тут можно переписать из статических методов в общие публичные, тока будет ли в этом профит?
		 * $controller = new $class;
		 * $results = call_user_func_array( array( $controller, self::$task ) );
		 */
		if (method_exists($class, self::$task)) {

			// в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подключение моделей, скриптов и т.д.
			method_exists($class, 'action_before') ? call_user_func_array($class . '::action_before', array(self::$task)) : null;

			$results = call_user_func($class . '::' . self::$task);

			// действия контроллера вызываемые после работы основного действия, на вход принимает результат работы основного действия
			method_exists($class, 'action_after') ? call_user_func_array($class . '::action_after', array(self::$task, $results)) : null;

			if (is_array($results)) {
				self::views($results, self::$controller, self::$task);
			} elseif (is_string($results)) {
				echo $results;
			}
		} else {
			//  в контроллере нет запрашиваемого метода
			return self::error404();
		}
	}

	/**
	 * Автоматическое определение и запуск метода действия для Аякс-азпросов
	 */
	public static function ajax_run() {

		$class = 'actions' . ucfirst(self::$controller);

		JDEBUG ? joosDebug::add($class . '::' . self::$task) : null;

		if (method_exists($class, self::$task)) {

			// в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подключение моделей, скриптов и т.д.
			method_exists($class, 'action_before') ? call_user_func_array($class . '::action_before', array(self::$task)) : null;

			$results = call_user_func($class . '::' . self::$task);

			method_exists($class, 'action_after') ? call_user_func_array($class . '::action_after', array(self::$task, $results)) : null;
		} else {
			//  в контроллере нет запрашиваемого метода
			return self::ajax_error404();
		}
		if (is_array($results)) {
			self::views($results, self::$controller, self::$task);
		} elseif (is_string($results)) {
			echo $results;
		}
	}

	public static function set_json_data(array $add_to_json) {
		self::$jsondata['extradata'] += $add_to_json;
	}

	private static function views(array $params, $option, $task) {

		//Готовим модули к выдаче: выбираем модули, которые нужны для текущей страницы
		self::prepare_modules_for_current_page($params, $option, $task);

		(isset($params['as_json']) && $params['as_json'] == true) ? self::as_json($params) : self::as_html($params, $option, $task);
	}

	private static function as_html(array $params, $option, $task) {

		$template = isset($params['template']) ? $params['template'] : 'default';
		$views = isset($params['task']) ? $params['task'] : $task;

		extract($params, EXTR_OVERWRITE);
		$viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $option . DS . 'views' . DS . $views . DS . $template . '.php';

		unset($params, $option, $task);

		is_file($viewfile) ? require ($viewfile) : null;
	}

	private static function as_json(array $params) {
		unset($params['as_json']);

		echo json_encode($params);
		exit();
	}

	/**
	 * joosController::prepare_modules_for_current_page()
	 * Формирует массив модулей, необходимых для вывода на данной странице
	 *
	 * @param array $params Массив параметров, передаваемый из контроллера компонента
	 * @param atring $option
	 * @param string $task
	 * @return void
	 */
	private static function prepare_modules_for_current_page(array $params, $option, $task) {

		joosModule::modules_by_page($option, $task, $params);

		unset($params);
	}

	public static function error404() {

		header('HTTP/1.0 404 Not Found');
		header("Status: 404 Not Found");

		if (!joosConfig::get('404_page')) {
			echo __('Страница не найдена');
		} else {
			require_once (JPATH_BASE . '/app/templates/system/404.php');
			exit(404);
		}

		self::$error = 404;

		return;
	}

	public static function ajax_error404() {
		header('HTTP/1.0 404 Not Found');
		header("Status: 404 Not Found");
		echo _NOT_EXIST;

		self::$error = 404;

		return;
	}

	/**
	 * Статичный запуск проитзвольной задачи из произвольного контроллера
	 * @param string $controller название контроллера
	 * @param string $task выполняемая задача
	 * @param array $params массив парамеьтров передаваемых задаче
	 */
	public static function static_run($controller, $task, array $params = array()) {

		self::$controller = $controller;
		self::$task = $task;
		self::$param = $params;
		self::$activroute = 'staticrun';

		self::run();
	}

	/**
	 * Подключение шаблона
	 * @param string $controller название контроллера
	 * @param string $task выполняемая задача
	 * @param array $params массив параметров, которые могут переданы в шаблон
	 */
	public static function get_view($controller, $task, $template = 'default', $params = array()) {
		extract($params, EXTR_OVERWRITE);
		$viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $controller . DS . 'views' . DS . $task . DS . $template . '.php';
		is_file($viewfile) ? require ($viewfile) : null;
	}

}

/**
 * Заглушка для локализации интерфейса
 *
 * @example __('К нам пришёл :username', array(':username'=>'Дед мороз') );
 * @param string $string
 * @param array $args
 * @return string
 */
function __($string, array $args=null) {
	return $args === NULL ? $string : strtr($string, $args);
}

/**
 * Убрать, заменить везде и использовать как joosDebug::dump($var);
 * @deprecated
 */
function _xdump($var) {
	joosDebug::dump($var);
}

class joosCoreException extends joosException {
	
}